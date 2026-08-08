<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/rocker/demo/vertical/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:03:47 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
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
    <title>{{ $gtext['company_name'] }} - Admin Dashboard</title>
	<!--plugins-->
	<link href="{{ asset('public/assets/css') }}/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="{{ asset('public/assets/css') }}/simplebar.css" rel="stylesheet" />
	<link href="{{ asset('public/assets/css') }}/perfect-scrollbar.css" rel="stylesheet" />
	<link href="{{ asset('public/assets/css') }}/metisMenu.min.css" rel="stylesheet"/>
	<!-- loader-->
	<link href="{{ asset('public/assets/css') }}/pace.min.css" rel="stylesheet"/>
	<script src="{{ asset('public/assets/js') }}/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('public/assets/css') }}/bootstrap.min.css" rel="stylesheet">
	<link href="{{ asset('public/assets/css') }}/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{ asset('public/assets/css') }}/app.css" rel="stylesheet">
	<link href="{{ asset('public/assets/css') }}/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/dark-theme.css"/>
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/semi-dark.css"/>
	<link rel="stylesheet" href="{{ asset('public/assets/css') }}/header-colors.css"/>
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
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                   <div class="col">
					 <div class="card radius-10 border-start border-0 border-4 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Purchase</p>
									<h4 class="my-1 text-info">{{ auth()->user()->currency_symbol }}{{ round($total_purchase,2) }}</h4>
									{{-- <p class="mb-0 font-13">+2.5% from last week</p> --}}
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				   <div class="col">
					<div class="card radius-10 border-start border-0 border-4 border-danger">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total Sales</p>
								   <h4 class="my-1 text-success">{{ auth()->user()->currency_symbol }}{{  round($total_sale,2) }}</h4>
								   {{-- <p class="mb-0 font-13">+5.4% from last week</p> --}}
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-wallet'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-4 border-success">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Sales Return</p>
								   <h4 class="my-1 text-danger">{{ $return_percent }}%</h4>
								   {{-- <p class="mb-0 font-13">-4.5% from last week</p> --}}
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-4 border-warning">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total Customers</p>
								   {{-- <h4 class="my-1 text-warning">8.4K</h4> --}}
                                   <h4 class="my-1 text-warning">{{  round($total_customer,2) }}</h4>
								   {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				</div><!--end row-->

				<div class="row">
                   <div class="col-12 col-lg-8 d-flex">
                      <div class="card radius-10 w-100">
						<div class="card-header">
							<div class="d-flex align-items-center">
								<div>
									<h6 class="mb-0">Sales Overview</h6>
								</div>
								<div class="dropdown ms-auto">
									<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
									</a>
									
								</div>
							</div>
						</div>
						  <div class="card-body">
							<div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">

								<span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #ffc107"></i>Purchase</span>
                                <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Sales</span>
							</div>
							<div class="chart-container-1">
								<canvas id="chart1"></canvas>
							  </div>
						  </div>
						  
					  </div>
				   </div>
				   <div class="col-12 col-lg-4 d-flex">
                       <div class="card radius-10 w-100">
						<div class="card-header">
							<div class="d-flex align-items-center">
								<div>
									<h6 class="mb-0">Trending Products</h6>
								</div>
								<div class="dropdown ms-auto">
									<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
									</a>
									
								</div>
							</div>
						</div>
						   <div class="card-body">
							<div class="chart-container-2">
								<canvas id="chart2"></canvas>
							  </div>
						   </div>
						   <ul class="list-group list-group-flush">
                            @foreach ($top_4_products as $k=>$product)
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">{{ $product->product_name }}
                                @if($k == 0)
                                <span class="badge bg-success rounded-pill">{{ $top_4_products_data[0] }}</span>
                                @endif
                                @if($k == 1)
                                <span class="badge bg-danger rounded-pill">{{ $top_4_products_data[1] }}</span>
                                @endif
                                 @if($k == 2)
                                <span class="badge bg-primary rounded-pill">{{ $top_4_products_data[2] }}</span>
                                @endif
                                 @if($k == 3)
                                <span class="badge bg-dark rounded-pill">{{ $top_4_products_data[3] }}</span>
                                @endif
							</li>
                            @endforeach
							
						</ul>
					   </div>
				   </div>
				</div><!--end row-->

				<div class="card radius-10">
					<div class="card-header">
						<div class="d-flex align-items-center">
							<div>
								<h6 class="mb-0">Recent Sales</h6>
							</div>
							<div class="dropdown ms-auto">
								<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
								</a>
								
							</div>
						</div>
					</div>
                    <div class="card-body">
						<div class="table-responsive">
						   	<table class="table align-middle mb-0">
								<thead class="table-light">
									<tr>
									<th>Product</th>
									<th>Photo</th>
									<th>Category</th>
									<th>Status</th>
									<th>Amount</th>
									<th>Date</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($recent_product_sales as $sale_p)


									<tr>
										<td>{{ $sale_p->product->product_name }}</td>
										<td><img src="{{ $sale_p->product->image_show }}" class="product-img-2" alt="{{ $sale_p->product->product_name }}"></td>
										<td>{{ $sale_p->product->category->name }}</td>
										<td>
											@if($sale_p->invoice->status == 1)
												<span class="badge bg-gradient-quepal text-white shadow-sm w-100">Received</span></td>

											@elseif($sale_p->invoice->status == 2)
											<span class="badge bg-gradient-blooker text-white shadow-sm w-100">Partial</span>

											@elseif($sale_p->invoice->status == 3)
											<span class="badge bg-gradient-blooker text-white shadow-sm w-100">Pending</span>
											@else
											<span class="badge bg-gradient-bloody text-white shadow-sm w-100">Ordered</span>
											@endif

										<td>{{ auth()->user()->currency_symbol }}{{  round($sale_p->g_total,2) }}</td>
										<td>{{ date('d M Y',strtotime($sale_p->invoice->invoice_date)) }}</td>
									
									</tr>
									@endforeach



								</tbody>
						  	</table>
						</div>
					</div>
				</div>




					<div class="row row-cols-1 row-cols-lg-3">
						<div class="col d-flex">
						<div class="card radius-10 w-100">
							<div class="card-body">
							<p class="font-weight-bold mb-1 text-secondary">Weekly Revenue</p>

							<div class="d-flex align-items-center mb-4">
								<div>
									<h4 class="mb-0">{{ auth()->user()->currency_symbol }}{{ $total_revenus }}</h4>
								</div>
							</div>
							<div class="chart-container-0 mt-5">
								<canvas id="chart3"></canvas>
								</div>
							</div>
						</div>
						</div>
						<div class="col d-flex">
						<div class="card radius-10 w-100">
							<div class="card-header bg-transparent">
								<div class="d-flex align-items-center">
									<div>
										<h6 class="mb-0">Orders Summary</h6>
									</div>
									<div class="dropdown ms-auto">
										<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
										</a>
										
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="chart-container-1 mt-3">
									<canvas id="chart4"></canvas>
									</div>
							</div>
							<ul class="list-group list-group-flush">



								<li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">Received <span class="badge bg-gradient-quepal rounded-pill">{{ $order_summary_data[0] }}</span>
								</li>

								<li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Pending <span class="badge bg-gradient-ibiza rounded-pill">{{ $order_summary_data[1] }}</span>
								</li>
								<li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Ordered <span class="badge bg-gradient-deepblue rounded-pill">{{ $order_summary_data[2] }}</span>
								</li>
							</ul>
						</div>
						</div>
						<div class="col d-flex">
							<div class="card radius-10 w-100">
									<div class="card-header bg-transparent">
									<div class="d-flex align-items-center">
										<div>
											<h6 class="mb-0">Top Selling Categories</h6>
										</div>
										
										</div>
									</div>
								<div class="card-body">
									<div class="chart-container-0">
										<canvas id="chart5"></canvas>
									</div>
								</div>
								<div class="row row-group border-top g-0">
									@foreach ($top_selling_category as $o_cat)


									<div class="col">
										<div class="p-3 text-center">
											<h4 class="mb-0 text-danger">{{ auth()->user()->currency_symbol }}{{  round($o_cat->g_total,2) }}</h4>
											<p class="mb-0">{{ $o_cat->product->category->name }}</p>
										</div>
									</div>
										@endforeach

								</div><!--end row-->
							</div>
						</div>
					</div><!--end row-->

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


	<!-- search modal -->
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
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-dropbox fs-4' ></i>Html5 Templates</a>
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
    <!-- end search modal -->


	<!--start switcher-->
	<div class="switcher-wrapper">
		<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div>
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
			<h6 class="mb-0">Theme Styles</h6>
			<hr/>
			<div class="d-flex align-items-center justify-content-between">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
					<label class="form-check-label" for="lightmode">Light</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
					<label class="form-check-label" for="darkmode">Dark</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
					<label class="form-check-label" for="semidark">Semi Dark</label>
				</div>
			</div>
			<hr/>
			<div class="form-check">
				<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
				<label class="form-check-label" for="minimaltheme">Minimal Theme</label>
			</div>
			<hr/>
			<h6 class="mb-0">Header Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator headercolor1" id="headercolor1"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor2" id="headercolor2"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor3" id="headercolor3"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor4" id="headercolor4"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor5" id="headercolor5"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor6" id="headercolor6"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor7" id="headercolor7"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor8" id="headercolor8"></div>
					</div>
				</div>
			</div>
			<hr/>
			<h6 class="mb-0">Sidebar Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('public/assets/js') }}/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="{{ asset('public/assets/js') }}/jquery.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/simplebar.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/metisMenu.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/perfect-scrollbar.js"></script>
	<script src="{{ asset('public/assets/js') }}/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{ asset('public/assets/js') }}/jquery-jvectormap-world-mill-en.js"></script>
	<script src="{{ asset('public/assets/js') }}/chart.js"></script>
    <script>
        var sales_data = @json($sales);
        var purchase_data = @json($purchases);
        var revenue_days = @json($revenue_days);
        var revenue_days_price = @json($revenue_days_price);
        var top_product_names = [];
        var top_product_sale_data =@json($top_4_products_data);
        var i=0;
        @foreach ($top_4_products as $p)
            top_product_names[i]="{{ $p->product_name }}";
            i++;
        @endforeach
        var order_summary_data = @json($order_summary_data);
        var top_selling_category_database=[];
        var top_cat_ctx = document.getElementById("chart5").getContext("2d");
        var gradientStroke1 = top_cat_ctx.createLinearGradient(0, 0, 0, 300);
        gradientStroke1.addColorStop(0, "#f54ea2");
        gradientStroke1.addColorStop(1, "#ff7676");

        var gradientStroke2 = top_cat_ctx.createLinearGradient(0, 0, 0, 300);
        gradientStroke2.addColorStop(0, "#42e695");
        gradientStroke2.addColorStop(1, "#3bb2b8");
        @foreach ($top_selling_category as $k=>$tsc)
            @if($k == 0)
             top_selling_category_database[0]= {
                    label: "{{ $tsc->product?->category?->name }}",
                    data:  @json($cat_data[ $tsc->product?->category?->id]),
                    borderColor: gradientStroke1,
                    backgroundColor: gradientStroke1,
                    hoverBackgroundColor: gradientStroke1,
                    pointRadius: 0,
                    fill: false,
                    borderWidth: 1,
                };
            @else
                top_selling_category_database[1]= {
                    label:"{{ $tsc->product?->category?->name }}",
                    data: @json($cat_data[ $tsc->product?->category?->id]),
                    borderColor: gradientStroke2,
                    backgroundColor: gradientStroke2,
                    hoverBackgroundColor: gradientStroke2,
                    pointRadius: 0,
                    fill: false,
                    borderWidth: 1,
                };
            @endif

        @endforeach
    </script>
	<script src="{{ asset('public/assets/js') }}/index.js"></script>
	<!--app JS-->
	<script src="{{ asset('public/assets/js') }}/app.js"></script>
	<script>
		// new PerfectScrollbar(".app-container")
	</script>
    @if($gtext['custom_js'] != '')
	<script>
        {!! $gtext['custom_js'] !!}
	</script>
	@endif
</body>


<!-- Mirrored from codervent.com/rocker/demo/vertical/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:04:20 GMT -->
</html>
