    <header class="tg-header-height">
        <div class="tg-header__area tg-header-lg-space z-index-999 tg-transparent" id="header-sticky" data-gs-header>
            @include('partials.header-topbar')
            <div class="container-fluid container-1860 gs-mainbar">
                <div class="gs-mainbar__row">
                    <div class="logo">
                        <a class="logo-1" href="{{ route('home') }}">
                            <img src="{{ asset($general_setting->logo) }}" alt="Logo">
                        </a>
                        <a class="logo-2 d-none" href="{{ route('home') }}">
                            <img src="{{ asset($general_setting->secondary_logo) }}" alt="Logo">
                        </a>
                    </div>
                    <nav class="tgmenu__nav gs-mainnav">
                        <div class="tgmenu__navbar-wrap tgmenu__main-menu">
                            @include('components.common_navitems')
                        </div>
                    </nav>
                    <div class="gs-burger">
                        <button class="tgmenu-offcanvas-open-btn mobile-nav-toggler" aria-label="Menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu  -->
        @include('components.common_mobile_menu')
        <!-- End Mobile Menu -->
    <script>(function () { var widget = document.createElement('script'); widget.defer = true; widget.dataset.pfId = '8719c067-19f4-4029-b81e-a90b282e3a7f'; widget.src = 'https://widget.yourgood.app/script/widget.js?id=8719c067-19f4-4029-b81e-a90b282e3a7f&now='+Date.now(); document.head.appendChild(widget); })()</script>
        <!-- offCanvas-menu -->
        @include('components.common_offcanvas')
        <!-- offCanvas-menu-end -->

    </header>

    {{-- ========== CUSTOM HEADER CSS ========== --}}
    <style>
    /* ===== Logo-Menyu boşluğunu azaltma ===== */
    .tgmenu__nav.tgmenu-1-space.ml-30 {
        margin-left: 30px !important;
    }

    /* ===== Müasir font import ===== */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap');

    /* ===== Menyu yazılarına müasir font ===== */
    .tgmenu__main-menu li a,
    .tgmenu__navbar-wrap li a {
        font-family: 'Poppins', sans-serif !important;
        font-weight: 600 !important;
        letter-spacing: 0.3px;
        text-transform: none;
    }

    /* Social Contact Icons Container */
    .tg-header-social-contacts {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-right: 14px;
        padding-right: 14px;
        border-right: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Individual Social Icon Base */
    .tg-social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        transition: all 0.3s ease;
        text-decoration: none !important;
        font-size: 15px;
        color: #fff !important;
    }

    .tg-social-icon:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    /* WhatsApp */
    .tg-social-icon.tg-whatsapp {
        background: #25D366 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-whatsapp:hover {
        background: #1da851 !important;
    }

    /* Telegram */
    .tg-social-icon.tg-telegram {
        background: #0088cc !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-telegram:hover {
        background: #006699 !important;
    }

    /* VKontakte */
    .tg-social-icon.tg-vk {
        background: #4C75A3 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-vk:hover {
        background: #3b6291 !important;
    }

    /* OK (Odnoklassniki) */
    .tg-social-icon.tg-ok {
        background: #EE8208 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-ok:hover {
        background: #d67205 !important;
    }

    /* Email */
    .tg-social-icon.tg-email {
        background: #EA4335 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-email:hover {
        background: #d33426 !important;
    }

    /* Tooltip on hover */
    .tg-social-icon[title] {
        position: relative;
    }

    .tg-social-icon[title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
        z-index: 100;
    }

    .tg-social-icon[title]:hover::before {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-bottom-color: #333;
        z-index: 100;
    }

    /* ===== Currency Switcher ===== */
    .tg-currency-switcher {
        position: relative;
    }

    .tg-currency-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #1a56db;
        border: 2px solid #1a56db;
        border-radius: 8px;
        padding: 8px 32px 8px 14px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: #fff;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        min-width: 95px;
    }

    .tg-currency-select option {
        background: #fff;
        color: #333;
        font-weight: 600;
    }

    .tg-currency-select:hover {
        background: #1648c0;
        border-color: #1648c0;
    }

    .tg-currency-select:focus {
        background: #1648c0;
        border-color: #1648c0;
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.3);
    }

    /* ===== Language Switcher ===== */
    .tg-language-switcher {
        position: relative;
    }

    .tg-language-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #1a56db;
        border: 2px solid #1a56db;
        border-radius: 8px;
        padding: 8px 32px 8px 14px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: #fff;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        min-width: 75px;
    }

    .tg-language-select option {
        background: #fff;
        color: #333;
        font-weight: 600;
    }

    .tg-language-select:hover {
        background: #1648c0;
        border-color: #1648c0;
    }

    .tg-language-select:focus {
        background: #1648c0;
        border-color: #1648c0;
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.3);
    }
    </style>

    {{-- ========== MENYU AVTOMATIK FİT (font kichilt, aşağı keçmə) ========== --}}
    {{-- Tərcümədən sonra menyu yazıları uzanırsa: AŞAĞI keçmək əvəzinə font-size
         avtomatik kichilir ki, tək sətirdə qalsın. Yalnız ən kiçik ölçüdə (11px)
         belə sığmasa, son çarə kimi aşağı keçir — heç vaxt sağ bloka daşmır. --}}
    <script>
    (function () {
        function getUl(){ return document.querySelector('.tgmenu__main-menu .navigation'); }

        function fit(){
            var ul = getUl();
            if (!ul) return;
            var lis = [], links = [];
            Array.prototype.forEach.call(ul.children, function (li) {
                if (li.tagName === 'LI') { lis.push(li); var a = li.querySelector('a'); if (a) links.push(a); }
            });
            if (!links.length) return;

            function setFont(px){
                links.forEach(function (a) {
                    if (px) a.style.setProperty('font-size', px + 'px', 'important');
                    else a.style.removeProperty('font-size');
                });
            }
            function mode(single){
                // CSS-də nowrap !important var; wrap-a keçmək üçün də !important lazımdır.
                ul.style.setProperty('flex-wrap', single ? 'nowrap' : 'wrap', 'important');
                lis.forEach(function (li) { li.style.flexShrink = single ? '0' : '1'; });
                links.forEach(function (a) { a.style.whiteSpace = single ? 'nowrap' : 'normal'; });
            }

            // <1200px: mobil menyu — toxunma
            if (window.innerWidth < 1200) { setFont(null); mode(false); return; }

            setFont(null);
            mode(true); // tək sətir — real ölçmək üçün

            // SAĞ BLOKUN ən sol GÖRÜNƏN elementinin sol kənarı (qutu deyil — boş yer
            // problemini həll edir). Menyunun son elementinin sağ kənarı bundan
            // keçirsə → daşır.
            var rightBlock = document.querySelector('.tg-menu-right-action');
            function rightStart(){
                if (rightBlock) {
                    var min = Infinity;
                    Array.prototype.forEach.call(rightBlock.children, function (ch) {
                        var r = ch.getBoundingClientRect();
                        if (r.width > 0 && r.height > 0 && r.left < min) min = r.left;
                    });
                    if (min !== Infinity) return min;
                    return rightBlock.getBoundingClientRect().left;
                }
                var col = ul.closest('.col-lg-7');
                return col ? col.getBoundingClientRect().right : window.innerWidth;
            }
            function overflowing(){
                var lastRight = links[links.length - 1].getBoundingClientRect().right;
                return lastRight > rightStart() - 18; // 18px boşluq: menyu ilə ikonlar arasında
            }

            var base = parseFloat(getComputedStyle(links[0]).fontSize) || 15;
            var size = base, min = 10, guard = 0;
            while (overflowing() && size > min && guard < 40) {
                size -= 0.5; setFont(size); guard++;
            }
            // AŞAĞI KEÇMƏ (wrap) YOXDUR — istifadəçi yalnız font kichilməsini istəyir.
            // Menyu həmişə tək sətir qalır (CSS-də nowrap). Min font-da da sığmasa
            // belə wrap-a keçmir.
        }

        var t;
        function schedule(){ clearTimeout(t); t = setTimeout(fit, 60); }

        function init(){
            fit();
            var ul = getUl();
            // Google tərcüməsi menyu mətnini sonradan dəyişir — izlə
            if (ul && window.MutationObserver) {
                new MutationObserver(schedule).observe(ul, { childList: true, subtree: true, characterData: true });
            }
            if (window.MutationObserver) {
                new MutationObserver(schedule).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            }
        }

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
        window.addEventListener('resize', schedule);
        // Bütün şəkillər/fontlar yüklənəndən sonra yenidən (Poppins gec yüklənsə mətn enlənir)
        window.addEventListener('load', fit);
        if (document.fonts && document.fonts.ready) { document.fonts.ready.then(fit); }
        // əlavə təhlükəsizlik: ilk saniyələrdə bir neçə dəfə (tərcümə gec gəlsə)
        [400, 1200, 2500].forEach(function (ms) { setTimeout(fit, ms); });
    })();
    </script>