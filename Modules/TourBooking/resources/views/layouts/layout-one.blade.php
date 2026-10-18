<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="{{ asset($general_setting->favicon) }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site Title -->
    @include('partials.seo_head')

    @include('components.front.css')

    @include('components.front.google_analytic')

    @include('components.front.pixel_status')

    @stack('style_section')

</head>

<body>

    @include('components.front.loader')

    @include('components.front.backtotop')

    <!-- header-area -->
    <header class="tg-header-height">
        <div class="tg-header__area">
            <div class="tg-header-top tg-header-top-space tg-primary-bg d-none d-lg-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="tg-header-top-info d-flex align-items-center">
                                <a href="https://www.google.com/maps/@41.6758525,-86.2531698,18.17z"><i
                                        class="mr-5 fa-regular fa-location-dot"></i> 178 makr street, 8007 Australia</a>
                                <span class="tg-header-dvdr mr-10 ml-10"></span>
                                <a href="mailto:info@Tourex.com"><i class="mr-5 fa-regular fa-envelope"></i>
                                    info@Tourex.com</a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="tg-header-top-info d-flex align-items-center justify-content-end">
                                <a href="tel:+123595966"><i class="fa-sharp fa-regular fa-phone"></i> +123 5959 66</a>
                                <span class="tg-header-dvdr mr-10 ml-10"></span>
                                <a href="login.html"><i class="fa-regular fa-user"></i> Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tg-header-4-bootom tg-header-lg-space" id="header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-5">
                            <div class="tgmenu__wrap d-flex align-items-center">
                                <div class="logo flex-auto">
                                    <a href="index.html"><img src="assets/img/logo/logo-green.png" alt="Logo"></a>
                                </div>
                                <nav class="tgmenu__nav  ml-90 d-none d-xl-block">
                                    <div
                                        class="tgmenu__navbar-wrap tgmenu__main-menu tgmenu__navbar-wrap-4 d-none d-xl-flex">
                                        <ul class="navigation">
                                            <li class="menu-item-has-children"><a href="index.html">Home</a>
                                                <ul class="sub-menu">
                                                    <li><a href="index.html">Home One</a></li>
                                                    <li><a href="index-2.html">Home Two</a></li>
                                                    <li><a href="index-3.html">Home Three</a></li>
                                                    <li><a href="index-4.html">Home Four</a></li>
                                                    <li><a href="index-5.html">Home Five</a></li>
                                                    <li><a href="index-6.html">Home Six</a></li>
                                                    <li><a href="index-7.html">Home Saven</a></li>
                                                </ul>
                                            </li>
                                            <li class="active menu-item-has-children"><a
                                                    href="hotel-grid.html">Features</a>
                                                <ul class="sub-menu">
                                                    <li class="active"><a href="hotel-grid.html">Hotel Grid</a></li>
                                                    <li><a href="tour-grid-1.html">Tour Grid One</a></li>
                                                    <li><a href="tour-grid-2.html">Tour Grid Two</a></li>
                                                    <li><a href="map-listing.html">Hotel Listing</a></li>
                                                    <li><a href="tour-details.html">Tour Details One</a></li>
                                                    <li><a href="tour-details-2.html">Tour Details Two</a></li>
                                                </ul>
                                            </li>
                                            <li class="menu-item-has-children"><a href="#">Pages</a>
                                                <ul class="sub-menu">
                                                    <li><a href="about.html">About</a></li>
                                                    <li><a href="team.html">Team</a></li>
                                                    <li><a href="team-details.html">Team Details</a></li>
                                                    <li><a href="shop.html">Shop</a></li>
                                                    <li><a href="shop-details.html">Shop Details</a></li>
                                                    <li><a href="pricing.html">Pricing</a></li>
                                                    <li><a href="faq.html">Faq</a></li>
                                                    <li><a href="login.html">Log In</a></li>
                                                    <li><a href="register.html">Register</a></li>
                                                    <li><a href="error.html">Error</a></li>
                                                </ul>
                                            </li>
                                            <li class="menu-item-has-children"><a href="blog-grid.html">Blogs</a>
                                                <ul class="sub-menu">
                                                    <li><a href="blog-grid.html">Blog Grid</a></li>
                                                    <li><a href="blog-standard.html">Blog Standard</a></li>
                                                    <li><a href="blog-details.html">Blog Details</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="contact.html">Contact</a></li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="col-lg-4 col-7">
                            <div
                                class="tg-menu-right-action tg-menu-right-action-3 tg-menu-4-right-action d-flex align-items-center justify-content-end">
                                <button class="search-button search-open-btn">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.3047 16.8044L13.8294 13.3291M15.9857 8.14485C15.9857 12.1989 12.6992 15.4854 8.64519 15.4854C4.59114 15.4854 1.30469 12.1989 1.30469 8.14485C1.30469 4.09081 4.59114 0.804352 8.64519 0.804352C12.6992 0.804352 15.9857 4.09081 15.9857 8.14485Z"
                                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div class="tg-header-menu-bar lh-1 p-relative ml-10">
                                    <button class="tgmenu-offcanvas-open-btn menu-tigger d-none d-xl-block">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </button>
                                    <button class="tgmenu-offcanvas-open-btn mobile-nav-toggler d-block d-xl-none">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu  -->
        <div class="tgmobile__menu">
            <nav class="tgmobile__menu-box">
                <div class="close-btn"><i class="fa-solid fa-xmark"></i></div>
                <div class="nav-logo">
                    <a href="index.html"><img src="assets/img/logo/logo-green.png" alt="logo"></a>
                </div>
                <div class="tgmobile__search">
                    <form action="#">
                        <input type="text" placeholder="Search here...">
                        <button><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="tgmobile__menu-outer">
                    <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
                </div>
                <div class="social-links">
                    <ul class="list-wrap">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="tgmobile__menu-backdrop"></div>
        <!-- End Mobile Menu -->

        <!-- offCanvas-menu -->
        <div class="offCanvas__info">
            <div class="offCanvas__close-icon menu-close">
                <button><i class="fa-sharp fa-regular fa-xmark"></i></button>
            </div>
            <div class="offCanvas__logo mb-30">
                <a href="index.html"><img src="assets/img/logo/logo-green.png" alt="Logo"></a>
            </div>
            <div class="offCanvas__side-info mb-30">
                <div class="contact-list mb-30">
                    <h4>Office Address</h4>
                    <p>123/A, Miranda City Likaoli <br> Prikano, Dope</p>
                </div>
                <div class="contact-list mb-30">
                    <h4>Phone Number</h4>
                    <p>+0989 7876 9865 9</p>
                    <p>+(090) 8765 86543 85</p>
                </div>
                <div class="contact-list mb-30">
                    <h4>Email Address</h4>
                    <p>info@example.com</p>
                    <p>example.mail@hum.com</p>
                </div>
            </div>
            <div class="offCanvas__social-icon mt-30">
                <a href="javascript:void(0)"><i class="fab fa-facebook-f"></i></a>
                <a href="javascript:void(0)"><i class="fab fa-twitter"></i></a>
                <a href="javascript:void(0)"><i class="fab fa-google-plus-g"></i></a>
                <a href="javascript:void(0)"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="offCanvas__overly"></div>
        <!-- offCanvas-menu-end -->

    </header>
    <!-- header-area-end -->

    @yield('front-content')

    <!-- footer-area-start -->
    <footer>
        <div class="tg-footer-area pt-130 include-bg" data-background="assets/img/footer/footer-2.jpg">
            <div class="container">
                <div class="tg-footer-top pb-40">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget mb-40">
                                <div class="tg-footer-logo mb-20">
                                    <a href="index.html"><img src="assets/img/logo/logo-white.png"
                                            alt=""></a>
                                </div>
                                <p class="mb-20">Pharetra maecenas felis vestibulum convallis mollis nullam congue
                                    sit.d rivers of Finland Quebec.</p>
                                <div class="tg-footer-form mb-30">
                                    <form action="#">
                                        <input type="email" placeholder="Enter your mail">
                                        <button class="tg-footer-form-btn" type="submit">
                                            <svg width="22" height="17" viewBox="0 0 22 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.52514 8.47486H20.4749M20.4749 8.47486L13.5 1.5M20.4749 8.47486L13.5 15.4497"
                                                    stroke="white" stroke-width="1.77778" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="tg-footer-social">
                                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                    <a href="#"><i class="fa-brands fa-pinterest-p"></i></a>
                                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget tg-footer-link ml-80 mb-40">
                                <h3 class="tg-footer-widget-title mb-25">Quick Links</h3>
                                <ul>
                                    <li><a href="index.html">Home</a></li>
                                    <li><a href="about.html">About Us</a></li>
                                    <li><a href="#">Services</a></li>
                                    <li><a href="#">Tour Guide</a></li>
                                    <li><a href="contact.html"> Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget tg-footer-info mb-40">
                                <h3 class="tg-footer-widget-title mb-25">Information</h3>
                                <ul>
                                    <li>
                                        <a class="d-flex"
                                            href="https://www.google.com/maps/@41.6758525,-86.2531698,18.17z">
                                            <span class="mr-15">
                                                <svg width="20" height="24" viewBox="0 0 20 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M19.0013 10.0608C19.0013 16.8486 10.3346 22.6668 10.3346 22.6668C10.3346 22.6668 1.66797 16.8486 1.66797 10.0608C1.66797 7.74615 2.58106 5.52634 4.20638 3.88965C5.83169 2.25297 8.03609 1.3335 10.3346 1.3335C12.6332 1.3335 14.8376 2.25297 16.4629 3.88965C18.0882 5.52634 19.0013 7.74615 19.0013 10.0608Z"
                                                        stroke="white" stroke-width="1.73333" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M10.3346 12.9699C11.9301 12.9699 13.2235 11.6674 13.2235 10.0608C13.2235 8.45412 11.9301 7.15168 10.3346 7.15168C8.73915 7.15168 7.44575 8.45412 7.44575 10.0608C7.44575 11.6674 8.73915 12.9699 10.3346 12.9699Z"
                                                        stroke="white" stroke-width="1.73333" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            58 Street Commercial Road<br> Fratton, Australia
                                        </a>
                                    </li>
                                    <li>
                                        <a class="d-flex" href="tel:+1238889999">
                                            <span class="mr-15">
                                                <i class="fa-sharp text-white fa-solid fa-phone"></i>
                                            </span>
                                            +123 888 9999
                                        </a>
                                    </li>
                                    <li class="d-flex">
                                        <span class="mr-15">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.9987 5.60006V12.0001L16.2654 14.1334M22.6654 12.0002C22.6654 17.8912 17.8897 22.6668 11.9987 22.6668C6.10766 22.6668 1.33203 17.8912 1.33203 12.0002C1.33203 6.10912 6.10766 1.3335 11.9987 1.3335C17.8897 1.3335 22.6654 6.10912 22.6654 12.0002Z"
                                                    stroke="white" stroke-width="1.6" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        <p class="mb-0">
                                            Mon – Sat: 8 am – 5 pm,<br>
                                            Sunday: <span class="text-white d-inline-block">CLOSED</span>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget tg-footer-link mb-40">
                                <h3 class="tg-footer-widget-title mb-25">Utility Pages</h3>
                                <ul>
                                    <li><a href="#">Style Guide</a></li>
                                    <li><a href="#">Password Protected</a></li>
                                    <li><a href="#">404 Error</a></li>
                                    <li><a href="#">Changelog</a></li>
                                    <li><a href="#">License</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tg-footer-copyright text-center">
                <span>
                    Copyright <a href="#">©Tourex</a> | All Right Reserved
                </span>
            </div>
        </div>
    </footer>
    <!-- footer-area-end -->


    @include('components.front.cookie-consent')

    @include('components.front.script')

    @stack('js_section')

</body>

</html>