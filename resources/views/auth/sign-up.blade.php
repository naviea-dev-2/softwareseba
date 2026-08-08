<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         @php
            $site_setting = \App\Models\SiteSetting::first();
        @endphp
        <title>{{ $site_setting->company_name }} - Sign Up</title>
        <link rel="icon" href="{{  $site_setting->favicon_show  }}" type="image/png"/>
        <link rel="stylesheet" href="{{ url('public/auth') }}/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="{{ url('public/auth') }}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ url('public/auth') }}/css/style.css">
        <link rel="stylesheet" href="{{ url('public/auth') }}/css/responsive.css">
    </head>
    <body>
        <div class="container-fluid">
            <header>
                <div class="logo_url">
                    <a href="{{$site_setting->redirect_url}}" target="__blank">
                        <img class="w-100" src="{{ $site_setting->header_image == '' ? $site_setting->no_image : $site_setting->header_image_show }}" alt="">
                    </a>

                    <p>Since  {{ $site_setting->company_establish_year }}</p>
                </div>
                <div class="sing_up_btn">
                    <div>
                        <a href="{{ route('sign_in') }}">
                            <button class="btn_one">Sign In</button>
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('sign_up') }}">
                            <button class="btn_two">Sign Up</button>
                        </a>
                    </div>
                </div>

            </header>

            <section class="singin_main pb-5">
                <div class="container-fluid">
                    <div class="item">
                        <div class="left_part">
                            <div class="left">
                                <h1 class="header">{{ $site_setting->software_service_slogan }}</h1>
                            </div>
                        </div>
                        <div class="right_part mt-0">
                            <div style="margin-left: 25px;">
                            <h1 class="header_two">SIGN UP YOUR BUSINESS NOW!</h1>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="left_part">
                            <div class="left">
                                
                                <div class="items">
                                    @php
                                        $software_services = \App\Models\SoftwareService::get()
                                    @endphp
                                    @foreach ($software_services as $software_service)
                                        <div class="one">
                                            <div class="one-container">
                                            <a style="color: white;" @if($software_service->url != '#' &&  $software_service->url != '') href="{{ $software_service->url }}" target="__blank" @endif>
                                            <i   class="fa-solid fa-key"></i>
                                            <h1>{{ $software_service->title }}</h1>
                                            </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="right_part">
                           
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
                            <form class="right" action="{{ route('register') }}"  method="post">
                                @csrf
                                <div class="row">
                                    <div class="one col-md-6">
                                        <label class="text" for>
                                            Business Name
                                        </label>
                                        <div>
                                            <input placeholder="Enter Your Business Name" class="mb-1" type="text" value="{{ old('business_name') }}" name="business_name">
                                        </div>
                                        @error('business_name')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="one col-md-6">
                                        <label class="text" for="mobile">
                                            Phone Number</label>
                                        <div>
                                            <input value="{{ old('mobile') }}" type="text" class="form-control" name="mobile" id="mobile">

                                        </div>
                                        @error('mobile')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="one col-md-6">
                                        <label class="text" for>
                                            Email
                                        </label>
                                        <div>
                                            <input placeholder="example@gmail.com" class="mb-1" type="email" value="{{ old('email') }}"  name="email">
                                            @error('email')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="one col-md-6">
                                        <label class="text" for="business_type">Business
                                            Type</label>
                                        <div>
                                            <select class="w-100 selet" name="business_type"
                                                id="business_type"
                                                >
                                                    @php
                                                    $types = [
                                                        ''=>'Select Business Type',
                                                        '1'=>'Clothing & Brand',
                                                        '2'=>'Super Shop',
                                                        '3'=>'Cosmetices Shop',
                                                        '4'=>'Jewellery Shop',
                                                        '5'=>'Pharmacy Shop',
                                                        '6'=>'Mobile Shop',
                                                        '7'=>'Glossary Shop',
                                                        '8'=>'Agro Farm',
                                                        '9'=>'Ecommerce & F-commerce',
                                                        '10'=>'Restaurant',
                                                        '11'=>'Electric & Electronics',
                                                        '12'=>'Trading & Traders',
                                                        '13'=>'Book Shop',
                                                        '14'=>'Computer Shop',
                                                        '15'=>'Dealership',
                                                        '16'=>'Software Company',
                                                        '17'=>'Bangladesh Principal Association',
                                                        '18'=>'Food Products Industry ',
                                                        '19'=>'Constructions Company',
                                                        '20'=>'Realestate Company',
                                                    ];
                                                    @endphp
                                                    @foreach ($types as $k=>$type)
                                                        <option @if(old('business_type') == $k) selected @endif value="{{ $k }}">{{ $type }}</option>
                                                    @endforeach


                                            </select>
                                        </div>
                                        @error('business_type')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="one col-md-6">
                                        <label class="text" for>
                                            Password
                                        </label>
                                        <div>
                                            <input placeholder="Enter Password" class="mb-1 @error('password')is-invalid @enderror" type="password"  value="{{ old('password') }}" name="password">
                                            @error('password')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="one col-md-6">
                                        <label class="text" for>
                                            Confirm Password
                                        </label>
                                        <div>
                                            <input placeholder="Confirm Password" class="mb-1  @error('password_confirmation')is-invalid @enderror" type="password" value="{{ old('password_confirmation') }}"  name="password_confirmation">
                                            @error('password_confirmation')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- <label for="chack"></label> -->

                                    <label class="d-flex gap-2 text-white"
                                        for="termandagree">
                                        <div>
                                            <input value="1" name="term"  @if(old('term')) checked @endif id="termandagree" type="checkbox">
                                        </div>
                                        I read and agree to Terms & Conditions
                                    </label>
                                </div>
                               
                                <div class="singup_btn">
                                    <button type="submit" class="singup">Sign Up</button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </section>

            <section>
                <div class="container-fluid pb-2">
                    <footer>
                        <div class="one">
                           <p>Support Email :-  {{  $site_setting->email1 ?? '' }}</p>
                            <p>Help Line : {{  $site_setting->phone1 ?? '' }}</p>
                        </div>
                        <div class="Two text-white">
                            <h2>Connect With Us :</h2>
                            <a style="color: white;" href="{{  $site_setting->facebook ?? '#' }}"><i class="fa-brands fa-facebook-f icon"></i></a>
                            <a style="color: white;" href="{{  $site_setting->facebook ?? '#' }}"><i class="fa-brands fa-twitter icon"></i></a>
                            <a style="color: white;" href="{{  $site_setting->facebook ?? '#' }}"><i class="fa-brands fa-instagram icon"></i></a>
                            <a style="color: white;" href="{{  $site_setting->facebook ?? '#' }}"><i class="fa-brands fa-youtube icon"></i></a>
                            <a style="color: white;" href="{{  $site_setting->facebook ?? '#' }}"><i class="fa-brands fa-linkedin icon"></i></a>
                            <a style="color: white;" href="{{  $site_setting->facebook ?? '#' }}"><i class="fa-solid fa-envelope icon"></i></a>
                        </div>
                    </footer>
                </div>
            </section>
            <section>
                <div class="container-fluid text-center">
                   <span class="copyright">{{  $site_setting->right_text ?? '' }}</span>
                </div>
            </section>
        </div>
        <script src="{{ url('public/auth') }}/js/all.min.js"></script>
        <script src="{{ url('public/auth') }}/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.2/build/css/intlTelInput.css">
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.2/build/js/intlTelInput.js"></script>
        <script>
             var input = document.querySelector("#mobile");
            var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
            window.addEventListener("load", function () {

                errorMsg = document.querySelector("#error-msg"),
                validMsg = document.querySelector("#valid-msg");
                var iti =window.intlTelInput(input, {
                // allowDropdown: false,
                // autoHideDialCode: false,
                autoPlaceholder: "off",
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
           // $(validMsg).addClass("hide");
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
