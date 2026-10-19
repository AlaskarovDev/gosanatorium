<?php

use Illuminate\Support\Facades\File;
use App\Models\Menu;
use App\Models\MenuItem;

/**
 * Get theme instance
 *
 * @return \App\Themes\Core\Theme
 */
if (!function_exists('theme')) {
    function theme() {
        return app('theme');
    }
}

/**
 * Get theme translation
 *
 * @param string $key
 * @param array $replace
 * @return string
 */
if (!function_exists('theme_trans')) {
    function theme_trans($key, $replace = []) {
        $theme = app('theme');
        $themePath = $theme->getThemePath($theme->current());
        $langPath = $themePath . '/lang';
        
        if (File::exists($langPath)) {
            $locale = app()->getLocale();
            $fallbackLocale = config('app.fallback_locale');
            
            $localePath = $langPath . '/' . $locale . '.php';
            $fallbackPath = $langPath . '/' . $fallbackLocale . '.php';
            
            if (File::exists($localePath)) {
                $translations = require $localePath;
                if (isset($translations[$key])) {
                    return strtr($translations[$key], $replace);
                }
            }
            
            if (File::exists($fallbackPath)) {
                $translations = require $fallbackPath;
                if (isset($translations[$key])) {
                    return strtr($translations[$key], $replace);
                }
            }
        }
        
        return $key;
    }
}

/**
 * Generate theme breadcrumbs
 *
 * @return string
 */
if (!function_exists('theme_breadcrumbs')) {
    function theme_breadcrumbs() {
        $theme = app('theme');
        $breadcrumbs = $theme->getBreadcrumbs();
        
        if (empty($breadcrumbs)) {
            return '';
        }
        
        $output = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            if ($index === count($breadcrumbs) - 1) {
                $output .= '<li class="breadcrumb-item active" aria-current="page">' . $breadcrumb['label'] . '</li>';
            } else {
                $output .= '<li class="breadcrumb-item"><a href="' . $breadcrumb['url'] . '">' . $breadcrumb['label'] . '</a></li>';
            }
        }
        
        $output .= '</ol></nav>';
        
        return $output;
    }
}

/**
 * Get theme content
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
if (!function_exists('theme_content')) {
    function theme_content($key, $default = null) {
        $theme = app('theme');
        $content = $theme->getContent($key);
        
        return $content ? $content : $default;
    }
}

/**
 * Display a navigation menu
 *
 * @param string|array $args Menu location or arguments
 * @return string HTML menu
 */
if (!function_exists('wp_nav_menu')) {
    function wp_nav_menu($args = []) {
        // Normalize arguments
        $defaults = [
            'theme_location' => '',
            'menu' => '',
            'container' => 'ul',
            'container_class' => '',
            'container_id' => '',
            'menu_class' => '',
            'menu_id' => '',
            'echo' => true,
            'fallback_cb' => '',
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth' => 0,
            'walker' => null,
        ];

        if (is_string($args)) {
            $args = ['theme_location' => $args];
        }

        $args = array_merge($defaults, $args);
        
        // Find the menu
        $menu = null;
        
        // If a theme location is specified
        if (!empty($args['theme_location'])) {
            $menu = Menu::where('location', $args['theme_location'])
                  ->where('status', 1)
                  ->first();
        }
        
        // If a specific menu is specified by name or ID
        if (!$menu && !empty($args['menu'])) {
            if (is_numeric($args['menu'])) {
                $menu = Menu::where('id', $args['menu'])
                      ->where('status', 1)
                      ->first();
            } else {
                $menu = Menu::where('name', $args['menu'])
                      ->orWhere('slug', $args['menu'])
                      ->where('status', 1)
                      ->first();
            }
        }
        
        // If no menu was found
        if (!$menu) {
            if (is_callable($args['fallback_cb'])) {
                return call_user_func($args['fallback_cb'], $args);
            }
            return '';
        }
        
        // Build menu items
        $menu_items = $menu->activeRootItems()->with(['activeChildren'])->get();
        
        // Generate menu HTML
        $items_output = '';

        // "Акции" link: added automatically unless the menu already has it
        $add_akcii = $args['theme_location'] === 'primary_menu'
            && !$menu_items->contains(fn ($i) => stripos((string) $i->url, 'akcii') !== false);
        $akcii_added = false;

        foreach ($menu_items as $item) {
            $items_output .= render_menu_item($item, $args);
            if ($add_akcii && !$akcii_added
                && preg_match('#/blogs?$#i', rtrim((string) parse_url((string) $item->url, PHP_URL_PATH), '/'))) {
                $items_output .= render_akcii_menu_item($args);
                $akcii_added = true;
            }
        }
        if ($add_akcii && !$akcii_added) {
            $items_output .= render_akcii_menu_item($args);
        }
        
        // Wrap the menu items
        $menu_id = $args['menu_id'] ? $args['menu_id'] : 'menu-' . $menu->slug;
        $menu_class = $args['menu_class'] ? $args['menu_class'] : 'menu-' . $menu->slug . '-container';
        
        $output = sprintf(
            $args['items_wrap'],
            esc_attr($menu_id),
            esc_attr($menu_class),
            $items_output
        );
        
        // if ($args['echo']) {
        //     echo $output;
        // }
        
        return $output;
    }
}

/**
 * Daxili (öz saytımızın) URL-ə cari dil prefiksini (/ru, /az, ...) əlavə edir.
 *
 * Menyu elementlərinin URL-i DB-də prefiksiz saxlanılır (məs. /blogs).
 * SetLocaleFromUrl middleware yalnız URL-də prefix olduqda forceRootUrl qurur;
 * prefiksiz URL-ə düşən kimi bütün route()/url() linkləri prefiksini itirir.
 * Ona görə menyu linklərinə prefiksi burada əl ilə əlavə edirik ki, istifadəçi
 * naviqasiya zamanı seçdiyi dildən "düşməsin".
 *
 * Xarici linklər, mailto:/tel:/javascript:, anchor (#) və admin/agency
 * URL-ləri olduğu kimi saxlanılır.
 *
 * @param string|null $url
 * @return string
 */
if (!function_exists('localize_front_url')) {
    function localize_front_url($url) {
        $url = trim((string) ($url ?? ''));

        if ($url === '' || $url === '#') {
            return $url === '' ? '#' : $url;
        }

        // Xüsusi sxemlər və anchor → toxunmuruq
        if (preg_match('~^(mailto:|tel:|javascript:|data:|#)~i', $url)) {
            return $url;
        }

        $parts = parse_url($url);

        // Xarici host → olduğu kimi
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $reqHost = request()->getHost();
        if (!empty($parts['host']) && !in_array($parts['host'], array_filter([$appHost, $reqHost]), true)) {
            return $url;
        }

        $path = $parts['path'] ?? '/';
        if ($path === '') {
            $path = '/';
        }
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        // Mövcud dil kodları
        $codes = array_keys(config('site_languages', []));
        if (empty($codes)) {
            $codes = ['ru', 'az'];
        }
        $pattern = implode('|', array_map('preg_quote', $codes));

        // Əvvəlcədən mövcud dil prefiksini təmizlə (təkrar prefiks olmasın: /ru/ru/...)
        $cleanPath = preg_replace('#^/(' . $pattern . ')(?=/|$)#', '', $path);
        if ($cleanPath === '' || $cleanPath === null) {
            $cleanPath = '/';
        }

        // Admin/agency həmişə prefiksiz qalır
        if (preg_match('#^/(admin|agency)(/|$)#', $cleanPath)) {
            return $cleanPath . $query . $fragment;
        }

        // Cari dil (URL prefiksi) — SetLocaleFromUrl tərəfindən təyin olunur
        $lang = request()->attributes->get('display_lang');
        if (!$lang || !in_array($lang, $codes, true)) {
            $lang = $codes[0] ?? 'ru';
        }

        $prefixed = '/' . $lang . ($cleanPath === '/' ? '' : $cleanPath);

        return $prefixed . $query . $fragment;
    }
}

/**
 * Render the built-in "Акции" (promotions) menu item
 */
if (!function_exists('render_akcii_menu_item')) {
    function render_akcii_menu_item($args) {
        $item = new MenuItem([
            'title' => (request()->attributes->get('display_lang') ?: app()->getLocale()) === 'ru' ? 'Акции' : 'Promotions',
            'url' => '/akcii',
        ]);

        return render_menu_item($item, $args);
    }
}

/**
 * Render a menu item and its children
 *
 * @param MenuItem $item
 * @param array $args
 * @param int $depth
 * @return string
 */
if (!function_exists('render_menu_item')) {
    function render_menu_item($item, $args, $depth = 0) {
        $has_children = $item->hasActiveChildren();
        $item_classes = [];
        
        // Add classes for items with children
        if ($has_children) {
            $item_classes[] = 'menu-item-has-children';
            // if ($depth == 0) {
            //     $item_classes[] = 'dropdown';
            // } else {
            //     $item_classes[] = 'dropend';
            // }
        }
        
        // Custom CSS classes from the item
        if ($item->css_class) {
            $item_classes = array_merge($item_classes, explode(' ', $item->css_class));
        }
        
        // Current page detection (simple version)
        $current_url = url()->current();
        // request path-i prefiksiz gəlir (SetLocaleFromUrl REQUEST_URI-ni təmizləyir)
        $current_path = '/' . trim(request()->getPathInfo(), '/');
        $item_path = $item->url ? '/' . trim((string) parse_url($item->url, PHP_URL_PATH), '/') : null;
        if ($item->url && (
            $current_url == $item->url
            || $current_url == url($item->url)
            || ($item_path !== null && $current_path === $item_path)
        )) {
            $item_classes[] = 'current-menu-item';
            $item_classes[] = 'active';
        }
        
        $item_class_string = !empty($item_classes) ? ' class="' . esc_attr(implode(' ', $item_classes)) . '"' : '';
        
        // Build the menu item
        $item_output = '<li' . $item_class_string . '>';
        
        // Link attributes — daxili URL-ə cari dil prefiksini əlavə et (/ru, /az, ...)
        $atts = [
            'href' => !empty($item->url) ? localize_front_url($item->url) : '#',
            'target' => !empty($item->target) ? $item->target : '',
            // 'class' => $has_children && $depth == 0 ? 'nav-link dropdown-toggle' : 'nav-link',
        ];
        
        if ($has_children && $depth == 0) {
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
        }
        
        // Build the link element
        $attributes = '';
        foreach ($atts as $key => $value) {
            if (!empty($value)) {
                $attributes .= ' ' . $key . '="' . esc_attr($value) . '"';
            }
        }
        
        $title = $args['link_before'] . $item->title . $args['link_after'];
        $item_output .= $args['before'] . '<a' . $attributes . '>';
        
        // Add icon if exists
        if (!empty($item->icon_class)) {
            $item_output .= '<i class="' . esc_attr($item->icon_class) . '"></i> ';
        }
        
        $item_output .= $title . '</a>' . $args['after'];
        
        // If this item has children, append a submenu
        if ($has_children) {
            $sub_menu_classes = $depth == 0 ? 'sub-menu' : 'sub-menu';
            $item_output .= '<ul class="' . $sub_menu_classes . '">';
            
            $child_args = $args;
            $child_args['menu_class'] = 'sub-menu';
            
            foreach ($item->activeChildren as $child) {
                $item_output .= render_menu_item($child, $child_args, $depth + 1);
            }
            
            $item_output .= '</ul>';
        }
        
        $item_output .= '</li>';
        
        return $item_output;
    }
}

/**
 * Escape an HTML attribute
 */
if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Get all registered menu locations for the current theme
 *
 * @return array Menu locations
 */
if (!function_exists('get_registered_nav_menus')) {
    function get_registered_nav_menus() {
        $theme = app('theme');
        $themeInfo = $theme->loadThemeInfo($theme->current());
        
        return $themeInfo['menu_locations'] ?? [];
    }
}

/**
 * Check if a menu location has a menu assigned
 *
 * @param string $location Menu location identifier
 * @return bool
 */
if (!function_exists('has_nav_menu')) {
    function has_nav_menu($location) {
        return Menu::where('location', $location)
              ->where('status', 1)
              ->exists();
    }
} 