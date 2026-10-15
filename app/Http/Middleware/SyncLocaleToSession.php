<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Http\Request;
use Modules\Language\App\Models\Language;
use Symfony\Component\HttpFoundation\Response;

class SyncLocaleToSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $detected = $request->attributes->get('detected_lang');

        if ($detected) {
            $language = Language::where('lang_code', $detected)->first();

            Session::put('front_lang', $detected);

            if ($language) {
                Session::put('front_lang_name', $language->lang_name);
                Session::put('lang_dir', $language->lang_direction);
            }

            app()->setLocale($detected);
        }

        return $next($request);
    }
}
