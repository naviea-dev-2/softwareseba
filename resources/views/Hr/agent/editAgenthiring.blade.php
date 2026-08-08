@extends('BackEnd.layouts.master')

@section('title', 'Agent')

<link rel="stylesheet" href="css/custom/class.css">

@section('content')

<div class="br-mainpanel">

      <!-- <div class="br-pagetitle">
        <i class="fa-duotone fa-screen-users"></i>
        <div>
          <h4>Edit agent</h4>
          <p class="mg-b-0">Edit Agent Data</p>
        </div>
      </div>d-flex -->


        <!-- <div class="p-5"> -->

            <div class="br-section-wrapper">

            <div class="text-center mb-4">
                    <h1 class="">Edit Agent Data</h1>
                    <br>
                </div>
                @foreach ($agent_info as  $agent)

                <form action="/updateAgentInfo/{{ $agent->agent_id }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Agent Name : </label>
                                <input type="text"
                                class="form-control" name="name" value="{{ $agent->name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Agent Email : </label>
                                <input type="email"
                                class="form-control" name="email" value="{{ $agent->email }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>License Number : </label>
                                <input type="text"
                                class="form-control" name="license" value="{{ $agent->license }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mobile Number : </label>
                                <input type="number"
                                class="form-control" name="phone" value="{{ $agent->phone }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Education : </label>
                                <input type="text"
                                class="form-control" name="education" value="{{ $agent->education }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Specialist : </label>
                                <input type="text"
                                class="form-control" name="specialist" value="{{ $agent->specialist }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-info"
                        style="width:20%;margin:0 auto; margin-top:30px;">
                        Update Agent Info</button>
                    </div>
                </form>

                @endforeach

        <!-- </div> -->

    </div>
</div>
@stop

