<?php

namespace App\Http\Controllers\Reading;

use App\Http\Controllers\Controller;
use App\Models\Reading\ReadingFilterSetting;
use App\Models\Reading\ReadingAnalyzerHistory;
use App\Models\Vocabulary\VocabWord;
use App\Models\Vocabulary\VocabProperty;
use App\Models\Vocabulary\VocabStage;
use Illuminate\Http\Request;

class ReadingAnalyzerController extends Controller
{
    // =========================================================
    // INDEX: Trang chính Reading Analyzer
    // =========================================================
    public function index()
    {
        $filters = ReadingFilterSetting::orderBy('name')->get();
        $activeFilter = $filters->firstWhere('is_active', true);
        return view('skills.reading.analyzer', compact('filters', 'activeFilter'));
    }

    // =========================================================
    // UPLOAD: Nhận file, parse text, phân tích từ vựng
    // =========================================================
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,doc,txt|max:10240',
            'filter_id' => 'nullable|exists:reading_filter_settings,id',
        ]);

        $file     = $request->file('document');
        $ext      = strtolower($file->getClientOriginalExtension());
        $text     = '';

        try {
            if ($ext === 'txt') {
                $text = file_get_contents($file->getRealPath());
                // Normalize encoding
                if (!mb_check_encoding($text, 'UTF-8')) {
                    $text = mb_convert_encoding($text, 'UTF-8', 'auto');
                }
            } elseif ($ext === 'pdf') {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($file->getRealPath());
                $text   = $pdf->getText();
            } elseif (in_array($ext, ['docx', 'doc'])) {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getRealPath());
                $sections = $phpWord->getSections();
                $lines = [];
                foreach ($sections as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getElements')) {
                            $lineText = '';
                            foreach ($element->getElements() as $child) {
                                if (method_exists($child, 'getText')) {
                                    $lineText .= $child->getText() . ' ';
                                }
                            }
                            if (trim($lineText)) {
                                $lines[] = trim($lineText);
                            }
                        }
                    }
                }
                $text = implode("\n", $lines);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể đọc file: ' . $e->getMessage()], 422);
        }

        if (empty(trim($text))) {
            return response()->json(['error' => 'File không có nội dung văn bản.'], 422);
        }

        // -------------------------------------------------------
        // Load vocabulary từ database
        // -------------------------------------------------------
        $vocabWords = VocabWord::pluck('word')->map(fn($w) => strtolower($w))->toArray();
        $vocabSet   = array_flip($vocabWords); // O(1) lookup

        // -------------------------------------------------------
        // Load bộ lọc đang active
        // -------------------------------------------------------
        $ignoredWords = [];
        if ($request->filter_id) {
            $filter = ReadingFilterSetting::find($request->filter_id);
        } else {
            $filter = ReadingFilterSetting::where('is_active', true)->first();
        }
        if ($filter) {
            $ignoredWords = $filter->getNormalizedIgnoredWords();
        }
        $ignoredSet = array_flip($ignoredWords);

        // -------------------------------------------------------
        // Tokenize theo dòng
        // -------------------------------------------------------
        $rawLines = preg_split('/\r?\n/', $text);
        $result   = $this->analyzeText($rawLines, $vocabSet, $ignoredSet);

        // -------------------------------------------------------
        // Lưu lịch sử phân tích
        // -------------------------------------------------------
        ReadingAnalyzerHistory::create([
            'file_name'            => $file->getClientOriginalName(),
            'file_ext'             => $ext,
            'filter_id'            => $filter?->id ?? null,
            'filter_name_snapshot' => $filter?->name ?? null,
            'total_words'          => $result['stats']['total_words'],
            'new_count'            => $result['stats']['new_count'],
            'known_count'          => $result['stats']['known_count'],
            'comprehension_pct'    => $result['stats']['comprehension'],
        ]);

        return response()->json($result);
    }

    // =========================================================
    // ANALYZE: Phân tích text thành token đã phân loại
    // =========================================================
    private function analyzeText(array $rawLines, array $vocabSet, array $ignoredSet): array
    {
        $newWordFirstLine  = [];   // word -> lineNumber (1-indexed)
        $wordOccurrences   = [];   // word -> count total
        $newWordsInfo      = [];   // Danh sách từ mới với lineNum
        $analyzedLines     = [];   // Output: mỗi dòng với tokens
        $totalWords        = 0;
        $knownCount        = 0;

        foreach ($rawLines as $lineIdx => $lineText) {
            $lineNum  = $lineIdx + 1;
            $tokens   = $this->tokenizeLine($lineText);
            $lineOut  = [];

            foreach ($tokens as $token) {
                if ($token['type'] === 'punctuation' || $token['type'] === 'space') {
                    $lineOut[] = $token;
                    continue;
                }

                $original = $token['text'];
                $lower    = strtolower($original);
                $root     = $this->lemmatize($lower);

                // Bỏ qua từ quá ngắn hoặc là số
                if (strlen($lower) <= 1 || is_numeric($lower)) {
                    $lineOut[] = array_merge($token, ['type' => 'word', 'class' => 'plain']);
                    continue;
                }

                // Bỏ qua tiếng Việt, từ phi Anh, hoặc từ không chứa nguyên âm tiếng Anh (viết tắt, ký hiệu)
                if ($this->isVietnameseWord($lower)
                    || !preg_match('/^[a-zA-Z\'-]+$/', $lower)
                    || !preg_match('/[aeiouy]/i', $lower)) {
                    $lineOut[] = array_merge($token, ['type' => 'word', 'class' => 'plain']);
                    continue;
                }

                $totalWords++;

                // Kiểm tra bỏ qua
                if (isset($ignoredSet[$lower]) || isset($ignoredSet[$root])) {
                    $lineOut[] = array_merge($token, ['type' => 'word', 'class' => 'ignored']);
                    $knownCount++;
                    continue;
                }

                // Kiểm tra đã có trong vocab
                if (isset($vocabSet[$lower]) || isset($vocabSet[$root])) {
                    $lineOut[] = array_merge($token, ['type' => 'word', 'class' => 'known']);
                    $knownCount++;
                    continue;
                }

                // Từ không có trong vocab — từ mới hoặc lặp
                $wordOccurrences[$root] = ($wordOccurrences[$root] ?? 0) + 1;

                if (!isset($newWordFirstLine[$root])) {
                    // Lần đầu xuất hiện → từ mới
                    $newWordFirstLine[$root] = $lineNum;
                    $anchorId = 'word-anchor-' . preg_replace('/[^a-z0-9]/', '', $root);
                    $newWordsInfo[$root] = [
                        'word'      => $root,
                        'original'  => $original,
                        'line'      => $lineNum,
                        'anchor_id' => $anchorId,
                    ];
                    $lineOut[] = array_merge($token, [
                        'type'      => 'word',
                        'class'     => 'new',
                        'root'      => $root,
                        'anchor_id' => $anchorId,
                    ]);
                } else {
                    // Lặp lại
                    $anchorId = 'word-anchor-' . preg_replace('/[^a-z0-9]/', '', $root);
                    $lineOut[] = array_merge($token, [
                        'type'      => 'word',
                        'class'     => 'repeat',
                        'root'      => $root,
                        'anchor_id' => $anchorId,
                        'first_line' => $newWordFirstLine[$root],
                    ]);
                }
            }

            $analyzedLines[] = [
                'line_num' => $lineNum,
                'tokens'   => $lineOut,
            ];
        }

        $newCount   = count($newWordsInfo);
        $repeatPct  = $totalWords > 0 ? round(($knownCount / $totalWords) * 100) : 0;

        // Build summary
        $summary = [];
        foreach ($newWordsInfo as $root => $info) {
            $summary[] = [
                'root'      => $root,
                'variant'   => $info['original'],
                'frequency' => $wordOccurrences[$root] ?? 1,
                'line'      => $info['line'],
                'anchor_id' => $info['anchor_id'],
            ];
        }

        // Sort by line
        usort($summary, fn($a, $b) => $a['line'] - $b['line']);

        return [
            'lines'       => $analyzedLines,
            'new_words'   => array_values($newWordsInfo),
            'summary'     => $summary,
            'stats'       => [
                'total_words'   => $totalWords,
                'new_count'     => $newCount,
                'known_count'   => $knownCount,
                'comprehension' => $repeatPct,
            ],
        ];
    }

    // =========================================================
    // TOKENIZE: Tách một dòng thành mảng tokens (Hỗ trợ Unicode)
    // =========================================================
    private function tokenizeLine(string $line): array
    {
        $tokens  = [];
        $pattern = '/([\p{L}\'-]+)|([^\p{L}\'-]+)/u';
        preg_match_all($pattern, $line, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            if (!empty($match[1][0])) {
                $tokens[] = ['type' => 'word', 'text' => $match[1][0]];
            } elseif (!empty($match[2][0])) {
                $tokens[] = ['type' => 'punctuation', 'text' => $match[2][0]];
            }
        }

        return $tokens;
    }

    // =========================================================
    // KIỂM TRA TỪ TIẾNG VIỆT: Kiểm tra xem từ có chứa ký tự tiếng Việt không
    // =========================================================
    private function isVietnameseWord(string $word): bool
    {
        $vietnameseChars = "ăâđêôơưáàảãạéèẻẽẹíìỉĩịóòỏõọúùủũụýỳỷỹỵĂÂĐÊÔƠƯÁÀẢÃẠÉÈẺẼẸÍÌỈĨỊÓÒỎÕỌÚÙỦŨỤÝỲỶỸỴ";
        return (bool) preg_match('/[' . $vietnameseChars . ']/u', $word);
    }

    // =========================================================
    // LEMMATIZE: Heuristic rule-based suffix stripping (English)
    // =========================================================
    private function lemmatize(string $word): string
    {
        $word = rtrim($word, "'");

        // Exceptions
        $exceptions = [
            'is' => 'be', 'are' => 'be', 'was' => 'be', 'were' => 'be',
            'am'  => 'be', 'been' => 'be', 'being' => 'be',
            'has' => 'have', 'had' => 'have', 'having' => 'have',
            'does' => 'do', 'did' => 'do', 'done' => 'do', 'doing' => 'do',
            'ran' => 'run', 'running' => 'run', 'runs' => 'run',
            'went' => 'go', 'goes' => 'go', 'gone' => 'go', 'going' => 'go',
            'said' => 'say', 'says' => 'say', 'saying' => 'say',
            'made' => 'make', 'makes' => 'make', 'making' => 'make',
            'took' => 'take', 'takes' => 'take', 'taken' => 'take', 'taking' => 'take',
            'came' => 'come', 'comes' => 'come', 'coming' => 'come',
            'got' => 'get', 'gets' => 'get', 'getting' => 'get', 'gotten' => 'get',
            'saw' => 'see', 'seen' => 'see', 'sees' => 'see', 'seeing' => 'see',
            'knew' => 'know', 'known' => 'know', 'knows' => 'know', 'knowing' => 'know',
            'thought' => 'think', 'thinks' => 'think', 'thinking' => 'think',
            'children' => 'child', 'men' => 'man', 'women' => 'woman',
            'people' => 'person', 'mice' => 'mouse', 'feet' => 'foot',
            'teeth' => 'tooth', 'geese' => 'goose', 'oxen' => 'ox',
            'better' => 'good', 'best' => 'good',
            'worse' => 'bad', 'worst' => 'bad',
        ];

        if (isset($exceptions[$word])) {
            return $exceptions[$word];
        }

        $len = strlen($word);

        // -ing: running → run, making → make
        if ($len > 5 && substr($word, -3) === 'ing') {
            $stem = substr($word, 0, -3);
            // Double consonant: running → run
            if ($len > 6 && $stem[-1] === $stem[-2]) {
                return substr($stem, 0, -1);
            }
            // making → make (add e)
            if ($len > 5 && preg_match('/[^aeiou]$/', $stem)) {
                return $stem . 'e';
            }
            return $stem;
        }

        // -ed: walked → walk, loved → love
        if ($len > 4 && substr($word, -2) === 'ed') {
            $stem = substr($word, 0, -2);
            if ($len > 5 && $stem[-1] === $stem[-2]) {
                return substr($stem, 0, -1);
            }
            if (preg_match('/[^aeiou]$/', $stem)) {
                return $stem . 'e';
            }
            return $stem;
        }

        // -er / -est: bigger → big, faster → fast
        if ($len > 4 && substr($word, -2) === 'er') {
            $stem = substr($word, 0, -2);
            if ($len > 5 && $stem[-1] === $stem[-2]) {
                return substr($stem, 0, -1);
            }
            return $stem;
        }

        // -ly: quickly → quick
        if ($len > 4 && substr($word, -2) === 'ly') {
            return substr($word, 0, -2);
        }

        // -ies: cities → city
        if ($len > 4 && substr($word, -3) === 'ies') {
            return substr($word, 0, -3) . 'y';
        }

        // -es: boxes → box, churches → church
        if ($len > 4 && substr($word, -2) === 'es') {
            $stem = substr($word, 0, -2);
            return $stem;
        }

        // -s: cats → cat (simple)
        if ($len > 3 && substr($word, -1) === 's' && !preg_match('/[sxz]$/', substr($word, 0, -1))) {
            return substr($word, 0, -1);
        }

        return $word;
    }

    // =========================================================
    // SAVE WORDS: Lưu từ vựng mới vào bảng vocab_words
    // =========================================================
    public function saveWords(Request $request)
    {
        $request->validate([
            'words'                 => 'required|array|min:1|max:200',
            'words.*.word'          => 'required|string|max:100',
            'words.*.meaning'       => 'nullable|string|max:500',
            'words.*.part_of_speech'=> 'nullable|string|max:50',
            'source_document'       => 'nullable|string|max:255',
        ]);

        // Lấy stage đầu tiên để gắn vào vocab_properties (nếu có)
        $defaultStage = VocabStage::orderBy('order')->first();
        $sourceDocument = trim($request->input('source_document') ?? '');

        $saved   = 0;
        $skipped = 0;
        $errors  = [];

        foreach ($request->words as $item) {
            $wordStr = strtolower(trim($item['word']));
            if (empty($wordStr)) continue;

            try {
                // firstOrCreate: không lỗi nếu từ đã tồn tại
                [$vocabWord, $created] = [
                    VocabWord::firstOrCreate(['word' => $wordStr]),
                    false,
                ];
                // Check if it was actually created
                $created = $vocabWord->wasRecentlyCreated;

                if (!$created) {
                    $skipped++;
                } else {
                    $saved++;
                }

                // Lưu property nếu có nghĩa HOẶC đây là từ mới cần lưu vết nguồn tài liệu
                $meaning = trim($item['meaning'] ?? '');
                if ($defaultStage && ($meaning || ($created && $sourceDocument))) {
                    VocabProperty::create([
                        'word_id'       => $vocabWord->id,
                        'stage_id'      => $defaultStage->id,
                        'part_of_speech'=> trim($item['part_of_speech'] ?? 'Other'),
                        'meaning'       => $meaning ?: '—',
                        'reference'     => $sourceDocument ?: null,
                    ]);
                }
            } catch (\Exception $e) {
                $errors[] = $wordStr . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'saved'   => $saved,
            'skipped' => $skipped,
            'errors'  => $errors,
            'message' => "✅ Đã lưu {$saved} từ mới" . ($skipped > 0 ? ", bỏ qua {$skipped} từ đã có" : '') . '.',
        ]);
    }

    // =========================================================
    // HISTORY — Lịch sử phân tích file
    // =========================================================
    public function historyIndex()
    {
        $histories = ReadingAnalyzerHistory::with('filter')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn($h) => [
                'id'                   => $h->id,
                'file_name'            => $h->file_name,
                'file_ext'             => $h->file_ext,
                'file_icon'            => $h->file_icon,
                'filter_name'          => $h->display_filter_name,
                'filter_id'            => $h->filter_id,
                'total_words'          => $h->total_words,
                'new_count'            => $h->new_count,
                'known_count'          => $h->known_count,
                'comprehension_pct'    => $h->comprehension_pct,
                'created_at_human'     => $h->created_at->format('d/m/Y H:i'),
                'created_at_diff'      => $h->created_at->diffForHumans(),
            ]);

        return response()->json(['data' => $histories]);
    }

    public function historyDestroy(int $id)
    {
        ReadingAnalyzerHistory::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => '🗑️ Đã xóa lịch sử.']);
    }

    // =========================================================
    // FILTER — Trang quản lý bộ lọc
    // =========================================================
    public function filterIndex()
    {
        $filters = ReadingFilterSetting::orderByDesc('is_active')->orderBy('name')->get();
        return view('skills.reading.filter', compact('filters'));
    }

    public function filterStore(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'language'      => 'required|string|max:10',
            'topic'         => 'nullable|string|max:255',
            'category'      => 'nullable|string|max:255',
            'context'       => 'nullable|string|max:255',
            'ignored_words' => 'nullable|string',
            'is_active'     => 'nullable|boolean',
        ]);

        // Parse ignored_words từ textarea (comma/newline separated)
        $ignored = [];
        if (!empty($data['ignored_words'])) {
            $ignored = preg_split('/[\s,]+/', $data['ignored_words']);
            $ignored = array_filter(array_map('trim', $ignored));
            $ignored = array_values($ignored);
        }

        ReadingFilterSetting::create([
            'name'          => $data['name'],
            'language'      => $data['language'],
            'topic'         => $data['topic'] ?? null,
            'category'      => $data['category'] ?? null,
            'context'       => $data['context'] ?? null,
            'ignored_words' => $ignored,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('reading.analyzer.filter.index')
            ->with('success', '✅ Đã thêm bộ lọc mới!');
    }

    public function filterUpdate(Request $request, int $id)
    {
        $filter = ReadingFilterSetting::findOrFail($id);
        $data   = $request->validate([
            'name'          => 'required|string|max:255',
            'language'      => 'required|string|max:10',
            'topic'         => 'nullable|string|max:255',
            'category'      => 'nullable|string|max:255',
            'context'       => 'nullable|string|max:255',
            'ignored_words' => 'nullable|string',
            'is_active'     => 'nullable|boolean',
        ]);

        $ignored = [];
        if (!empty($data['ignored_words'])) {
            $ignored = preg_split('/[\s,]+/', $data['ignored_words']);
            $ignored = array_filter(array_map('trim', $ignored));
            $ignored = array_values($ignored);
        }

        $filter->update([
            'name'          => $data['name'],
            'language'      => $data['language'],
            'topic'         => $data['topic'] ?? null,
            'category'      => $data['category'] ?? null,
            'context'       => $data['context'] ?? null,
            'ignored_words' => $ignored,
            'is_active'     => $request->boolean('is_active', false),
        ]);

        return redirect()->route('reading.analyzer.filter.index')
            ->with('success', '✅ Đã cập nhật bộ lọc!');
    }

    public function filterDestroy(int $id)
    {
        ReadingFilterSetting::findOrFail($id)->delete();
        return redirect()->route('reading.analyzer.filter.index')
            ->with('success', '🗑️ Đã xóa bộ lọc!');
    }

    // =========================================================
    // EXPORT DOCX: Xuất tệp Word với highlight từ mới
    // =========================================================
    public function exportDocx(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string',
            'lines' => 'required|array',
            'summary' => 'required|array',
        ]);

        $fileName = $request->input('file_name');
        $lines = $request->input('lines');
        $summary = $request->input('summary');

        // Khởi tạo PHPWord
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        $section = $phpWord->addSection([
            'marginTop' => 1200,
            'marginBottom' => 1200,
            'marginLeft' => 1200,
            'marginRight' => 1200,
        ]);

        // Thêm tiêu đề
        $section->addText("English Reading Analyzer - Báo cáo phân tích từ vựng", ['name' => 'Arial', 'size' => 16, 'bold' => true, 'color' => '0F172A']);
        $section->addText("Tài liệu: " . $fileName, ['name' => 'Arial', 'size' => 10, 'italic' => true, 'color' => '64748B']);
        $section->addTextBreak(1);

        // Duyệt từng dòng
        foreach ($lines as $line) {
            $textrun = $section->addTextRun([
                'lineHeight' => 1.5,
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(4),
            ]);

            // Thêm số dòng
            $lineNumStr = str_pad($line['line_num'], 4, ' ', STR_PAD_RIGHT);
            $textrun->addText($lineNumStr, ['name' => 'Courier New', 'size' => 9, 'color' => '94A3B8']);

            // Duyệt từng token
            foreach ($line['tokens'] as $token) {
                $text = $token['text'];
                $class = $token['class'] ?? '';

                if (($token['type'] ?? '') === 'punctuation' || ($token['type'] ?? '') === 'space') {
                    $textrun->addText($text, ['name' => 'Arial', 'size' => 11, 'color' => '1E293B']);
                } else {
                    if ($class === 'new') {
                        $textrun->addText($text, [
                            'name' => 'Arial',
                            'size' => 11,
                            'bold' => true,
                            'bgColor' => 'DCFCE7',
                            'color' => '15803D',
                        ]);
                    } elseif ($class === 'repeat') {
                        $textrun->addText($text, [
                            'name' => 'Arial',
                            'size' => 11,
                            'bold' => true,
                            'bgColor' => 'FEF3C7',
                            'color' => '92400E',
                        ]);
                    } else {
                        $textrun->addText($text, ['name' => 'Arial', 'size' => 11, 'color' => '1E293B']);
                    }
                }
            }
        }

        // Thêm ngắt trang
        $section->addPageBreak();

        // Tiêu đề phần danh sách từ mới
        $section->addText("📋 Danh sách từ mới", ['name' => 'Arial', 'size' => 14, 'bold' => true, 'color' => '0F172A']);
        $section->addTextBreak(1);

        // Tạo bảng danh sách từ mới
        $tableStyle = [
            'borderSize'  => 6,
            'borderColor' => 'E2E8F0',
            'cellMargin'  => 100,
        ];
        $phpWord->addTableStyle('NewWordsTable', $tableStyle);
        $table = $section->addTable('NewWordsTable');

        // Header bảng
        $table->addRow();
        $table->addCell(800)->addText("#", ['bold' => true, 'size' => 10, 'color' => '374151']);
        $table->addCell(3500)->addText("Từ gốc (Root)", ['bold' => true, 'size' => 10, 'color' => '374151']);
        $table->addCell(3500)->addText("Biến thể (Variant)", ['bold' => true, 'size' => 10, 'color' => '374151']);
        $table->addCell(1500)->addText("Dòng đầu", ['bold' => true, 'size' => 10, 'color' => '374151']);
        $table->addCell(1500)->addText("Tần suất", ['bold' => true, 'size' => 10, 'color' => '374151']);

        foreach ($summary as $idx => $item) {
            $table->addRow();
            $table->addCell(800)->addText($idx + 1, ['size' => 9]);
            $table->addCell(3500)->addText(ucfirst($item['root']), ['bold' => true, 'size' => 9, 'color' => '1E293B']);
            $table->addCell(3500)->addText($item['variant'], ['italic' => true, 'size' => 9, 'color' => '6366F1']);
            $table->addCell(1500)->addText($item['line'], ['size' => 9]);
            $table->addCell(1500)->addText($item['frequency'] . 'x', ['size' => 9]);
        }

        // Lưu và xuất tệp
        $tempFile = tempnam(sys_get_temp_dir(), 'docx');
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        $downloadName = pathinfo($fileName, PATHINFO_FILENAME) . '_highlighted.docx';

        return response()->download($tempFile, $downloadName)->deleteFileAfterSend(true);
    }

    // =========================================================
    // EXPORT CSV: Xuất danh sách từ mới sang định dạng CSV (Excel)
    // =========================================================
    public function exportCsv(Request $request)
    {
        $request->validate([
            'summary' => 'required|array',
            'file_name' => 'required|string',
        ]);

        $summary = $request->input('summary');
        $fileName = $request->input('file_name');

        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvContent .= "STT,Từ gốc (Root),Biến thể (Variant),Dòng đầu tiên,Tần suất xuất hiện\n";

        foreach ($summary as $idx => $item) {
            $num = $idx + 1;
            $root = str_replace('"', '""', ucfirst($item['root']));
            $variant = str_replace('"', '""', $item['variant']);
            $line = $item['line'];
            $freq = $item['frequency'];

            $csvContent .= "{$num},\"{$root}\",\"{$variant}\",{$line},{$freq}\n";
        }

        $downloadName = pathinfo($fileName, PATHINFO_FILENAME) . '_new_words.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $downloadName . '"');
    }
}
