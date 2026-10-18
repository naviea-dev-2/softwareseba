{{-- partials/menu/_admin.blade.php --}}
@foreach($menu->adminCaptions() as $caption)
    @if($caption->is_caption == 1)
        <li class="menu-label">{{ $caption->name }}</li>
    @endif

    @foreach($menu->adminMenus($caption->id) as $item)
        @if(!$menu->passesCondition($item))
            @continue
        @endif

        @if($item->parent_menu == 1 && empty($item->route_name))
            @include('inc.menu._group', ['group' => $item, 'children' => $menu->adminMenus($item->id), 'menu' => $menu])
        @else
            @include('inc.menu._item', ['item' => $item, 'menu' => $menu])
        @endif
    @endforeach
@endforeach