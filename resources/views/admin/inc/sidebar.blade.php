<!--navigation-->
<ul class="metismenu" id="menu">
    <li>
        <a href="{{ route('admin.dashboard') }}">
            <div class="parent-icon"><i class='bx bx-home-alt'></i>
            </div>
            <div class="menu-title">Dashboard</div>
        </a>

    </li>

    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-line-chart'></i>
            </div>
            <div class="menu-title">Manage Business</div>
        </a>
        <ul>

            <li @if(Route::is('admin.add_business')) class="mm-active" @endif> <a href="{{ route('admin.add_business') }}"><i class='bx bx-radio-circle'></i>Add Business</a>
            </li>
            <li @if(Route::is('admin.business.change_password') || Route::is('admin.all_business') || Route::is('admin.edit_business') || Route::is('admin.package.add_pack')) class="mm-active" @endif> <a href="{{ route('admin.all_business') }}"><i class='bx bx-radio-circle'></i>All Business</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-line-chart'></i>
            </div>
            <div class="menu-title">Manage User</div>
        </a>
        <ul>
            <li @if(Route::is('admin.user.add')) class="mm-active" @endif> <a href="{{ route('admin.user.add') }}"><i class='bx bx-radio-circle'></i>Add User</a>
            </li>
            <li @if(Route::is('admin.user.change_password') || Route::is('admin.user.index') || Route::is('admin.user.edit') ) class="mm-active" @endif> <a href="{{ route('admin.user.index') }}"><i class='bx bx-radio-circle'></i>All User</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-line-chart'></i>
            </div>
            <div class="menu-title">Manage Package</div>
        </a>
        <ul>

            <li @if(Route::is('admin.package.add')) class="mm-active" @endif> <a href="{{ route('admin.package.add') }}"><i class='bx bx-radio-circle'></i>Add Package</a>
            </li>
            <li @if(Route::is('admin.package.index') || Route::is('admin.package.edit') ) class="mm-active" @endif> <a href="{{ route('admin.package.index') }}"><i class='bx bx-radio-circle'></i>All Package</a></li>
            <li @if(Route::is('admin.package.order.all') ) class="mm-active" @endif> <a href="{{ route('admin.package.order.all') }}"><i class='bx bx-radio-circle'></i>All Order</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-cart'></i>
            </div>
            <div class="menu-title">Products</div>
        </a>
        <ul>
            <li @if( Route::is('admin.product.create')) class="mm-active" @endif> <a href="{{ route('admin.product.create') }}"><i class='bx bx-radio-circle'></i>Add New Product</a>
            </li>
            <li @if(Route::is('admin.product.index') || Route::is('admin.product.create') || Route::is('admin.product.edit')) class="mm-active" @endif> <a href="{{ route('admin.product.index') }}"><i class='bx bx-radio-circle'></i>Manage Product</a>
            </li>
            <li> <a href="{{ route('admin.category.index') }}"><i class='bx bx-radio-circle'></i>Manage Category</a>
            </li>
            <li> <a href="{{ route('admin.brand.index') }}"><i class='bx bx-radio-circle'></i>Manage Brand</a>
            </li>
            <li> <a href="{{ route('admin.unit.index') }}"><i class='bx bx-radio-circle'></i>Manage Unit</a>
            </li>
            <li> <a href="{{ route('admin.p_type.index') }}"><i class='bx bx-radio-circle'></i>Manage Product Type</a>
            </li>
            
            <li><a href="{{ route('admin.generic.index') }}"><i class='bx bx-radio-circle'></i>Manage Generic</a>
            </li>
        
           
            <li> <a href="{{ route('admin.manufacture.index') }}"><i class='bx bx-radio-circle'></i>Manage Manufacture</a>
            </li>
            <li> <a href="{{ route('admin.attributes.index') }}"><i class='bx bx-radio-circle'></i>Manage Attribute</a>
            </li>

        </ul>
    </li>

    <li class="menu-label">Setting</li>
    <li @if(Route::is('backend.theme_options') || Route::is('backend.social-media') || Route::is('backend.service_list') || Route::is('backend.theme-options-color') || Route::is('backend.theme-options-seo') || Route::is('backend.theme-options-facebook') || Route::is('backend.theme-options-facebook-pixel') || Route::is('backend.theme-options-twitter') || Route::is('backend.google-analytics') || Route::is('backend.google-tag-manager') || Route::is('backend.custom-css') || Route::is('backend.custom-js')) class="mm-active" @endif>
        <a href="{{ route('backend.theme_options') }}">
             <i class='bx bx-cog'></i>
            <div class="menu-title">Theme Option</div>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.currency.index') }}"><i class='bx bx-store'></i>  <div class="menu-title">Currency</div></a>

    </li>
    <li>
        <a href="{{ route('admin.tax.index') }}"><i class='bx bx-store'></i> <div class="menu-title">Tax</div></a>
    </li>
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class='bx bx-map'></i>
            </div>
            <div class="menu-title">Location</div>
        </a>
        <ul>
            <li> <a href="{{ route('admin.country.index') }}"><i class='bx bx-radio-circle'></i>Country</a>
            </li>
                <li> <a href="{{ route('admin.state.index') }}"><i class='bx bx-radio-circle'></i>State</a>
            </li>
                <li> <a href="{{ route('admin.city.index') }}"><i class='bx bx-radio-circle'></i>City</a>
            </li>
        </ul>
    </li>

</ul>
<!--end navigation-->
