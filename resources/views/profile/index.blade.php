@extends('vocabularies.vocabulary')

@section('title', 'Thông Tin Bản Thân & Phân Tích Chỉ Số Học Tập')

@section('content_voca')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid py-4 px-4" style="background: #f8fafc; min-height: 100vh;">

    <!-- Premium Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="fw-bold text-dark mb-1" style="font-size: 28px; letter-spacing: -0.5px;">👤 Thông Tin Bản Thân</h1>
            <p class="text-muted mb-0">Hồ sơ năng lực, mục tiêu học tập và biểu đồ phân tích chỉ số học thuật</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center" style="background-color: #2563eb !important; font-size: 12.5px; height: 35px;">
                🔥 Học tập liên tục: {{ $streakDays }} ngày
            </span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
            <strong>🎉 Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- SECTION 1: PROFILE COVER CARD -->
    <div class="card border-0 shadow-sm p-4 mb-4 text-start" style="border-radius: 20px; background: linear-gradient(135deg, #1e293b, #0f172a); color: white; position: relative; overflow: hidden;">
        <!-- Glowing background accent -->
        <div style="position: absolute; top: -50px; right: -50px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, transparent 70%); pointer-events: none;"></div>
        
        <div class="row align-items-center position-relative">
            <div class="col-md-auto text-center mb-3 mb-md-0">
                <div class="position-relative d-inline-block">
                    @if($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="Avatar" class="rounded-circle border border-4 border-primary shadow" style="width: 120px; height: 120px; object-fit: cover; border-color: #3b82f6 !important;">
                    @else
                        <div class="rounded-circle border border-4 border-primary shadow-sm d-flex align-items-center justify-content-center fw-bold text-white fs-1" style="width: 120px; height: 120px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-color: #3b82f6 !important;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-2" style="width: 16px; height: 16px;" title="Online"></span>
                </div>
            </div>
            
            <div class="col-md">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <h2 class="fw-bold text-white mb-0" style="font-size: 26px;">{{ $user->name }}</h2>
                    <span class="badge bg-info text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 12px; background-color: #0ea5e9 !important; color: white !important;">
                        Trình độ: {{ $user->english_level }}
                    </span>
                </div>
                <p class="text-gray-400 small mb-2" style="color: #94a3b8;"><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                <p class="mb-3 italic" style="color: #cbd5e1; font-style: italic; max-width: 600px;">
                    "{{ $user->bio ?? 'Chưa thiết lập lời giới thiệu bản thân.' }}"
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <div class="px-3 py-1.5 rounded-3 bg-white bg-opacity-10 text-white small d-flex align-items-center gap-1">
                        🎯 <strong>Mục tiêu:</strong> {{ $user->target_goal ?? 'Chưa xác định mục tiêu' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: DYNAMIC STATS OVERVIEW CARDS -->
    <div class="row g-4 mb-4 text-start">
        <!-- Vocabulary Stat -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white; transition: all 0.2s;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Từ Vựng Đã Học</span>
                    <span class="p-2 rounded-3 bg-blue-50 text-primary" style="background-color: #eff6ff; color: #2563eb;"><i class="bi bi-book"></i> 📘</span>
                </div>
                <h3 class="fw-bold text-dark mb-1" style="font-size: 28px;">{{ $vocabWordsCount }} từ</h3>
                <p class="text-muted small mb-0">Đã tạo và tương tác cây</p>
            </div>
        </div>

        <!-- Vocab Reviews Stat -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white; transition: all 0.2s;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Bài Ôn Từ Vựng</span>
                    <span class="p-2 rounded-3 bg-green-50 text-success" style="background-color: #ecfdf5; color: #10b981;"><i class="bi bi-check-all"></i> 📝</span>
                </div>
                <h3 class="fw-bold text-dark mb-1" style="font-size: 28px;">{{ $vocabReviewsCount }} lượt</h3>
                <p class="text-muted small mb-0">Hoàn thành Active Recall</p>
            </div>
        </div>

        <!-- Grammar Lessons Stat -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white; transition: all 0.2s;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Ngữ Pháp Đã Học</span>
                    <span class="p-2 rounded-3 bg-purple-50 text-purple" style="background-color: #f3e8ff; color: #a855f7;"><i class="bi bi-bookmark-star"></i> 📖</span>
                </div>
                <h3 class="fw-bold text-dark mb-1" style="font-size: 28px;">{{ $grammarLessonsCount }} bài</h3>
                <p class="text-muted small mb-0">Trong tiến trình lộ trình</p>
            </div>
        </div>

        <!-- Assessments Stat -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white; transition: all 0.2s;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Đề Thi Tổng Quát</span>
                    <span class="p-2 rounded-3 bg-amber-50 text-warning" style="background-color: #fffbeb; color: #f59e0b;"><i class="bi bi-award"></i> 🏆</span>
                </div>
                <h3 class="fw-bold text-dark mb-1" style="font-size: 28px;">{{ $assessmentsTaken }} đề</h3>
                <p class="text-muted small mb-0">Điểm trung bình: <strong>{{ round($avgAssessmentScore) }}đ</strong></p>
            </div>
        </div>
    </div>

    <!-- SECTION 3: ADVANCED COGNITIVE ANALYTICS & PARAMETERS -->
    <div class="row g-4 mb-4 text-start">
        
        <!-- Khả năng ghi nhớ (Memory Retention Analytics) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white;">
                <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 18px;">
                    🧠 Khả Năng Ghi Nhớ Sâu (Active Recall)
                </h4>
                
                <div class="row align-items-center mt-2">
                    <div class="col-sm-5 text-center mb-3 mb-sm-0">
                        <!-- Custom CSS circular progress ring -->
                        <div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 130px; height: 130px;">
                            <svg class="w-100 h-100" style="transform: rotate(-90deg);">
                                <circle cx="65" cy="65" r="55" stroke="#f1f5f9" stroke-width="12" fill="transparent" />
                                <circle cx="65" cy="65" r="55" stroke="#10b981" stroke-width="12" fill="transparent" 
                                        stroke-dasharray="345.5" stroke-dashoffset="{{ 345.5 - (345.5 * $actualRetentionRate) / 100 }}" />
                            </svg>
                            <div class="position-absolute d-flex flex-column align-items-center">
                                <span class="fw-bold text-dark fs-2" style="letter-spacing: -1px;">{{ $actualRetentionRate }}%</span>
                                <span class="text-muted fw-bold" style="font-size: 9px; text-transform: uppercase;">Thực tế</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-7">
                        <div class="d-flex flex-column gap-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-1 small">📈 Tự Đánh Giá Trí Nhớ:</h6>
                                <div class="progress" style="height: 10px; border-radius: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $user->retention_rate }}%; border-radius: 6px;" aria-valuenow="{{ $user->retention_rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1" style="font-size: 11px;">
                                    <span class="text-muted">Mức cài đặt</span>
                                    <span class="fw-bold text-primary">{{ $user->retention_rate }}%</span>
                                </div>
                            </div>

                            <hr class="my-2 text-muted opacity-25">
                            
                            <div>
                                <h6 class="fw-bold text-dark mb-1 small">💡 Đánh Giá Thuật Toán:</h6>
                                <p class="text-muted small mb-0" style="line-height: 1.4;">
                                    Học viên có tỷ lệ ghi nhớ đạt chuẩn **{{ $actualRetentionRate }}%** dựa trên tổng số bài thi ôn tập đạt điểm tuyệt đối từ 80 trở lên.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tốc độ học & Khả năng phản xạ (Reflex Speed Analytics) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: white;">
                <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 18px;">
                    ⏱️ Tốc Độ Học & Khả Năng Phản Xạ
                </h4>

                <div class="d-flex flex-column gap-3 mt-1">
                    <!-- Speed Parameter -->
                    <div class="p-3 rounded-3 border" style="background: #fafafa; border-color: #f1f5f9 !important;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 14.5px;">Tốc độ thiết lập:</h5>
                                <p class="text-muted small mb-0">Mức tự nhận thức bản thân</p>
                            </div>
                            <span class="badge bg-primary px-3 py-1.5" style="border-radius: 6px;">{{ $user->learning_speed }}</span>
                        </div>
                    </div>

                    <!-- Computed Response Speed -->
                    <div class="p-3 rounded-3 border-start border-primary border-4 shadow-sm" style="background: #f8fafc;">
                        <h5 class="fw-bold text-secondary mb-1" style="font-size: 14px;">⏱️ Tốc độ phản xạ thực tế (Từ Vựng):</h5>
                        <div class="d-flex align-items-baseline gap-1 mt-1">
                            <span class="fw-bold text-dark font-monospace" style="font-size: 20px;">{{ round($avgVocabDuration) }}s</span>
                            <span class="text-muted small">giây / từ vựng</span>
                        </div>
                        <p class="text-muted small mb-0 mt-1" style="line-height: 1.3;">
                            Chỉ số phân tích: **{{ $actualLearningSpeed }}** tính bằng thời gian trung bình học sinh suy nghĩ và gõ câu trả lời trắc nghiệm ôn tập.
                        </p>
                    </div>

                    <!-- Analytics Tip -->
                    <div class="small text-muted italic" style="font-style: italic; line-height: 1.3;">
                        💡 <strong>Lời khuyên:</strong> @if(round($avgVocabDuration) <= 45 && $vocabReviewsCount > 0)
                            Tốc độ phản xạ của bạn rất nhanh! Hãy thử duy trì tính chính xác và chuyển sang học từ nâng cao.
                        @elseif($vocabReviewsCount == 0)
                            Hãy làm thêm các bài thi ôn tập để hệ thống đo lường và đưa ra biểu đồ tốc độ phản xạ chuẩn xác nhất!
                        @else
                            Tốc độ của bạn ở mức trung bình. Hãy tập trung viết nhiều câu ví dụ hơn để tăng khả năng nhạy bén.
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- SECTION 3.5: PERSONALIZED AI RECOMMENDATIONS & RADAR SKILL MAP -->
    <div class="row g-4 mb-4 text-start">
        <!-- Radar Chart Năng Lực -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px; background: white;">
                <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 17px;">
                    📊 Bản Đồ Năng Lực 5 Chiều (Radar Skill Map)
                </h4>
                <p class="text-muted small mb-4">Phân tích khả năng phản xạ, trí nhớ sâu, tiến độ ngữ pháp và từ vựng từ dữ liệu thực tế.</p>
                <div style="position: relative; height: 260px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="skillRadarChart" style="max-height: 100%; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Daily AI Study Recommendations -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px; background: white;">
                <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 17px;">
                    💡 Lộ Trình Học Tập Đề Xuất (Daily Recommendations)
                </h4>
                <p class="text-muted small mb-4">Các nhiệm vụ thực tế được đề xuất tự động dựa trên thuật toán Spaced Repetition dành riêng cho bạn.</p>

                <div class="d-flex flex-column gap-3">
                    <!-- Gợi ý từ vựng cần ôn gấp -->
                    <div class="p-3 rounded-3" style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold" style="color: #b45309; font-size: 13px;">⚠️ Ôn tập từ vựng gấp (Weakest Words)</span>
                            <span class="badge rounded-pill" style="background: #fff7ed; color: #c2410c; font-size: 10px; border: 1px solid #ffedd5;">Spaced Repetition</span>
                        </div>
                        @if($weakestWords->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($weakestWords as $w)
                                    <div class="px-2.5 py-1 px-3 bg-white border d-flex align-items-center gap-2" style="border-radius: 8px; font-size: 12.5px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                        <strong class="text-dark">{{ $w['word'] }}</strong>
                                        @if($w['phonetic'])
                                            <span class="text-muted" style="font-size: 11px;">/{{ $w['phonetic'] }}/</span>
                                        @endif
                                        @if($w['score'] !== null)
                                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger fw-bold" style="font-size: 9px; padding: 2px 6px;">{{ $w['score'] }}đ</span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary fw-bold" style="font-size: 9px; padding: 2px 6px;">Chưa ôn</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('vocabulary.review', $weakestWords->first()['id']) }}" class="btn btn-sm w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-1"
                               style="background: linear-gradient(135deg, #d97706, #b45309); color: white; border-radius: 10px; font-size: 12px; padding: 8px 12px; border: none; transition: all 0.2s;"
                               onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(217,119,6,0.25)'"
                               onmouseout="this.style.transform='';this.style.boxShadow=''">
                                ⚡ Bắt đầu Active Recall ôn tập ngay
                            </a>
                        @else
                            <p class="text-muted small mb-0">Tuyệt vời! Bạn không có từ vựng nào cần ôn tập gấp.</p>
                        @endif
                    </div>

                    <!-- Gợi ý bài học ngữ pháp dở dang hoặc tiếp theo -->
                    <div class="p-3 rounded-3" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold" style="color: #15803d; font-size: 13px;">🎯 Bài học ngữ pháp khuyên học</span>
                            <span class="badge rounded-pill" style="background: #f0fdf4; color: #166534; font-size: 10px; border: 1px solid #dcfce7;">Grammar Roadmap</span>
                        </div>
                        @if($suggestedGrammarLesson)
                            <div class="mb-3">
                                <h6 class="fw-bold text-dark mb-1.5" style="font-size: 13.5px;">{{ $suggestedGrammarLesson['title'] }}</h6>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    {{-- 3 pips --}}
                                    <div class="d-flex gap-1">
                                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $suggestedGrammarLesson['sections_status']['formula'] ? '#10b981' : '#e2e8f0' }};" title="Công thức"></div>
                                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $suggestedGrammarLesson['sections_status']['usage'] ? '#10b981' : '#e2e8f0' }};" title="Cách dùng"></div>
                                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $suggestedGrammarLesson['sections_status']['example'] ? '#10b981' : '#e2e8f0' }};" title="Ví dụ"></div>
                                    </div>
                                    <span class="text-muted" style="font-size: 11px;">Đã hoàn thành {{ $suggestedGrammarLesson['passed_sections'] }}/3 phần lý thuyết</span>
                                </div>
                            </div>
                            <a href="{{ route('grammar.lesson.show', $suggestedGrammarLesson['id']) }}" class="btn btn-sm w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-1"
                               style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 10px; font-size: 12px; padding: 8px 12px; border: none; transition: all 0.2s;"
                               onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(16,185,129,0.25)'"
                               onmouseout="this.style.transform='';this.style.boxShadow=''">
                                📖 Vào bài học & Chinh phục ngay
                            </a>
                        @else
                            <p class="text-muted small mb-0">Hoan hô! Bạn đã hoàn thành xuất sắc tất cả bài học ngữ pháp.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: ACHIEVEMENTS — Thành Tựu Học Tập -->
    <div class="card border-0 shadow-sm p-4 mb-4 text-start" style="border-radius: 20px; background: white;" id="achievements-section">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 flex-wrap gap-2">
            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 18px;">
                🏅 Thành Tựu Học Tập
                <span class="badge rounded-pill ms-1 fw-bold" style="background: {{ $earnedCount === $totalAchievements ? '#ecfdf5' : '#eff6ff' }}; color: {{ $earnedCount === $totalAchievements ? '#059669' : '#2563eb' }}; font-size: 12px;">
                    {{ $earnedCount }}/{{ $totalAchievements }} đã đạt
                </span>
            </h4>
            {{-- Overall progress --}}
            <div style="display: flex; align-items: center; gap: 8px; min-width: 160px;">
                <div class="flex-grow-1" style="height: 7px; background: #f1f5f9; border-radius: 6px; overflow: hidden;">
                    <div style="height: 100%; width: {{ $totalAchievements > 0 ? round(($earnedCount / $totalAchievements) * 100) : 0 }}%; background: linear-gradient(90deg, #f59e0b, #10b981); border-radius: 6px; transition: width 0.6s ease;"></div>
                </div>
                <span class="fw-bold text-muted" style="font-size: 12px; white-space: nowrap;">{{ $totalAchievements > 0 ? round(($earnedCount / $totalAchievements) * 100) : 0 }}%</span>
            </div>
        </div>

        @php
            $achCategories = [
                'vocab'   => ['label' => '📘 Từ Vựng',   'color' => '#2563eb', 'bg' => '#eff6ff'],
                'grammar' => ['label' => '📖 Ngữ Pháp',  'color' => '#7c3aed', 'bg' => '#f3e8ff'],
                'review'  => ['label' => '📝 Ôn Tập',    'color' => '#0891b2', 'bg' => '#ecfeff'],
                'streak'  => ['label' => '🔥 Streak',    'color' => '#d97706', 'bg' => '#fffbeb'],
            ];
        @endphp

        @foreach($achCategories as $catKey => $catMeta)
        @php $catItems = $achievements->where('cat', $catKey); @endphp
        @if($catItems->isNotEmpty())
        <div class="mb-4">
            {{-- Category label --}}
            <p class="fw-bold mb-3" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px; color: {{ $catMeta['color'] }};">
                {{ $catMeta['label'] }}
            </p>
            <div class="row g-3">
                @foreach($catItems as $ach)
                @php
                    $earned = $ach['earned'];
                    $pct    = $ach['progress'];
                @endphp
                <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                    <div class="p-3 rounded-3 text-center h-100 position-relative"
                         style="
                            border: 2px solid {{ $earned ? $catMeta['color'] . '44' : '#f1f5f9' }};
                            background: {{ $earned ? $catMeta['bg'] : '#fafafa' }};
                            filter: {{ $earned ? 'none' : 'grayscale(0.6) opacity(0.75)' }};
                            transition: transform 0.15s, box-shadow 0.15s;
                            cursor: default;
                         "
                         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 18px rgba(0,0,0,0.09)'"
                         onmouseout="this.style.transform='';this.style.boxShadow=''"
                         title="{{ $ach['desc'] }} ({{ $ach['current'] }}/{{ $ach['target'] }})">

                        {{-- Earned glow ring --}}
                        @if($earned)
                        <div style="position: absolute; inset: -1px; border-radius: 12px; box-shadow: 0 0 0 3px {{ $catMeta['color'] }}22; pointer-events: none;"></div>
                        @endif

                        {{-- Icon --}}
                        <div style="font-size: 30px; line-height: 1.2; margin-bottom: 6px; {{ $earned ? '' : 'filter: grayscale(1) opacity(0.5);' }}">
                            {{ $ach['icon'] }}
                        </div>

                        {{-- Title --}}
                        <div class="fw-bold" style="font-size: 12px; color: {{ $earned ? $catMeta['color'] : '#94a3b8' }}; line-height: 1.3; margin-bottom: 4px;">
                            {{ $ach['title'] }}
                        </div>

                        {{-- Desc --}}
                        <div class="text-muted" style="font-size: 10px; line-height: 1.3; margin-bottom: 6px;">
                            {{ $ach['desc'] }}
                        </div>

                        @if($earned)
                            {{-- Earned badge --}}
                            <span class="badge rounded-pill fw-bold" style="font-size: 9.5px; background: {{ $catMeta['color'] }}; color: white; padding: 2px 8px;">✓ Đạt được</span>
                            @if($ach['unlocked_at'])
                                <div class="text-muted mt-1.5" style="font-size: 8.5px; opacity: 0.85;">
                                    Đạt ngày: {{ \Carbon\Carbon::parse($ach['unlocked_at'])->format('d/m/Y') }}
                                </div>
                            @endif
                        @else
                            {{-- Progress bar toward unlock --}}
                            <div style="height: 4px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 4px;">
                                <div style="height: 100%; width: {{ $pct }}%; background: {{ $catMeta['color'] }}; border-radius: 4px;"></div>
                            </div>
                            <div class="text-muted" style="font-size: 9.5px;">{{ $ach['current'] }}/{{ $ach['target'] }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <!-- SECTION 5: KNOWLEDGE PROGRESS — Kiến Thức Đã Học & Hoàn Thành -->

    <div class="card border-0 shadow-sm p-4 mb-4 text-start" style="border-radius: 20px; background: white;" id="knowledge-progress-section">

        {{-- Section Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 flex-wrap gap-2">
            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 18px;">
                🎓 Kiến Thức Đã Học & Hoàn Thành
            </h4>
            {{-- Module Tabs --}}
            <div class="d-flex gap-2" id="kp-tabs">
                <button class="btn btn-sm fw-bold kp-tab active" data-tab="roadmap"
                        style="border-radius: 8px; background: #2563eb; color: white; border: 1.5px solid #2563eb; font-size: 12.5px; padding: 5px 16px;">
                    🗺️ Lộ Trình Học
                </button>
                <button class="btn btn-sm fw-bold kp-tab" data-tab="grammar"
                        style="border-radius: 8px; background: white; color: #64748b; border: 1.5px solid #e2e8f0; font-size: 12.5px; padding: 5px 16px;">
                    📖 Ngữ Pháp
                </button>
                <button class="btn btn-sm fw-bold kp-tab" data-tab="vocab"
                        style="border-radius: 8px; background: white; color: #64748b; border: 1.5px solid #e2e8f0; font-size: 12.5px; padding: 5px 16px;">
                    📘 Từ Vựng
                </button>
            </div>
        </div>

        {{-- ── CUSTOM ROADMAP PANEL ── --}}
        <div id="kp-roadmap-panel">
            <div class="p-3 mb-4 rounded-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #93c5fd;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark" style="font-size: 14px;">🗺️ Tiến trình Lộ trình học tự xây dựng</span>
                    <span class="fw-bold text-primary" style="font-size: 14px; font-family: monospace;" id="roadmapStepCountText">{{ $completedRoadmapSteps }} / {{ $totalRoadmapSteps }} mốc hoàn thành</span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 8px; background: #dbeafe;">
                    <div class="progress-bar" id="roadmapProgressBar" role="progressbar"
                         style="width: {{ $roadmapProgressPct }}%; border-radius: 8px; background: linear-gradient(90deg, #2563eb, #0d9488);"
                         aria-valuenow="{{ $roadmapProgressPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size: 11px; color: #1e40af;">
                    <span id="roadmapProgressPctText">{{ $roadmapProgressPct }}% hoàn thành</span>
                    <span>Tự thiết lập trong Cài đặt Hồ sơ</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted small mb-0">Tích chọn vào ô bên dưới để cập nhật trạng thái hoàn thành mốc học tập cá nhân:</p>
                <button class="btn btn-sm btn-outline-primary fw-bold px-3" style="border-radius: 8px; font-size: 12px;" onclick="scrollToRoadmapSettings()">
                    ⚙️ Quản lý / Chỉnh sửa Lộ Trình
                </button>
            </div>

            <div class="d-flex flex-column gap-3 mb-2" id="roadmapListContainer">
                @foreach($customRoadmap as $idx => $step)
                    @php
                        $stepId = $step['id'] ?? ('step_' . ($idx + 1));
                        $isCompleted = ($step['status'] ?? '') === 'completed';
                        $isInProgress = ($step['status'] ?? '') === 'in_progress';
                        $catLabel = $step['category_label'] ?? match($step['category'] ?? 'custom') {
                            'vocab' => '📘 Từ vựng',
                            'grammar' => '📖 Ngữ pháp',
                            'listening' => '🎧 Luyện nghe',
                            'assessment' => '📝 Đề thi',
                            default => '🎯 Khác'
                        };
                    @endphp
                    <div class="p-3 rounded-3 border transition-all roadmap-card-item {{ $isCompleted ? 'bg-success bg-opacity-10 border-success border-opacity-25' : ($isInProgress ? 'bg-warning bg-opacity-10 border-warning border-opacity-25' : 'bg-light border-gray-200') }}"
                         id="roadmap-card-{{ $stepId }}" style="border-radius: 12px !important; transition: all 0.2s ease;">
                        <div class="d-flex align-items-start gap-3">
                            <div class="pt-1">
                                <input class="form-check-input roadmap-toggle-check" type="checkbox" 
                                       data-step-id="{{ $stepId }}"
                                       {{ $isCompleted ? 'checked' : '' }} 
                                       style="width: 22px; height: 22px; cursor: pointer;">
                            </div>
                            <div class="flex-grow-1 text-start">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                    <h6 class="fw-bold mb-0 {{ $isCompleted ? 'text-decoration-line-through text-muted' : 'text-dark' }}" style="font-size: 15px;" id="step-title-{{ $stepId }}">
                                        {{ $step['title'] ?? 'Mốc học tập' }}
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill bg-white text-dark border px-2.5 py-1 small fw-bold" style="font-size: 11px;">
                                            {{ $catLabel }}
                                        </span>
                                        <span class="badge rounded-pill step-status-badge {{ $isCompleted ? 'bg-success' : ($isInProgress ? 'bg-warning text-dark' : 'bg-secondary') }}" 
                                              id="step-status-badge-{{ $stepId }}" style="font-size: 11px;">
                                            {{ $isCompleted ? '✓ Hoàn thành' : ($isInProgress ? '⚡ Đang học' : '⚪ Chưa bắt đầu') }}
                                        </span>
                                    </div>
                                </div>
                                @if(!empty($step['notes']))
                                    <p class="text-muted small mb-1" style="font-size: 12.5px;">{{ $step['notes'] }}</p>
                                @endif
                                @if(!empty($step['target_date']))
                                    <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 11.5px;">
                                        📅 <span>Hạn hoàn thành: <strong>{{ \Carbon\Carbon::parse($step['target_date'])->format('d/m/Y') }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── GRAMMAR PANEL ── --}}
        <div id="kp-grammar-panel" style="display: none;">
            @if($grammarCategories->isEmpty())
                <div class="text-center py-5">
                    <div style="font-size: 52px; opacity: 0.3; margin-bottom: 10px;">📖</div>
                    <p class="text-muted fw-semibold mb-0">Chưa có dữ liệu ngữ pháp nào.</p>
                </div>
            @else
                {{-- Overall Grammar Progress Bar --}}
                @php
                    $grammarTotalAll = $grammarCategories->sum('total');
                    $grammarDoneAll  = $grammarCategories->sum('done');
                    $grammarPctAll   = $grammarTotalAll > 0 ? round(($grammarDoneAll / $grammarTotalAll) * 100) : 0;
                @endphp
                <div class="p-3 mb-4 rounded-3" style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #bae6fd;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark" style="font-size: 14px;">📊 Tổng tiến trình Ngữ Pháp</span>
                        <span class="fw-bold" style="color: #0369a1; font-size: 14px; font-family: monospace;">{{ $grammarDoneAll }} / {{ $grammarTotalAll }} bài</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 8px; background: #e0f2fe;">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $grammarPctAll }}%; border-radius: 8px; background: linear-gradient(90deg, #3b82f6, #06b6d4);"
                             aria-valuenow="{{ $grammarPctAll }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1" style="font-size: 11px; color: #0369a1;">
                        <span>{{ $grammarPctAll }}% hoàn thành</span>
                        <span>Còn lại: {{ $grammarTotalAll - $grammarDoneAll }} bài</span>
                    </div>
                </div>

                {{-- Category Accordions --}}

                {{-- Legend giải thích ký hiệu --}}
                <div class="d-flex flex-wrap gap-3 mb-3 px-1" style="font-size: 11.5px; color: #64748b;">
                    <span class="d-flex align-items-center gap-1">
                        <span style="width:10px;height:10px;border-radius:50%;background:#10b981;display:inline-block;"></span> Hoàn thành (3/3 phần)
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block;"></span> Đang học
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span style="width:10px;height:10px;border-radius:50%;background:#e2e8f0;display:inline-block;"></span> Chưa bắt đầu
                    </span>
                    <span class="d-flex align-items-center gap-1 ms-2" style="border-left: 1px solid #e2e8f0; padding-left: 12px;">
                        <span style="display:inline-flex;gap:2px;">
                            <span style="width:9px;height:9px;border-radius:50%;background:#10b981;display:inline-block;"></span>
                            <span style="width:9px;height:9px;border-radius:50%;background:#10b981;display:inline-block;"></span>
                            <span style="width:9px;height:9px;border-radius:50%;background:#e2e8f0;display:inline-block;"></span>
                        </span>
                        3 chấm = 3 phần: <strong style="margin-left:3px;">Công thức · Cách dùng · Ví dụ</strong>
                    </span>
                </div>

                <div class="accordion accordion-flush" id="grammar-category-accordion">
                    @foreach($grammarCategories as $catIdx => $cat)
                    <div class="accordion-item border mb-2" style="border-radius: 12px !important; overflow: hidden; border-color: #f1f5f9 !important;">

                        {{-- Category Header --}}
                        <h2 class="accordion-header" id="cat-heading-{{ $cat['id'] }}">
                            <button class="accordion-button {{ $catIdx > 0 ? 'collapsed' : '' }} fw-semibold"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#cat-body-{{ $cat['id'] }}"
                                    aria-expanded="{{ $catIdx === 0 ? 'true' : 'false' }}"
                                    aria-controls="cat-body-{{ $cat['id'] }}"
                                    style="background: #f8fafc; border-radius: 12px; font-size: 14.5px; gap: 12px;">

                                {{-- Status dot --}}
                                @if($cat['pct'] >= 100)
                                    <span style="width:10px;height:10px;border-radius:50%;background:#10b981;flex-shrink:0;display:inline-block;"></span>
                                @elseif($cat['pct'] > 0)
                                    <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;flex-shrink:0;display:inline-block;"></span>
                                @else
                                    <span style="width:10px;height:10px;border-radius:50%;background:#e2e8f0;flex-shrink:0;display:inline-block;"></span>
                                @endif

                                <span class="flex-grow-1">{{ $cat['name'] }}</span>

                                {{-- Mini progress pill --}}
                                <span class="badge rounded-pill ms-auto me-3 fw-bold" style="font-size: 11px; background: {{ $cat['pct'] >= 100 ? '#ecfdf5' : '#eff6ff' }}; color: {{ $cat['pct'] >= 100 ? '#059669' : '#2563eb' }}; min-width: 80px; text-align: center;">
                                    {{ $cat['done'] }}/{{ $cat['total'] }} bài — {{ $cat['pct'] }}%
                                </span>
                            </button>
                        </h2>

                        {{-- Category Body --}}
                        <div id="cat-body-{{ $cat['id'] }}"
                             class="accordion-collapse collapse {{ $catIdx === 0 ? 'show' : '' }}"
                             aria-labelledby="cat-heading-{{ $cat['id'] }}"
                             data-bs-parent="#grammar-category-accordion">
                            <div class="accordion-body pt-2 pb-3 px-3">
                                @foreach($cat['topics'] as $topic)
                                <div class="mb-3">
                                    {{-- Topic label --}}
                                    <p class="text-muted fw-bold mb-2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                        📂 {{ $topic['title'] }}
                                        <span class="ms-1 fw-normal" style="color: #94a3b8;">({{ $topic['done'] }}/{{ $topic['total'] }})</span>
                                    </p>

                                    {{-- Lesson rows --}}
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($topic['lessons'] as $lesson)
                                        @php
                                            $dotBg    = match($lesson['status']) { 'completed' => '#10b981', 'in_progress' => '#f59e0b', default => '#e2e8f0' };
                                            $barColor = match($lesson['status']) { 'completed' => 'linear-gradient(90deg,#10b981,#06b6d4)', 'in_progress' => 'linear-gradient(90deg,#f59e0b,#fbbf24)', default => '#e2e8f0' };
                                            $statusText = match($lesson['status']) { 'completed' => 'Hoàn thành', 'in_progress' => 'Đang học', default => 'Chưa bắt đầu' };
                                        @endphp
                                        <div class="d-flex align-items-center gap-3 px-2 py-2 rounded-3"
                                             style="background: {{ $lesson['status'] === 'completed' ? '#f0fdf4' : ($lesson['status'] === 'in_progress' ? '#fffbeb' : '#fafafa') }}; border: 1px solid {{ $lesson['status'] === 'completed' ? '#bbf7d0' : ($lesson['status'] === 'in_progress' ? '#fde68a' : '#f1f5f9') }};">
                                            {{-- Status dot --}}
                                            <div style="width:10px;height:10px;border-radius:50%;background:{{ $dotBg }};flex-shrink:0;"></div>

                                            {{-- Lesson title --}}
                                            <span class="fw-semibold text-dark flex-grow-1" style="font-size: 13.5px;">
                                                <a href="{{ route('grammar.lesson.show', $lesson['id']) }}"
                                                   style="color: inherit; text-decoration: none;"
                                                   onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='inherit'">
                                                    {{ $lesson['title'] }}
                                                </a>
                                            </span>

                                            {{-- Section pips (3 sections: Công thức / Cách dùng / Ví dụ) --}}
                                            <div class="d-flex gap-1" title="{{ $lesson['passed_sections'] }}/3 phần đã đạt chuẩn">
                                                <div style="width:9px;height:9px;border-radius:50%;background:{{ $lesson['sections_status']['formula'] ? '#10b981' : '#e2e8f0' }};" title="Công thức: {{ $lesson['sections_status']['formula'] ? 'Đã đạt ✓' : 'Chưa đạt' }}"></div>
                                                <div style="width:9px;height:9px;border-radius:50%;background:{{ $lesson['sections_status']['usage'] ? '#10b981' : '#e2e8f0' }};" title="Cách dùng: {{ $lesson['sections_status']['usage'] ? 'Đã đạt ✓' : 'Chưa đạt' }}"></div>
                                                <div style="width:9px;height:9px;border-radius:50%;background:{{ $lesson['sections_status']['example'] ? '#10b981' : '#e2e8f0' }};" title="Ví dụ: {{ $lesson['sections_status']['example'] ? 'Đã đạt ✓' : 'Chưa đạt' }}"></div>
                                            </div>

                                            {{-- Progress bar with % label --}}
                                            <div style="width: 70px; flex-shrink: 0;">
                                                <div style="height: 5px; border-radius: 4px; background: #e2e8f0; overflow: hidden; margin-bottom: 2px;">
                                                    <div style="height:100%; width:{{ $lesson['pct'] }}%; background:{{ $barColor }};"></div>
                                                </div>
                                                <div style="font-size: 9.5px; color: #94a3b8; text-align: right;">{{ $lesson['pct'] }}%</div>
                                            </div>

                                            {{-- Badge --}}
                                            <span class="badge rounded-pill fw-bold" style="font-size: 10px; min-width: 76px; text-align:center; background: {{ $lesson['status'] === 'completed' ? '#ecfdf5' : ($lesson['status'] === 'in_progress' ? '#fff7ed' : '#f1f5f9') }}; color: {{ $lesson['status'] === 'completed' ? '#059669' : ($lesson['status'] === 'in_progress' ? '#d97706' : '#94a3b8') }};">
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── VOCABULARY PANEL ── --}}
        <div id="kp-vocab-panel" style="display: none;">
            @if($studiedWords->isEmpty())
                <div class="text-center py-5">
                    <div style="font-size: 52px; opacity: 0.3; margin-bottom: 10px;">📘</div>
                    <p class="text-muted fw-semibold mb-0">Chưa có từ vựng nào được học.</p>
                </div>
            @else
                {{-- Overall Vocab Progress Bar --}}
                @php $vocabPct = $totalVocabStudied > 0 ? round(($totalVocabMastered / $totalVocabStudied) * 100) : 0; @endphp
                <div class="p-3 mb-4 rounded-3" style="background: linear-gradient(135deg, #faf5ff, #ede9fe); border: 1px solid #c4b5fd;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark" style="font-size: 14px;">📊 Tổng tiến trình Từ Vựng</span>
                        <span class="fw-bold" style="color: #7c3aed; font-size: 14px; font-family: monospace;">{{ $totalVocabMastered }} / {{ $totalVocabStudied }} từ thành thạo</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 8px; background: #ede9fe;">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $vocabPct }}%; border-radius: 8px; background: linear-gradient(90deg, #a855f7, #7c3aed);"
                             aria-valuenow="{{ $vocabPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1" style="font-size: 11px; color: #7c3aed;">
                        <span>{{ $vocabPct }}% thành thạo</span>
                        <span>Đang luyện: {{ $totalVocabStudied - $totalVocabMastered }} từ</span>
                    </div>
                </div>

                {{-- Stage filter chips --}}
                <div class="d-flex gap-2 flex-wrap mb-3" id="vocab-stage-filter">
                    <button class="btn btn-sm fw-bold vocab-stage-btn active" data-stage="all"
                            style="border-radius: 20px; font-size: 12px; background: #7c3aed; color: white; border: 1.5px solid #7c3aed; padding: 3px 14px;">
                        Tất cả ({{ $totalVocabStudied }})
                    </button>
                    <button class="btn btn-sm fw-bold vocab-stage-btn" data-stage="mastered"
                            style="border-radius: 20px; font-size: 12px; background: white; color: #059669; border: 1.5px solid #bbf7d0; padding: 3px 14px;">
                        ✔ Thành thạo ({{ $totalVocabMastered }})
                    </button>
                    <button class="btn btn-sm fw-bold vocab-stage-btn" data-stage="learning"
                            style="border-radius: 20px; font-size: 12px; background: white; color: #d97706; border: 1.5px solid #fde68a; padding: 3px 14px;">
                        ⚡ Đang luyện ({{ $totalVocabStudied - $totalVocabMastered }})
                    </button>
                </div>

                {{-- Word grid --}}
                <div class="row g-2" id="vocab-word-grid">
                    @foreach($studiedWords as $vw)
                    @php
                        $isMastered = $vw['mastered'];
                        $stageBg    = match($vw['stage_label']) { 'Nâng cao' => '#fdf2f8', 'Phát triển' => '#f0fdf4', default => '#eff6ff' };
                        $stageColor = match($vw['stage_label']) { 'Nâng cao' => '#be185d', 'Phát triển' => '#059669', default => '#1d4ed8' };
                    @endphp
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 vocab-word-card"
                         data-mastered="{{ $isMastered ? 'true' : 'false' }}">
                        <div class="p-2 rounded-3 text-center h-100 position-relative"
                             style="background: {{ $isMastered ? '#f0fdf4' : '#fafafa' }}; border: 1.5px solid {{ $isMastered ? '#bbf7d0' : '#f1f5f9' }}; transition: box-shadow 0.15s; cursor: default;"
                             onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow=''">

                            {{-- Mastered check icon --}}
                            @if($isMastered)
                                <span class="position-absolute top-0 end-0 m-1" style="font-size: 13px;">✅</span>
                            @endif

                            {{-- Word --}}
                            <div class="fw-bold text-dark" style="font-size: 15px; letter-spacing: -0.3px;">{{ $vw['word'] }}</div>

                            {{-- Phonetic --}}
                            @if($vw['phonetic'])
                                <div class="text-muted" style="font-size: 10.5px; font-style: italic; line-height: 1.2;">{{ $vw['phonetic'] }}</div>
                            @endif

                            {{-- Stage badge --}}
                            <span class="badge rounded-pill mt-1" style="font-size: 9.5px; font-weight: 700; background: {{ $stageBg }}; color: {{ $stageColor }}; padding: 2px 8px;">
                                {{ $vw['stage_label'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- SECTION 5: REVIEW TIMELINE — Quá Trình Ôn Tập -->

    <div class="card border-0 shadow-sm p-4 mb-4 text-start" style="border-radius: 20px; background: white;" id="review-timeline-section">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 flex-wrap gap-2">
            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 18px;">
                📅 Quá Trình Ôn Tập
                <span class="badge rounded-pill ms-1" style="background: #eff6ff; color: #2563eb; font-size: 12px; font-weight: 600;">
                    {{ $reviewTimeline->count() }} lượt
                </span>
            </h4>
            <!-- Filter Tabs -->
            <div class="btn-group btn-group-sm" role="group" id="review-filter-tabs">
                <button type="button" class="btn review-filter-btn active" data-filter="all" style="border-radius: 8px 0 0 8px; border: 1.5px solid #e2e8f0; background: #2563eb; color: white; font-size: 12.5px; font-weight: 600; padding: 5px 14px;">Tất cả</button>
                <button type="button" class="btn review-filter-btn" data-filter="vocab" style="border: 1.5px solid #e2e8f0; background: white; color: #64748b; font-size: 12.5px; font-weight: 600; padding: 5px 14px;">📘 Từ Vựng</button>
                <button type="button" class="btn review-filter-btn" data-filter="grammar" style="border-radius: 0 8px 8px 0; border: 1.5px solid #e2e8f0; background: white; color: #64748b; font-size: 12.5px; font-weight: 600; padding: 5px 14px;">📖 Ngữ Pháp</button>
            </div>
        </div>

        @if($reviewTimeline->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-5">
                <div style="font-size: 56px; line-height: 1; opacity: 0.35; margin-bottom: 12px;">📋</div>
                <p class="text-muted fw-semibold mb-1" style="font-size: 15px;">Chưa có buổi ôn tập nào được ghi lại.</p>
                <p class="text-muted small mb-0">Hãy hoàn thành một bài ôn tập Từ Vựng hoặc Ngữ Pháp để bắt đầu xây dựng lịch sử học tập của bạn!</p>
            </div>
        @else
            <!-- Monthly Summary Mini-Cards -->
            @if($monthlyStats->isNotEmpty())
            <div class="row g-3 mb-4">
                @foreach($monthlyStats->sortKeys()->reverse()->take(4) as $month => $count)
                @php
                    $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('vi')->isoFormat('MMMM [năm] YYYY');
                @endphp
                <div class="col-6 col-md-3">
                    <div class="p-3 text-center rounded-3" style="background: #f8fafc; border: 1.5px solid #f1f5f9;">
                        <div class="fw-bold text-dark" style="font-size: 22px;">{{ $count }}</div>
                        <div class="text-muted" style="font-size: 11px; text-transform: capitalize;">{{ $monthLabel }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Timeline List -->
            <div class="position-relative" id="review-timeline-list">
                <!-- Vertical line -->
                <div style="position: absolute; left: 23px; top: 16px; bottom: 16px; width: 2px; background: linear-gradient(to bottom, #3b82f6, #8b5cf6, #e2e8f0); border-radius: 4px; z-index: 0;"></div>

                <div id="timeline-items-wrapper">
                    @foreach($reviewTimeline as $index => $review)
                    @php
                        $dotColor  = $review['passed'] ? '#10b981' : '#f59e0b';
                        $typeColor = $review['type'] === 'vocab' ? '#2563eb' : '#a855f7';
                        $typeBg    = $review['type'] === 'vocab' ? '#eff6ff' : '#f3e8ff';
                        $hidden    = $index >= 10 ? 'timeline-extra d-none' : '';
                        $duration  = $review['duration'];
                        $durationStr = $duration >= 60
                            ? floor($duration/60) . 'p ' . ($duration % 60) . 'g'
                            : $duration . ' giây';
                        $scoreColor = $review['score'] >= 80 ? '#10b981' : ($review['score'] >= 60 ? '#f59e0b' : '#ef4444');
                        $dateStr = \Carbon\Carbon::parse($review['created_at'])->locale('vi')->diffForHumans();
                        $dateFullStr = \Carbon\Carbon::parse($review['created_at'])->format('d/m/Y H:i');
                    @endphp
                    <div class="d-flex align-items-start gap-3 mb-3 review-timeline-item {{ $hidden }}" data-type="{{ $review['type'] }}" style="position: relative; z-index: 1;">
                        <!-- Dot -->
                        <div class="flex-shrink-0 rounded-circle border-3 border-white shadow-sm d-flex align-items-center justify-content-center fw-bold text-white"
                             style="width: 46px; height: 46px; min-width: 46px; background: {{ $dotColor }}; font-size: 18px; box-shadow: 0 0 0 3px {{ $dotColor }}22; z-index: 2;">
                            {{ $review['passed'] ? '✓' : '!' }}
                        </div>
                        <!-- Content card -->
                        <div class="flex-grow-1 p-3 rounded-3 border" style="background: #fafafa; border-color: #f1f5f9 !important; transition: box-shadow 0.18s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.07)'" onmouseout="this.style.boxShadow=''">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-1 mb-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <!-- Type badge -->
                                    <span class="badge rounded-pill px-2 py-1" style="background: {{ $typeBg }}; color: {{ $typeColor }}; font-size: 11px; font-weight: 700; letter-spacing: 0.2px;">
                                        {{ $review['label'] }}
                                    </span>
                                    <!-- Subject -->
                                    <span class="fw-bold text-dark" style="font-size: 14.5px;">{{ $review['subject'] }}</span>
                                </div>
                                <!-- Score -->
                                <span class="fw-bold font-monospace" style="font-size: 18px; color: {{ $scoreColor }}; letter-spacing: -0.5px;">
                                    {{ $review['score'] }}đ
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size: 12px;">
                                <!-- Status badge -->
                                @if($review['passed'])
                                    <span class="badge rounded-pill px-2 py-1" style="background: #ecfdf5; color: #059669; font-size: 11px; font-weight: 700;">✔ Đạt chuẩn</span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1" style="background: #fff7ed; color: #d97706; font-size: 11px; font-weight: 700;">⚡ Chưa đạt</span>
                                @endif
                                <!-- Duration -->
                                <span class="text-muted">⏱ {{ $durationStr }}</span>
                                <!-- Date -->
                                <span class="text-muted" title="{{ $dateFullStr }}">🕐 {{ $dateStr }}</span>
                                <!-- Detail link for grammar -->
                                @if($review['detail_url'])
                                    <a href="{{ $review['detail_url'] }}" class="text-primary small fw-semibold ms-auto" style="text-decoration: none; font-size: 12px;">Xem chi tiết →</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Load more button -->
                @if($reviewTimeline->count() > 10)
                <div class="text-center mt-3 pt-2" id="load-more-wrapper">
                    <button type="button" id="load-more-btn" class="btn btn-sm px-5 py-2 fw-bold"
                            style="border-radius: 10px; background: #f8fafc; color: #475569; border: 1.5px solid #e2e8f0; font-size: 13px; transition: all 0.2s;">
                        ↓ Xem thêm {{ $reviewTimeline->count() - 10 }} lượt ôn trước đó
                    </button>
                </div>
                @endif
            </div>
        @endif
    </div>

    <!-- SECTION 5: EDIT PROFILE SETTINGS FORM -->
    <div class="card border-0 shadow-sm p-4 text-start" style="border-radius: 16px; background: white;" id="profile-edit-section">
        <h4 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2 border-bottom pb-3" style="font-size: 18px;">
            ⚙️ Cập Nhật Hồ Sơ & Điều Chỉnh Chỉ Số
        </h4>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
            @csrf
            
            <div class="row g-4">
                <!-- Left Column: Avatar & Basic Info -->
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Họ và Tên:</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required style="border-radius: 8px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Mục Tiêu Học Tập:</label>
                        <input type="text" name="target_goal" class="form-control" value="{{ $user->target_goal }}" placeholder="Ví dụ: Đạt IELTS 7.5 trong 6 tháng" style="border-radius: 8px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Lời Giới Thiệu (Bio):</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Viết một mô tả ngắn về bản thân..." style="border-radius: 8px;">{{ $user->bio }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Ảnh Đại Diện (Profile Photo):</label>
                        <input type="file" name="avatar" class="form-control" style="border-radius: 8px;">
                        <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Chấp nhận ảnh JPG, PNG dưới 2MB.</span>
                    </div>
                </div>

                <!-- Right Column: Settings & Cognitive Levels -->
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Trình Độ Hiện Tại (CEFR English Level):</label>
                        <select name="english_level" class="form-select" required style="border-radius: 8px;">
                            <option value="A1" {{ $user->english_level == 'A1' ? 'selected' : '' }}>A1 - Beginner (Cơ bản tinh anh)</option>
                            <option value="A2" {{ $user->english_level == 'A2' ? 'selected' : '' }}>A2 - Elementary (Sơ cấp)</option>
                            <option value="B1" {{ $user->english_level == 'B1' ? 'selected' : '' }}>B1 - Intermediate (Trung cấp)</option>
                            <option value="B2" {{ $user->english_level == 'B2' ? 'selected' : '' }}>B2 - Upper-Intermediate (Trung cao cấp)</option>
                            <option value="C1" {{ $user->english_level == 'C1' ? 'selected' : '' }}>C1 - Advanced (Nâng cao)</option>
                            <option value="C2" {{ $user->english_level == 'C2' ? 'selected' : '' }}>C2 - Proficient (Bậc thầy ngôn ngữ)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Tự Đánh Giá Tốc Độ Học:</label>
                        <select name="learning_speed" class="form-select" required style="border-radius: 8px;">
                            <option value="Nhanh" {{ $user->learning_speed == 'Nhanh' ? 'selected' : '' }}>Nhanh (Hiểu sâu, phản xạ nhanh nhạy)</option>
                            <option value="Trung bình" {{ $user->learning_speed == 'Trung bình' ? 'selected' : '' }}>Trung bình (Vừa học vừa củng cố kỹ lưỡng)</option>
                            <option value="Chậm" {{ $user->learning_speed == 'Chậm' ? 'selected' : '' }}>Chậm (Cần thời gian làm nhiều bài tập)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark d-flex justify-content-between">
                            <span>Khả Năng Ghi Nhớ Sâu (Retention Rate):</span>
                            <span id="retentionVal" class="fw-bold text-primary font-monospace">{{ $user->retention_rate }}%</span>
                        </label>
                        <input type="range" name="retention_rate" class="form-range" min="10" max="100" step="5" value="{{ $user->retention_rate }}" id="retentionRange">
                        <div class="d-flex justify-content-between" style="font-size: 11px;">
                            <span class="text-muted">10% (Hay quên)</span>
                            <span class="text-muted">100% (Trí nhớ siêu phàm)</span>
                        </div>
                    </div>
                </div>

                <!-- Custom Roadmap Settings Builder -->
                <div class="col-12 mt-4 pt-3 border-top" id="roadmap-settings-builder-block">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">🗺️ Thiết Lập Lộ Trình Học Cá Nhân</h5>
                            <p class="text-muted small mb-0">Tự thiết lập các mốc bài học, hạn hoàn thành và mục tiêu cá nhân của bạn</p>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold px-3" id="addRoadmapStepBtn" style="border-radius: 8px;">
                            ➕ Thêm Mốc Mới
                        </button>
                    </div>

                    <input type="hidden" name="custom_roadmap_json" id="custom_roadmap_json">

                    <div class="d-flex flex-column gap-3" id="builderStepList">
                        @foreach($customRoadmap as $idx => $step)
                            @php $stepId = $step['id'] ?? ('step_' . ($idx + 1)); @endphp
                            <div class="builder-step-row p-3 rounded-3 border bg-light position-relative" data-builder-id="{{ $stepId }}" style="border-radius: 12px !important;">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <label class="form-label mb-1 small fw-bold">Tên mốc học tập:</label>
                                        <input type="text" class="form-control form-control-sm builder-title" value="{{ $step['title'] ?? '' }}" placeholder="Ví dụ: Hoàn thành 20 từ vựng..." style="border-radius: 6px;">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 small fw-bold">Phân loại:</label>
                                        <select class="form-select form-select-sm builder-category" style="border-radius: 6px;">
                                            <option value="vocab" {{ ($step['category'] ?? '') == 'vocab' ? 'selected' : '' }}>📘 Từ vựng</option>
                                            <option value="grammar" {{ ($step['category'] ?? '') == 'grammar' ? 'selected' : '' }}>📖 Ngữ pháp</option>
                                            <option value="listening" {{ ($step['category'] ?? '') == 'listening' ? 'selected' : '' }}>🎧 Luyện nghe</option>
                                            <option value="assessment" {{ ($step['category'] ?? '') == 'assessment' ? 'selected' : '' }}>📝 Đề thi</option>
                                            <option value="custom" {{ ($step['category'] ?? '') == 'custom' ? 'selected' : '' }}>🎯 Khác</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 small fw-bold">Hạn hoàn thành:</label>
                                        <input type="date" class="form-control form-control-sm builder-date" value="{{ $step['target_date'] ?? '' }}" style="border-radius: 6px;">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <label class="form-label mb-1 d-block small fw-bold">&nbsp;</label>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-builder-step" style="border-radius: 6px;">🗑️</button>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <input type="text" class="form-control form-control-sm builder-notes" value="{{ $step['notes'] ?? '' }}" placeholder="Ghi chú chi tiết (nếu có)..." style="border-radius: 6px;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-primary px-5 py-2.5 fw-bold shadow-sm" style="border-radius: 10px; background-color: #2563eb;">
                    💾 Lưu Cập Nhật Hồ Sơ & Lộ Trình
                </button>
            </div>

        </form>
    </div>

        </form>
    </div>

</div>

<!-- Interactive slider display script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // -- Retention Rate Slider --
        var slider = document.getElementById("retentionRange");
        var output = document.getElementById("retentionVal");
        if (slider && output) {
            slider.oninput = function() {
                output.innerHTML = this.value + "%";
            };
        }

        // -- Review Timeline Filter Tabs --
        var filterBtns = document.querySelectorAll('.review-filter-btn');
        var timelineItems = document.querySelectorAll('.review-timeline-item');

        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var filter = this.dataset.filter;
                filterBtns.forEach(function(b) {
                    b.style.background = 'white';
                    b.style.color = '#64748b';
                });
                this.style.background = '#2563eb';
                this.style.color = 'white';
                timelineItems.forEach(function(item) {
                    var isMatch = (filter === 'all') || (item.dataset.type === filter);
                    item.classList.toggle('d-none', !isMatch);
                });
                var loadMoreWrapper = document.getElementById('load-more-wrapper');
                if (loadMoreWrapper) {
                    loadMoreWrapper.style.display = (filter !== 'all') ? 'none' : '';
                }
            });
        });

        // -- Load More Timeline Items --
        var loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                document.querySelectorAll('.timeline-extra').forEach(function(el) {
                    el.classList.remove('d-none', 'timeline-extra');
                });
                document.getElementById('load-more-wrapper').style.display = 'none';
            });
        }

        // ── Knowledge Progress: Module Tab Switch (Lộ Trình ↔ Ngữ Pháp ↔ Từ Vựng) ──
        var kpTabs    = document.querySelectorAll('.kp-tab');
        var kpRoadmap = document.getElementById('kp-roadmap-panel');
        var kpGrammar = document.getElementById('kp-grammar-panel');
        var kpVocab   = document.getElementById('kp-vocab-panel');

        kpTabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var target = this.dataset.tab;

                // Toggle panel visibility
                if (kpRoadmap) kpRoadmap.style.display = (target === 'roadmap') ? '' : 'none';
                if (kpGrammar) kpGrammar.style.display = (target === 'grammar') ? '' : 'none';
                if (kpVocab)   kpVocab.style.display   = (target === 'vocab')   ? '' : 'none';

                // Update tab styles
                kpTabs.forEach(function(t) {
                    t.style.background = 'white';
                    t.style.color      = '#64748b';
                    t.style.borderColor = '#e2e8f0';
                });
                this.style.background  = '#2563eb';
                this.style.color       = 'white';
                this.style.borderColor = '#2563eb';
            });
        });

        // ── Interactive Roadmap Step Toggle ──
        var toggleChecks = document.querySelectorAll('.roadmap-toggle-check');
        toggleChecks.forEach(function(chk) {
            chk.addEventListener('change', function() {
                var stepId = this.dataset.stepId;
                var isChecked = this.checked;
                var newStatus = isChecked ? 'completed' : 'not_started';

                // Send AJAX POST
                fetch("{{ route('profile.roadmap.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        step_id: stepId,
                        status: newStatus
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update UI elements
                        var card = document.getElementById('roadmap-card-' + stepId);
                        var badge = document.getElementById('step-status-badge-' + stepId);
                        var title = document.getElementById('step-title-' + stepId);
                        
                        if (isChecked) {
                            if (card) {
                                card.className = 'p-3 rounded-3 border transition-all roadmap-card-item bg-success bg-opacity-10 border-success border-opacity-25';
                            }
                            if (badge) {
                                badge.className = 'badge rounded-pill step-status-badge bg-success';
                                badge.textContent = '✓ Hoàn thành';
                            }
                            if (title) {
                                title.classList.add('text-decoration-line-through', 'text-muted');
                                title.classList.remove('text-dark');
                            }
                        } else {
                            if (card) {
                                card.className = 'p-3 rounded-3 border transition-all roadmap-card-item bg-light border-gray-200';
                            }
                            if (badge) {
                                badge.className = 'badge rounded-pill step-status-badge bg-secondary';
                                badge.textContent = '⚪ Chưa bắt đầu';
                            }
                            if (title) {
                                title.classList.remove('text-decoration-line-through', 'text-muted');
                                title.classList.add('text-dark');
                            }
                        }

                        // Update progress bar
                        var countText = document.getElementById('roadmapStepCountText');
                        var pctText = document.getElementById('roadmapProgressPctText');
                        var progressBar = document.getElementById('roadmapProgressBar');

                        if (countText) countText.textContent = data.completed + ' / ' + data.total + ' mốc hoàn thành';
                        if (pctText) pctText.textContent = data.pct + '% hoàn thành';
                        if (progressBar) {
                            progressBar.style.width = data.pct + '%';
                            progressBar.setAttribute('aria-valuenow', data.pct);
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        });

        // ── Roadmap Settings Builder ──
        window.scrollToRoadmapSettings = function() {
            var target = document.getElementById('roadmap-settings-builder-block');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        };

        var addBtn = document.getElementById('addRoadmapStepBtn');
        var builderList = document.getElementById('builderStepList');
        var profileForm = document.getElementById('profileUpdateForm');

        if (addBtn && builderList) {
            addBtn.addEventListener('click', function() {
                var newId = 'step_' + Date.now();
                var newRow = document.createElement('div');
                newRow.className = 'builder-step-row p-3 rounded-3 border bg-light position-relative';
                newRow.style.borderRadius = '12px';
                newRow.dataset.builderId = newId;
                newRow.innerHTML = `
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label mb-1 small fw-bold">Tên mốc học tập:</label>
                            <input type="text" class="form-control form-control-sm builder-title" placeholder="Ví dụ: Hoàn thành bài nghe TOEIC..." style="border-radius: 6px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 small fw-bold">Phân loại:</label>
                            <select class="form-select form-select-sm builder-category" style="border-radius: 6px;">
                                <option value="vocab">📘 Từ vựng</option>
                                <option value="grammar">📖 Ngữ pháp</option>
                                <option value="listening">🎧 Luyện nghe</option>
                                <option value="assessment">📝 Đề thi</option>
                                <option value="custom" selected>🎯 Khác</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 small fw-bold">Hạn hoàn thành:</label>
                            <input type="date" class="form-control form-control-sm builder-date" style="border-radius: 6px;">
                        </div>
                        <div class="col-md-1 text-end">
                            <label class="form-label mb-1 d-block small fw-bold">&nbsp;</label>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-builder-step" style="border-radius: 6px;">🗑️</button>
                        </div>
                        <div class="col-12 mt-2">
                            <input type="text" class="form-control form-control-sm builder-notes" placeholder="Ghi chú chi tiết..." style="border-radius: 6px;">
                        </div>
                    </div>
                `;
                builderList.appendChild(newRow);
            });

            builderList.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-builder-step')) {
                    var row = e.target.closest('.builder-step-row');
                    if (row) {
                        row.remove();
                    }
                }
            });
        }

        if (profileForm) {
            profileForm.addEventListener('submit', function() {
                var steps = [];
                var rows = document.querySelectorAll('.builder-step-row');
                rows.forEach(function(row, idx) {
                    var title = row.querySelector('.builder-title')?.value || '';
                    if (title.trim() !== '') {
                        var cat = row.querySelector('.builder-category')?.value || 'custom';
                        var catLabel = matchCategoryLabel(cat);
                        var date = row.querySelector('.builder-date')?.value || '';
                        var notes = row.querySelector('.builder-notes')?.value || '';
                        var stepId = row.dataset.builderId || ('step_' + (idx + 1));
                        
                        var existingCard = document.getElementById('roadmap-card-' + stepId);
                        var existingCheck = existingCard?.querySelector('.roadmap-toggle-check');
                        var status = existingCheck ? (existingCheck.checked ? 'completed' : 'not_started') : 'not_started';

                        steps.push({
                            id: stepId,
                            title: title,
                            category: cat,
                            category_label: catLabel,
                            target_date: date,
                            status: status,
                            notes: notes
                        });
                    }
                });

                var jsonInput = document.getElementById('custom_roadmap_json');
                if (jsonInput) {
                    jsonInput.value = JSON.stringify(steps);
                }
            });
        }

        function matchCategoryLabel(cat) {
            switch(cat) {
                case 'vocab': return '📘 Từ vựng';
                case 'grammar': return '📖 Ngữ pháp';
                case 'listening': return '🎧 Luyện nghe';
                case 'assessment': return '📝 Đề thi';
                default: return '🎯 Khác';
            }
        }

        // ── Vocabulary Stage Filter Chips ──
        var vocabChips = document.querySelectorAll('.vocab-stage-btn');
        var wordCards  = document.querySelectorAll('.vocab-word-card');

        vocabChips.forEach(function(chip) {
            chip.addEventListener('click', function() {
                var stage = this.dataset.stage;

                // Reset all chip styles
                vocabChips.forEach(function(c) {
                    c.style.background = 'white';
                    c.style.color      = c.dataset.stage === 'mastered' ? '#059669'
                                       : c.dataset.stage === 'learning' ? '#d97706' : '#7c3aed';
                });
                // Active chip
                this.style.background = '#7c3aed';
                this.style.color      = 'white';

                // Filter word cards
                wordCards.forEach(function(card) {
                    var isMastered = card.dataset.mastered === 'true';
                    var show = (stage === 'all')
                             || (stage === 'mastered' && isMastered)
                             || (stage === 'learning' && !isMastered);
                    card.style.display = show ? '' : 'none';
                });
            });
        });

        // ── Radar Chart Năng Lực 5 Chiều ──
        var vocabScore = Math.min(100, Math.round(({{ $totalVocabStudied }} / 100) * 100));
        var grammarScore = {{ $grammarLessonsCount > 0 ? round(($grammarDoneTotal / $grammarLessonsCount) * 100) : 0 }};
        var speedScore = Math.max(20, Math.min(100, Math.round(100 - ({{ $avgVocabDuration }} > 5 ? ({{ $avgVocabDuration }} - 5) * 1.5 : 0))));
        var retentionScore = {{ $actualRetentionRate }};
        var disciplineScore = Math.min(100, Math.round(({{ $streakDays }} / 15) * 100));

        var ctx = document.getElementById('skillRadarChart').getContext('2d');
        if (ctx) {
            var skillRadarChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Từ Vựng Size', 'Ngữ Pháp Mastery', 'Tốc Độ Phản Xạ', 'Trí Nhớ (Recall)', 'Kỷ Luật (Streak)'],
                    datasets: [{
                        label: 'Chỉ số học tập',
                        data: [vocabScore, grammarScore, speedScore, retentionScore, disciplineScore],
                        backgroundColor: 'rgba(124, 58, 237, 0.12)',
                        borderColor: 'rgba(124, 58, 237, 0.85)',
                        borderWidth: 2,
                        pointBackgroundColor: '#06b6d4',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                color: '#e2e8f0',
                                display: true
                            },
                            grid: {
                                color: '#f1f5f9'
                            },
                            pointLabels: {
                                color: '#475569',
                                font: {
                                    size: 11,
                                    weight: '600',
                                    family: 'system-ui'
                                }
                            },
                            ticks: {
                                display: false,
                                stepSize: 20
                            },
                            min: 0,
                            max: 100
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
