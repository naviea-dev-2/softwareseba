@section('head')
<title>Admin - Site Setting</title>

@endsection

@extends('admin.inc.master')

 @section('content')

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">


        <div class="br-pagebody">
          <div class="br-section-wrapper">
            <h6 class="br-section-label text-center mb-4">Site Setting</h6>
            @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
            @endif
            <!----- Start Add Category Form input ------->
            <div class="col-xl-12 mx-auto">
                <div class="form-layout form-layout-4 py-5">

                    <form action="{{ route('backend.setting.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="row col-sm-4">
                                <label class="col-sm-5 form-control-label">Logo: <span class="tx-danger"></span></label>
                                <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                    <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 76px;">
                                        <img class="display-upload-img" style="width: 76px;height: 70px;" src="{{ $site_setting->header_image == '' ? $site_setting->no_image : $site_setting->header_image_show}}" alt="">
                                            <input type="file" name="header_logo" class="form-control upload-img" placeholder="Enter Activity Image"
                                            style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                    </div>
                                </div>
                            </div><!-- row -->


                            <div class="row col-sm-4">
                                <label class="col-sm-5 form-control-label">Favicon: <span class="tx-danger"></span></label>
                                <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                    <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 76px;">
                                        <img class="display-upload-img" style="width: 76px;height: 70px;" src="{{ $site_setting->favicon == '' ? $site_setting->no_image : $site_setting->favicon_show}}" alt="">
                                            <input type="file" name="favicon" class="form-control upload-img" placeholder="Enter Activity Image"
                                            style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                    </div>
                                </div>
                            </div><!-- row -->
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <label class=" form-control-label">Company Name:<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" value="{{ $site_setting->company_name ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Email:<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="email" class="form-control" placeholder="Enter Email" value="{{ $site_setting->email1 ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Help Phone:<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="phone" class="form-control" placeholder="Enter Help Phone" value="{{ $site_setting->phone1 ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Company Establish Year:<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="company_establish_year" class="form-control" placeholder="Enter Establish Year" value="{{ $site_setting->company_establish_year ?? '' }}">
                                </div>
                            </div>



                            <div class="col-sm-6">
                                <label class=" form-control-label">Copy Right's' Text:<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="right_text"  value="{{ $site_setting->right_text ?? '' }}" class="form-control" placeholder="Enter Copy Rights">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Software Service Slogan:<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="software_service_slogan" class="form-control" placeholder="Software Service Slogan" value="{{ $site_setting->software_service_slogan ?? '' }}">
                                </div>
                            </div>

                        </div><!-- row -->
                        <hr/>
                        <h2>Social Link</h2>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class=" form-control-label">Facebook<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="facebook" class="form-control" placeholder="Facebook Link" value="{{ $site_setting->facebook ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Twitter<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="twitter" class="form-control" placeholder="Twitter Link" value="{{ $site_setting->twitter ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Instagram<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="instagram" class="form-control" placeholder="Instagram Link" value="{{ $site_setting->instagram ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Youtube Link<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="youtube" class="form-control" placeholder="Youtube Link" value="{{ $site_setting->youtube ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Linkedin Link<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="linkedin" class="form-control" placeholder="Linkedin Link" value="{{ $site_setting->linkedin ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class=" form-control-label">Google Link<span class="tx-danger"></span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                <input type="text" name="google" class="form-control" placeholder="Google Link" value="{{ $site_setting->google ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <h2>Software Service List</h2>

                        @if($software_services->count() > 0)
                            <div id="soft_service_add">
                                @foreach ($software_services as $k=>$software_service)
                                    <div class="row align-items-end ">
                                        <div class="col-md-4">
                                            <label><b>Title</b></label>
                                            <input type="text" name="old_title[{{ $software_service->id }}]" class="form-control" value="{{ $software_service->title }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label><b>Url</b></label>
                                            <input name="old_url[{{ $software_service->id }}]" type="text" class="form-control" value="{{ $software_service->url }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label><b>Icon Class</b></label>
                                            <input type="text" name="old_icon_class[{{ $software_service->id }}]" class="form-control" value="{{ $software_service->icon_class }}">
                                        </div>
                                        <div class="col-md-1">
                                            @if($k == $software_services->count()-1)
                                            <a class="add_soft_service btn btn-primary" href="javascript:void(0);"><i class="bx bx-plus"></i></a>
                                            @else
                                             <a data-id="{{ $software_service->id }}" class="old_delete_soft_service btn btn-danger" href="javascript:void(0);"><i class="bx bx-trash"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            <div id="soft_service_add"></div>
                            <div class="row align-items-end ">
                                <div class="col-md-4">
                                    <label><b>Title</b></label>
                                    <input name="title[]" type="text" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label><b>Url</b></label>
                                    <input name="url[]" type="text" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label><b>Icon Class</b></label>
                                    <input name="icon_class[]" type="text" class="form-control">
                                </div>
                                <div class="col-md-1">
                                <a class="add_soft_service btn btn-primary" href="javascript:void(0);"><i class="bx bx-plus"></i></a>
                                </div>
                            </div>
                        @endif
                        <div class="row mt-4">
                          <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right">
                            <button type="submit" class="btn btn-info ">Save</button>
                          </div>
                        </div>
                    </form>

                </div><!-- form-layout -->
            </div><!-- col-6 -->
            <!----- Start Add Category Form input ------->
          </div><!-- br-section-wrapper -->
        </div><!-- br-pagebody -->

    </div><!-- br-mainpanel -->
    <!-- ########## END: MAIN PANEL ########## -->







@endsection
@section('script')
<script>
    $('.add_soft_service').on('click',function(){

        var myvar = '<div class="row align-items-end ">'+
        '                            <div class="col-md-4">'+
        '                                <label><b>Title</b></label>'+
        '                                <input type="text" name="title[]" class="form-control">'+
        '                            </div>'+
        '                            <div class="col-md-4">'+
        '                                <label><b>Url</b></label>'+
        '                                <input name="url[]" type="text" class="form-control">'+
        '                            </div>'+
        '                            <div class="col-md-3">'+
        '                                <label><b>Icon Class</b></label>'+
        '                                <input name="icon_class[]" type="text" class="form-control">'+
        '                            </div>'+
        '                            <div class="col-md-1">'+
        '                               <a class="delete_soft_service btn btn-danger" href="javascript:void(0);"><i class="bx bx-trash"></i></a>'+
        '                            </div>'+
        '                        </div>';
        $('#soft_service_add').prepend(myvar);

    });
    $(document).on('click','.delete_soft_service',function(){
        $(this).parent().parent().remove();
    });
     $(document).on('click','.old_delete_soft_service',function(){
        var id=$(this).attr('data-id');
        $(this).parent().parent().parent().append('<input type="hidden" name="del_software_service[]" value="'+id+'">');
        $(this).parent().parent().remove();
    });
        $(document).on('change','.upload-img',function(){
            var files = $(this).get(0).files;
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            var arg=this;
            reader.addEventListener("load", function(e) {
                var image = e.target.result;
                $(arg).parent().find('.display-upload-img').attr('src', image);
            });
        });
</script>
@endsection
