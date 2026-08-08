<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     @php
        $site_setting = \App\Models\SiteSetting::first();
    @endphp
    <title>{{ $site_setting->company_name }} - Sign Up</title>
    <link rel="icon" href="{{  $site_setting->favicon_show  }}" type="image/png"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('public/assets/css/login&Reg.css')}}">
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
    #show_hide_password2 i{
        color:white;
    }
    .iti__country-name{
        color:#000;
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
                <div class="col-sm-6">
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
                <div class="col-sm-6 ">
                    <h3 class="text-light"><span class="join" style="color: #d3eec4;">SIGN UP YOUR BUSINESS NOW!</span></h3>
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
                    <form action="{{ route('register') }}"  method="post">
                        @csrf
                        <div class="row notReg" style="background-color: rgb(13 152 152 / 46%)">


                        <div class="col-md-6">
                            <label><b>Business Name</b></label>
                            <input value="{{ old('business_name') }}" type="text" class="form-control" name="business_name" placeholder="">
                            @error('business_name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label><b>Phone Number</b></label>
                            <input value="{{ old('mobile') }}" type="text" class="form-control" name="mobile" id="mobile">
                            @error('mobile')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label><b>Email</b></label>
                            <input value="{{ old('email') }}" type="email" class="form-control" name="email" placeholder="example@user.com">
                            @error('email')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label><b>Business Type</b></label>
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
                                    <option @if(old('business_type') == $k) selected @endif value="{{ $k }}">{{ $type }}</option>
                                @endforeach
                            </select>

                            @error('business_type')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label><b>Password</b></label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" value="{{ old('password') }}" class="form-control border-end-0 @error('password')is-invalid @enderror"
                                name="password" placeholder="Enter Password">
                                <a href="javascript:;" class="input-group-text bg-transparent"><i class="fas fa-eye-slash"></i></a>
                            </div>

                            @error('password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror

                        </div>
                        <div class="col-md-6">
                            <label><b>Confirm Password</b></label>

                            <div class="input-group" id="show_hide_password2">
                                <input type="password" value="{{ old('password_confirmation') }}" class="form-control border-end-0 @error('password_confirmation')is-invalid @enderror"
                                name="password_confirmation" placeholder="Confirm Password">
                                <a href="javascript:;" class="input-group-text bg-transparent"><i class="fas fa-eye-slash"></i></a>
                            </div>

                            @error('password_confirmation')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-check form-switch">
                            <input @if(old('term')) checked @endif class="form-check-input" type="checkbox" value="1" name="term" id="termandagree">
                            <label class="form-check-label" for="termandagree">I read and agree to Terms & Conditions</label>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="float-right regbutton">Sign up</button>
                        </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.2/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.2/build/js/intlTelInput.js"></script>
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
            $("#show_hide_password2 a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password2 input').attr("type") == "text") {
					$('#show_hide_password2 input').attr('type', 'password');
					$('#show_hide_password2 i').addClass("fa-eye-slash");
					$('#show_hide_password2 i').removeClass("fa-eye");
				} else if ($('#show_hide_password2 input').attr("type") == "password") {
					$('#show_hide_password2 input').attr('type', 'text');
					$('#show_hide_password2 i').removeClass("fa-eye-slash");
					$('#show_hide_password2 i').addClass("fa-eye");
				}
			});
		});
        // $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
        //         var countryCode = (resp && resp.country) ? resp.country : "";
        //         console.log(countryCode);
        // });
        //        $.get("http://ipinfo.io", function (response) {
        //  console.log(response)
        // });
        var input = document.querySelector("#mobile");
        var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
        window.addEventListener("load", function () {

        errorMsg = document.querySelector("#error-msg"),
        validMsg = document.querySelector("#valid-msg");
        // var iti = window.intlTelInput(input, {
        //     utilsScript:"https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.2/build/js/utils.js"
        // });
            var iti =window.intlTelInput(input, {
                // allowDropdown: false,
                // autoHideDialCode: false,
                // autoPlaceholder: "off",
                // dropdownContainer: document.body,
                // excludeCountries: ["us"],
                // formatOnDisplay: false,
                geoIpLookup: callback => {
                        fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                    },
                hiddenInput: "full_number",
                initialCountry: "bd",

                // localizedCountries: { 'de': 'Deutschland' },
                nationalMode: true,
                // autoInsertDialCode:true,
                // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                placeholderNumberType: "MOBILE",
                // preferredCountries: ['cn', 'jp'],
                //  separateDialCode: true,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.2/build/js/utils.js",
            });
            $(validMsg).addClass("hide");
            input.addEventListener('blur', function () {
                reset();
                if (input.value.trim()) {
                    if (iti.isValidNumber()) {
                        validMsg.classList.remove("hide");
                    } else {
                        input.classList.add("error");
                        var errorCode = iti.getValidationError();
                        // errorMsg.innerHTML = errorMap[errorCode];
                        // errorMsg.classList.remove("hide");
                    }
                }
            });

            input.addEventListener('change', reset);
            input.addEventListener('keyup', reset);



            var reset = function () {
                input.classList.remove("error");
                // errorMsg.innerHTML = "";
                // errorMsg.classList.add("hide");
                // validMsg.classList.add("hide");
            };
            @if(old('full_number'))
                console.log("{{ old('full_number') }}");
                iti.setNumber("{{ old('full_number') }}");
            @endif
        })
	</script>
</body>
</html>
