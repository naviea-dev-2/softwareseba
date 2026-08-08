<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/rocker/demo/vertical/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:03:47 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    @php
       $gtext = gtext();
    @endphp

    <meta name="keywords" content="{{ $gtext['og_keywords'] }}" />
	<meta name="description" content="{{ $gtext['og_description'] }}" />
	<meta property="og:title" content="{{ $gtext['og_title'] }}" />
	{{-- <meta property="og:site_name" content="{{ $gtext['site_name'] }}" /> --}}
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
	<!-- Facebook Pixel Code -->
	<script>
	  !function(f,b,e,v,n,t,s)
	  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	  n.queue=[];t=b.createElement(e);t.async=!0;
	  t.src=v;s=b.getElementsByTagName(e)[0];
	  s.parentNode.insertBefore(t,s)}(window, document,'script',
	  'https://connect.facebook.net/en_US/fbevents.js');
	  fbq('init', '{{ $gtext["fb_pixel_id"] }}');
	  fbq('track', 'PageView');
	</script>
	<noscript>
	  <img height="1" width="1" style="display:none"
		   src="https://www.facebook.com/tr?id={{ $gtext['fb_pixel_id'] }}&ev=PageView&noscript=1"/>
	</noscript>
	<!-- End Facebook Pixel Code -->
	@endif
    @if($gtext['ga_publish'] == 1)
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtext['tracking_id'] }}"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', '{{ $gtext["tracking_id"] }}');
	</script>
	@endif
    @if($gtext['gtm_publish'] == 1)
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','{{ $gtext["google_tag_manager_id"] }}');</script>
	<!-- End Google Tag Manager -->
	@endif
    <!--favicon-->
	<link rel="shortcut icon" href="{{ $gtext['favicon'] ? asset('public/upload/site_setting/'.$gtext['favicon']) : asset('public/assets/images/fav.png') }}" type="image/x-icon">
	<link rel="icon" href="{{ $gtext['favicon'] ? asset('public/upload/site_setting/'.$gtext['favicon']) : asset('public/assets/images/fav.png') }}" type="image/x-icon">
	{{-- <!--favicon-->
	<link rel="icon" href="{{  asset('public')  }}/assets/images/fav.png" type="image/png"/> --}}
	<!--plugins-->

    <style>
        :root {
	        --header_back_color: {{ $gtext['header_back_color'] }};
	        --header_font_color: {{ $gtext['header_font_color'] }};
            --sidebar_back_color: {{ $gtext['sidebar_back_color'] }};
	        --sidebar_font_color: {{ $gtext['sidebar_font_color'] }};
	        --sidebar_font_hover_color: {{ $gtext['sidebar_font_hover_color'] }};
	        --sidebar_back_hover_color: {{ $gtext['sidebar_back_hover_color'] }};
        }

    </style>
	<link href="{{ asset('public/assets/css') }}/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="{{ asset('public/assets/css') }}/simplebar.css" rel="stylesheet" />
	<link href="{{ asset('public/assets/css') }}/perfect-scrollbar.css" rel="stylesheet" />
	<link href="{{ asset('public/assets/css') }}/metisMenu.min.css" rel="stylesheet"/>

    <link href="{{ asset('public/assets/css') }}/flatpickr.min.css" rel="stylesheet"/>
    <link href="{{ asset('public/assets/css') }}/flatpickr-monthSelect.css" rel="stylesheet"/>
	<!-- loader-->
	<link href="{{ asset('public/assets/css') }}/pace.min.css" rel="stylesheet"/>
	<script src="{{ asset('public/assets/js') }}/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('public/assets/css') }}/bootstrap.min.css" rel="stylesheet">
	<link href="{{ asset('public/assets/css') }}/bootstrap-extended.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/select2.min.css"/>
    <link rel="stylesheet" href="{{ asset('public/assets/css') }}/select2-bootstrap-5-theme.min.css"/>

    <link rel="stylesheet" href="{{ asset('public/assets/js') }}/toasterCss.css"/>

	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{ asset('public/assets/css') }}/app.css" rel="stylesheet">
	<link href="{{ asset('public/assets/css') }}/theme_option.css" rel="stylesheet">
	<link href="{{ asset('public/assets/css') }}/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/dark-theme.css"/>
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/semi-dark.css"/>
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/header-colors.css"/>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    {{-- <link href="{{ asset('public/assets/css') }}/bootstrap-datepicker.min.css" rel="stylesheet"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
	@yield("head")
    @if($gtext['custom_css'] != '')
	<style type="text/css">
        {!! $gtext['custom_css'] !!}
	</style>
	@endif
</head>

<body>
    @if($gtext['gtm_publish'] == 1)
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtext['google_tag_manager_id'] }}"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	@endif
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div class="logo-container" style=" width: 70%;">
					<img src="{{ $gtext['back_logo'] ? asset('public/upload/site_setting/'.$gtext['back_logo']) : asset('public/assets/images/logo.png') }}" class="logo-icon" alt="logo icon" style="height: 45px; width: 100%;">
				</div>

				<div>
					{{-- <h4 class="logo-text">Rocker</h4> --}}
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
				</div>
			 </div>
			@include('admin.inc.sidebar')
		</div>
		<!--end sidebar wrapper -->
		@include('admin.inc.header')
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
                @yield('content')
            </div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		 <div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button-->
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">{{$gtext['right_text']}}</p>
		</footer>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('public/assets/js') }}/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="{{ asset('public/assets/js') }}/jquery.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/simplebar.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/metisMenu.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/perfect-scrollbar.js"></script>
    <script src="{{ asset('public/assets/js') }}/select2.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/app.js"></script>
    <script src="{{ asset('public/assets/js') }}/script.js"></script>
    <script src="{{ asset('public/assets/js') }}/toastr.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/flatpickr.js"></script>
    <script src="{{ asset('public/assets/js') }}/flatpickr-monthSelect.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

{{--
<script src="{{ asset('public/assets/js') }}/bootstrap-datepicker.min.js"></script> --}}
	<script>
		// new PerfectScrollbar(".app-container")
        var type="{{Session::get('alert-type')}}"
        switch(type){
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
    @yield('script')
    @if($gtext['custom_js'] != '')
	<script>
        {!! $gtext['custom_js'] !!}
	</script>
	@endif
</body>

</html>
