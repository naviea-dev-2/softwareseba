{{-- partials/menu/_principal_admin.blade.php --}}
@foreach(\App\Models\Permission::where('is_caption', '!=', 0)->whereRaw('JSON_CONTAINS(condition_show, ?)', ['"Principal Association"'])->orderBy('menu_sort')->get() as $caption)
    @if($caption->is_caption == 1)
        <li class="menu-label">{{ $caption->name }}</li>
    @endif

    @foreach(\App\Models\Permission::whereIn('section', $menu->sections())->where('parent_id', $caption->id)->whereRaw('JSON_CONTAINS(condition_show, ?)', ['"Principal Association"'])->orderBy('menu_sort')->get() as $item)
        @if($item->parent_menu == 1 && empty($item->route_name))
            @include('inc.menu._group', ['group' => $item, 'children' => \App\Models\Permission::whereIn('section', $menu->sections())->where('parent_id', $item->id)->whereRaw('JSON_CONTAINS(condition_show, ?)', ['"Principal Association"'])->orderBy('menu_sort')->get(), 'menu' => $menu])
        @else
            @include('inc.menu._item', ['item' => $item, 'menu' => $menu])
        @endif
    @endforeach
@endforeach