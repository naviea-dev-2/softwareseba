

            <script type="text/javascript">
              var mainurl = "https://geniuscart.royalscripts.com";
              var admin_loader = 1;
              var whole_sell = 6;
              var getattrUrl = 'https://geniuscart.royalscripts.com/admin/getattributes';
              var curr = {"id":1,"name":"USD","sign":"$","value":"1","is_default":"1"};
              // console.log(curr);
            </script>



          <!-- Dashboard Core -->
          <script src="{{asset('public/Dashboard/jquery-1.12.4.min.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/vue.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/bootstrap.min.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/jqueryui.min.js.download')}}"></script>
          <!-- Fullside-menu Js-->
          <script src="{{asset('public/Dashboard/jquery.slimscroll.min.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/waves.min.js.download')}}"></script>

          <script src="{{asset('public/Dashboard/plugin.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/Chart.min.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/tag-it.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/nicEdit.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/bootstrap-colorpicker.min.js.download')}}"></script>
          <script src="{{asset('public/Dashboard/notify.js.download')}}"></script>

          <script src="{{asset('public/Dashboard/jquery.canvasjs.min.js.download')}}"></script>

          <script src="{{asset('public/Dashboard/load.js.download')}}"></script>
          <!-- Custom Js-->
          <script src="{{asset('public/Dashboard/custom.js.download')}}"></script>
          <!-- AJAX Js-->
          <script src="{{asset('public/Dashboard/myscript.js.download')}}"></script>




  </div>
    <!-- ========= Menu Toggle Script -->
    <script>
    function openNav() {
      document.getElementById("mySidebar").style.width = "250px";
      document.getElementById("main").style.marginLeft = "228px";
    }

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
    </script>



<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>

<!-- swal alert -->
<script src="{{asset('public/Dashboard/SweetAlert/sweetalert.min.js')}}"></script>

<!-- start bootstrap  data Table -->
<script>
  $(document).ready(function() {
    $('#dataTable').DataTable();
  } );
</script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<!-- end bootstrap data table -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
@yield('script')

</body>
</html>
