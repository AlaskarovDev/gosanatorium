{{-- Conversion goals (Task 18): Metrika reachGoal + GTM dataLayer + GA4 (if enabled in admin) --}}
<script>
    window.GS_GOALS = {
        metrika: 109569026,
        ga4: @json(($general_setting->google_analytic_status ?? 0) == 1 && \Illuminate\Support\Str::startsWith((string) ($general_setting->google_analytic_id ?? ''), 'G-') ? $general_setting->google_analytic_id : ''),
        {{-- one-time goal after a redirect (e.g. consultation form sent) --}}
        once: @json(session('gs_goal') ? [['goal' => (string) session('gs_goal'), 'key' => 'r' . now()->getTimestampMs(), 'source' => 'form']] : [])
    };
</script>
<script src="{{ asset('frontend/assets/js/gs-goals.js') }}?v={{ @filemtime(public_path('frontend/assets/js/gs-goals.js')) }}" defer></script>
