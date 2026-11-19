@extends('vocabularies.vocabulary')

@section('content_voca')

{{-- Custom Roadmap Page Styles --}}
<style>
    .roadmap-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 16px;
        font-family: 'Segoe UI', 'Outfit', sans-serif;
    }

    /* ─── Green Header Banner ─── */
    .roadmap-header {
        background: linear-gradient(135deg, #1a6d3a, #22874a);
        border-radius: 16px 16px 0 0;
        padding: 28px 32px 20px;
        color: #fff;
    }
    .roadmap-header h1 {
        font-size: 26px;
        font-weight: 800;
        margin: 0 0 18px 0;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .roadmap-header h1 span.icon { font-size: 30px; }

    .header-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
    }
    .header-card {
        background: rgba(255,255,255, 0.15);
        border: 1px solid rgba(255,255,255, 0.2);
        border-radius: 12px;
        padding: 14px 16px;
        backdrop-filter: blur(4px);
    }
    .header-card .hc-icon { font-size: 28px; margin-bottom: 4px; display: block; }
    .header-card .hc-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        opacity: 0.85;
        margin-bottom: 4px;
    }
    .header-card .hc-value {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
    }

    /* ─── Table ─── */
    .roadmap-table-wrap {
        background: #fff;
        border: 1px solid #d1d5db;
        border-top: none;
        border-radius: 0 0 16px 16px;
        overflow: hidden;
    }
    .roadmap-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    .roadmap-table thead th {
        background: #276a3e;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 10px;
        text-align: center;
        border: 1px solid #1f5a33;
        white-space: nowrap;
    }
    .roadmap-table thead th:nth-child(2) {
        text-align: left;
        min-width: 260px;
    }
    .roadmap-table tbody tr {
        transition: background 0.15s;
    }
    .roadmap-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }
    .roadmap-table tbody tr:hover {
        background: #ecfdf5;
    }
    .roadmap-table tbody td {
        padding: 10px 10px;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
        text-align: center;
        color: #374151;
    }
    .roadmap-table tbody td:nth-child(2) {
        text-align: left;
    }

    /* STT column */
    .stt-cell {
        font-size: 15px;
        font-weight: 800;
        color: #276a3e;
        width: 40px;
    }

    /* Title cell */
    .title-cell .step-title {
        font-weight: 700;
        font-size: 14px;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .title-cell .step-title .step-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #dcfce7;
        font-size: 16px;
        flex-shrink: 0;
    }
    .title-cell .step-desc {
        font-size: 12px;
        color: #6b7280;
        margin: 2px 0 0 38px;
        line-height: 1.4;
    }

    /* Status radios */
    .status-cell {
        min-width: 110px;
    }
    .status-radio-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .status-radio-group label {
        display: flex;
        align-items: center;
        gap: 3px;
        cursor: pointer;
        font-size: 11px;
        color: #6b7280;
        white-space: nowrap;
    }
    .status-radio-group input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        border: 2px solid #9ca3af;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .status-radio-group input[type="radio"]:checked {
        border-color: #22874a;
    }
    .status-radio-group input[type="radio"]:checked::after {
        content: '';
        position: absolute;
        top: 2px; left: 2px;
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #22874a;
    }
    .status-radio-group input[type="radio"].radio-progress:checked {
        border-color: #f59e0b;
    }
    .status-radio-group input[type="radio"].radio-progress:checked::after {
        background: #f59e0b;
    }
    .status-radio-group input[type="radio"].radio-done:checked {
        border-color: #16a34a;
    }
    .status-radio-group input[type="radio"].radio-done:checked::after {
        background: #16a34a;
    }

    /* Notes editable */
    .notes-cell {
        min-width: 120px;
    }
    .notes-cell input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 12.5px;
        color: #374151;
        outline: none;
        padding: 2px 0;
    }
    .notes-cell input::placeholder {
        color: #cbd5e1;
        font-style: italic;
    }
    .notes-cell input:focus {
        border-bottom: 1.5px solid #22874a;
    }

    /* ─── Footer Summary ─── */
    .roadmap-footer {
        display: grid;
        grid-template-columns: 1fr 1fr 1.5fr;
        gap: 0;
        border: 1px solid #d1d5db;
        border-top: 2px solid #276a3e;
        border-radius: 0 0 16px 16px;
        overflow: hidden;
        margin-top: -1px;
        background: #fff;
    }
    .footer-block {
        padding: 16px 20px;
        border-right: 1px solid #e5e7eb;
    }
    .footer-block:last-child { border-right: none; }
    .footer-block .fb-title {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        color: #276a3e;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .footer-block .fb-title .fb-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #dcfce7;
        font-size: 14px;
    }
    .fb-stats {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #374151;
        flex-wrap: wrap;
    }
    .fb-stats strong { color: #111827; }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: #374151;
    }
    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        border: 2px solid #9ca3af;
    }
    .legend-dot.dot-none   { background: transparent; }
    .legend-dot.dot-progress { background: #f59e0b; border-color: #f59e0b; }
    .legend-dot.dot-done     { background: #16a34a; border-color: #16a34a; }

    .fb-notes-list {
        font-size: 12.5px;
        color: #374151;
        padding-left: 16px;
        margin: 0;
        line-height: 1.8;
    }

    /* Button float */
    .roadmap-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 16px;
    }

    @media (max-width: 900px) {
        .header-cards { grid-template-columns: 1fr 1fr; }
        .roadmap-footer { grid-template-columns: 1fr; }
        .roadmap-table { font-size: 12px; }
    }
</style>

<div class="roadmap-container">

    {{-- ═══ GREEN HEADER BANNER ═══ --}}
    <div class="roadmap-header">
        <h1>
            <span class="icon">📋</span>
            LỘ TRÌNH HỌC CÁ NHÂN
        </h1>

        <div class="header-cards">
            <div class="header-card">
                <span class="hc-icon">🎯</span>
                <div class="hc-label">Mục Tiêu</div>
                <div class="hc-value">{{ $user->target_goal ?? 'Nắm vững các cấu trúc ngữ pháp cơ bản đến nâng cao' }}</div>
            </div>
            <div class="header-card">
                <span class="hc-icon">📅</span>
                <div class="hc-label">Thời Gian Dự Kiến</div>
                <div class="hc-value">17 – 21 giờ</div>
            </div>
            <div class="header-card">
                <span class="hc-icon">📚</span>
                <div class="hc-label">Tài Liệu Chính</div>
                <div class="hc-value">Gốc ngữ pháp Tiếng Anh – Basic<br><small style="opacity:0.7">(hoặc tài liệu tương đương)</small></div>
            </div>
            <div class="header-card">
                <span class="hc-icon">🔄</span>
                <div class="hc-label">Cập Nhật</div>
                <div class="hc-value">Ghi chú tiến độ sau mỗi chuyên đề</div>
            </div>
        </div>
    </div>

    {{-- ═══ TABLE ═══ --}}
    <div class="roadmap-table-wrap">
        <table class="roadmap-table" id="roadmapMainTable">
            <thead>
                <tr>
                    <th style="width:44px">STT</th>
                    <th>DANH SÁCH CẤU TRÚC / KIẾN THỨC</th>
                    <th style="width:100px">SỐ TRANG DỰ KIẾN</th>
                    <th style="width:170px">TÀI LIỆU</th>
                    <th style="width:110px">THỜI GIAN ƯỚC TÍNH</th>
                    <th style="width:170px">TRẠNG THÁI</th>
                    <th style="width:140px">GHI CHÚ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customRoadmap as $idx => $step)
                @php
                    $stepId = $step['id'] ?? ('step_' . ($idx + 1));
                    $status = $step['status'] ?? 'not_started';
                @endphp
                <tr data-step-id="{{ $stepId }}">
                    {{-- STT --}}
                    <td class="stt-cell">{{ $idx + 1 }}</td>

                    {{-- Title & Description --}}
                    <td class="title-cell">
                        <div class="step-title">
                            <span class="step-icon">{{ $step['icon'] ?? '📌' }}</span>
                            <span>{{ $step['title'] ?? 'Chuyên đề' }}</span>
                        </div>
                        @if(!empty($step['description']))
                            <div class="step-desc">{{ $step['description'] }}</div>
                        @endif
                    </td>

                    {{-- Pages --}}
                    <td>{{ $step['pages'] ?? '—' }}</td>

                    {{-- Material --}}
                    <td style="font-size: 12px;">{{ $step['material'] ?? '—' }}</td>

                    {{-- Estimated Time --}}
                    <td><strong>{{ $step['estimated_time'] ?? '—' }}</strong></td>

                    {{-- Status Radios --}}
                    <td class="status-cell">
                        <div class="status-radio-group">
                            <label title="Chưa học">
                                <input type="radio" name="status_{{ $stepId }}" value="not_started"
                                       class="roadmap-status-radio" data-step-id="{{ $stepId }}"
                                       {{ $status === 'not_started' ? 'checked' : '' }}>
                                <span>Chưa học</span>
                            </label>
                            <label title="Đang học">
                                <input type="radio" name="status_{{ $stepId }}" value="in_progress"
                                       class="roadmap-status-radio radio-progress" data-step-id="{{ $stepId }}"
                                       {{ $status === 'in_progress' ? 'checked' : '' }}>
                            </label>
                            <label title="Đã hoàn thành">
                                <input type="radio" name="status_{{ $stepId }}" value="completed"
                                       class="roadmap-status-radio radio-done" data-step-id="{{ $stepId }}"
                                       {{ $status === 'completed' ? 'checked' : '' }}>
                            </label>
                        </div>
                    </td>

                    {{-- Notes --}}
                    <td class="notes-cell">
                        <input type="text" class="roadmap-note-input" data-step-id="{{ $stepId }}"
                               value="{{ $step['notes'] ?? '' }}" placeholder="Ghi chú...">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ═══ FOOTER SUMMARY ═══ --}}
    <div class="roadmap-footer">
        {{-- Block 1: Tổng quan --}}
        <div class="footer-block">
            <div class="fb-title">
                <span class="fb-icon">📊</span> Tổng Quan
            </div>
            <div class="fb-stats">
                <div><strong id="footerTotalSteps">{{ $totalRoadmapSteps }}</strong> chuyên đề</div>
                <div><strong>72 – 91</strong> trang</div>
                <div><strong>17 – 21</strong> giờ</div>
            </div>
        </div>

        {{-- Block 2: Trạng thái legend --}}
        <div class="footer-block">
            <div class="fb-title">
                <span class="fb-icon">📌</span> Trạng Thái
            </div>
            <div class="d-flex gap-3 flex-wrap" style="gap: 16px;">
                <span class="legend-item">
                    <span class="legend-dot dot-none"></span> Chưa học (<span id="footerNotStarted">{{ $notStartedRoadmapSteps }}</span>)
                </span>
                <span class="legend-item">
                    <span class="legend-dot dot-progress"></span> Đang học (<span id="footerInProgress">{{ $inProgressRoadmapSteps }}</span>)
                </span>
                <span class="legend-item">
                    <span class="legend-dot dot-done"></span> Đã hoàn thành (<span id="footerCompleted">{{ $completedRoadmapSteps }}</span>)
                </span>
            </div>
        </div>

        {{-- Block 3: Ghi chú --}}
        <div class="footer-block">
            <div class="fb-title">
                <span class="fb-icon">✏️</span> Ghi Chú
            </div>
            <ul class="fb-notes-list">
                <li>Hoàn thành mỗi chuyên đề → Làm bài tập → Tự kiểm tra.</li>
                <li>Ghi chú khó khăn &amp; điểm cần ôn lại.</li>
            </ul>
        </div>
    </div>

    {{-- ═══ ACTION BUTTONS ═══ --}}
    <div class="roadmap-actions">
        <a href="{{ route('profile.index') }}#roadmap-settings-builder-block"
           class="btn btn-outline-success px-4 py-2 fw-bold"
           style="border-radius: 10px; font-size: 13.5px;">
            ⚙️ Tùy Chỉnh Lộ Trình
        </a>
    </div>

</div>

{{-- ═══ INTERACTIVE JS ═══ --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    // ── Status Radio Handler (AJAX toggle) ──
    var radios = document.querySelectorAll('.roadmap-status-radio');
    radios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            var stepId = this.dataset.stepId;
            var newStatus = this.value;

            fetch("{{ route('profile.roadmap.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ step_id: stepId, status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update footer counts
                    document.getElementById('footerCompleted').textContent = data.completed;
                    var total = data.total;
                    var notStarted = 0, inProgress = 0;
                    document.querySelectorAll('.roadmap-status-radio:checked').forEach(function(r) {
                        if (r.value === 'not_started') notStarted++;
                        if (r.value === 'in_progress') inProgress++;
                    });
                    document.getElementById('footerNotStarted').textContent = notStarted;
                    document.getElementById('footerInProgress').textContent = inProgress;
                }
            })
            .catch(err => console.error(err));
        });
    });

});
</script>

@endsection
