<div id="main" style="margin-left: 237px;">
    <nav class="navbar fixed-top navbar-expand-md   main-nav color">
       <!--  <button class="openbtn" onclick="openNav()">☰ </button>   -->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav pl-4 mr-auto text-center">
                <li>
                <a class="nav-link" href="{{route('dashboard')}}"><img src="{{url('public/upload/logo/logo.png')}}" alt="" style="height:40px;margin-right:80px"></a>
                </li>
                <li class="nav-item active">
                <a class="nav-link" href="{{route('hr.payroll')}}"><i class="fas fa-tachometer-alt" aria-hidden="true"></i> <br>
                    Dashboard <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                <a class="nav-link" href="{{route('monthManage.view')}}" style="{{($route=='monthManage.view')?'color:#89d6fb':''}}"><i class="fas fa-calendar-alt" aria-hidden="true"></i> <br>
                  Create Invoice</a>
                </li>

                <li class="nav-item">
                <a class="nav-link" href="{{route('holiday.view')}}" style="{{($route=='holiday.view')?'color:#89d6fb':''}}"><i class="fas fa-calendar-alt" aria-hidden="true"></i> <br>
                    Create Purchase</a>
                </li>

                <li class="nav-item">
                <a class="nav-link" href="{{ route('viewNotice') }}" style="{{($route=='viewNotice' || $route=='addNotice' || $route=='editNotice')?'color:#89d6fb':''}}"><i class="fas fa-bullhorn" aria-hidden="true"></i> <br>
                    Stock</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('country.index') }}" style="{{($route=='country.index')?'color:#89d6fb':''}}"><i class="fas fa-bullhorn" aria-hidden="true"></i> <br>
                    Country</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('state.index') }}" style="{{($route=='state.index')?'color:#89d6fb':''}}"><i class="fas fa-bullhorn" aria-hidden="true"></i> <br>
                    State</a>
                </li>
               <li class="nav-item">
                <a class="nav-link" href="{{ route('city.index') }}" style="{{($route=='city.index')?'color:#89d6fb':''}}"><i class="fas fa-bullhorn" aria-hidden="true"></i> <br>
                    City</a>
                </li>


            </ul>
        </div>
        <div class="user">
            <div class="row">
                <div class="dropdown text-center pr-4">
                    {{-- <button onclick="myFunction()" > --}}
                        <img src="https://geniuscart.royalscripts.com/assets/images/admins/1556780563user.png" alt="" class="dropbtn " onclick="myFunction()">

                        {{-- </button> --}}

                    <div id="myDropdown" class="dropdown-content" style="right:24px;">
                        <P style="color:black;">{{@Auth::user()->name}}</P>
                        <hr>
                    <a href="profile.html">Profile</a>
                    <a href="profileEdit.html">Edit Profile</a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="icon ion-power"></i> {{ __('Sign Out') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

