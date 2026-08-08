<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/rocker/demo/vertical/auth-cover-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:08:01 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->

	<!--plugins-->
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
	@php
        $site_setting = \App\Models\SiteSetting::first();
    @endphp
    <title>{{ $site_setting->company_name }} - Admin Login</title>
    <link rel="icon" href="{{  $site_setting->favicon_show  }}" type="image/png"/>
</head>

<body class="">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-cover">
			<div class="">
				<div class="row g-0 justify-content-center ">



					<div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center bg-transparent">
						<div class="card rounded-0 shadow bg-white mb-0">
							<div class="card-body p-5 ">
								<div class="">
									<div class="mb-2 text-center">
										<img src="{{  $site_setting->header_image_show  }}" width="60" alt="">
									</div>
									<div class="text-center mb-2">
										<h5 class="">Admin Login</h5>

									</div>
									<div class="form-body">
										<form method="post" action="{{ route("login") }}" class="row g-3">
                                            @csrf
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<input type="email"
                                                class="form-control" name="email" placeholder="jhon@example.com">
                                                @error('email')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0"
                                                    name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
												</div>
                                                @error('password')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
													<label class="form-check-label" for="remember_me">Remember Me</label>
												</div>
											</div>
											<div class="col-md-6 text-end">	<a href="{{ route('forget_password') }}">Forgot Password ?</a>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary">Login</button>
												</div>
											</div>

										</form>
									</div>



								</div>
							</div>
						</div>
					</div>

				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('public/assets/js') }}/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="{{ asset('public/assets/js') }}/jquery.min.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
</body>


<!-- Mirrored from codervent.com/rocker/demo/vertical/auth-cover-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:08:01 GMT -->
</html>
