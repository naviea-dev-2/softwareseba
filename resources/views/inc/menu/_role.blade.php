{{-- partials/menu/_role.blade.php --}}
@foreach($menu->roleCaptions() as $caption)
    @if($caption->is_caption == 1)
        <li class="menu-label">{{ $caption->name }}</li>
    @endif

    @foreach($menu->roleMenus($caption->id) as $item)
        @if($item->parent_menu == 1 && empty($item->route_name))
            @include('inc.menu._group', ['group' => $item, 'children' => $menu->roleMenus($item->id), 'menu' => $menu])
        @else
            @include('inc.menu._item', ['item' => $item, 'menu' => $menu])
        @endif
    @endforeach
@endforeach