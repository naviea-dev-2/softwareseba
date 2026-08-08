<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/rocker/demo/vertical/auth-cover-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:08:01 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{  asset('public')  }}/assets/images/fav.png" type="image/png"/>
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
	<title>Signin</title>
</head>

<body class="">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-cover">
			<div class="">
				<div class="row g-0">

					<div class="col-12 col-xl-5 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
							<div class="card-body">
                                 <img src="{{ asset('public') }}/assets/images/register.jpg" class="img-fluid auth-img-cover-login" width="550" alt=""/>
							</div>
						</div>

					</div>

					<div class="col-12 col-xl-7 auth-cover-right align-items-center justify-content-center">
						<div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
							<div class="card-body p-sm-5">
								<div class="">
									<div class="mb-3 text-center">
										<img src="assets/images/logo-icon.png" width="60" alt="" />
									</div>
									<div class="text-center mb-4">
										<h5 class="">Create Account</h5>
										<p class="mb-0">Fill the below form to create a new account.</p>
									</div>
									<div class="form-body">
										<form  action="{{ route('register') }}" class="row g-3" method="post">
                                            @csrf
                                            <div class="col-6">
												<label for="inputEmailAddress" class="form-label">Business Name</label>
												<input value="{{ old('business_name') }}" type="text" class="form-control" name="business_name" placeholder="">
                                                @error('business_name')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
											</div>
                                            <div class="col-6">
												<label for="inputEmailAddress" class="form-label">Mobile</label>
												<input value="{{ old('mobile') }}" type="text" class="form-control" name="mobile" >
                                                @error('mobile')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
											</div>
											<div class="col-6">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<input value="{{ old('email') }}" type="email" class="form-control" name="email" placeholder="example@user.com">
                                                @error('email')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
											</div>
                                            <div class="col-6">
												<label for="inputEmailAddress" class="form-label">Business Type</label>
                                                <select name="business_type" class="form-control">
                                                    @php
                                                        $types = [
                                                        ''=>'Select Business Type',
                                                        '1'=>'Artists, Photographers & Creative Types',
                                                        '2'=>'Consultants & Professionals',
                                                        '3'=>'Financial Services',
                                                        '4'=>'General: I make or sell a PRODUCT',
                                                        '5'=>'General: I provide a SERVICE',
                                                        '6'=>'Hair, Spa & Aesthetics',
                                                        '7'=>'Medical, Dental, Health',
                                                        '8'=>'Non-profits, Associations & Groups',
                                                        '9'=>'Real Estate, Construction & Home Improvement',
                                                        '10'=>'Retailers, Resellers & Sales',
                                                        '11'=>'Web, Tech & Media',
                                                    ];
                                                    @endphp
                                                    @foreach ($types as $k=>$type)
                                                        <option value="{{ $k }}">{{ $type }}</option>
                                                    @endforeach
                                                </select>

                                                @error('mobile')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
											</div>
											<div class="col-6">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0"
                                                    name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
                                                @error('password')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
											</div>
                                            <div class="col-6">
												<label for="inputChoosePassword" class="form-label">Confirm Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0"
                                                    name="password_confirmation"  placeholder="Confirm Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>


											<div class="col-12">
												<div class="form-check form-switch">
													<input
                                                    @if(old('term')) checked @endif class="form-check-input" type="checkbox" value="1" name="term">
													<label class="form-check-label" for="flexSwitchCheckChecked">I read and agree to Terms & Conditions</label>
												</div>
											</div>
											<div class="col-12">
												<div >
													<button type="submit" class="btn btn-primary">Sign up</button>
												</div>
											</div>
											<div class="col-12">
												<div class="text-center ">
													<p class="mb-0">Already have an account? <a href="{{ route('sign_in') }}">Sign in here</a></p>
												</div>
											</div>
										</form>
									</div>
									<div class="login-separater text-center mb-5"> <span>OR SIGN UP WITH EMAIL</span>
										<hr/>
									</div>
									<div class="list-inline contacts-social text-center">
										<a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
										<a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
										<a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
										<a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
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
	<script src="{{ asset('public/assets/js') }}/simplebar.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/metisMenu.min.js"></script>
	<script src="{{ asset('public/assets/js') }}/perfect-scrollbar.js"></script>
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
	<!--app JS-->
    <script src="{{ asset('public/assets/js') }}/app.js"></script>
</body>


<!-- Mirrored from codervent.com/rocker/demo/vertical/auth-cover-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:08:01 GMT -->
</html>
