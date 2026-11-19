{{-- Task 14: Organization + WebSite on every page (data from Admin → Company facts). --}}
{!! \App\Support\StructuredData::script(\App\Support\StructuredData::site($general_setting->logo ?? null)) !!}
