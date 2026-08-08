<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $site_setting = \App\Models\SiteSetting::first();
    @endphp
    <title>{{ $site_setting->company_name }} - Sign In</title>
    <link rel="icon" href="{{  $site_setting->favicon_show  }}" type="image/png"/>
    <link rel="stylesheet" href="{{ url('public/auth') }}/css/all.min.css">
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/auth') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('public/auth') }}/css/style.css">
    <link rel="stylesheet" href="{{ url('public/auth') }}/css/responsive.css">
    <style>
        .Or_inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin: 1rem 0;
        }
        .sp {
            height: 1px;
            background-color: #fff;
            width: 100%;
        }
        .Or_inner span:nth-child(2) {
            color: #fff;
        }
        .google_btn button {
            display: block;
            transition: 0.35s ease-in-out;
            font-size: 14px;
            border-radius: 36px;
            border: 1px solid rgb(148, 148, 148);
            height: 40px;
            text-align: center;
            padding: 3px 16px;
            font-weight: 600;
            /* width: 100%; */
            color: #303030;
            background-color: transparent;
            line-height: 34px;
            position: relative;
            background-color: #40e16b;
            text-decoration: none;
        }
        .google_btn form{

            display: flex;
        }
        .google_btn button img {
            width: 18px;
            height: 18px;
            /* position: absolute;
            left: 22px;
            top: 26%; */
        }
        @media (min-width: 992px){
            .google_btn form.google_sec{
                justify-content: right;
            }
            .google_btn form.facebook_sec{
                justify-content: left;
            }
        }
        @media (max-width: 991px){
            .google_btn form{
                justify-content: center;
            }
        }

    </style>
</head>
<body>
<div class="container-fluid">


    <header>
        <div class="logo_url">
            <a href="{{ $site_setting->redirect_url }}" target="__blank">
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
                <h1 class="header_two">SIGN IN YOUR BUSINESS NOW!</h1>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="left_part">
             <div class="left">
                
               <div class="items">

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

            <div class="right_part mt-0">
                
                    <form class="right" method="POST" action="{{ route('sign_in_post') }}">
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
                        <div class="row">
                            <div class="col-md-6 one">
                                <label class="text" for="">
                                    Email/Phone Number
                                </label>
                                <div>
                                    <input name="email" id="email" value="{{ old('email') }}" placeholder="Enter Email/Phone" class="mb-4" type="text">
                                </div>
                            </div>
                            <div class="col-md-6 one">
                                <label class="text" for="">
                                Password
                                </label>
                                <div>
                                    <input  name="password" class="mb-4 @error('password')is-invalid @enderror" type="password">
                                    @error('password')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- <label for="chack"></label> -->
                        <div>
                            <label class="d-flex gap-2 text-white" for="remember">
                                <div>
                                    <input name="remember" placeholder="Enter Password" id="remember"  type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                </div>
                                Remember Me | <span class="forgot"><a href="{{ route('forget_password') }}">Forget Password?</a></span>
                            </label>
                        </div>
                        <div class="singup_btn">
                            <button type="submit" class="singup">Sign In</button>
                        </div>

                    </form>
                    <div class="Auth_GoogleUserbtn">
                        <div class="Or_inner">
                            <span class="sp"></span>
                            <span>Or</span>
                            <span class="sp"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="google_btn">
                                    <form class="google_sec" action="{{ route('auth.google') }}" method="get">
                                        <input type="hidden" name="login_type" value="1">
                                        <button> <img  src="{{ asset("public/assets/images/google.svg") }}" alt="" /> Continue with Google</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="google_btn">
                                    <form class="facebook_sec" action="{{ route('auth.google') }}" method="get">
                                        <input type="hidden" name="login_type" value="1">
                                        <button> <img style="width: 47px;height: 30px;" src="{{ asset("public/assets/images/facebook1.svg") }}" alt="" /> Continue with Facebook</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
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
                <a style="color: white;" href="{{  $site_setting->twitter ?? '#' }}"><i class="fa-brands fa-twitter icon"></i></a>
                <a style="color: white;" href="{{  $site_setting->instagram ?? '#' }}"><i class="fa-brands fa-instagram icon"></i></a>
                <a style="color: white;" href="{{  $site_setting->youtube ?? '#' }}"><i class="fa-brands fa-youtube icon"></i></a>
                <a style="color: white;" href="{{  $site_setting->linkedin ?? '#' }}"><i class="fa-brands fa-linkedin icon"></i></a>
                <a style="color: white;" href="{{  $site_setting->google ?? '#' }}"><i class="fa-solid fa-envelope icon"></i></a>
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

</body>
</html>
