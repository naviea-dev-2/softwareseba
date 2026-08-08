<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $site_setting = \App\Models\SiteSetting::first();
    @endphp
    <title>{{ $site_setting->company_name }} - Forget Password</title>
    <link rel="icon" href="{{  $site_setting->favicon_show  }}" type="image/png"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link rel="stylesheet" href="{{asset('public/assets/css/login&Reg.css')}}"> --}}
<style>
    .is-invalid{
        border: 1px solid red;
    }
    .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: calc(var(--bs-border-width) * -1);
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    #show_hide_password i{
        color:white;
    }
</style>
</head>
<body>
    <section id="LoginReg" class="loginReg" style="min-height: 100vh; background-image: url('{{asset('public/assets/images/logReg.jpg')}}');; background-size: cover;background-repeat: no-repeat;">
        <div class="container text-white">
            <div class="row">
                <div class="col-sm-7 my-4">

                     <h2>{{ $site_setting->company_name }} - Since {{ $site_setting->company_establish_year }}</h2>


                </div>
                <div class="col-sm-5 my-4">
                    <a href="{{ route('sign_up') }}" class="float-right text-white regbutton ml-3" style="background-color: #057240;border-color:#057240">Sign Up</a>
                    <a href="{{ route('sign_in') }}" class="float-right text-white regbutton ml-3">Sign In</a>

                </div>
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>{{ $site_setting->software_service_slogan }}</h4>
                        </div>
                        @php
                            $software_services = \App\Models\SoftwareService::get()
                        @endphp
                        @foreach ($software_services as $software_service)
                        <div class="col-sm-3 text-center my-3">
                            <a style="color: white;" @if($software_service->url != '#' &&  $software_service->url != '') href="{{ $software_service->url }}" target="__blank" @endif>
                                <i class="fas {{ ($software_service->icon_class == '#' || $software_service->icon_class == '') ? 'fa-user' :  $software_service->icon_class }} icon"></i> <br>
                                <small><b>{{ $software_service->title }}</b></small>
                            </a>

                        </div>
                        @endforeach
                    </div>


                </div>
                <div class="col-sm-5 mb-4">
                    <h3 class="text-light"><span class="join" style="color: #d3eec4;">Forget Password</span></h3>
                    <form method="POST" action="{{ route('forget.send_mail') }}">
                    @csrf

                        @if(Session::get('message'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{Session::get('message')}}</strong>
                            </div>
                        @endif
                        @if(Session::get('error'))
                        <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{Session::get('error')}}</strong>
                            </div>
                        @endif
                        <div class="row notReg" style="background-color: rgb(13 152 152 / 46%)">
                            <div class="col-md-12 mt-3">
                                <label><b>Enter Your Email Address</b></label>
                                <input type="email" class="w-100 @error('email')is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" placeholder="Email" autocomplete="off">
                                 @error('email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>






                            <div class="col-12 mt-3">
                                <button type="submit"  class="float-right regbutton">Reset Password</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("fa-eye-slash");
					$('#show_hide_password i').removeClass("fa-eye");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("fa-eye-slash");
					$('#show_hide_password i').addClass("fa-eye");
				}
			});
		});
	</script>
</body>
</html>
