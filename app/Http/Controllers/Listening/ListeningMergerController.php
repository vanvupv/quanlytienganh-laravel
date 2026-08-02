<?php

namespace App\Http\Controllers\Listening;

use App\Http\Controllers\Controller;
use App\Models\Listening\Listening;
use App\Models\Listening\ListeningTopic;
use App\Models\Listening\ListeningTranscript;
use App\Models\Document\Document;
use App\Models\Document\DocumentPart;
use App\Models\Document\DocumentUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListeningMergerController extends Controller
{
    /**
     * Display the audio merger interface.
     */
    public function index()
    {
        $topics = ListeningTopic::orderBy('name')->get();
        $documents = Document::where('skill', 'listening')
            ->orWhereHas('listenings')
            ->orderBy('name')
            ->get();
            
        $existingAudios = Listening::orderBy('title')->get(['id', 'title', 'link', 'duration']);

        return view('skills.listening.merger', compact('topics', 'documents', 'existingAudios'));
    }

    /**
     * Save the merged audio file and create a new listening record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string|max:10',
            'score_level' => 'nullable|numeric|min:0|max:100',
            'topic_id' => 'nullable|exists:listening_topics,id',
            'document_id' => 'nullable|exists:documents,id',
            'chapter_id' => 'nullable|exists:document_parts,id',
            'unit_id' => 'nullable|exists:document_units,id',
            'transcript' => 'nullable|string',
            'translation' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'audio_data' => 'required|file', // Uploaded merged blob
        ]);

        DB::beginTransaction();
        try {
            // Store the uploaded merged audio file
            $file = $request->file('audio_data');
            
            // Tự động nhận diện định dạng file gửi lên (mp3 hoặc wav)
            $extension = $file->getClientOriginalExtension() ?: 'wav';
            if (!in_array(strtolower($extension), ['wav', 'mp3'])) {
                $mime = $file->getMimeType();
                $extension = ($mime === 'audio/mpeg' || $mime === 'audio/mp3') ? 'mp3' : 'wav';
            }
            
            $fileName = 'merged_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            // Store in storage/app/public/listening
            $path = $file->storeAs('public/listening', $fileName);
            $link = '/storage/listening/' . $fileName; // Public asset link

            // Create the Listening record
            $listening = Listening::create([
                'title' => $request->title,
                'link' => $link,
                'type' => $file->getClientMimeType() ?: 'audio/' . $extension,
                'size' => $this->formatBytes($file->getSize()),
                'duration' => $request->duration,
                'level' => $request->level,
                'score_level' => $request->score_level ?? 0,
                'document_id' => $request->document_id,
                'chapter_id' => $request->chapter_id,
                'unit_id' => $request->unit_id,
                'topic_id' => $request->topic_id,
            ]);

            // Save original transcript if provided
            if (!empty($request->transcript)) {
                $words = str_word_count($request->transcript);
                $sents = preg_match_all('/[.!?]+/', $request->transcript) ?: 1;
                ListeningTranscript::create([
                    'listening_id' => $listening->id,
                    'type' => 'original',
                    'language_code' => 'en',
                    'transcript' => $request->transcript,
                    'word_count' => $words,
                    'sentence_count' => $sents,
                ]);

                // Create default sentences
                $sentencesText = preg_split('/(?<=[.!?])\s+/', $request->transcript);
                foreach ($sentencesText as $idx => $text) {
                    $text = trim($text);
                    if (!empty($text)) {
                        $listening->sentences()->create([
                            'sentence_text' => $text,
                            'start_time' => 0.00,
                            'end_time' => 0.00,
                            'order' => $idx + 1,
                        ]);
                    }
                }
            }

            // Save translation if provided
            if (!empty($request->translation)) {
                ListeningTranscript::create([
                    'listening_id' => $listening->id,
                    'type' => 'translation',
                    'language_code' => 'vi',
                    'transcript' => $request->translation,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Đã ghép và lưu file nghe thành công!',
                'redirect_url' => route('listening.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lưu bài nghe: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to format bytes.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
