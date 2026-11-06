@php $results = array_values(array_filter((array) ($service->results ?? []), fn ($r) => trim((string) $r) !== '')); @endphp
@if(count($results))
<style>
.results-block{background:linear-gradient(135deg,#e8f8ef,#d3f1e0);border:2px solid #16a34a;border-radius:16px;padding:22px 24px;margin:24px 0}
.results-block h3{font-size:20px;font-weight:800;color:#14532d;margin:0 0 14px;text-transform:none}
.results-block ul{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:1fr 1fr;gap:10px 18px}
.results-block li{display:flex;align-items:flex-start;gap:10px;font-size:14px;font-weight:600;color:#14532d;line-height:1.5;text-transform:none}
.results-block li .results-check{flex:0 0 22px;width:22px;height:22px;border-radius:50%;background:#16a34a;color:#fff;font-size:13px;display:flex;align-items:center;justify-content:center}
@media(max-width:640px){.results-block ul{grid-template-columns:1fr}}
</style>
<div class="results-block">
  <h3>Наши гости после курса</h3>
  <ul>
    @foreach($results as $result)
      <li><span class="results-check">✓</span><span>{{ $result }}</span></li>
    @endforeach
  </ul>
</div>
@endif
