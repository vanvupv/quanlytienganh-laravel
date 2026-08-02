@extends('vocabularies.vocabulary')

@section('title', 'Vocabulary Development Tree')

@section('content_voca')
  <!-- Main Content -->
  <div class="container-fluid py-4" style="background: #f8fafc; min-height: 100vh;">
    <!-- Premium Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <h1 class="fw-bold text-dark mb-1" style="font-size: 28px; letter-spacing: -0.5px;">🌳 Lộ Trình Phát Triển Từ Vựng</h1>
        <p class="text-muted mb-0">Hệ thống hóa từ vựng lũy tiến đa tầng nghĩa theo lộ trình thông minh</p>
      </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
        <strong>🎉 Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: white;">
      <div class="row">
        <!-- Start Section 1 - Vocabulary -->
        <div class="col-lg-12">
          <div class="section__tuvung">
            
            <!-- Bảng Từ vựng -->
            <div class="mt-2">
              <h3 class="mb-3 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 18px;">
                📘 BẢNG LƯU TRỮ TỪ VỰNG DỰ ÁN
              </h3>

              <div class="table-responsive">
                <table class="table vocab-table table-hover align-middle" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                  <colgroup>
                    <col class="word-col" style="width: 12%;">
                    <col class="uk-col" style="width: 10%;">
                    <col class="us-col" style="width: 10%;">
                    <col class="pos-col" style="width: 8%;">
                    <col class="meaning-col" style="width: 20%;">
                    <col class="example-col" style="width: 25%;">
                    <col class="topic-col" style="width: 10%;">
                    <col class="ref-col" style="width: 10%;">
                  </colgroup>

                  <thead class="table-light text-secondary" style="font-size: 13px;">
                    <tr>
                      <th class="ps-3">Word<br><small class="text-muted">Từ</small></th>
                      <th>UK Phonetic<br><small class="text-muted">Phiên âm Anh</small></th>
                      <th>US Phonetic<br><small class="text-muted">Phiên âm Mỹ</small></th>
                      <th>POS<br><small class="text-muted">Loại từ</small></th>
                      <th>Meaning<br><small class="text-muted">Nghĩa</small></th>
                      <th>Example<br><small class="text-muted">Ví dụ</small></th>
                      <th>Topic<br><small class="text-muted">Chủ đề</small></th>
                      <th class="pe-3">Reference<br><small class="text-muted">Tài liệu</small></th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach($words as $w)
                      @php
                        $props = $w->properties;
                      @endphp
                      @forelse($props as $index => $prop)
                        @if($index == 0)
                          <!-- Dòng từ gốc đầu tiên -->
                          <tr class="word-row {{ $w->id == $activeWord->id ? 'table-active border-primary' : '' }}" 
                              onclick="window.location.href='?word_id={{ $w->id }}'" 
                              style="cursor: pointer; transition: all 0.2s; {{ $w->id == $activeWord->id ? 'border-left: 4px solid #3498db !important;' : '' }}">
                            <td class="ps-3 fw-bold text-primary" style="font-size: 15px;">
                              {{ $w->word }}
                              @if($w->id == $activeWord->id)
                                <span class="badge bg-primary ms-1" style="font-size: 9px; padding: 3px 6px;">Focus</span>
                              @endif
                            </td>
                            <td class="text-muted font-monospace">{{ $w->uk_phonetic }}</td>
                            <td class="text-muted font-monospace">{{ $w->us_phonetic }}</td>
                            <td class="fw-semibold text-secondary">{{ $prop->part_of_speech }}</td>
                            <td><strong>{{ $prop->meaning }}</strong></td>
                            <td class="small">
                              @if($prop->example_sentence)
                                <em>{{ $prop->example_sentence }}</em><br>
                                <span class="text-muted">{{ $prop->example_translation }}</span>
                              @endif
                            </td>
                            <td>
                              @if($prop->topic)
                                <span class="badge bg-info text-dark" style="font-size: 10px; padding: 3px 6px;">{{ $prop->topic }}</span>
                              @endif
                            </td>
                            <td class="pe-3">
                              @if($prop->reference)
                                <span class="badge bg-success" style="font-size: 10px; padding: 3px 6px;">{{ $prop->reference }}</span>
                              @endif
                            </td>
                          </tr>
                        @else
                          <!-- Các dòng thuộc tính/từ loại bổ sung của cùng từ đó -->
                          <tr class="pos-row {{ $w->id == $activeWord->id ? 'table-active border-primary' : '' }}" 
                              onclick="window.location.href='?word_id={{ $w->id }}'" 
                              style="cursor: pointer; transition: all 0.2s; opacity: 0.95; {{ $w->id == $activeWord->id ? 'border-left: 4px solid #3498db !important;' : '' }}">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="fw-semibold text-secondary">{{ $prop->part_of_speech }}</td>
                            <td><strong>{{ $prop->meaning }}</strong></td>
                            <td class="small">
                              @if($prop->example_sentence)
                                <em>{{ $prop->example_sentence }}</em><br>
                                <span class="text-muted">{{ $prop->example_translation }}</span>
                              @endif
                            </td>
                            <td>
                              @if($prop->topic)
                                <span class="badge bg-info text-dark" style="font-size: 10px; padding: 3px 6px;">{{ $prop->topic }}</span>
                              @endif
                            </td>
                            <td class="pe-3">
                              @if($prop->reference)
                                <span class="badge bg-success" style="font-size: 10px; padding: 3px 6px;">{{ $prop->reference }}</span>
                              @endif
                            </td>
                          </tr>
                        @endif
                      @empty
                        <!-- Nếu từ chưa có thuộc tính nào -->
                        <tr class="word-row {{ $w->id == $activeWord->id ? 'table-active' : '' }}" 
                            onclick="window.location.href='?word_id={{ $w->id }}'" 
                            style="cursor: pointer;">
                          <td class="ps-3 fw-bold text-primary">{{ $w->word }}</td>
                          <td class="text-muted font-monospace">{{ $w->uk_phonetic }}</td>
                          <td class="text-muted font-monospace">{{ $w->us_phonetic }}</td>
                          <td colspan="5" class="text-muted italic text-center small">Chưa có thuộc tính phát triển nào.</td>
                        </tr>
                      @endforelse
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End Bảng Từ vựng -->

            <!-- Timeline lộ trình phát triển chung -->
            <div class="mt-5">
              <h3 class="mb-3 fw-bold text-dark" style="font-size: 18px;">
                📜 DÒNG THỜI GIAN LỘ TRÌNH CHUNG
              </h3>
              <div class="timeline mt-3 mb-4 d-flex justify-content-between gap-3 overflow-auto py-2">
                @foreach($stages as $stage)
                  <div class="timeline__stage flex-fill p-3 border rounded shadow-sm bg-white" style="min-width: 300px; border-radius: 12px !important;">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3 text-center" style="font-size: 16px;">
                      {{ $stage->name }} <br>
                      <small class="text-muted" style="font-size: 12px; font-weight: normal;">({{ $stage->description }})</small>
                    </h5>
                    
                    <div class="timeline__category">
                      <div class="items d-flex flex-column gap-2" style="text-align: left;">
                        @forelse($stage->properties as $prop)
                          <div class="item new p-2 rounded border" 
                               onclick="window.location.href='?word_id={{ $prop->word_id }}'" 
                               style="cursor: pointer; transition: background-color 0.2s; font-size: 12.5px; background: #f8fafc; border-color: #e2e8f0; border-radius: 8px !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                              <strong class="text-dark" style="font-size: 13px;">{{ $prop->word->word }}</strong>
                              <span class="badge bg-secondary" style="font-size: 9px; padding: 2px 4px;">{{ $prop->part_of_speech }}</span>
                            </div>
                            
                            @if($stage->order >= 2)
                              <div class="text-muted small mb-1" style="font-size: 11px;">
                                Phonetics: <span class="font-monospace">{{ $prop->word->uk_phonetic }}</span>
                              </div>
                            @endif
                            
                            <div class="fw-semibold text-dark">Nghĩa: {{ $prop->meaning }}</div>
                            
                            @if($stage->order >= 3)
                              <div class="mt-1 pt-1 border-top" style="font-size: 11px; line-height: 1.3;">
                                <strong class="text-secondary">Ví dụ:</strong> <em>{{ $prop->example_sentence }}</em><br>
                                <span class="text-muted">{{ $prop->example_translation }}</span>
                              </div>
                              <div class="mt-1 d-flex gap-1 flex-wrap">
                                @if($prop->topic)
                                  <span class="badge bg-info text-dark" style="font-size: 8.5px; padding: 2px 4px;">{{ $prop->topic }}</span>
                                @endif
                                @if($prop->reference)
                                  <span class="badge bg-success" style="font-size: 8.5px; padding: 2px 4px;">{{ $prop->reference }}</span>
                                @endif
                              </div>
                            @endif
                          </div>
                        @empty
                          <p class="text-muted small italic text-center py-3">Không có từ vựng nào được thêm mới ở giai đoạn này.</p>
                        @endforelse
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <!-- Cây Từ vựng cho từ đang active -->
            @if($activeWord)
              <div class="container-fluid mt-5 p-0">
                <div class="p-4 rounded-4 shadow-sm" style="background: #fafbfe; border: 1px solid #e4e9f3; border-radius: 16px;">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 18px;">
                      🌳 TIẾN TRÌNH HỌC TỪ VỰNG: <span class="text-primary font-monospace">{{ $activeWord->word }}</span>
                    </h3>
                    <div class="d-flex gap-2 align-items-center">
                      <a href="{{ route('vocabulary.review', $activeWord->id) }}" class="btn btn-success fw-bold d-flex align-items-center gap-1 px-3 py-2 shadow-sm me-3" style="border-radius: 10px; font-size: 13.5px; color: white !important; background-color: #198754; border-color: #198754;">
                        📝 Vào Ôn Tập Từ Vựng
                      </a>
                      <span class="badge" style="background-color: #fdecea; color: #c0392b; border: 1px solid #f5c2c2; font-size: 11px; font-weight: 600; padding: 6px 10px; border-radius: 8px;">
                        🔴 Nút Đỏ: Kiến thức MỚI (NEW) ở giai đoạn này
                      </span>
                      <span class="badge" style="background-color: #eafaf1; color: #27ae60; border: 1px solid #c2f5d9; font-size: 11px; font-weight: 600;">
                        🟢 Nút Xanh: Kiến thức CŨ (OLD) đã học tích lũy
                      </span>
                    </div>
                  </div>

                  <div class="row g-4">
                    <!-- Cây Tiến Trình 3 Giai Đoạn -->
                    <div class="col-lg-8">
                      <div class="row g-3">
                        @foreach($stages as $stage)
                          @php
                            $stageTree = $growthTree[$stage->id];
                            $accumulatedProps = $stageTree['properties'];
                            $groupedByPOS = $accumulatedProps->groupBy('part_of_speech');
                          @endphp
                          <div class="col-md-4">
                            <div class="word-stage stage-{{ $stage->order }} h-100 shadow-sm border-2" style="background: white !important;">
                              <h5 class="fw-bold fs-5 text-center mb-3">
                                {{ $stage->name }} <br>
                                <small class="text-muted" style="font-size: 13px; font-weight: normal;">({{ $stage->description }})</small>
                              </h5>
                              
                              <!-- Từ gốc -->
                              <div class="field mb-2" style="font-size: 13.5px;">
                                <b class="text-secondary small">Word:</b>
                                <span class="{{ $stage->order == 1 ? 'new' : 'old' }} fw-bold">{{ $activeWord->word }}</span>
                              </div>

                              <!-- Phiên âm -->
                              <div class="field mb-2" style="font-size: 13.5px;">
                                <b class="text-secondary small">Phonetics:</b>
                                @if($stage->order == 1)
                                  <span class="new font-monospace">{{ $activeWord->uk_phonetic }}</span>
                                @else
                                  <span class="{{ $stage->order == 2 ? 'new' : 'old' }} font-monospace d-block mb-1">UK: {{ $activeWord->uk_phonetic }}</span>
                                  <span class="{{ $stage->order == 2 ? 'new' : 'old' }} font-monospace d-block">US: {{ $activeWord->us_phonetic }}</span>
                                @endif
                              </div>

                              <!-- Các từ loại & Nghĩa phát triển dưới dạng Cây -->
                              <div class="field mb-2" style="font-size: 13.5px;">
                                <b class="text-secondary small">Parts of Speech & Meanings:</b>
                                <ul class="tree text-start ps-3" style="border-left: 2px solid #e2e8f0; list-style-type: none; margin-top: 5px;">
                                  @forelse($groupedByPOS as $pos => $props)
                                    @php
                                      $isPosNew = $props->contains('status', 'new');
                                    @endphp
                                    <li class="mb-2 position-relative">
                                      <span class="{{ $isPosNew ? 'new' : 'old' }} fw-bold" style="font-size: 11px;">{{ $pos }}</span>
                                      <ul class="tree ps-3 mt-1" style="border-left: 1px dashed #cbd5e1; list-style-type: none;">
                                        @foreach($props as $prop)
                                          <li class="mb-1">
                                            <span class="{{ $prop->status }}" style="font-size: 11.5px;">{{ $prop->meaning }}</span>
                                          </li>
                                        @endforeach
                                      </ul>
                                    </li>
                                  @empty
                                    <li class="text-muted small italic">Chưa có thông tin nghĩa của từ ở giai đoạn này.</li>
                                  @endforelse
                                </ul>
                              </div>

                              <!-- Hiển thị Ví dụ nâng cao & Chủ đề, Reference ở Giai đoạn 3 -->
                              @if($stage->order == 3)
                                @php
                                  $stage3Props = $accumulatedProps->where('stage_id', $stage->id);
                                @endphp
                                @if($stage3Props->isNotEmpty())
                                  <div class="field border-top pt-2 mt-2" style="font-size: 13px;">
                                    <b class="text-secondary small d-block mb-1">Example (Contextual):</b>
                                    @foreach($stage3Props as $prop)
                                      @if($prop->example_sentence)
                                        <div class="p-2 mb-2 rounded bg-light border" style="font-size: 11.5px; line-height: 1.4;">
                                          <span class="new d-block mb-1 font-monospace" style="font-size: 11px;">"{{ $prop->example_sentence }}"</span>
                                          <span class="text-muted d-block">👉 {{ $prop->example_translation }}</span>
                                        </div>
                                      @endif
                                    @endforeach
                                  </div>

                                  <div class="field mb-2" style="font-size: 13px;">
                                    <b class="text-secondary small">Topic:</b>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                      @foreach($stage3Props as $prop)
                                        @if($prop->topic)
                                          <span class="new" style="font-size: 10px; padding: 2px 5px;">{{ $prop->topic }}</span>
                                        @endif
                                      @endforeach
                                    </div>
                                  </div>

                                  <div class="field" style="font-size: 13px;">
                                    <b class="text-secondary small">Reference:</b>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                      @foreach($stage3Props as $prop)
                                        @if($prop->reference)
                                          <span class="new" style="font-size: 10px; padding: 2px 5px;">{{ $prop->reference }}</span>
                                        @endif
                                      @endforeach
                                    </div>
                                  </div>
                                @endif
                              @endif

                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>

                    <!-- THỐNG KÊ & LỊCH SỬ ÔN TẬP SIDEBAR -->
                    <div class="col-lg-4">
                      <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: white; border: 1px solid #e4e9f3; text-align: left;">
                        <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 15px;">
                          📊 THỐNG KÊ & TIẾN TRÌNH ÔN TẬP
                        </h5>
                        
                        <!-- Summary Stats Grid -->
                        <div class="row g-2 text-center mb-3">
                          @php
                            $totalReviews = $activeWord->reviews->count();
                            $studyLog = $activeWord->studyLogs->first();
                            $totalReviewMinutes = $studyLog ? $studyLog->review_time_minutes : 0;
                            $latestReview = $activeWord->reviews->sortByDesc('created_at')->first();
                          @endphp
                          
                          <div class="col-6 bg-light p-2.5 rounded border border-white" style="background-color: #f8fafc !important;">
                            <span class="text-muted d-block" style="font-size: 10.5px;">Tổng Số Lần Ôn</span>
                            <strong class="text-primary font-monospace" style="font-size: 17px;">{{ $totalReviews }} lần</strong>
                          </div>
                          
                          <div class="col-6 bg-light p-2.5 rounded border border-white" style="background-color: #f8fafc !important;">
                            <span class="text-muted d-block" style="font-size: 10.5px;">Thời Gian Ôn Tập</span>
                            <strong class="text-success font-monospace" style="font-size: 17px;">{{ $totalReviewMinutes }} phút</strong>
                          </div>
                        </div>
                        
                        <!-- Latest Attempt status -->
                        @if($latestReview)
                          <div class="p-2.5 rounded mb-3 d-flex justify-content-between align-items-center" 
                               style="background-color: {{ $latestReview->passed ? '#eafaf1' : '#fdecea' }}; border: 1px solid {{ $latestReview->passed ? '#c2f5d9' : '#f5c2c2' }}; padding: 8px 12px; border-radius: 8px;">
                            <span class="small fw-semibold text-secondary" style="font-size: 12px;">Trạng thái gần nhất:</span>
                            <span class="badge {{ $latestReview->passed ? 'bg-success' : 'bg-danger' }} px-2 py-1" style="font-size: 9.5px; border-radius: 5px;">
                              {{ $latestReview->passed ? 'ĐẠT (' . $latestReview->score . 'đ)' : 'CHƯA ĐẠT (' . $latestReview->score . 'đ)' }}
                            </span>
                          </div>
                        @else
                          <div class="p-2.5 rounded bg-light border text-muted small text-center mb-3" style="padding: 10px; border-radius: 8px;">
                            Từ vựng này chưa có lượt ôn tập nào.
                          </div>
                        @endif
                        
                        <!-- Review History List -->
                        <h6 class="fw-bold text-secondary mb-2" style="font-size: 12px; letter-spacing: 0.5px;">📜 LỊCH SỬ CÁC LẦN ÔN TẬP</h6>
                        <div class="overflow-auto pe-1 d-flex flex-column gap-2" style="max-height: 280px;">
                          @forelse($activeWord->reviews->sortByDesc('created_at') as $rev)
                            <div class="p-2.5 rounded border d-flex justify-content-between align-items-center" 
                                 style="font-size: 12.5px; background: #fafbfc; border-color: #e2e8f0; border-radius: 8px; padding: 10px;">
                              <div style="text-align: left;">
                                <span class="text-dark fw-bold d-block" style="font-size: 12px;">
                                  {{ $rev->created_at->format('d/m/Y H:i') }}
                                </span>
                                <span class="text-muted small" style="font-size: 11px;">Thời gian: {{ $rev->duration_seconds }}s</span>
                              </div>
                              <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $rev->passed ? 'bg-success' : 'bg-danger' }} font-monospace" style="font-size: 11px; min-width: 40px; text-align: center; padding: 4px 6px;">
                                  {{ $rev->score }}đ
                                </span>
                                <a href="{{ route('vocabulary.reviews.show', $rev->id) }}" class="btn btn-sm btn-outline-primary p-1 d-flex align-items-center justify-content-center" title="Xem chi tiết bài làm" style="border-radius: 5px; width: 24px; height: 24px; font-size: 11px; padding: 0 !important;">
                                  🔍
                                </a>
                              </div>
                            </div>
                          @empty
                            <div class="text-muted small italic text-center py-4">Chưa có lịch sử làm bài ôn tập.</div>
                          @endforelse
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif

          </div>
        </div>
        <!-- End Section 1 - Vocabulary -->
      </div>
    </div>
  </div>
@endsection