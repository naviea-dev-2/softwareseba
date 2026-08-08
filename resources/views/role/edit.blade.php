@extends('inc.master')

@section('head')

<title>Role Edit</title>
<style>

</style>
@endsection

@section('content')

<Form action="{{ route('bussiness.role.edit',$role->id) }}" method="POST" class="form form-horizontal custom-form-horizontal" enctype="multipart/form-data">
    @csrf
<div class="card">
  <div class="card-header">
    <h4 class="card-title">Edit Role</h4>
  </div>
  <hr class="my-1">
  <div class="card-content">
    <div class="card-body">
        
        <div class="row mt-2">
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label"> Name *</Label>
                    <input type="text" name="name" value="{{ old('name',$role->name) }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name">

                    @if ($errors->has('name'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Is Admin</Label>
                    <select class="form-control " id="is_admin" name="is_admin" required>
                        <option @if($role->is_admin == 0) selected @endif value="0">No</option>
                        <option @if($role->is_admin == 1) selected @endif value="1">YES</option>
                    </select>
                </div>   
            </div>
          
        </div>
        <h5 class="mt-3">Permission Access Menu</h5>
        @php
            $permissions = $role->permissions;
        @endphp
        @foreach($menus as $cap_menu)
            <div>
                <label for="cap_menu_id_{{$cap_menu->id}}"><strong>{{ $cap_menu->name }}</strong><label>
                @if($permissions->where('permission_id',$cap_menu->id)->count() > 0)
                <input checked  id="cap_menu_id_{{$cap_menu->id}}" class="cap-menu menu_old cap_select_{{$cap_menu->id}}" type="checkbox" value="{{ $cap_menu->id }}" name="old_menu_permission[]" >
                @else
                <input id="cap_menu_id_{{$cap_menu->id}}" class="cap-menu cap_select_{{$cap_menu->id}}" type="checkbox" value="{{ $cap_menu->id }}" name="menu_permission[]" >
                @endif
            </div>
            @foreach($cap_menu->child_menus as $m_menu)
                @if($m_menu->parent_menu == 1)
                    <div style="margin-left:10px;">
                        <label for="m_menu_id_{{$m_menu->id}}"><strong>{{ $m_menu->name }}</strong></label>
                        @if($permissions->where('permission_id',$m_menu->id)->count() > 0)
                        <input checked id="m_menu_id_{{$m_menu->id}}" class="m-menu cap_m_select_{{$cap_menu->id}} menu_old m_select_{{$m_menu->id}}" cap_id="{{$cap_menu->id}}" type="checkbox" value="{{ $m_menu->id }}" name="old_menu_permission[]" >
                        @else
                        <input id="m_menu_id_{{$m_menu->id}}" class="m-menu cap_m_select_{{$cap_menu->id}} m_select_{{$m_menu->id}}" cap_id="{{$cap_menu->id}}" type="checkbox" value="{{ $m_menu->id }}" name="menu_permission[]" >
                        @endif
                        <i class="bx bx-plus m-menu-expand" m_id="{{$m_menu->id}}" style="font-size: 16px;font-weight: 700;cursor: pointer;"></i>
                    </div>
                    <div class="m-menu-sub-{{$m_menu->id}}" style="display:none;">
                    @foreach($m_menu->child_menus as $sub_menu)
                        <div style="margin-left:20px;">
                            @if($permissions->where('permission_id',$sub_menu->id)->count() > 0)
                            <input  checked id="sub_menu_id_{{$sub_menu->id}}" class="sub-menu cap_sub_select_{{$cap_menu->id}} menu_old m_sub_select_{{$m_menu->id}} sub_select_{{$sub_menu->id}}" m_id="{{$m_menu->id}}" cap_id="{{$cap_menu->id}}" type="checkbox" value="{{ $sub_menu->id }}" name="old_menu_permission[]" >
                            @else 
                            <input id="sub_menu_id_{{$sub_menu->id}}" class="sub-menu cap_sub_select_{{$cap_menu->id}} m_sub_select_{{$m_menu->id}} sub_select_{{$sub_menu->id}}" m_id="{{$m_menu->id}}" cap_id="{{$cap_menu->id}}" type="checkbox" value="{{ $sub_menu->id }}" name="menu_permission[]" >
                            @endif 
                            <label for="sub_menu_id_{{$sub_menu->id}}">{{ $sub_menu->name }}</label>
                            @if($sub_menu->parent_menu == 1)
                                <i class="bx bx-plus sub-menu-expand sub-menu-t-{{$m_menu->id}}" sub_id="{{$sub_menu->id}}" style="font-size: 16px;font-weight: 700;cursor: pointer;"></i>
                                <div class="sub-menu-child-{{$sub_menu->id}} sub-menu-child-t-{{$m_menu->id}}" style="display:none;">
                                    @foreach($sub_menu->child_menus as $child_menu)
                                        <div style="margin-left:30px;">
                                        @if($permissions->where('permission_id',$child_menu->id)->count() > 0)
                                        <input checked id="child_menu_id_{{$child_menu->id}}" class="child-menu cap_child_select_{{$cap_menu->id}} m_child_select_{{$m_menu->id}} sub_child_select_{{$sub_menu->id}} menu_old child_select_{{$sub_menu->id}}" sub_id="{{$sub_menu->id}}" m_id="{{$m_menu->id}}" cap_id="{{$cap_menu->id}}" type="checkbox" value="{{ $child_menu->id }}" name="old_menu_permission[]" > 
                                        @else
                                        <input id="child_menu_id_{{$child_menu->id}}" class="child-menu cap_child_select_{{$cap_menu->id}} m_child_select_{{$m_menu->id}} sub_child_select_{{$sub_menu->id}} child_select_{{$sub_menu->id}}" sub_id="{{$sub_menu->id}}" m_id="{{$m_menu->id}}" cap_id="{{$cap_menu->id}}" type="checkbox" value="{{ $child_menu->id }}" name="menu_permission[]" > 
                                        @endif
                                        <label for="child_menu_id_{{$child_menu->id}}">{{ $child_menu->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                    </div>
                @else 
                    <div style="margin-left:10px;">
                        <label for="m_menu_id_{{$m_menu->id}}"><strong>{{ $m_menu->name }}</strong></label>
                        @if($permissions->where('permission_id',$m_menu->id)->count() > 0)
                        <input checked id="m_menu_id_{{$m_menu->id}}" class="m-menu cap_m_select_{{$cap_menu->id}} menu_old m_select_{{$m_menu->id}}" type="checkbox" value="{{ $m_menu->id }}" name="old_menu_permission[]">
                        @else
                        <input id="m_menu_id_{{$m_menu->id}}" class="m-menu cap_m_select_{{$cap_menu->id}} m_select_{{$m_menu->id}}" type="checkbox" value="{{ $m_menu->id }}" name="menu_permission[]">
                        @endif
                    </div>
                @endif
            @endforeach
        @endforeach
        <div id="delete_menu_container"></div>
        <div class="card-btns mt-4 mb-2 me-2" style="text-align: right;">
            <button type="submit" class="btn btn-primary" >Update</button>
        </div>
    </div><!--/.card-body-->
  </div><!--/.card-content-->
</div><!--/.card-->
</Form>
@endsection

@section('script')
    <script>
        $(document).on('change','.cap-menu',function(){
            var cap_id = $(this).val();
            if($(this).is(":checked")){
                $(document).find('.cap_m_select_'+cap_id).prop( "checked", true );
                $(document).find('.cap_sub_select_'+cap_id).prop( "checked", true );
                $(document).find('.cap_child_select_'+cap_id).prop( "checked", true );
                if($(this).hasClass('menu_old')){
                    $('#del_menu_id_'+$(this).val()).remove();
                    $(document).find('.cap_m_select_'+cap_id).each(function(){
                        $('#del_menu_id_'+$(this).val()).remove();
                    });
                    $(document).find('.cap_sub_select_'+cap_id).each(function(){
                        $('#del_menu_id_'+$(this).val()).remove();
                    });
                    $(document).find('.cap_child_select_'+cap_id).each(function(){
                        $('#del_menu_id_'+$(this).val()).remove();
                    });
                }
            }else{
                if($(this).hasClass('menu_old')){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                }
                $(document).find('.cap_m_select_'+cap_id).each(function(){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                });
                $(document).find('.cap_sub_select_'+cap_id).each(function(){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                });
                $(document).find('.cap_child_select_'+cap_id).each(function(){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                });
                $(document).find('.cap_m_select_'+cap_id).prop( "checked", false );
                $(document).find('.cap_sub_select_'+cap_id).prop( "checked", false );
                $(document).find('.cap_child_select_'+cap_id).prop( "checked", false );
            }
        });
        $(document).on('change','.m-menu',function(){
            var m_id = $(this).val();
            var cap_id = $(this).attr('cap_id');
            if($(this).is(":checked")){
                $(document).find('.cap_select_'+cap_id).prop( "checked", true );
                $(document).find('.m_sub_select_'+m_id).prop( "checked", true );
                $(document).find('.m_child_select_'+m_id).prop( "checked", true );
                if($(this).hasClass('menu_old')){
                    $('#del_menu_id_'+$(this).val()).remove();
                    $(document).find('.m_child_select_'+m_id).each(function(){
                        $('#del_menu_id_'+$(this).val()).remove();
                    });
                    $(document).find('.m_sub_select_'+m_id).each(function(){
                        $('#del_menu_id_'+$(this).val()).remove();
                    });
                    $('#del_menu_id_'+$(document).find('.cap_select_'+cap_id).val()).remove();
                }
            }else{
                if($(this).hasClass('menu_old')){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                }
                $(document).find('.m_child_select_'+m_id).each(function(){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                });
                $(document).find('.m_sub_select_'+m_id).each(function(){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                });
                $(document).find('.m_child_select_'+m_id).prop( "checked", false );
                $(document).find('.m_sub_select_'+m_id).prop( "checked", false );
                if($('.cap_m_select_'+cap_id+':checked').length == 0){
                    $(document).find('.cap_select_'+cap_id).prop( "checked", false );
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(document).find('.cap_select_'+cap_id).val()+'" name="delete_menu[]" value="'+$(document).find('.cap_select_'+cap_id).val()+'">');
                }
               
                
            }
        });
        $(document).on('change','.sub-menu',function(){
            var m_id = $(this).attr('m_id');
            var cap_id = $(this).attr('cap_id');
            var sub_id = $(this).val();
            //console.log($('.cap_sub_select_'+cap_id+':checked'));
            if($(this).is(":checked")){
                $(document).find('.cap_select_'+cap_id).prop( "checked", true );
                $(document).find('.m_select_'+m_id).prop( "checked", true );
                $(document).find('.sub_child_select_'+sub_id).prop( "checked", true );
                if($(this).hasClass('menu_old')){
                    $('#del_menu_id_'+$(this).val()).remove();
                    $(document).find('.sub_child_select_'+sub_id).each(function(){
                        $('#del_menu_id_'+$(this).val()).remove();
                    });
                    
                    $('#del_menu_id_'+$(document).find('.m_select_'+m_id).val()).remove();
                    $('#del_menu_id_'+$(document).find('.cap_select_'+cap_id).val()).remove();
                }
            }else{
                if($(this).hasClass('menu_old')){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                }
                $(document).find('.sub_child_select_'+sub_id).each(function(){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                });
                $(document).find('.sub_child_select_'+sub_id).prop( "checked", false );
                if($('.m_sub_select_'+m_id+':checked').length == 0){
                    $(document).find('.m_select_'+m_id).prop( "checked", false );
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(document).find('.m_select_'+m_id).val()+'" name="delete_menu[]" value="'+$(document).find('.m_select_'+m_id).val()+'">');
                }
                if($('.cap_sub_select_'+cap_id+':checked').length == 0){
                    $(document).find('.cap_select_'+cap_id).prop( "checked", false );
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(document).find('.cap_select_'+cap_id).val()+'" name="delete_menu[]" value="'+$(document).find('.cap_select_'+cap_id).val()+'">');
                }
                
            }
        });
        $(document).on('change','.child-menu',function(){
            var m_id = $(this).attr('m_id');
            var cap_id = $(this).attr('cap_id');
            var sub_id = $(this).attr('sub_id');
            //console.log($('.cap_sub_select_'+cap_id+':checked'));
            if($(this).is(":checked")){
                $(document).find('.cap_select_'+cap_id).prop( "checked", true );
                $(document).find('.m_select_'+m_id).prop( "checked", true );
                $(document).find('.sub_select_'+sub_id).prop( "checked", true );
                if($(this).hasClass('menu_old')){
                    $('#del_menu_id_'+$(this).val()).remove();
                    $('#del_menu_id_'+$(document).find('.sub_select_'+sub_id).val()).remove();
                    $('#del_menu_id_'+$(document).find('.m_select_'+m_id).val()).remove();
                    $('#del_menu_id_'+$(document).find('.cap_select_'+cap_id).val()).remove();
                }
            }else{
                if($(this).hasClass('menu_old')){
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(this).val()+'" name="delete_menu[]" value="'+$(this).val()+'">');
                }
                if($('.sub_child_select_'+sub_id+':checked').length == 0){
                    $(document).find('.sub_select_'+sub_id).prop( "checked", false );
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(document).find('.sub_select_'+sub_id).val()+'" name="delete_menu[]" value="'+$(document).find('.sub_select_'+sub_id).val()+'">');
                }
                if($('.m_sub_select_'+m_id+':checked').length == 0){
                    $(document).find('.m_select_'+m_id).prop( "checked", false );
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(document).find('.m_select_'+m_id).val()+'" name="delete_menu[]" value="'+$(document).find('.m_select_'+m_id).val()+'">');
                }
                if($('.cap_sub_select_'+cap_id+':checked').length == 0){
                    $(document).find('.cap_select_'+cap_id).prop( "checked", false );
                    $('#delete_menu_container').append('<input type="hidden" id="del_menu_id_'+$(document).find('.cap_select_'+cap_id).val()+'" name="delete_menu[]" value="'+$(document).find('.cap_select_'+cap_id).val()+'">');
                }
                
               // console.log($('.sub_child_select_'+sub_id+':checked'));
               
            }
        });
        $(document).on('click','.m-menu-expand',function(){
            var m_id = $(this).attr('m_id');
            if($(this).hasClass('bx-plus')){
                $('.m-menu-sub-'+m_id).show();
                $(this).removeClass('bx-plus');
                $(this).addClass('bx-minus');
            }else{
                $(this).removeClass('bx-minus');
                $(this).addClass('bx-plus');
                $('.m-menu-sub-'+m_id).hide();
                $('.sub-menu-child-t-'+m_id).hide();
                $(document).find('.sub-menu-t-'+m_id).removeClass('bx-minus');
                $(document).find('.sub-menu-t-'+m_id).addClass('bx-plus');
            }
            
        });
        $(document).on('click','.sub-menu-expand',function(){
            var sub_id = $(this).attr('sub_id');
            if($(this).hasClass('bx-plus')){
                $('.sub-menu-child-'+sub_id).show();
                $(this).removeClass('bx-plus');
                $(this).addClass('bx-minus');
            }else{
                $(this).removeClass('bx-minus');
                $(this).addClass('bx-plus');
                $('.sub-menu-child-'+sub_id).hide();
            }
            
        });
    </script>
    
@endsection

