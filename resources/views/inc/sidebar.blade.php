<ul class="metismenu" id="menu">
    @php
        $menu = new \App\Services\MenuService(auth()->user());
    @endphp

    @if($menu->isBlocked())
        <li>
            <a href="{{ route('dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
    @elseif(auth()->user()->business->business_type_id == 17)
        @if(auth()->user()->user_type == 0)
            @include('inc.menu._principal_admin', ['menu' => $menu])
        @else
            @include('inc.menu._principal_user')
        @endif
    @elseif($menu->isAdmin())
        @include('inc.menu._admin', ['menu' => $menu])
    @else
        @include('inc.menu._role', ['menu' => $menu])
    @endif
</ul>