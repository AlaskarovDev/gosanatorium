@if(!empty($service->youtube_id))
<style>
.video-section{background:#fff;border-radius:0 0 16px 16px;padding:28px 0 8px;margin:0 0 4px}
.video-section h2{font-size:22px;font-weight:800;color:#1a1a2e;margin:0 0 16px;text-transform:none}
.video-section .video-wrapper{position:relative;width:100%;border-radius:14px;overflow:hidden;background:#000}
.video-section .video-wrapper iframe{display:block;width:100%;height:480px;border:0}
@media(max-width:768px){.video-section .video-wrapper iframe{height:240px}}
</style>
<div class="video-section">
<h2>Санаторий в видео — смотрите вживую</h2>
<div class="video-wrapper">
<iframe src="https://www.youtube.com/embed/{{ $service->youtube_id }}" title="{{ e($service?->translation?->title ?? $service->title ?? 'Видео санатория') }}" width="100%" height="480" frameborder="0" loading="lazy" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture; fullscreen" allowfullscreen></iframe>
</div>
</div>
@endif
