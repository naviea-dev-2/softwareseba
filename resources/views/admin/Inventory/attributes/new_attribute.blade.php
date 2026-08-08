@if($is_edit == 1)
@foreach ($attribute_set->attributes as $attribute)
<tr data-id="{{ $attribute->id }}">
    <td>
        <label class="form-check form-check-inline form-check-single">
            <input class="form-check-input" type="radio" name="is_default" value="{{ $attribute->id }}" @if($attribute->is_default == 1) checked @endif>

            <span class="form-check-label"></span>
        </label>
    </td>

    <td >
        <input type="text" name="old_title[{{ $attribute->id}}]" class="form-control" value="{{ $attribute->title }}">
    </td>

    {{-- <td >
        <div class="input-group tw-picker color-input">
            <input name="old_color[{{ $attribute->id }}]" id="color"  type="text" value="{{  $attribute->color }}" class="form-control dnone"/>
            <span class="input-group-addon"><i></i></span>
        </div>
    </td> --}}

    {{-- <td>
        <div class="image-box image-box-swatch-image" action="select-image">
            <input class="image-data" name="old_image[{{ $attribute->id }}]" type="hidden" value="{{ $attribute->image }}" />
            <div style="width: 3rem" class="preview-image-wrapper mb-1">
                <div class="preview-image-inner">
                    <a class="image-box-actions show_media_dialog" href="javascript:void(0)">
                        <img class="preview-image" src="{{ $attribute->image ? url("public/media/".$attribute->image) : 'https://nest.botble.com/vendor/core/core/base/images/placeholder.png' }}" alt="Preview image" />
                        <span class="image-picker-backdrop"></span>
                    </a>
                    <button class="btn btn-pill btn-icon  btn-sm image-picker-remove-button p-0" style="--bb-btn-font-size: 0.5rem;" type="button" title="Remove image" >
                        <span class="icon-tabler-wrapper icon-sm icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                        </svg>


                        </span>
                    </button>
                </div>
            </div>

            <a class="show_media_dialog"  href="javascript:void(0)">
                Choose image
            </a>


        </div>
    </td> --}}

    <td>
        <a href="javascript:(0)" class="old-remove-item text-decoration-none text-danger" data-id="{{ $attribute->id }}">
            <span class="icon-tabler-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 7l16 0" />
                <path d="M10 11l0 6" />
                <path d="M14 11l0 6" />
                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>


            </span>
        </a>
    </td>
</tr>
@endforeach
@else
<tr data-id="{{ $row_no }}">
    <td>
        <label class="form-check form-check-inline form-check-single">
            <input class="form-check-input" type="radio" name="is_default" value="{{ $row_no }}" checked>

            <span class="form-check-label"></span>
        </label>
    </td>

    <td >
        <input type="text" name="title[{{ $row_no }}]" class="form-control" value="">
    </td>

    {{-- <td >
        <div class="input-group tw-picker color-input">
            <input name="color[{{ $row_no }}]" id="color"  type="text" value="{{  '#61a402' }}" class="form-control dnone"/>
            <span class="input-group-addon"><i></i></span>
        </div>
    </td> --}}

    {{-- <td>
        <div class="image-box image-box-swatch-image" action="select-image">
            <input class="image-data" name="image[{{ $row_no }}]" type="hidden" value="" />
            <div style="width: 3rem" class="preview-image-wrapper mb-1">
                <div class="preview-image-inner">
                    <a class="image-box-actions show_media_dialog" href="javascript:void(0)">
                        <img class="preview-image" data-default="https://nest.botble.com/vendor/core/core/base/images/placeholder.png" src="https://nest.botble.com/vendor/core/core/base/images/placeholder.png" alt="Preview image" />
                        <span class="image-picker-backdrop"></span>
                    </a>
                    <button class="btn btn-pill btn-icon  btn-sm image-picker-remove-button p-0" style="--bb-btn-font-size: 0.5rem;" type="button" title="Remove image" >
                        <span class="icon-tabler-wrapper icon-sm icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                        </svg>


                        </span>
                    </button>
                </div>
            </div>

            <a class="show_media_dialog"  href="javascript:void(0)">
                Choose image
            </a>


        </div>
    </td> --}}

    <td>
        <a href="javascript:(0)" class="remove-item text-decoration-none text-danger">
            <span class="icon-tabler-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 7l16 0" />
                <path d="M10 11l0 6" />
                <path d="M14 11l0 6" />
                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>


            </span>
        </a>
    </td>
</tr>
@endif
