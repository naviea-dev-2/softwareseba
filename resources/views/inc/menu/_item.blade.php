{{-- partials/menu/_item.blade.php --}}
@if($item->route_name === 'dashboard')
    <li>
        <a href="{{ route('dashboard') }}">
            <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
            <div class="menu-title">Dashboard</div>
        </a>
    </li>
@else
    <li @if($menu->isActive($item)) class="mm-active" @endif>
        <a href="{{ route($item->route_name) }}">
            <i class='bx {{ $item->logo_class }}'></i>
            <div class="menu-title">{{ $item->name }}</div>
        </a>
    </li>
@endif