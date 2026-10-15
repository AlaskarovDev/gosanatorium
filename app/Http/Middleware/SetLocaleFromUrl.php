<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $langs = config('site_languages', []);
        $codes = array_keys($langs);
        if (empty($codes)) {
            $codes = ['ru', 'az'];
            $langs = ['ru' => ['native' => true], 'az' => ['native' => true]];
        }
        $pattern = implode('|', array_map('preg_quote', $codes));

        $path = $request->getPathInfo();

        // Admin/agency dil prefiksiz qalır: /az/admin → /admin
        if (preg_match('#^/(' . $pattern . ')/(admin|agency)(/|$)#', $path)) {
            $stripped = preg_replace('#^/(' . $pattern . ')#', '', $path);
            return redirect($stripped . ($request->getQueryString() ? '?' . $request->getQueryString() : ''));
        }

        if (preg_match('#^/(' . $pattern . ')(/.*)?$#', $path, $matches)) {
            $code = $matches[1];
            $newPath = ($matches[2] ?? '') ?: '/';

            $qs = $request->server->get('QUERY_STRING', '');
            $newRequestUri = $newPath . ($qs ? '?' . $qs : '');

            $server = $request->server->all();
            $server['REQUEST_URI'] = $newRequestUri;
            unset($server['PATH_INFO']);

            $request->initialize(
                $request->query->all(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $server,
                $request->getContent()
            );

            $cfg = $langs[$code] ?? [];
            $isNative = $cfg['native'] ?? false;

            // Yalnız "native" dil (ru) öz real məzmununu göstərir.
            // Qalan dillər ru məzmununu göstərir, Google client-side çevirir.
            $contentLocale = $isNative ? $code : 'ru';
            app()->setLocale($contentLocale);

            // detected_lang → SyncLocaleToSession/CurrencyLangauge məzmun locale-ni
            // bundan götürür (tərcümə dilləri üçün ru olmalıdır).
            $request->attributes->set('detected_lang', $contentLocale);
            // display_lang → bayraq düyməsi hansı dilin seçildiyini bundan bilir (URL prefiksi).
            $request->attributes->set('display_lang', $code);
            // gt_lang → Google tərcümə hədəfi (native = null = tərcümə yoxdur).
            $request->attributes->set('gt_lang', $isNative ? null : ($cfg['gt'] ?? $code));

            $root = $request->getSchemeAndHttpHost();
            $urlGen = app('url');
            (function () use ($root) {
                $this->assetRoot = $root;
            })->call($urlGen);
            URL::forceRootUrl($root . '/' . $code);
        } else {
            // Prefiksiz URL gəldi.
            // SEO üçün bütün front səhifələri əsas dilə (/ru) 301 yönləndirilir.
            // Admin/agency/api/payment, fayllar (.xml, .txt, ...) və qeyri-GET
            // sorğular istisnadır — onlar prefiksiz qalır.
            $defaultCode = 'ru';
            foreach ($langs as $c => $cfg) {
                if (!empty($cfg['native'])) {
                    $defaultCode = $c;
                    break;
                }
            }
            if (!in_array($defaultCode, $codes, true)) {
                $defaultCode = $codes[0] ?? 'ru';
            }

            $isSafeMethod = in_array($request->getMethod(), ['GET', 'HEAD'], true);
            $isExcluded = preg_match(
                '#^/(admin|agency|api|payment|clear|migrate|storage|build|livewire|broadcasting|sitemap\.xml|robots\.txt|download-file|language-switcher|currency-switcher|theme)(/|$)#i',
                $path
            );
            // Fayl uzantısı olan yollar (məs. /sitemap.xml, /favicon.ico, /file.html)
            $isFile = preg_match('#\.[a-zA-Z0-9]{2,5}$#', $path);

            if ($isSafeMethod && !$isExcluded && !$isFile) {
                $target = '/' . $defaultCode . ($path === '/' ? '' : $path);
                $qs = $request->getQueryString();

                return redirect($target . ($qs ? '?' . $qs : ''), 301);
            }

            // Prefiksiz qalan yollar (admin və s.) — köhnə davranış saxlanılır
            app()->setLocale('en');
            $request->attributes->set('detected_lang', 'ru');
            $request->attributes->set('display_lang', 'ru');
        }

        return $next($request);
    }
}
