@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Payroll</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection


@section('content')

<div class="content-area">
  <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
      
      <div class="d-flex justify-content-between align-items-center">
          <h5 style="font-size: 0.875rem; margin:0;">Payroll</h5>
          <div class="d-flex" style="gap:10px;">
              @if($viewAll->count() == 0)
                <a href="{{ route('addPayroll') }}" class="btn btn-info btn-sm float-right">Add Payroll</a>
              @endif
          </div>
      </div>
          
  </div>
  <div class="row" style="padding-top: 24px;">
      <div class="col-md-12 col-lg-12 col-sm-12">
          <div class="card">
              <div class="card-body">
                @php
                  $p_edit = can_p('editPayroll');
                @endphp
                <table class="table cell-border" id="tablePayrollSetup">
                  <thead>
                    <tr>
                      <th scope="col">SL</th>
                      <th scope="col">House Rent (%)</th>
                      <th scope="col">Medical Cost (%)</th>
                      <th scope="col">Transport Costt (%)</th>
                      <th scope="col">Tax (%)</th>
                      <th scope="col">Provident Fund (%)</th>

                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>


                      @foreach ($viewAll as $i=>$data)
                        <tr>
                          <th scope="row">{{ $i+1 }}</th>
                          <th scope="row">{{$data->house_rent}}</th>
                          <th scope="row">{{$data->medical_cost}}</th>
                          <th scope="row">{{$data->transport_cost}}</th>
                          <th scope="row">{{$data->tax}}</th>
                          <th scope="row">{{$data->provident_fund}}</th>

                          <td>
                              @if($p_edit)
                              <a class="btn btn-primary" href="{{ route('editPayroll',$data->id) }}">
                                <i class="bx bx-edit"></i>
                              </a>
                              @endif
                              
                          </td>
                        </tr>
                      @endforeach



                  </tbody>
                </table>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function(){
  $('#tablePayrollSetup').DataTable({
    "searching":false,
    "paging":   false,
        "ordering": false,
        "info":     false,
    "dom": 'rtip'
  });
});
</script>

<!-- </div> -->
@endsection
