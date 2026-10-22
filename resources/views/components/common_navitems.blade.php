@php ob_start(); @endphp
{!! wp_nav_menu([
    'theme_location' => 'primary_menu',
    'menu_class' => 'navigation',
    'container' => false,
    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'menu_id' => 'main-nav',
    'before' => '',
    'after' => '',
    'link_before' => '',
    'link_after' => ''
]) !!}

@php
$gsNavHtml = ob_get_clean();
$gsMapUrl = '/' . app()->getLocale() . '/karta-sanatoriev';
if (strpos($gsNavHtml, 'karta-sanatoriev') === false) {
    $gsMapItem = '<li class="menu-item"><a href="' . $gsMapUrl . '">Карта санаториев</a></li>';
    $gsPos = strrpos($gsNavHtml, '</ul>');
    if ($gsPos !== false) {
        $gsNavHtml = substr_replace($gsNavHtml, $gsMapItem, $gsPos, 0);
    }
}
echo $gsNavHtml;
@endphp
