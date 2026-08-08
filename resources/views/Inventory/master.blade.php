@include('Inventory.headerLink')
@php
    $prefix=Request::route()->getPrefix();
    $route=Route::current()->getName();
@endphp
@include('Inventory.sideBar')
@include('Inventory.header')


    @yield('content')

@include('Inventory.footer')
 <script type="text/javascript">
    function myFunction() {
      document.getElementById("myDropdown").classList.toggle("show");
    }
    @if(Session::has('message'))
        var type="{{Session::get('alert-type','info')}}"
        switch(type){
            case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
            case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
            case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
            }
    @endif
</script>
<!-- delete -->
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#delete',function(){
            //e.preventDefault();
            var actionTo=$(this).attr('href');
            var token=$(this).attr('data-token');
            var id=$(this).attr('data-id');
            swal({
                title:"Are you sure?",
                type:"success",
                showCancelButton:true,
                confirmButtonText:'Yes',
                cancelButtonText:"No",
                closeOnConfirm:false,
                closeOnCancel:false
            },
            function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        url:actionTo,
                        type:'post',
                        data:{id:id,_token:token},
                        success:function(data){
                            swal({
                                title:"Deleted!",
                                type:"success"
                            },
                            function(isConfirm){
                                if(isConfirm){
                                    $('.'+id).fadeOut();
                                }
                            });
                        }
                    });
                }else{
                    swal("Cancel"," ","error");
                }
            });
            return false;
        });

    });
</script>
@include('HRandPayroll.footerLink')
