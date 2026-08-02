{{-- ==== PHẦN 1: CÂY DOCUMENT ==== --}}
<div class="document__tree mb-5">
  <h3 class="fw-bold text-primary mb-3">📂 Document Tree</h3>

  @forelse($documents as $document)
    <div class="document__item mb-3">
      <h5 class="mb-2">{{ $document->title ?? 'Untitled document' }}</h5>

      @if($document->parts && $document->parts->count())
        @foreach($document->parts as $part)
          <div class="ms-3 mb-2">
            {{-- dùng <details> để có collapse/expand mà không cần JS --}}
              <details class="part__details" open>
                <summary class="d-flex align-items-center gap-2">
                  <span class="me-1">📘</span>
                  <strong class="part__title">{{ $part->title ?? 'Untitled part' }}</strong>
                  <small class="text-muted ms-2">({{ $part->units->count() ?? 0 }} units)</small>
                </summary>

                @if($part->units && $part->units->count())
                  @foreach($part->units as $unit)
                    <div class="ms-4 mt-2 unit__block">
                      <details class="unit__details" open>
                        <summary class="d-flex align-items-center gap-2">
                          <span>🔹</span>
                          <strong class="unit__title">{{ $unit->title ?? 'Untitled unit' }}</strong>
                          <small class="text-muted ms-2">({{ $unit->listenings->count() ?? 0 }} items)</small>
                        </summary>

                        @if($unit->listenings && $unit->listenings->count())
                          <ul class="ms-3 mt-2 list-unstyled unit__list">
                            @foreach($unit->listenings as $listening)
                              <li class="py-1 d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center gap-2">
                                  <span class="me-1">🎧</span>
                                  {{-- link tới chi tiết (thay url nếu bạn có route khác) --}}
                                  <a href="{{ url('/skills/listening/' . $listening->id) }}" class="text-decoration-none">
                                    {{ $listening->title ?? 'Untitled listening' }}
                                  </a>
                                  <small class="text-muted ms-2">— {{ $listening->topic->name ?? 'No topic' }}</small>
                                </div>

                                {{-- meta nhẹ (duration / level) --}}
                                <div class="small text-muted">
                                  {{ $listening->duration ? $listening->duration . 's' : '' }}
                                  @if($listening->level)
                                    <span class="ms-2">• {{ $listening->level }}</span>
                                  @endif
                                </div>
                              </li>
                            @endforeach
                          </ul>
                        @else
                          <div class="ms-3 small text-muted py-2">No listening items in this unit.</div>
                        @endif
                      </details>
                    </div>
                  @endforeach
                @else
                  <div class="ms-3 small text-muted py-2">No units in this part.</div>
                @endif
              </details>
          </div>
        @endforeach
      @else
        <div class="ms-3 small text-muted">No parts in this document.</div>
      @endif
    </div>
  @empty
    <div class="text-muted small">No documents found.</div>
  @endforelse
</div>