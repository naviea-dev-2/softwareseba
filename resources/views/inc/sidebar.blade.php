<!--navigation-->
<ul class="metismenu" id="menu">
    
    @php
        $user = auth()->user();
        $au_business = $user->business;
        $free_exipre = false;
        $pack_expire = false;

        if($au_business->user_type == 0){
            if($au_business->package){
                $arr_con = [];
                if(\Carbon\Carbon::now()->lte($au_business->pack_end_date) == false){
                    $pack_expire = true;
                }
                $arr_con = json_decode($au_business->package?->pack_option,true);
                array_push($arr_con,'general');
                

                // $results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
                // $data = array();
                // if($results){
                //     $dataObj = json_decode($results->option_value);
                //     $data['days'] = $dataObj->days;
                // }else{
                //     $data['days'] = 0;
                // }
                // $user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays($data['days']);
                // $user_now_date = \Carbon\Carbon::now();
            
                // if($user_now_date > $user_end_date){
                //     $free_exipre = true;
                // }
               // $arr_con = ['inventory','hr-payroll','accounts','general'];
            }else{
                $results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
                $data = array();
                if($results){
                    $dataObj = json_decode($results->option_value);
                    $data['days'] = $dataObj->days;
                }else{
                    $data['days'] = 0;
                }
                $user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays((int)$data['days']);
                $user_now_date = \Carbon\Carbon::now();
            
                if($user_now_date > $user_end_date){
                    $free_exipre = true;
                }
                $arr_con = ['inventory','hr-payroll','accounts','dealer','work_order','production','general','crm'];
            }
        }else{
            if($au_business->package){
                $arr_con = [];
                if(\Carbon\Carbon::now()->lte($au_business->pack_end_date) == false){
                    $pack_expire = true;
                }
                $arr_con = json_decode($au_business->package?->pack_option,true);
                array_push($arr_con,'general');
            }else{
                $results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
                $data = array();
                if($results){
                    $dataObj = json_decode($results->option_value);
                    $data['days'] = $dataObj->days;
                }else{
                    $data['days'] = 0;
                }
                $user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays((int)$data['days']);
                $user_now_date = \Carbon\Carbon::now();
            
                if($user_now_date > $user_end_date){
                    $free_exipre = true;
                }
                $arr_con = ['inventory','hr-payroll','accounts','dealer','work_order','production','general','crm'];
            }
            
        }
       
    @endphp

    @if($free_exipre == false)
        @if($pack_expire == false)
            @if($user->business->business_type_id == 17)
                @if($user->user_type == 0)
                    @php
                        $cap_menus = \App\Models\Permission::where('is_caption','!=',0)->whereRaw('JSON_CONTAINS(condition_show, ?)', ['"Principal Association"'])->orderBy('menu_sort','asc')->get();
                    @endphp
                    @foreach($cap_menus as $cap_menu)
                        @if($cap_menu->is_caption == 1)
                            <li class="menu-label">{{$cap_menu->name}}</li>
                        @endif
                        @php
                            $m_menus = \App\Models\Permission::whereIn('section',$arr_con)->where('parent_id',$cap_menu->id)->whereRaw('JSON_CONTAINS(condition_show, ?)', ['"Principal Association"'])->orderBy('menu_sort','asc')->get();
                        @endphp
                        @foreach($m_menus as $m_menu)
                            @if($m_menu->parent_menu == 1)
                                @if($m_menu->route_name != "")
                                    @php
                                        $check_r = false;
                                    @endphp
                                    @foreach ($m_menu->child_menus as $r_menu)
                                        @php
                                            if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                $check_r = true;
                                            }
                                            
                                        @endphp
                                    @endforeach
                                    <li @if($check_r)  class="mm-active" @endif>
                                        <a href="{{ route($m_menu->route_name) }}">
                                            <i class='bx {{$m_menu->logo_class}}'></i>
                                            <div class="menu-title">{{$m_menu->name}}</div>
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="javascript:;" class="has-arrow">
                                            <div class="parent-icon"><i class='bx {{$m_menu->logo_class}}'></i>
                                            </div>
                                            <div class="menu-title">{{$m_menu->name}}</div>
                                        </a>
                                        <ul>
                                            @php
                                                $sub_menus = \App\Models\Permission::whereIn('section',$arr_con)->where('parent_id',$m_menu->id)->whereRaw('JSON_CONTAINS(condition_show, ?)', ['"Principal Association"'])->orderBy('menu_sort','asc')->get();
                                            @endphp
                                            

                                            @foreach($sub_menus as $sub_menu)
                                                
                                                @php
                                                    $check_r = false;
                                                @endphp
                                                @foreach ($sub_menu->child_menus as $r_menu)
                                                    @php
                                                        if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                            $check_r = true;
                                                        }
                                                        
                                                    @endphp
                                                @endforeach
                                                <li @if($check_r)  class="mm-active" @endif> 
                                                    <a href="{{ route($sub_menu->route_name) }}"><i class='bx {{$sub_menu->logo_class}}'></i>{{$sub_menu->name}}</a>
                                                </li>
                                                
                                            @endforeach

                                        </ul>
                                    </li>
                                @endif
                            @else
                                @if($m_menu->route_name == 'dashboard')
                                    <li>
                                        <a href="{{ route('dashboard') }}">
                                            <div class="parent-icon"><i class='bx bx-home-alt'></i>
                                            </div>
                                            <div class="menu-title">Dashboard</div>
                                        </a>

                                    </li>
                                @else
                                    @php
                                        $check_r = false;
                                    @endphp
                                    @foreach ($m_menu->child_menus as $r_menu)
                                        @php
                                            if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                $check_r = true;
                                            }
                                            
                                        @endphp
                                    @endforeach
                                    <li @if($check_r)  class="mm-active" @endif>
                                        <a href="{{ route($m_menu->route_name) }}">
                                            <i class='bx {{$m_menu->logo_class}}'></i>
                                            <div class="menu-title">{{$m_menu->name}}</div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endforeach

                    @endforeach
                @else
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <div class="parent-icon"><i class='bx bx-home-alt'></i>
                            </div>
                            <div class="menu-title">Dashboard</div>
                        </a>
                    </li>               
                    <li>
                        <a href="{{ route('property.index_user') }}">
                            <div class="parent-icon"><i class='bx bx-building-house'></i>
                            </div>
                            <div class="menu-title">Land Plots</div>
                        </a>
                    </li>               
                    <li>
                        <a href="{{ route('user_deposit.create') }}">
                            <div class="parent-icon"><i class='bx bx-dollar-circle'></i>
                            </div>
                            <div class="menu-title">Add New Deposit</div>
                        </a>
                    </li>               
                    <li>
                        <a href="{{ route('user_deposit.index') }}">
                            <div class="parent-icon"><i class='bx bx-money'></i>
                            </div>
                            <div class="menu-title">Manage Deposit</div>
                        </a>
                    </li>               
                @endif
            @else
                @if($user->user_type == 0)
                    @php
                        $cap_menus = \App\Models\Permission::where('is_caption','!=',0)->orderBy('menu_sort','asc')->get();
                    @endphp
                    @foreach($cap_menus as $cap_menu)
                        @if($cap_menu->is_caption == 1)
                            <li class="menu-label">{{$cap_menu->name}}</li>
                        @endif
                        @php
                            $m_menus = \App\Models\Permission::whereIn('section',$arr_con)->whereNotIn("name",["Land Plot","Member","Deposit Payment","Online Payment Setting"])->where('parent_id',$cap_menu->id)->orderBy('menu_sort','asc')->get();
                        @endphp
                        @foreach($m_menus as $m_menu)

                            @if($m_menu->parent_menu == 1)
                                
                                @php
                                    $menu_allow = true;
                                    if($m_menu->is_condition == 1){
                                        //$arr_con_a = ['inventory','hr-payroll','accounts','dealer','work_order','production','general','crm'];
                                        $arr_con_val = in_array($m_menu->condition_type,$arr_con);
                                        if($m_menu->condition_val == 0){
                                            if($arr_con_val == false){
                                                $menu_allow = true;
                                            }else{
                                                $menu_allow =false;
                                            }
                                        }else{
                                            if($arr_con_val == false){
                                                $menu_allow = false;
                                            }else{
                                                $menu_allow =true;
                                            }
                                        }
                                    }else  if($m_menu->is_condition == 2){
                                        if(auth()->user()->business->business_type_id == $m_menu->condition_val){
                                            $menu_allow = false;
                                        }
                                    }
                                @endphp
                                @if($menu_allow)
                                    @if($m_menu->route_name != "")
                                        @php
                                            $check_r = false;
                                        @endphp
                                        @foreach ($m_menu->child_menus as $r_menu)
                                            @php
                                                if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                    $check_r = true;
                                                }
                                                
                                            @endphp
                                        @endforeach
                                        <li @if($check_r)  class="mm-active" @endif>
                                            <a href="{{ route($m_menu->route_name) }}">
                                                <i class='bx {{$m_menu->logo_class}}'></i>
                                                <div class="menu-title">{{$m_menu->name}}</div>
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="javascript:;" class="has-arrow">
                                                <div class="parent-icon"><i class='bx {{$m_menu->logo_class}}'></i>
                                                </div>
                                                <div class="menu-title">{{$m_menu->name}}</div>
                                            </a>
                                            <ul>
                                                @php
                                                    $sub_menus = \App\Models\Permission::whereIn('section',$arr_con)->where('parent_id',$m_menu->id)->orderBy('menu_sort','asc')->get();
                                                @endphp
                                                

                                                @foreach($sub_menus as $sub_menu)
                                                    @if($sub_menu->route_name == "p_type.index" || $sub_menu->route_name == 'generic.index')
                                                        @if($user->business->business_type_id == 5)
                                                            @php
                                                                $check_r = false;
                                                            @endphp
                                                            @foreach ($sub_menu->child_menus as $r_menu)
                                                                @php
                                                                    if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                                        $check_r = true;
                                                                    }
                                                                    
                                                                @endphp
                                                            @endforeach
                                                            <li @if($check_r)  class="mm-active" @endif> 
                                                                <a href="{{ route($sub_menu->route_name) }}"><i class='bx {{$sub_menu->logo_class}}'></i>{{$sub_menu->name}}</a>
                                                            </li>
                                                        @endif
                                                    @elseif($sub_menu->route_name == "territory.index" || $sub_menu->route_name == 'road.index')
                                                        @if($user->business->business_type_id == 15)
                                                            @php
                                                                $check_r = false;
                                                            @endphp
                                                            @foreach ($sub_menu->child_menus as $r_menu)
                                                                @php
                                                                    if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                                        $check_r = true;
                                                                    }
                                                                    
                                                                @endphp
                                                            @endforeach
                                                            <li @if($check_r)  class="mm-active" @endif> 
                                                                <a href="{{ route($sub_menu->route_name) }}"><i class='bx {{$sub_menu->logo_class}}'></i>{{$sub_menu->name}}</a>
                                                            </li>
                                                        @endif
                                                    @else
                                                        @php
                                                            $check_r = false;
                                                        @endphp
                                                        @foreach ($sub_menu->child_menus as $r_menu)
                                                            @php
                                                                if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                                    $check_r = true;
                                                                }
                                                                
                                                            @endphp
                                                        @endforeach
                                                        <li @if($check_r)  class="mm-active" @endif> 
                                                            <a href="{{ route($sub_menu->route_name) }}"><i class='bx {{$sub_menu->logo_class}}'></i>{{$sub_menu->name}}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                            </ul>
                                        </li>
                                    @endif
                                @endif
                            @else
                                @if($m_menu->route_name == 'dashboard')
                                    <li>
                                        <a href="{{ route('dashboard') }}">
                                            <div class="parent-icon"><i class='bx bx-home-alt'></i>
                                            </div>
                                            <div class="menu-title">Dashboard</div>
                                        </a>

                                    </li>
                                @else
                                    @php
                                        $check_r = false;
                                    @endphp
                                    @foreach ($m_menu->child_menus as $r_menu)
                                        @php
                                            if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                $check_r = true;
                                            }
                                            
                                        @endphp
                                    @endforeach
                                    <li @if($check_r)  class="mm-active" @endif>
                                        <a href="{{ route($m_menu->route_name) }}">
                                            <i class='bx {{$m_menu->logo_class}}'></i>
                                            <div class="menu-title">{{$m_menu->name}}</div>
                                        </a>
                                    </li>
                                @endif
                            @endif

                        @endforeach
                    @endforeach
                @else
                    @php
                        $cap_menus = \App\Models\RolePermission::Join('permissions','permissions.id','role_permissions.permission_id')->where('role_permissions.role_id',$user->role_id)->where('permissions.is_caption','!=',0)->orderBy('permissions.menu_sort','asc')->get();
                    @endphp
                    @foreach($cap_menus as $cap_menu)
                        @if($cap_menu->is_caption == 1)
                        <li class="menu-label">{{$cap_menu->name}}</li>
                        @endif
                        @php
                            $m_menus = \App\Models\RolePermission::Join('permissions','permissions.id','role_permissions.permission_id')->whereNotIn("name",["Land Plot","Member","Deposit Payment","Online Payment Setting"])->where('role_permissions.role_id',$user->role_id)->where('permissions.parent_id',$cap_menu->id)->orderBy('permissions.menu_sort','asc')->get();
                        @endphp
                        @foreach($m_menus as $m_menu)

                            @if($m_menu->parent_menu == 1)
                                @if($m_menu->route_name != "")
                                    @php
                                        $check_r = false;
                                    @endphp
                                    @if($m_menu->child_menus)
                                    @foreach ($m_menu->child_menus as $r_menu)
                                        @php
                                            if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                $check_r = true;
                                            }
                                            
                                        @endphp
                                    @endforeach
                                    @endif
                                    <li @if($check_r)  class="mm-active" @endif>
                                        <a href="{{ route($m_menu->route_name) }}">
                                            <i class='bx {{$m_menu->logo_class}}'></i>
                                            <div class="menu-title">{{$m_menu->name}}</div>
                                        </a>
                                    </li>
                                @else
                                <li>
                                    <a href="javascript:;" class="has-arrow">
                                        <div class="parent-icon"><i class='bx {{$m_menu->logo_class}}'></i>
                                        </div>
                                        <div class="menu-title">{{$m_menu->name}}</div>
                                    </a>
                                    <ul>
                                        @php
                                            $sub_menus = \App\Models\RolePermission::Join('permissions','permissions.id','role_permissions.permission_id')->where('role_permissions.role_id',$user->role_id)->where('permissions.parent_id',$m_menu->id)->orderBy('permissions.menu_sort','asc')->get();
                                        @endphp
                                        @foreach($sub_menus as $sub_menu)
                                            @if($sub_menu->route_name == "p_type.index" || $sub_menu->route_name == 'generic.index')
                                                @if($user->business->business_type_id == 5)
                                                    @php
                                                        $check_r = false;
                                                    @endphp
                                                    @foreach ($sub_menu->child_menus as $r_menu)
                                                        @php
                                                            if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                                $check_r = true;
                                                            }
                                                            
                                                        @endphp
                                                    @endforeach
                                                    <li @if($check_r)  class="mm-active" @endif> 
                                                        <a href="{{ route($sub_menu->route_name) }}"><i class='bx {{$sub_menu->logo_class}}'></i>{{$sub_menu->name}}</a>
                                                    </li>
                                                @endif
                                            @elseif($sub_menu->route_name == "territory.index" || $sub_menu->route_name == 'road.index')
                                                @if($user->business->business_type_id == 15)
                                                    @php
                                                        $check_r = false;
                                                    @endphp
                                                    @foreach ($sub_menu->child_menus as $r_menu)
                                                        @php
                                                            if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                                $check_r = true;
                                                            }
                                                            
                                                        @endphp
                                                    @endforeach
                                                    <li @if($check_r)  class="mm-active" @endif> 
                                                        <a href="{{ route($sub_menu->route_name) }}"><i class='bx {{$sub_menu->logo_class}}'></i>{{$sub_menu->name}}</a>
                                                    </li>
                                                @endif
                                            @else
                                                @php
                                                    $check_r = false;
                                                @endphp
                                                @if($sub_menu->child_menus)
                                                @foreach ($sub_menu->child_menus as $r_menu)
                                                    @php
                                                        if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                            $check_r = true;
                                                        }
                                                        
                                                    @endphp
                                                @endforeach
                                                @endif
                                                <li @if($check_r)  class="mm-active" @endif> 
                                                    <a href="{{ route($sub_menu->route_name) }}"><i class='bx {{$sub_menu->logo_class}}'></i>{{$sub_menu->name}}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>
                                </li>
                                @endif
                            @else
                                @if($m_menu->route_name == 'dashboard')
                                    <li>
                                        <a href="{{ route('dashboard') }}">
                                            <div class="parent-icon"><i class='bx bx-home-alt'></i>
                                            </div>
                                            <div class="menu-title">Dashboard</div>
                                        </a>

                                    </li>
                                @else
                                    @php
                                        $check_r = false;
                                    @endphp
                                    @foreach ($m_menu->child_menus as $r_menu)
                                        @php
                                            if(Route::currentRouteName() == $r_menu->route_name && $check_r == false){
                                                $check_r = true;
                                            }
                                            
                                        @endphp
                                    @endforeach
                                    <li @if($check_r)  class="mm-active" @endif>
                                        <a href="{{ route($m_menu->route_name) }}">
                                            <i class='bx {{$m_menu->logo_class}}'></i>
                                            <div class="menu-title">{{$m_menu->name}}</div>
                                        </a>
                                    </li>
                                @endif
                            @endif

                        @endforeach
                    @endforeach
                @endif
            @endif
        @endif
    @else
    <li>
        <a href="{{ route('dashboard') }}">
            <div class="parent-icon"><i class='bx bx-home-alt'></i>
            </div>
            <div class="menu-title">Dashboard</div>
        </a>

    </li>
    @endif


</ul>
<!--end navigation-->
