<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/rocker/demo/vertical/auth-cover-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Dec 2023 11:08:01 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
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
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                            <div class="card-body">
                                @if (session('resent'))
                                    <div class="alert alert-success" role="alert">
                                        {{ __('A fresh verification link has been sent to your email address.') }}
                                    </div>
                                @endif

                                {{ __('Before proceeding, please check your email for a verification link.') }}
                                {{ __('If you did not receive the email') }},
                                <form class="d-inline" method="POST" action="{{ route('verification.resend',$id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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


