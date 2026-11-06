<style>
.urgency-block{background:linear-gradient(135deg,#ff6b1a,#e8340c);color:#fff;font-size:13px;font-weight:800;line-height:1.4;padding:10px 14px;border-radius:10px;margin:0 0 10px;text-align:center}
.availability-note{background:#f0faf8;color:#157a68;font-size:12px;font-weight:600;line-height:1.4;padding:8px 12px;border-radius:10px;margin:0 0 14px;text-align:center}
</style>
<div class="urgency-block">
@php($views = (int) $service->views_count)
@php($w = ($views % 100 >= 11 && $views % 100 <= 14) ? 'просмотров' : (['1'=>'просмотр','2'=>'просмотра','3'=>'просмотра','4'=>'просмотра'][$views % 10] ?? 'просмотров'))
@if($views > 20)
🔥 Популярный выбор: {{ $views }} {{ $w }} за последние 7 дней
@else
🔥 Высокий спрос: на этот санаторий за последние 7 дней смотрели более 40 человек
@endif
</div>
