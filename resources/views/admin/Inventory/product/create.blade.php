@extends('admin.inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Add Product</title>
<style>
    label{
       font-size: 1rem;
    }
    .error-show .select2-container--bootstrap-5 .select2-selection{
        border: 1px solid red;
    }
</style>
@endsection
@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="row row-card-one">
            <div class="col-sm-12 ">
                <div class="row report-title">
                    <h4 class=""><b>Add Product</b></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">

        <div class="row row-card-one">
            <div class="col-sm-12">
                <!-- start form here -->
                <form method="POST" action="{{route('admin.product.store')}}" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <input type="hidden" value="0" name="id"  required>

                        <div class="col-sm-4" id="exampleModalSelect" style="position: relative;">
                            <label for=""> Business *</label>
                            <input type="hidden" id="h_business_id" name="h_business_id" value="{{old('h_business_id')}}">
                            <Select name="business_id" id="business_id" class="form-control">
                            
                            </Select>
                            <span class="invalid-feedback mb-0">
                            </span>
                        </div>
                        
                        <div class="col-sm-4 @error('manufacture') error-show @enderror">
                            <label for=""><b>Manufacturer</b> *</label>
                            <select id="manufacture" class="form-control select_manufacture" name="manufacture">
                                <option value="">-- Select One --</option>
                            </select>
                        </div>


                        <div class="col-sm-4 @error('category') error-show @enderror">
                            <label for=""><b>Category</b> *</label>
                            <select id="add_category" class="form-control select_category" name="category">
                                <option value="">-- Select One --</option>
                            </select>
                        </div>
                        <div class="col-sm-4 @error('brand') error-show @enderror">
                            <label for=""><b>Brand</b></label>
                            <select class="form-control select2-brand" name="brand">
                                <option value="">-- Select One --</option>

                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for=""><b>Product Name</b></label>
                            <input @error('product_name')
                            style="border:1px solid red!important;"
                            @enderror   type="text" class=" form-control " name="product_name" autocomplete="off" value="{{ old('product_name') }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label for=""><b>Product Code</b></label>
                            <input @error('product_code')
                            style="border:1px solid red!important;"
                            @enderror   type="text" class=" form-control " name="product_code" autocomplete="off" value="{{ old('product_code') }}">
                        </div>
                        <div class="col-sm-4">
                            <label for=""><b>Batch/DDR No.</b></label>
                            <input @error('batch_no') style="border:1px solid red!important;"
                            @enderror type="text" class=" form-control" name="batch_no" autocomplete="off" value="{{ old('batch_no') }}">
                        </div>
                        <div class="col-sm-4">
                            <label for=""><b>Manufacture Date</b></label>
                            <input @error('manufacture_date')
                            style="border:1px solid red!important;"
                            @enderror   type="text" class=" form-control datepicker" name="manufacture_date" autocomplete="off" value="{{ old('manufacture_date') ?? date('Y-m-d') }}">
                        </div>
                        <div class="col-sm-4">
                            <label for=""><b>Expire Date</b></label>
                            <input @error('exipre_date')
                            style="border:1px solid red!important;"
                            @enderror   type="text" class=" form-control datepicker" name="exipre_date" autocomplete="off" value="{{ old('exipre_date') ?? date('Y-m-d') }}">
                        </div>
                        
                        <div class="col-sm-4 show-business-type show-business-type-6 show-business-type-15 @if(old('business_type') == 6 || old('business_type') == 15) d-block  @else d-none @endif">
                            <label for=""><b>IMEI 1</b></label>
                            <input @error('imei_1') style="border:1px solid red!important;"
                            @enderror type="text" class=" form-control" name="imei_1" autocomplete="off" value="{{ old('imei_1') }}">
                        </div>
                        <div class="col-sm-4  show-business-type show-business-type-6 show-business-type-15 @if(old('business_type') == 6 || old('business_type') == 15) d-block  @else d-none @endif">
                            <label for=""><b>IMEI 2</b></label>
                            <input @error('imei_2') style="border:1px solid red!important;"
                            @enderror type="text" class=" form-control" name="imei_2" autocomplete="off" value="{{ old('imei_2') }}">
                        </div>
                        
                        <div class="col-sm-3  show-business-type show-business-type-5 @if(old('business_type') != 5) d-none @endif">
                            <label for=""><b>Product Type</b></label>
                            <select @error('p_type')
                            style="border:1px solid red!important;"
                            @enderror name="p_type" class="form-control select2-p_type">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <div class="col-sm-3  show-business-type show-business-type-5 @if(old('business_type') != 5) d-none @endif">
                            <label for=""><b>Generics</b></label>
                            <select @error('generic')
                            style="border:1px solid red!important;"
                            @enderror name="generic" class="form-control select2-generic">
                                <option value="">Select</option>

                            </select>
                        </div>

                       
                        <div class="col-sm-3">
                            <label for=""><b>Tax</b></label>
                            <select @error('tax')
                            style="border:1px solid red!important;"
                            @enderror name="tax" class="form-control ">
                                <option value="">Select Tax</option>
                                @foreach ($taxes as $tax)
                                <option @if($tax->id == old('tax')) selected @endif value="{{ $tax->id }}">{{ $tax->name.($tax->rate_type == "percent" ? '('.$tax->rate."%".')' : '') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 @error('unit') error-show @enderror">
                            <label for=""><b>Unit</b></label>
                            <select  class="add-select2-unit form-control mb-0" name="unit"></select>
                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Discount Type</b></label>
                            <select
                            id="add_discount_type" name="discount_type" class="form-control ">
                                <option value="percent">Percent</option>
                                <option value="fixed">Fixed</option>
                            </select>

                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Discount</b></label>
                            <input id="add_product_discount" type="number" class=" form-control " name="product_discount" autocomplete="off" value="{{ old('product_discount') ?? 0 }}" @error('product_discount') style="border:1px solid red!important;" @enderror>
                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Purchase Price</b></label>
                            <input @error('purchase_price')
                            style="border:1px solid red!important;"
                            @enderror   type="number" step="any" class=" form-control " name="purchase_price" autocomplete="off" value="{{ old('purchase_price') ?? 0 }}" required>
                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Sale Price</b></label>
                            <input @error('sale_price')
                            style="border:1px solid red!important;"
                            @enderror   type="number" step="any" class=" form-control " name="sale_price" autocomplete="off" value="{{ old('sale_price') ?? 0 }}" required>
                        </div>
                        

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 pb-4">

                            <button type="button" href="javascript:void(0);" class="btn border js-add-p-attribute">{{ __('Add Attribute') }}</button>
                            <div id="ajax-attribute-list">
                                @if(old('attribute'))
                                    @foreach (old('attribute') as $old_k=>$old_attribute)
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="attribute">{{ __('Attribute') }}</label>
                                                <select name="attribute[]" class="p-attribute chosen-select form-control">
                                                @php
                                                    $f_attribute_set = null;
                                                    $k=0;
                                                    $product_id=0;
                                                    $attribute_sets =\App\Models\Inventory\AttributeSet::where()->get();
                                                @endphp
                                                @foreach($attribute_sets as $attribute_set)
                                                    @php
                                                        $check_set =  \App\Models\Inventory\ProductWithAttributSet::where('attribute_set_id',$attribute_set->id)->where('product_id',$product_id)->first();
                                                    @endphp
                                                    @if(!$check_set)
                                                    @php
                                                        if($attribute_set->id == $old_attribute){
                                                            $f_attribute_set = $attribute_set;
                                                        }
                                                    @endphp
                                                    <option {{ $attribute_set->id == $old_attribute ? "selected=selected" : '' }} value="{{ $attribute_set->id }}">
                                                        {{ $attribute_set->title }}
                                                    </option>
                                                    @php
                                                        $k++;
                                                    @endphp
                                                    @endif
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if($f_attribute_set)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="attribute_value">{{ __('Value') }}</label>
                                                <select name="attribute_value[{{ $f_attribute_set->id }}][]"  class="p-attribute-value chosen-select form-control">
                                                @foreach($f_attribute_set->attributes as $k=>$attribute)
                                                    <option {{ old('attribute_value')[ $f_attribute_set->id][0] == $attribute->id ? "selected=selected" : '' }} value="{{ $attribute->id }}">
                                                        {{ $attribute->title }}
                                                    </option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-1">
                                            <div class="form-group">
                                                <label for="attribute_value"></label>
                                                <div class="form-control mt-2" style="margin-right: 2px;padding: 7px!important;background: transparent;border: navajowhite;">
                                                    <a style="color:red;" class="attribute-set-remove" href="javascript:void(0);"><i class="bx bx-trash"></i></a>
                                                </div>

                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""><b>Product Image</b></label>
                            <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 150px;">
                                <img class="display-upload-img-add" style="width: 150px;height: 70px;" src="{{ asset("public/images/No-image.jpg")}}" alt="">
                                <input type="file" name="product_image" class="form-control upload-img-add" placeholder="Enter Activity Image" style="position: absolute;top: 0;opacity: 0;height: 100%;">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <br/>
                            <button class="btn btn-sm btn-primary mt-4 ">
                                <i class="fa fa-save pr-2"></i>Save
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>








</div>
@endsection
@section('script')


<script>
    $(".datepicker").flatpickr();
    $('#business_type').on('change',function(){
       
        $('.show-business-type').addClass('d-none');
        $('.show-business-type-'+$(this).val()).removeClass('d-none');
    })
    var row_p = 1;
    var edit_row_p=1;
    $('.select_category').select2({
        theme: "bootstrap-5",
      placeholder: 'Select Category',
      allowClear: true,
      width:'100%',
      dropdownAutoWidth : true,
      containerCssClass: 'select-sm',
      ajax: {
        url: '{{route('admin.select2.product.categories')}}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            business_id:$('#business_id').val(),
            value: $.trim(params.term),
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
    @if(old('category'))
    @php
        $o_category = \App\Models\Inventory\Category::find(old('category'))
    @endphp
    var category_option = new Option("{{ $o_category->name }}","{{ $o_category->id }}", true, true);
    $('.select_category').append(category_option).trigger('change');
    @endif

    $('#business_id').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Business User',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent:$( '#exampleModalSelect' ) ,
        ajax: {
            url: '{{route('admin.select2.businesses')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var data = e.params.data;
        $('#h_business_id').val(data.text);

    });
    @if(old('business_id'))
    var category_option = new Option("{{ old('h_business_id') }}","{{ old('business_id') }}", true, true);
    $('#business_id').append(category_option).trigger('change');
    @endif
    $('#manufacture').select2({
        theme: "bootstrap-5",
      placeholder: 'Select',
      allowClear: true,
      width:'100%',
      dropdownAutoWidth : true,
      containerCssClass: 'select-sm',
      ajax: {
        url: '{{route('admin.select2.manufactures')}}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            business_id:$('#business_id').val(),
            value: $.trim(params.term),
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
    @if(old('manufacture'))
    @php
        $o_manufacture = \App\Models\Inventory\Manufature::find(old('manufacture'))
    @endphp
    var manufacture_option = new Option("{{ $o_manufacture->name }}","{{ $o_manufacture->id }}", true, true);
    $('#manufacture').append(manufacture_option).trigger('change');
    @endif


    $('.select2-brand').select2({
        theme: "bootstrap-5",
      placeholder: 'Select Brand',
      allowClear: true,
      width:'100%',
      dropdownAutoWidth : true,
      containerCssClass: 'select-sm',
      ajax: {
        url: '{{route('admin.select2.product.brands')}}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            business_id:$('#business_id').val(),
            value: $.trim(params.term),
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
    @if(old('brand'))
    console.log("{{ old('brand') }}");
    @php
        $o_brand = \App\Models\Inventory\Brand::find(old('brand'));
    @endphp
    console.log("{{ $o_brand }}");
    var brand_option = new Option("{{ $o_brand?->name }}","{{ $o_brand?->id }}", true, true);
    $('.select2-brand').append(brand_option).trigger('change');
    @endif

    $('.add-select2-unit').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Unit',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('admin.select2.category.units')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                business_id:$('#business_id').val(),
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    });
    @if(old('unit'))
    @php
        $o_unit = \App\Models\Inventory\Unit::find(old('unit'))
    @endphp
    var unit_option = new Option("{{ $o_unit->name }}","{{ $o_unit->id }}", true, true);
    $('.add-select2-unit').append(unit_option).trigger('change');
    @endif

    $('.select2-p_type').select2({
        theme: "bootstrap-5",
        placeholder: 'Select',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('admin.select2.p_types')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                business_id:$('#business_id').val(),
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    });
    @if(old('p_type'))
        @foreach(old('p_type') as $p_type_id)
        @php
            $p_type = \App\Models\Inventory\ProductType::find($p_type_id);
        @endphp
        var p_type_option = new Option("{{ $p_type->name }}","{{ $p_type->id }}", true, true);
        $('.select2-p_type').append(p_type_option).trigger('change');
        @endforeach
    @endif
    
        $('.select2-generic').select2({
            theme: "bootstrap-5",
            placeholder: 'Select',
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            containerCssClass: 'select-sm',
            ajax: {
                url: '{{route('admin.select2.generics')}}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    business_id:$('#business_id').val(),
                    value: $.trim(params.term),
                };
                },
                processResults: function (response) {
                return {
                    results: response
                };
                },
                cache: true
            }
        });
        @if(old('generic'))
            @foreach(old('generic') as $generic_id)
            @php
                $generic = \App\Models\Inventory\Generic::find($generic_id);
            @endphp
            var generic_option = new Option("{{ $generic->name }}","{{ $generic->id }}", true, true);
            $('.select2-generic').append(generic_option).trigger('change');
            @endforeach
        @endif
    



    $(document).on('change','.upload-img-add',function(){
        var files = $(this).get(0).files;
        var reader = new FileReader();
        reader.readAsDataURL(files[0]);
        var arg=this;
        reader.addEventListener("load", function(e) {
            var image = e.target.result;
            $(arg).parent().find('.display-upload-img-add').attr('src', image);
        });
    });



    $(document).on('click','.minus-btn-data',function(){
        $(this).parent().parent().remove();
    });


    $(document).on("click", ".js-add-p-attribute", function () {
        var id = $(this).attr("data-id");
        var b_id = $('#business_id').val();
        console.log(b_id);
        if(b_id == "" || b_id == null){
            alert('select business');
            return false;
        }
        $.ajax({
            type: "GET",
            url: '{{ url('/') }}' + "/admin/addNewProductAttribute",
            data: { is_new: 1, id: 0,b_id:b_id },
            success: function (response) {
                // console.log(response);
                $("#DataEntry_formId").show();
                $("#ajax-attribute-list").append(response);
                var msgType = response.msgType;
                var msg = response.msg;
                // if (msgType == "success") {
                //     onSuccessMsg(msg);
                // } else {
                //     onErrorMsg(msg);
                // }
            },
        });
    });
    $(document).on("click", ".attribute-set-remove", function () {
        $(this).parent().parent().parent().parent().remove();
    });
     $(document).on("change", ".p-attribute", function () {
        console.log(this);
        var id = $(this).val();
        var res = this;
        $.ajax({
            type: "GET",
            url: "{{ url('/') }}" + "/admin/getAttributeValue",
            data: { id: id },
            success: function (response) {
                // console.log(id);
                console.log(response);
                if (response.status != "no") {
                    $(res)
                        .parent()
                        .parent()
                        .parent()
                        // .parent()
                        .find(".p-attribute-value")
                        .html(response.data);
                    $(res)
                        .parent()
                        .parent()
                        .parent()
                        .find(".p-attribute-value")
                        .attr("name", response.set_name);
                }
            },
        });
    });
    @if( $errors->count() > 0)
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
    @if(Session::has('errors'))
        @foreach (Session::get('errors') as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
@endsection
