<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
    $gtext = gtext();
    $f_gtext = f_gtext();
    $l_user = auth()->user();
    @endphp
    <meta name="keywords" content="{{ $gtext['og_keywords'] }}" />
    <meta name="description" content="{{ $gtext['og_description'] }}" />
    <meta property="og:title" content="{{ $gtext['og_title'] }}" />
    <meta property="og:description" content="{{ $gtext['og_description'] }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('public/upload/theme_option/'.$gtext['og_image']) }}" />
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="315" />
    @if($gtext['fb_publish'] == 1)
    <meta name="fb:app_id" property="fb:app_id" content="{{ $gtext['fb_app_id'] }}" />
    @endif
    <meta name="twitter:card" content="summary_large_image">
    @if($gtext['twitter_publish'] == 1)
    <meta name="twitter:site" content="{{ $gtext['twitter_id'] }}">
    <meta name="twitter:creator" content="{{ $gtext['twitter_id'] }}">
    @endif
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $gtext['og_title'] }}">
    <meta name="twitter:description" content="{{ $gtext['og_description'] }}">
    <meta name="twitter:image" content="{{ asset('public/upload/theme_option/'.$gtext['og_image']) }}">

    @if($gtext['fb_pixel_publish'] == 1)
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $gtext["fb_pixel_id"] }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $gtext['fb_pixel_id'] }}&ev=PageView&noscript=1" />
    </noscript>
    @endif

    @if($gtext['ga_publish'] == 1)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtext['tracking_id'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '{{ $gtext["tracking_id"] }}');
    </script>
    @endif

    @if($gtext['gtm_publish'] == 1)
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $gtext["google_tag_manager_id"] }}');
    </script>
    @endif

    <link rel="shortcut icon" href="{{ $gtext['favicon'] ? asset('public/upload/site_setting/'.$gtext['favicon']) : asset('public/assets/images/fav.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ $gtext['favicon'] ? asset('public/upload/site_setting/'.$gtext['favicon']) : asset('public/assets/images/fav.png') }}" type="image/x-icon">

    <style>
        :root {
            --header_back_color: {
                    {
                    $f_gtext['header_back_color']
                }
            }

            ;

            --header_font_color: {
                    {
                    $f_gtext['header_font_color']
                }
            }

            ;

            --sidebar_back_color: {
                    {
                    $f_gtext['sidebar_back_color']
                }
            }

            ;

            --sidebar_font_color: {
                    {
                    $f_gtext['sidebar_font_color']
                }
            }

            ;

            --sidebar_font_hover_color: {
                    {
                    $f_gtext['sidebar_font_hover_color']
                }
            }

            ;

            --sidebar_back_hover_color: {
                    {
                    $f_gtext['sidebar_back_hover_color']
                }
            }

            ;
        }
    </style>

    <link href="{{ asset('public/assets/css') }}/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="{{ asset('public/assets/css') }}/simplebar.css" rel="stylesheet" />
    <link href="{{ asset('public/assets/css') }}/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ asset('public/assets/css') }}/metisMenu.min.css" rel="stylesheet" />
    <link href="{{ asset('public/assets/css') }}/flatpickr.min.css" rel="stylesheet" />
    <link href="{{ asset('public/assets/css') }}/flatpickr-monthSelect.css" rel="stylesheet" />
    <link href="{{ asset('public/assets/css') }}/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('public/assets/js') }}/pace.min.js"></script>
    <link href="{{ asset('public/assets/css') }}/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('public/assets/css') }}/bootstrap-extended.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/select2.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/js') }}/toasterCss.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('public/assets/css') }}/app.css" rel="stylesheet">
    <link href="{{ asset('public/assets/css') }}/icons.css" rel="stylesheet">
    <link href="{{ asset('public/assets/css') }}/theme_option.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/dark-theme.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/semi-dark.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/header-colors.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/production.css" />

    <style>
        .skiptranslate iframe {
            display: none;
        }

        #google_translate_element2 {
            position: relative;
            overflow: hidden;
            padding: 0px;
            margin: 0;
            width: 120px;
        }

        #google_translate_element2 .goog-te-gadget {
            overflow: hidden;
            position: relative;
            padding: 10px;
        }

        #google_translate_element2 .goog-te-gadget .goog-te-combo {
            position: absolute;
            background: var(--header_back_color);
            color: var(--header_font_color);
            width: 100%;
            top: 0px;
            left: 0;
            border: 1px solid #e2e2e2;
            padding: 7px;
            font-size: 12px;
            border-radius: 4px;
        }

        .topbar a {
            color: var(--header_font_color);
        }

        .topbar .navbar .navbar-nav .nav-link {
            color: var(--header_font_color);
        }

        .topbar .navbar .dropdown-menu {
            background: var(--header_back_color);
            color: var(--header_font_color);
        }

        .topbar .dropdown-item:focus .msg-info,
        .dropdown-item:hover .msg-info {
            color: #000;
        }

        .topbar .dropdown-item:focus .msg-name,
        .dropdown-item:hover .msg-name {
            color: #000;
        }

        .topbar .dropdown-item:focus .msg-time,
        .dropdown-item:hover .msg-time {
            color: #000;
        }

        #google_translate_element2 .goog-te-gadget .goog-te-combo option {
            font-size: 12px;
            background: #ffff;
            color: #000;
        }
    </style>

    <style>
        .countdown {
            display: none;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
        }

        .container {
            padding-top: 50px;
        }

        .timer-box {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
        }
    </style>

    @yield("head")
</head>

<body>
    <div class="wrapper">
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div class="logo-container" style="width: 70%;">
                    <img src="{{ $gtext['back_logo'] ? asset('public/upload/site_setting/'.$gtext['back_logo']) : asset('public/assets/images/logo.png') }}" class="logo-icon" alt="logo icon" style="height: 45px; width: 100%;">
                </div>
                <div></div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
            </div>
            @include('inc.sidebar')
        </div>
        @include('inc.header')
        <div class="page-wrapper" style="background:#eef2f6;">
            <div class="page-content">
                @include('inc.flash_message')
                @yield('content')
            </div>
        </div>
        <div class="overlay toggle-icon"></div>
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <footer class="page-footer">
            <p class="mb-0">{{$gtext['right_text']}}</p>
        </footer>
    </div>

    <div class="modal" id="SearchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header gap-2">
                    <div class="position-relative popup-search w-100">
                        <input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="Search">
                        <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
                    </div>
                    <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search-list">
                        <p class="mb-1">Html Templates</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bxl-angular fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vuejs fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-magento fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-shopify fs-4'></i>eCommerce Html Templates</a>
                        </div>
                        <p class="mb-1 mt-3">Web Designe Company</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-windows fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-dropbox fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-opera fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-wordpress fs-4'></i>eCommerce Html Templates</a>
                        </div>
                        <p class="mb-1 mt-3">Software Development</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-mailchimp fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-zoom fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-sass fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vk fs-4'></i>eCommerce Html Templates</a>
                        </div>
                        <p class="mb-1 mt-3">Online Shoping Portals</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-skype fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-twitter fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vimeo fs-4'></i>eCommerce Html Templates</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function googleTranslateElementInit2() {
            new google.translate.TranslateElement({
                pageLanguage: 'bd',
                autoDisplay: false
            }, 'google_translate_element2');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>
    <script src="{{ asset('public/assets/js') }}/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/jquery.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/simplebar.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/metisMenu.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/perfect-scrollbar.js"></script>
    <script src="{{ asset('public/assets/js') }}/select2.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/app.js"></script>
    <script src="{{ asset('public/assets/js') }}/toastr.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/flatpickr.js"></script>
    <script src="{{ asset('public/assets/js') }}/flatpickr-monthSelect.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var type = "{{Session::get('alert-type')}}"
        switch (type) {
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
    </script>

    <script>
        $('.select2').select2();
        $(document).ready(function() {
            let countdownDate;

            function startCountdown() {
                if (document.getElementById("countdownDate")) {
                    countdownDate = new Date(document.getElementById("countdownDate").value).getTime();
                    document.getElementById("countdown").style.display = "flex";

                    let x = setInterval(function() {
                        let now = new Date().getTime();
                        let distance = countdownDate - now;
                        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
                        document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
                        document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
                        document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

                        if (distance < 0) {
                            clearInterval(x);
                            document.getElementById("countdown").innerHTML = "<span>Free Trial Expired</span>, Please Contact us";
                        }
                    }, 1000);
                }
            }
            startCountdown();
        });
    </script>

    <script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
    <script>
        function initializeCKEditors() {

            document.querySelectorAll('.ckeditor').forEach(function(element) {

                // Prevent duplicate initialization
                if (element.dataset.ckeditorInitialized) {
                    return;
                }

                ClassicEditor
                    .create(element, {
                        toolbar: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'outdent',
                            'indent',
                            '|',
                            'blockQuote',
                            'insertTable',
                            '|',
                            'undo',
                            'redo'
                        ]
                    })
                    .then(editor => {

                        element.dataset.ckeditorInitialized = 'true';

                        // Keep editor instance on textarea
                        element._ckeditor = editor;

                        editor.editing.view.change(writer => {
                            writer.setStyle(
                                'min-height',
                                '300px',
                                editor.editing.view.document.getRoot()
                            );
                        });
                    })
                    .catch(error => {
                        console.error('CKEditor error:', error);
                    });
            });
        }

        // Create form
        document.addEventListener('DOMContentLoaded', function() {
            initializeCKEditors();
        });
    </script>
    @yield('script')
</body>

</html>