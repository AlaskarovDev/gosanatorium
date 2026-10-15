<?php

/*
|--------------------------------------------------------------------------
| Saytın dil seçicisi (bayraq modalı) üçün dillər
|--------------------------------------------------------------------------
| code  => URL prefiksi (məs. /az, /en) və açar
| name  => modalda görünən ad
| gt    => Google Translate dil kodu
| flag  => flagcdn.com ölkə kodu (yuvarlaq bayraq şəkli üçün)
| native=> true olanlar real məzmuna malikdir (ru, az). Qalanları Google çevirir.
*/

return [
    'ru' => ['name' => 'Русский',        'gt' => 'ru',    'flag' => 'ru', 'native' => true],
    'az' => ['name' => 'Azərbaycan',     'gt' => 'az',    'flag' => 'az'],
    'en' => ['name' => 'English',        'gt' => 'en',    'flag' => 'gb'],
    'tr' => ['name' => 'Türkçe',         'gt' => 'tr',    'flag' => 'tr'],
    'ar' => ['name' => 'العربية',        'gt' => 'ar',    'flag' => 'sa'],
    'de' => ['name' => 'Deutsch',        'gt' => 'de',    'flag' => 'de'],
    'fr' => ['name' => 'Français',       'gt' => 'fr',    'flag' => 'fr'],
    'es' => ['name' => 'Español',        'gt' => 'es',    'flag' => 'es'],
    'it' => ['name' => 'Italiano',       'gt' => 'it',    'flag' => 'it'],
    'pt' => ['name' => 'Português',      'gt' => 'pt',    'flag' => 'pt'],
    'nl' => ['name' => 'Nederlands',     'gt' => 'nl',    'flag' => 'nl'],
    'pl' => ['name' => 'Polski',         'gt' => 'pl',    'flag' => 'pl'],
    'uk' => ['name' => 'Українська',     'gt' => 'uk',    'flag' => 'ua'],
    'ka' => ['name' => 'ქართული',        'gt' => 'ka',    'flag' => 'ge'],
    'fa' => ['name' => 'فارسی',          'gt' => 'fa',    'flag' => 'ir'],
    'he' => ['name' => 'עברית',          'gt' => 'iw',    'flag' => 'il'],
    'zh' => ['name' => '中文',            'gt' => 'zh-CN', 'flag' => 'cn'],
    'ja' => ['name' => '日本語',          'gt' => 'ja',    'flag' => 'jp'],
    'ko' => ['name' => '한국어',          'gt' => 'ko',    'flag' => 'kr'],
    'hi' => ['name' => 'हिन्दी',          'gt' => 'hi',    'flag' => 'in'],
    'cs' => ['name' => 'Čeština',        'gt' => 'cs',    'flag' => 'cz'],
    'ro' => ['name' => 'Română',         'gt' => 'ro',    'flag' => 'ro'],
    'bg' => ['name' => 'Български',       'gt' => 'bg',    'flag' => 'bg'],
    'el' => ['name' => 'Ελληνικά',       'gt' => 'el',    'flag' => 'gr'],
    'sv' => ['name' => 'Svenska',        'gt' => 'sv',    'flag' => 'se'],
];
