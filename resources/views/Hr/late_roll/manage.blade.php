@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Late Roll</title>
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
        <h5 style="font-size: 0.875rem; margin:0;">Late Roll</h5>
        <div class="d-flex" style="gap:10px;">
          @if($viewAll->count() == 0)
            <a href="{{ route('addLateRoll') }}" class="btn btn-primary float-right">Add Late Roll</a>
          @endif
        </div>
      </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                  @php
                    $p_edit = can_p('editLateRoll');
                  @endphp
                    
                  <table class="table table-striped" id="dataTable">
                    <thead>
                      <tr>
                        <th scope="col">SL</th>
                        <th scope="col">Late</th>
                        <th scope="col">Absent</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>

                        <tr>
                        @foreach ($viewAll as $i=>$data)
                          <tr>
                            <th scope="row">{{ $i+1 }}</th>
                            <th scope="row">{{$data->late}}</th>
                            <th scope="row">{{$data->absent}}</th>
                            <td>
                              @if($p_edit)
                                <a href="{{ route('editLateRoll', $data->id) }}" class="btn btn-primary">
                                    <i class="bx bx-edit"></i>
                                </a>
                              @endif
                                {{-- <a href="{{ url('deleteLateRoll') }}/{{ $data->id }}" class="btn text-danger bg-white">
                                    <i class="icon ion-trash-a tx-28"></i>
                                </a> --}}
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
