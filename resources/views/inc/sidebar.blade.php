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
                $arr_con = ['inventory','hr-payroll','accounts','general','crm'];
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
                $arr_con = ['inventory','hr-payroll','accounts','general','crm'];
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
                                        //$arr_con_a = ['inventory','hr-payroll','accounts','general','crm'];
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
                    {{-- <li>
                        <a href="{{ route('dashboard') }}">
                            <div class="parent-icon"><i class='bx bx-home-alt'></i>
                            </div>
                            <div class="menu-title">Dashboard</div>
                        </a>

                    </li> --}}
                    {{-- <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cart'></i>
                            </div>
                            <div class="menu-title">Sales</div>
                        </a>
                        <ul>
                            <li @if(Route::is('invoice.create')) class="mm-active" @endif> <a href="{{ route('invoice.create') }}"><i class='bx bx-radio-circle'></i>Add New Sales</a>
                            </li>
                            <li @if(Route::is('invoice.index') || Route::is('invoice.edit')) class="mm-active" @endif> <a href="{{ route('invoice.index') }}"><i class='bx bx-radio-circle'></i>Manage Sales</a>
                            </li>
                            <li @if(Route::is('invoice_return.index') || Route::is('invoice_return.create') || Route::is('invoice_return.edit') || Route::is('invoice_return.add_edit')) class="mm-active" @endif> <a href="{{ route('invoice_return.index') }}"><i class='bx bx-radio-circle'></i>Manage Sales Return{{ Route::is('invoice_return.edit') }}</a>
                            </li>
                            <li @if(Route::is('quotation.create')) class="mm-active" @endif> <a href="{{ route('quotation.create') }}"><i class='bx bx-radio-circle'></i>Add New Quotation</a>
                            </li>
                            <li @if(Route::is('quotation.index')  || Route::is('quotation.edit')) class="mm-active" @endif> <a href="{{ route('quotation.index') }}"><i class='bx bx-radio-circle'></i>Manage Quotation</a>
                            </li>
                        </ul>
                    </li> --}}
                    {{-- <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cart'></i>
                            </div>
                            <div class="menu-title">Purchase</div>
                        </a>
                        <ul>
                            <li @if(Route::is('purchase.create')) class="mm-active" @endif> <a href="{{ route('purchase.create') }}"><i class='bx bx-radio-circle'></i>Add New Purchase</a>
                            </li>
                            <li @if(Route::is('purchase.index') || Route::is('purchase.edit')) class="mm-active" @endif> <a href="{{ route('purchase.index') }}"><i class='bx bx-radio-circle'></i>Manage Purchase</a>
                            </li>
                            <li @if(Route::is('purchase_return.index') || Route::is('purchase_return.create') || Route::is('purchase_return.edit')) class="mm-active" @endif> <a href="{{ route('purchase_return.index') }}"><i class='bx bx-radio-circle'></i>Manage Purchase Return</a>
                            </li>
                        </ul>
                    </li> --}}
                    {{-- <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cart'></i>
                            </div>
                            <div class="menu-title">Products</div>
                        </a>
                        <ul>
                            <li @if( Route::is('product.create')) class="mm-active" @endif> <a href="{{ route('product.create') }}"><i class='bx bx-radio-circle'></i>Add New Product</a>
                            </li>
                            <li @if(Route::is('product.index') || Route::is('product.create') || Route::is('product.edit')) class="mm-active" @endif> <a href="{{ route('product.index') }}"><i class='bx bx-radio-circle'></i>Manage Product</a>
                            </li>
                            <li> <a href="{{ route('category.index') }}"><i class='bx bx-radio-circle'></i>Manage Category</a>
                            </li>
                            <li> <a href="{{ route('brand.index') }}"><i class='bx bx-radio-circle'></i>Manage Brand</a>
                            </li>
                            <li> <a href="{{ route('unit.index') }}"><i class='bx bx-radio-circle'></i>Manage Unit</a>
                            </li>
                            <li> <a href="{{ route('p_type.index') }}"><i class='bx bx-radio-circle'></i>Manage Product Type</a>
                            </li>
                            @if($user->business->business_type_id == 5)
                                <li><a href="{{ route('generic.index') }}"><i class='bx bx-radio-circle'></i>Manage Generic</a>
                                </li>
                            @endif
                            @if($user->business->business_type_id == 15)
                                <li><a href="{{ route('territory.index') }}"><i class='bx bx-radio-circle'></i>Territory</a>
                                <li><a href="{{ route('road.index') }}"><i class='bx bx-radio-circle'></i>Road</a>
                                </li>
                            @endif
                            <li> <a href="{{ route('manufacture.index') }}"><i class='bx bx-radio-circle'></i>Manage Manufacture</a>
                            </li>
                            <li> <a href="{{ route('attributes.index') }}"><i class='bx bx-radio-circle'></i>Manage Attribute</a>
                            </li>

                        </ul>
                    </li> --}}
                    {{-- <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cart'></i>
                            </div>
                            <div class="menu-title">Customer</div>
                        </a>
                        <ul>
                            
                            <li> <a href="{{ route('customer.index') }}"><i class='bx bx-radio-circle'></i>Manage Customer</a>
                            </li>
                            
                        </ul>
                    </li> --}}
                    {{-- <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-cart'></i>
                            </div>
                            <div class="menu-title">Vendor</div>
                        </a>
                        <ul>
                            <li> <a href="{{ route('vendor.index') }}"><i class='bx bx-radio-circle'></i>Manage Vendor</a>
                            </li>
                        
                        </ul>
                    </li> --}}
                    {{-- <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-sitemap'></i>
                            </div>
                            <div class="menu-title">HR & Payroll</div>
                        </a>
                        <ul>
                            <li> <a href="{{ route('manageAttendance') }}"><i class='bx bx-radio-circle'></i>Attendence</a>
                            </li>
                            <li @if(Route::is('viewNotice') || Route::is('addNotice') || Route::is('editNotice')) class="mm-active" @endif> <a href="{{ route('viewNotice') }}"><i class='bx bx-radio-circle'></i>Notice</a>
                            </li>
                            <li @if(Route::is('manageSalary') || Route::is('addSalary') || Route::is('editSalary')) class="mm-active" @endif> <a href="{{ route('manageSalary') }}"><i class='bx bx-radio-circle'></i>Manage Salary</a>
                            </li>
                            <li @if(Route::is('allEmployee') || Route::is('addEmployee') || Route::is('editEmployee')) class="mm-active" @endif> <a href="{{ route('allEmployee') }}"><i class='bx bx-radio-circle'></i>Manage Employee</a>
                            </li>
                            <li @if(Route::is('bonuspay.view') || Route::is('bonuspay.search')) class="mm-active" @endif> <a href="{{ route('bonuspay.view') }}"><i class='bx bx-radio-circle'></i>Manage Bounus</a>
                            </li>
                            <li @if(Route::is('emploan.view')) class="mm-active" @endif> <a href="{{ route('emploan.view') }}"><i class='bx bx-radio-circle'></i>Employee Loan</a>
                            </li>
                            
                            <li> <a href="{{ route('shiftManage.view') }}"><i class='bx bx-radio-circle'></i>Manage Shift</a>
                            </li>
                            <li> <a href="{{ route('leaveApplication.view') }}"><i class='bx bx-radio-circle'></i>Manage Leave</a>
                            </li>
                            <li> <a href="{{ route('leaveType.view') }}"><i class='bx bx-radio-circle'></i>Manage Leave Type</a>
                            </li>
                            <li> <a href="{{ route('leavePart.view') }}"><i class='bx bx-radio-circle'></i>Manage Leave Part</a>
                            </li>
                            <li> <a href="{{ route('leaveTagline.view') }}"><i class='bx bx-radio-circle'></i>Manage Leave Tagline</a>
                            </li>
                            <li @if(Route::is('viewDepartment') || Route::is('addDepartment') || Route::is('editDepartment')) class="mm-active" @endif> <a href="{{ route('viewDepartment') }}"><i class='bx bx-radio-circle'></i>Manage Department</a>
                            </li>
                            <li @if(Route::is('viewDesignation') || Route::is('addDesignation') || Route::is('editDesignation')) class="mm-active" @endif> <a href="{{ route('viewDesignation') }}"><i class='bx bx-radio-circle'></i>Manage Designation</a>
                            </li>
                            <li @if(Route::is('managePayroll') || Route::is('addPayroll') || Route::is('editPayroll')) class="mm-active" @endif> <a href="{{ route('managePayroll') }}"><i class='bx bx-radio-circle'></i> Payroll Settings</a>
                            </li>
                            <li @if(Route::is('manageAbsent') || Route::is('addAbsent') || Route::is('editAbsent')) class="mm-active" @endif> <a href="{{ route('manageAbsent') }}"><i class='bx bx-radio-circle'></i>Absent Settings</a>
                            </li>
                            <li @if(Route::is('manageLateRoll') || Route::is('addLateRoll') || Route::is('editLateRoll')) class="mm-active" @endif> <a href="{{ route('manageLateRoll') }}"><i class='bx bx-radio-circle'></i>Late Roll Settings</a>
                            </li>
                            <li @if(Route::is('manageOvertime') || Route::is('addOvertime') || Route::is('editOvertime')) class="mm-active" @endif> <a href="{{ route('manageOvertime') }}"><i class='bx bx-radio-circle'></i>Overtime Settings</a>
                            </li>
                            <li> <a href="{{ route('managePaymentRange') }}"><i class='bx bx-radio-circle'></i>Manage Payment Range</a>
                            </li>
                            <li> <a href="{{ route('monthManage.view') }}"><i class='bx bx-radio-circle'></i>Manage Month</a>
                            </li>
                            <li> <a href="{{ route('holiday.view') }}"><i class='bx bx-radio-circle'></i>Manage Holiday</a>
                            </li>
                        </ul>
                    </li> --}}
                    {{-- 
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-dollar-circle'></i>
                            </div>
                            <div class="menu-title">Accounts</div>
                        </a>
                        <ul>

                            <li> <a href="{{ route('debit_vouchar.index') }}"><i class='bx bx-radio-circle'></i>Debit Voucher</a>
                            </li>
                            <li> <a href="{{ route('credit_vouchar.index') }}"><i class='bx bx-radio-circle'></i>Credit Voucher</a>
                            </li>

                            <li @if(Route::is('account_head.index') || Route::is('account_head.edit') || Route::is('account_head.create')) class="mm-active" @endif> <a href="{{ route('account_head.index') }}"><i class='bx bx-radio-circle'></i>Account Head</a>
                            </li>
                            <li @if(Route::is('balance_account.index') || Route::is('balance_account.edit') || Route::is('balance_account.create')) class="mm-active" @endif> <a href="{{ route('balance_account.index') }}"><i class='bx bx-radio-circle'></i>Bank Account</a>
                            </li>
                            <li> <a href="{{ route('payment_method.index') }}"><i class='bx bx-radio-circle'></i>Payment Method</a>
                            </li>
                            <li> <a href="{{ route('expense.index') }}"><i class='bx bx-radio-circle'></i>Expense</a>
                            </li>
                            <li> <a href="{{ route('expense_category.index') }}"><i class='bx bx-radio-circle'></i>Expense Category</a>
                            </li>
                            <li> <a href="{{ route('bank.view') }}"><i class='bx bx-radio-circle'></i>Bank</a>
                            </li>
                            <li> <a href="{{ route('bankaccount.view') }}"><i class='bx bx-radio-circle'></i>Bank Account</a>
                            </li>

                        </ul>
                    </li>
                    <li class="menu-label">Reports</li>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-line-chart'></i>
                            </div>
                            <div class="menu-title">Inventory</div>
                        </a>
                        <ul>

                            <li @if(Route::is('report.purchase')) class="mm-active" @endif> <a href="{{ route('report.purchase') }}"><i class='bx bx-radio-circle'></i>Purchase Report</a>
                            </li>
                            <li @if(Route::is('report.invoice')) class="mm-active" @endif> <a href="{{ route('report.invoice') }}"><i class='bx bx-radio-circle'></i>Sales Report</a>
                            </li>

                            <li @if(Route::is('report.stock')) class="mm-active" @endif> <a href="{{ route('report.stock') }}"><i class='bx bx-radio-circle'></i>Stock Report</a>
                            </li>

                            <li @if(Route::is('report.sales_return')) class="mm-active" @endif> <a href="{{ route('report.sales_return') }}"><i class='bx bx-radio-circle'></i>Sales Return</a>
                            </li>
                            <li @if(Route::is('report.purchase_return')) class="mm-active" @endif> <a href="{{ route('report.purchase_return') }}"><i class='bx bx-radio-circle'></i>Purchase Return</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-line-chart'></i>
                            </div>
                            <div class="menu-title">Accounting</div>
                        </a>
                        <ul>

                            <li @if(Route::is('balance_sheet')) class="mm-active" @endif> <a href="{{ route('balance_sheet') }}"><i class='bx bx-radio-circle'></i>Balance Sheet</a>
                            </li>
                            <li @if(Route::is('trail_balance')) class="mm-active" @endif> <a href="{{ route('trail_balance') }}"><i class='bx bx-radio-circle'></i>Trail Balance</a>
                            </li>
                            <li @if(Route::is('ledger_summary')) class="mm-active" @endif> <a href="{{ route('ledger_summary') }}"><i class='bx bx-radio-circle'></i>Ledger Summary</a>
                            </li>
                            <li @if(Route::is('profit_loss')) class="mm-active" @endif> <a href="{{ route('profit_loss') }}"><i class='bx bx-radio-circle'></i>Profit & Loss</a>
                            </li>
                            <li @if(Route::is('report.vendor_due')) class="mm-active" @endif> <a href="{{ route('report.vendor_due') }}"><i class='bx bx-radio-circle'></i>Vendor Due Report</a>
                            </li>
                            <li @if(Route::is('report.customer_due')) class="mm-active" @endif> <a href="{{ route('report.customer_due') }}"><i class='bx bx-radio-circle'></i>Customer Due Report</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-line-chart'></i>
                            </div>
                            <div class="menu-title">Hr & Payroll</div>
                        </a>
                        <ul>

                            <li @if(Route::is('report.attendance')) class="mm-active" @endif> <a href="{{ Route('report.attendance') }}"><i class='bx bx-radio-circle'></i>Attendence</a>
                            </li>
                            <li @if(Route::is('report.salary_sheet')) class="mm-active" @endif> <a href="{{ Route('report.salary_sheet') }}"><i class='bx bx-radio-circle'></i>Salary Sheet</a>
                            </li>
                            <li @if(Route::is('report.emp_leave')) class="mm-active" @endif> <a href="{{ Route('report.emp_leave') }}"><i class='bx bx-radio-circle'></i>Employee Leave</a>
                            </li>
                            <li @if(Route::is('report.emp_loan')) class="mm-active" @endif> <a href="{{ Route('report.emp_loan') }}"><i class='bx bx-radio-circle'></i>Employee Loan</a>
                            </li>
                            <li @if(Route::is('report.emp_bonus')) class="mm-active" @endif> <a href="{{ Route('report.emp_bonus') }}"><i class='bx bx-radio-circle'></i>Employee Bonus</a>
                            </li>
                        </ul>
                    </li> --}}
            
                    {{-- <li class="menu-label">Setting</li>
                    <li>
                        <a href="{{ route('business_setting') }}">
                            <i class='bx bx-cog'></i>
                            <div class="menu-title">Business Setting</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('branch.index') }}"><i class='bx bx-store'></i>  <div class="menu-title">Branch</div></a>

                    </li>
                    <li>
                        <a href="{{ route('currency.index') }}"><i class='bx bx-store'></i>  <div class="menu-title">Currency</div></a>

                    </li>
                    <li>
                        <a href="{{ route('tax.index') }}"><i class='bx bx-store'></i> <div class="menu-title">Tax</div></a>
                    </li>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-map'></i>
                            </div>
                            <div class="menu-title">Location</div>
                        </a>
                        <ul>
                            <li> <a href="{{ route('country.index') }}"><i class='bx bx-radio-circle'></i>Country</a>
                            </li>
                                <li> <a href="{{ route('state.index') }}"><i class='bx bx-radio-circle'></i>State</a>
                            </li>
                                <li> <a href="{{ route('city.index') }}"><i class='bx bx-radio-circle'></i>City</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-user-plus'></i>
                            </div>
                            <div class="menu-title">Role</div>
                        </a>
                        <ul>
                            <li> <a href="{{ route('bussiness.role.add') }}"><i class='bx bx-radio-circle'></i>Add Role</a>
                            </li>
                            <li @if(Route::is('bussiness.role.index') || Route::is('bussiness.role.edit') ) class="mm-active" @endif> <a href="{{ route('bussiness.role.index') }}"><i class='bx bx-radio-circle'></i>Role List</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-user-plus'></i>
                            </div>
                            <div class="menu-title">User</div>
                        </a>
                        <ul>
                            <li> <a href="{{ route('bussiness.user.add') }}"><i class='bx bx-radio-circle'></i>Add User</a>
                            </li>
                            <li @if(Route::is('bussiness.user.index') || Route::is('bussiness.user.edit') ) class="mm-active" @endif> <a href="{{ route('bussiness.user.index') }}"><i class='bx bx-radio-circle'></i>User List</a>
                            </li>
                        </ul>
                    </li> --}}
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
