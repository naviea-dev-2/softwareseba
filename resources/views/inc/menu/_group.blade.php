{{-- partials/menu/_group.blade.php --}}
<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class='bx {{ $group->logo_class }}'></i></div>
        <div class="menu-title">{{ $group->name }}</div>
    </a>
    <ul>
        @foreach($children as $child)
            @if($menu->passesBusinessType($child) && !empty($child->route_name))
                <li @if($menu->isActive($child)) class="mm-active" @endif>
                    <a href="{{ route($child->route_name) }}">
                        <i class='bx {{ $child->logo_class }}'></i>{{ $child->name }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</li>