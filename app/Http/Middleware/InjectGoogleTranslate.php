<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bütün frontend HTML səhifələrinə dil seçicisini əlavə edir:
 *  - Header-də dairəvi bayraq düyməsi (cari dilin bayrağı)
 *  - Klikləyəndə bayraqlı modal (Google booking.com kimi)
 *  - Seçilən dil URL-də görünür (/ru, /en ...), keçid eyni səhifədə qalır
 *  - Task 17: yalnız real (tərcümə olunmuş) dillər göstərilir; Google Translate yoxdur.
 *    Ad tarixi səbəbdən saxlanılıb (Kernel-də qeydiyyatdadır).
 */
class InjectGoogleTranslate
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldInject($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || stripos($content, '</body>') === false) {
            return $response;
        }
        if (stripos($content, 'id="lang_flag_btn"') !== false) {
            return $response;
        }

        // <head>-ə ən erkən: cookie-ni təyin/təmizlə və (tərcümə dilində)
        // səhifəni tərcümə bitənə qədər gizlət — beləcə default dil flash olmur.
        $head = $this->headSnippet($request);
        if ($head !== '' && stripos($content, '</head>') !== false) {
            $content = preg_replace('#</head>#i', $head . '</head>', $content, 1);
        }

        $content = preg_replace('#</body>#i', $this->widget($request) . '</body>', $content, 1);
        $response->setContent($content);

        return $response;
    }

    /**
     * <head>-də işləyən erkən snippet: köhnə googtrans cookie-ni təmizləyir.
     */
    private function headSnippet(Request $request): string
    {
        // Task 17: auto-translate removed — clear any old googtrans cookie left by the former widget.
        return <<<'JS'
<script>
(function(){var d=document,host=location.hostname.replace(/^www\./,''),ex=';expires=Thu, 01 Jan 1970 00:00:01 GMT';d.cookie='googtrans=;path=/'+ex;d.cookie='googtrans=;path=/;domain=.'+host+ex;})();
</script>
JS;
    }

    private function shouldInject(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $request->ajax() || $request->wantsJson()) {
            return false;
        }
        if ($request->is('admin', 'admin/*', 'agency', 'agency/*', 'user', 'user/*', 'api', 'api/*')) {
            return false;
        }
        if (method_exists($response, 'isRedirection') && $response->isRedirection()) {
            return false;
        }
        $contentType = $response->headers->get('Content-Type', '');
        if ($contentType !== '' && stripos($contentType, 'text/html') === false) {
            return false;
        }
        return true;
    }

    private function widget(Request $request): string
    {
        $langs = \App\Support\SiteLanguages::active();

        $current = $request->attributes->get('display_lang', 'ru');
        if (! isset($langs[$current])) {
            $current = \App\Support\SiteLanguages::default();
        }

        $currentFlag = $langs[$current]['flag'] ?? 'ru';
        $currentName = $langs[$current]['name'] ?? 'Русский';

        // Modal grid item-ləri
        $items = '';
        foreach ($langs as $code => $cfg) {
            $active = $code === $current ? ' lm-item--active' : '';
            $check  = $code === $current ? '<span class="lm-check">&#10003;</span>' : '';
            $items .= '<button type="button" class="lm-item' . $active . '" onclick="siteSelectLang(\'' . e($code) . '\')">'
                . '<img class="lm-flag" src="https://flagcdn.com/w40/' . e($cfg['flag']) . '.png" srcset="https://flagcdn.com/w80/' . e($cfg['flag']) . '.png 2x" alt="" loading="lazy">'
                . '<span class="lm-name">' . e($cfg['name']) . '</span>' . $check
                . '</button>';
        }

        // JS üçün məlumat
        $jsData = json_encode([
            'current' => $current,
            'active'  => array_keys($langs),
            // all known prefixes (incl. retired) so the current one is stripped correctly
            'codes'   => \App\Support\SiteLanguages::allCodes(),
        ], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);

        $modalClass = count($langs) <= 3 ? ' class="lm-compact"' : '';
        $currentFlag = e($currentFlag);
        $currentName = e($currentName);

        $css = <<<CSS
<style>
  /* Dairəvi bayraq düyməsi */
  #lang_flag_btn {
    width:40px; height:40px; border-radius:50%; overflow:hidden;
    border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,.18);
    padding:0; cursor:pointer; background:#fff; flex:0 0 auto;
  }
  #lang_flag_btn img { width:100%; height:100%; object-fit:cover; display:block; }
  /* Header-ə yerləşməyibsə — sağ-yuxarıda üzən */
  #lang_flag_btn.floating { position:fixed; top:90px; right:14px; z-index:99990; }
  #lang_flag_btn.in-header { position:static; margin-left:8px; }

  /* Modal */
  #lang_modal_overlay {
    display:none; position:fixed; inset:0; z-index:100000;
    background:rgba(15,25,50,.55); align-items:flex-start; justify-content:center;
    padding:40px 16px; overflow:auto;
  }
  #lang_modal {
    background:#fff; width:100%; max-width:980px; border-radius:14px;
    box-shadow:0 30px 90px rgba(0,0,0,.35); padding:24px 28px;
  }
  #lang_modal .lm-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
  #lang_modal .lm-top h3, #lang_modal .lm-top .lm-title { margin:0; font-size:22px; font-weight:700; color:#1d2540; }
  #lang_modal_close {
    width:36px; height:36px; border-radius:8px; border:1px solid #e3e7ef; background:#fff;
    font-size:20px; line-height:1; cursor:pointer; color:#4a5670;
  }
  #lang_modal_close:hover { background:#f4f6fb; }
  #lang_modal .lm-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:6px; }
  @media (max-width:900px){ #lang_modal .lm-grid { grid-template-columns:repeat(2,1fr); } }
  @media (max-width:520px){ #lang_modal .lm-grid { grid-template-columns:1fr; } }
  #lang_modal .lm-item {
    display:flex; align-items:center; gap:12px; padding:12px 14px; border:none;
    background:none; border-radius:10px; cursor:pointer; text-align:left; width:100%;
    font-size:15px; color:#2c3a55;
  }
  #lang_modal .lm-item:hover { background:#f4f7fc; }
  #lang_modal .lm-item--active { background:#eef3ff; color:#1f4ad7; font-weight:600; }
  #lang_modal .lm-flag {
    width:26px; height:26px; border-radius:50%; object-fit:cover; flex:0 0 auto;
    box-shadow:0 0 0 1px rgba(0,0,0,.08);
  }
  #lang_modal .lm-name { flex:1; }
  /* Task 17: few real languages → compact modal */
  #lang_modal.lm-compact { max-width:420px; }
  #lang_modal.lm-compact .lm-grid { grid-template-columns:1fr; }
  #lang_modal .lm-check { color:#1f4ad7; font-weight:700; }
</style>
CSS;

        $html = <<<HTML
<button id="lang_flag_btn" class="floating" type="button" title="{$currentName}" aria-label="Language">
  <img src="https://flagcdn.com/w80/{$currentFlag}.png" alt="">
</button>
<div id="lang_modal_overlay">
  <div id="lang_modal"{$modalClass}>
    <div class="lm-top">
      <div class="lm-title">Выберите язык</div>
      <button id="lang_modal_close" type="button" aria-label="Close">&times;</button>
    </div>
    <div class="lm-grid">{$items}</div>
  </div>
</div>
HTML;

        $jsStatic = <<<'JS'
(function () {
  var S = window.__siteLang;
  // Task 17: switcher keeps the same page (Chinar RU → Chinar EN), never the homepage.
  window.siteSelectLang = function (code) {
    if (S.active.indexOf(code) === -1) return;
    var re = new RegExp('^/(' + S.codes.join('|') + ')(?=/|$)');
    var p = location.pathname.replace(re, '');
    location.href = location.origin + '/' + code + p + location.search;
  };

  document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('lang_flag_btn');
    var overlay = document.getElementById('lang_modal_overlay');
    var closeBtn = document.getElementById('lang_modal_close');
    if (btn && overlay) {
      btn.addEventListener('click', function () { overlay.style.display = 'flex'; });
      overlay.addEventListener('click', function (e) { if (e.target === overlay) overlay.style.display = 'none'; });
      if (closeBtn) closeBtn.addEventListener('click', function () { overlay.style.display = 'none'; });
    }
    // Mümkünsə header-dəki köhnə dil seçicisinin yerinə qoy
    var slot = document.querySelector('.tg-language-switcher');
    if (slot && btn) {
      var sel = slot.querySelector('select');
      if (sel) sel.style.display = 'none';
      btn.classList.remove('floating');
      btn.classList.add('in-header');
      slot.appendChild(btn);
    }
  });
})();
JS;

        $js = '<script>window.__siteLang = ' . $jsData . ';' . $jsStatic . '</script>';


        return "\n" . $css . $html . $js . "\n";
    }
}
