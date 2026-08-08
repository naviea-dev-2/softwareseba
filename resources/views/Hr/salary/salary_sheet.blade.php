@extends('inc.master')

@section('head')

<title> Salary Report</title>
<style>
    label{
        /* font-size: 1.2rem; */
    }
</style>
@endsection


@section('content')
<div class="br-mainpanel">

  {{ $salaryData }}

</div>
<script>
$(document).ready(function(){
    $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#dataTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/alfrcr/paginathing/dist/paginathing.min.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function ($) {
    const listElement = $('.list-group');

    for (let i = 1; i <= 1500; i++) {
      listElement.append('<li class="list-group-item"> Item ' + i + '</li>');
    }

    listElement.paginathing({
      perPage: 5,
      limitPagination: 9,
      containerClass: 'panel-footer mt-4',
      pageNumbers: true,
      ulClass: 'pagination flex-wrap justify-content-center',
    })

    $('.table tbody').paginathing({
      perPage: 10,
      insertAfter: '.table',
    //   pageNumbers: true,
      ulClass: 'pagination flex-wrap justify-content-center'
    });
  });

    function printDiv(){
        var mywindow = window.open();
        var content = document.getElementById("print").innerHTML;
        var realContent = document.body.innerHTML;
        mywindow.document.write(content);
        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
        document.body.innerHTML = realContent;
        mywindow.close();
        return true;
    }
</script>
<!-- </div> -->
@stop
