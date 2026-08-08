<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/rocker/demo/vertical/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:03:47 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    @php
       $gtext = gtext();
       $f_gtext = f_gtext();
		$l_user  =auth()->user();
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
    <style>
        :root {
	        --header_back_color: {{ $f_gtext['header_back_color'] }};
	        --header_font_color: {{ $f_gtext['header_font_color'] }};
            --sidebar_back_color: {{ $f_gtext['sidebar_back_color'] }};
	        --sidebar_font_color: {{ $f_gtext['sidebar_font_color'] }};
	        --sidebar_font_hover_color: {{ $f_gtext['sidebar_font_hover_color'] }};
	        --sidebar_back_hover_color: {{ $f_gtext['sidebar_back_hover_color'] }};
        }

    </style>
	<!--plugins-->
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
	<link href="{{ asset('public/assets/css') }}/icons.css" rel="stylesheet">
    <link href="{{ asset('public/assets/css') }}/theme_option.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/dark-theme.css"/>
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/semi-dark.css"/>
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/header-colors.css"/>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    {{-- <link href="{{ asset('public/assets/css') }}/bootstrap-datepicker.min.css" rel="stylesheet"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .skiptranslate iframe{
            display:none;
        }
        #google_translate_element2{
            position: relative;
            overflow: hidden;
            padding: 0px;
            margin: 0;
            width: 120px;
        }
        #google_translate_element2 .goog-te-gadget{
            overflow: hidden;
            position: relative;
            padding: 10px;
        }
        #google_translate_element2 .goog-te-gadget .goog-te-combo{
            position: absolute;
            background: var(--header_back_color);
            color: var(--header_font_color);
            width: 100%;
            top: 0px;
            left: 0;
            border: 1px solid #e2e2e2;
            padding: 9px;
            font-size: 12px;
            border-radius: 4px;
        }
        .topbar a{
            color: var(--header_font_color);
        }
        .topbar .navbar .navbar-nav .nav-link{
            color: var(--header_font_color);
        }
        .topbar .navbar .dropdown-menu{
            background: var(--header_back_color);
            color: var(--header_font_color);
        }
        .topbar .dropdown-item:focus .msg-info, .dropdown-item:hover .msg-info{
                color: #000;
        }
        .topbar .dropdown-item:focus .msg-name, .dropdown-item:hover .msg-name{
                color: #000;
        }
        .topbar .dropdown-item:focus .msg-time, .dropdown-item:hover .msg-time{
                color: #000;
        }
        #google_translate_element2 .goog-te-gadget .goog-te-combo option{
            font-size: 12px;
            background: #ffff;
			color: #000;
        }
    </style>
	<style>
		.countdown {
			display: none; /* Initially hide */
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
	<!--wrapper-->
	<div class="wrapper">
		
		<!--start header -->

		<header>
			<div class="topbar d-flex justify-content-between align-items-center" style="left: 0;">
				<div class="logo-container">
					<a href="{{route('dashboard')}}">
						<img src="{{ $gtext['back_logo'] ? asset('public/upload/site_setting/'.$gtext['back_logo']) : asset('public/assets/images/logo.png') }}" class="logo-icon" alt="logo icon" style="height: 45px; width: 100%;">
					</a>	
				</div>
				<div class=" d-flex align-items-center" >
					<nav class="navbar navbar-expand gap-3" style="justify-content: space-between;">
						<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
						</div>
						@php
							$au_business = auth()->user()->business;
							$free_exipre = false;
							$pack_expire = false;
						@endphp
							{{-- <div class="search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
								<a href="avascript:;" class="btn d-flex align-items-center"><i class='bx bx-search'></i>Search</a>
							</div> --}}
							
						@if($au_business->user_type == 0)
							@php
								$results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
								$data = array();
								if($results){
									$dataObj = json_decode($results->option_value);
									$data['days'] = $dataObj->days;
								}else{
									$data['days'] = 0;
								}
								$user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays($data['days']);
								$user_now_date = \Carbon\Carbon::now();
							
								if($user_now_date > $user_end_date){
									$free_exipre = true;
								}
							@endphp
							<input type="hidden" id="countdownDate" value="{{ $user_end_date }}">
							<div class="countdown mt-0" id="countdown" style="font-size: 15px;">
								<span>End Free Trial</span> :
								<span id="days">00</span> :
								<span id="hours">00</span> :
								<span id="minutes">00</span> :
								<span id="seconds">00</span>
							</div>
						@else
							@if(\Carbon\Carbon::now()->lte($au_business->pack_end_date) == false)
								@php
									$pack_expire = true;
								@endphp
								<div  style="background:red;color:white;padding:8px;border-radius:10px;">
									Package Expired
								</div>
							@endif
						@endif
						@if($free_exipre == false)
						@if($pack_expire == false)
						<div class="top-menu ms-auto">
							<ul class="navbar-nav align-items-center gap-1">
								{{-- <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
									<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
									</a>
								</li> --}}
								@if(can_p('pos.create'))
								<li>
									<a href="{{route('pos.create')}}" class="btn border">POS</a>
								</li>

								@endif
								<li>
									<a href="{{route('invoice.create_instant')}}" class="btn border">Instant Sale</a>
								</li>
								<li>
									<a href="{{ route('invoice.create') }}" class="btn border">Create Sale</a>
								</li>
									<li>
									<a href="{{ route('purchase.create') }}" class="btn border">Create Purchase</a>
								</li>
								<li class="nav-item dropdown dropdown-laungauge  d-sm-flex">
									<div id="google_translate_element2"></div>
									
								</li>
							</ul>
						</div>
						@endif
						@endif
						<div class="user-box dropdown px-3">
							<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<img src="{{ auth()->user()->image_show }}" class="user-img" alt="user avatar">
								<div class="user-info">
									<p class="user-name mb-0">{{ auth()->user()->name }}</p>
								</div>
							</a>
							<ul class="dropdown-menu dropdown-menu-end">
								@if($free_exipre == false)
								<li><a class="dropdown-item d-flex align-items-center" href="{{route('bussiness.user.edit',auth()->user()->id)}}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
								</li>
								<li><a class="dropdown-item d-flex align-items-center" href="{{route('bussiness.profile.change_password',auth()->user()->id)}}"><i class="bx bx-user fs-5"></i><span>Change Password</span></a>
								</li>
								<li><a class="dropdown-item d-flex align-items-center" href="{{route('business_setting')}}"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
								</li>
								<li><a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
								</li>
								<li>
									<div class="dropdown-divider mb-0"></div>
								</li>
								@endif
								<li><a class="dropdown-item d-flex align-items-center" href="{{ route('sign_out') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="main-section py-0" style="margin-top: 60px;background-color: rgb(239 246 255 /1);min-height: 100vh;">
        @yield('content')
		</div>    

		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">{{$gtext['right_text']}}</p>
		</footer>
	</div>
	<!--end wrapper-->



	
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
	<!--plugins-->
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
	<script>
	$(document).ready(function(){
		console.log("hi");
		let countdownDate;
	
		function startCountdown() {
			if(document.getElementById("countdownDate")){	
				// Get the date and time set by the user
				countdownDate = new Date(document.getElementById("countdownDate")
										.value).getTime();
					console.log(countdownDate);
				// Show the countdown clock
				document.getElementById("countdown").style.display = "flex";
		
				// Update the countdown every 1 second
				let x = setInterval(function() {
					// Get the current date and time
					let now = new Date().getTime();
					
					// Calculate the distance between now and the countdown date
					let distance = countdownDate - now;
					
					// Calculate days, hours, minutes and seconds
					let days = Math.floor(distance / (1000 * 60 * 60 * 24));
					let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
					let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
					let seconds = Math.floor((distance % (1000 * 60)) / 1000);
					
					// Display the result
					document.getElementById("days").innerHTML = days.
						toString().padStart(2, '0');
					document.getElementById("hours").innerHTML = hours.
						toString().padStart(2, '0');
					document.getElementById("minutes").innerHTML = minutes.
						toString().padStart(2, '0');
					document.getElementById("seconds").innerHTML = seconds.
						toString().padStart(2, '0');
					
					// If the countdown is over, display a message
					if (distance < 0) {
					clearInterval(x);
					document.getElementById("countdown").innerHTML = "<span >Free Trial Expired</span>, Please Contact us";
					}
				}, 1000);
			}
		}
		startCountdown();
	 });
  	</script>
    @yield('script')
</body>


<!-- Mirrored from codervent.com/rocker/demo/vertical/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:04:20 GMT -->
</html>
