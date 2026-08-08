 <!-- start form here -->
<form method="POST" action="{{route('attributes.saveAttributesData')}}" enctype="multipart/form-data"  @if($is_add == 0) class="edit_data_form" @else class="add_data_form" @endif>
    @csrf
    <div class="row">
        @if($is_add == 0)
        <input type="hidden" value="0" name="id" id="edit_data_id" required>
        @endif
        <div class="col-sm-6">
            <label for="">Name</label>
            <input type="text" class=" form-control form-control-sm"
            @if($is_add == 1) id="name" @else id="edit_name" @endif
            name="name" autocomplete="off" required>
            <span class="invalid-feedback mb-0">
            </span>
        </div>
        <div class="col-sm-6">
            <label for="">Order</label>
            <input type="text" class=" form-control form-control-sm"
            @if($is_add == 1) id="order" @else id="edit_order" @endif name="order" autocomplete="off" value="0" required>
            <span class="invalid-feedback mb-0">
            </span>
        </div>


        <button style="margin: 10px 0;background: blue;color: white;" is_add={{$is_add  }} class="btn blue-btn add-new-attribute" type="button" >{{ __('Add New Attribute') }}</button>
        <div class="table-responsive">
            <table class="table table-borderless table-theme" style="width:100%;">
                <thead>
                    <tr>
                        <th class="text-left" style="width:10%">{{ __('Is Defalut') }}</th>
                        <th class="text-left" style="width:40%">{{ __('Title') }}</th>
                        {{-- <th class="text-left" style="width:20%">{{ __('Color') }}</th>
                        <th class="text-left" style="width:20%">{{ __('Image') }}</th> --}}
                        <th class="text-center" style="width:10%">{{ __('Remove') }}</th>
                    </tr>
                </thead>
                <tbody @if($is_add == 1) id="ajax-add-attribute-new" @else id="ajax-add-attribute-edit" @endif>

                </tbody>
            </table>
        </div>
        <div class="col-sm-3">
            <br/>
            <button class="btn btn-sm btn-primary mt-4 " type="submit">
                <i class="fa fa-save pr-2"></i>Save
            </button>
        </div>
    </div>
</form>
