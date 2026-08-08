@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Attendance Setting</title>
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
        <h5 style="font-size: 0.875rem; margin:0;">Attendance Setting</h5>
        <div class="d-flex" style="gap:10px;">
          @if($viewAll->count() == 0)
            <a href="{{ route('attendance_setting.add') }}" class="btn btn-primary float-right">Add Attendance Setting</a>
          @endif
        </div>
      </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                     @php
                        $p_edit = can_p('attendance_setting.edit');
                    @endphp
                    <table class="table table-bordered table-light" id="dataTable">
                      <thead>
                        <tr>
                          {{-- <th scope="col">SL</th> --}}
                          <th scope="col">Delay Time</th>
                          <th scope="col">Attendance Last Entry Time</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody id="dataTable" >

                          <tr>
                          @foreach ($viewAll as $i=>$data)
                            <tr>
                              {{-- <th scope="row">{{ $i+1 }}</th> --}}
                              @php
                                $st_arr=explode(":",$data->delay_time);
                                if($st_arr[0] > 12){
                                    $st_time = $st_arr[0] - 12 .":".$st_arr[1]." PM";
                                }else{
                                    $st_time =$data->delay_time.' AM';
                                }
                                $et_arr=explode(":",$data->last_entry_time);
                                if($et_arr[0] > 12){
                                    $et_time = $et_arr[0] - 12 .":".$et_arr[1]." PM";
                                }else{
                                    $et_time =$data->last_entry_time.' AM';
                                }
                              @endphp
                              <th scope="row">{{ $st_time }}</th>
                              <th scope="row">{{ $et_time }}</th>
                              <td>
                                @if($p_edit)
                                  <a class="btn text-primary" href="{{ route('attendance_setting.edit',$data->id) }}">
                                    <i class="bx bx-edit"></i>
                                  </a>
                                @endif
                              </td>
                            </tr>
                          @endforeach

                          </tr>

                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
