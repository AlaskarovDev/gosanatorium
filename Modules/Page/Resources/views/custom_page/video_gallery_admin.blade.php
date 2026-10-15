{{-- ============================================= --}}
{{-- VIDEO GALLERY MANAGEMENT --}}
{{-- ============================================= --}}
@if (admin_lang() == request()->get('lang_code'))
<div class="row mg-top-30">
    <div class="col-12">
        <h4 class="crancy-product-card__title" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
            <i class="fas fa-video"></i> Управление видеогалереей
        </h4>
        <p class="text-muted">Добавляйте видео с YouTube/Vimeo ссылками и миниатюрами</p>
    </div>
</div>

{{-- Add New Video - NO <form> tag! Uses div + JS to avoid parent form conflict --}}
<div class="row mg-top-20">
    <div class="col-12">
        <div class="card" style="border: 2px dashed #e0e0e0; border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="fas fa-plus-circle text-primary"></i> Добавить новое видео</h5>

                {{-- Status message --}}
                <div id="videoFormAlert" style="display:none;" class="alert mb-3"></div>

                <div id="addVideoFields">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Название видео</label>
                                <input type="text" id="videoTitle" class="form-control" placeholder="Например: Ванна минеральная">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ссылка на видео * <small class="text-muted">(YouTube / Vimeo)</small></label>
                                <input type="url" id="videoUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Миниатюра <small class="text-muted">(необязательно — авто из YouTube)</small></label>
                                <input type="file" id="videoThumbnail" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="addVideoBtn" onclick="submitVideo()">
                        <i class="fas fa-plus"></i> Добавить видео
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Existing Videos List --}}
<div class="row mg-top-20" id="videoListSection">
    <div class="col-12">
        <div id="videoList">
            @if(isset($custom_page) && $custom_page->videos && $custom_page->videos->count() > 0)
                @foreach($custom_page->videos->sortBy('sort_order') as $video)
                <div class="card mb-3 video-item" data-id="{{ $video->id }}" style="border-radius: 10px; border: 1px solid #eee;">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-grip-vertical text-muted" style="cursor: grab; font-size: 18px;"></i>
                            </div>
                            <div class="col-auto">
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}"
                                     style="width: 100px; height: 65px; object-fit: cover; border-radius: 8px;">
                            </div>
                            <div class="col">
                                <strong>{{ $video->title ?: 'Без названия' }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-link"></i>
                                    <a href="{{ $video->video_url }}" target="_blank">{{ Str::limit($video->video_url, 50) }}</a>
                                </small>
                            </div>
                            <div class="col-auto">
                                <div class="form-check form-switch">
                                    <input class="form-check-input video-status-toggle" type="checkbox"
                                           data-id="{{ $video->id }}" {{ $video->status ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger delete-video-btn" data-id="{{ $video->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center text-muted py-4" id="noVideosMessage">
                    <i class="fas fa-film fa-3x mb-3" style="opacity: 0.3;"></i>
                    <p>Видео пока не добавлены</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Video JS --}}
<script>
    var videoStoreUrl = '{{ url("admin/custom-page-videos") }}';
    var videoPageId = '{{ $custom_page->id }}';
    var csrfToken = '{{ csrf_token() }}';

    function showVideoAlert(msg, type) {
        var el = document.getElementById('videoFormAlert');
        el.className = 'alert mb-3 alert-' + type;
        el.textContent = msg;
        el.style.display = 'block';
    }

    function submitVideo() {
        var btn = document.getElementById('addVideoBtn');
        var alertBox = document.getElementById('videoFormAlert');
        var videoUrl = document.getElementById('videoUrl').value.trim();
        var title = document.getElementById('videoTitle').value.trim();
        var thumbInput = document.getElementById('videoThumbnail');

        // Validation
        if (!videoUrl) {
            showVideoAlert('Пожалуйста, введите ссылку на видео', 'warning');
            document.getElementById('videoUrl').focus();
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Добавление...';
        alertBox.style.display = 'none';

        // Build FormData manually (no <form> tag needed)
        var formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('custom_page_id', videoPageId);
        formData.append('title', title);
        formData.append('video_url', videoUrl);

        if (thumbInput.files.length > 0) {
            formData.append('thumbnail', thumbInput.files[0]);
        }

        fetch(videoStoreUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(function(r) {
            if (!r.ok) {
                return r.text().then(function(text) {
                    throw new Error('Server ' + r.status + ': ' + text.substring(0, 200));
                });
            }
            return r.json();
        })
        .then(function(data) {
            if (data.success) {
                showVideoAlert('Видео успешно добавлено! Перезагрузка...', 'success');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                showVideoAlert(data.message || 'Ошибка при добавлении', 'danger');
            }
        })
        .catch(function(err) {
            showVideoAlert('Ошибка: ' + err.message, 'danger');
            console.error('Video add error:', err);
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Добавить видео';
        });
    }

    // Delete Video
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-video-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (!confirm('Удалить это видео?')) return;
                var videoId = this.getAttribute('data-id');
                var card = this.closest('.video-item');

                fetch(videoStoreUrl + '/' + videoId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) { card.remove(); }
                })
                .catch(function(err) { alert('Ошибка: ' + err.message); });
            });
        });

        // Toggle Status
        document.querySelectorAll('.video-status-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function(e) {
                e.stopPropagation();
                var videoId = this.getAttribute('data-id');
                var status = this.checked ? 1 : 0;

                fetch(videoStoreUrl + '/' + videoId + '/status', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });
            });
        });
    });
</script>
@endif