@extends('BackEnd.layouts.master')

@section('title', 'Hire Agent')

<link rel="stylesheet" href="css/custom/class.css">

@section('content')

<div class="br-mainpanel">

      <!-- <div class="br-pagetitle">
        <i class="fa-duotone fa-screen-users"></i>
        <div>
          <h4>Add an agent</h4>
          <p class="mg-b-0">Add a new Agent</p>
        </div>
      </div>d-flex -->


        <!-- <div class="p-5"> -->

            <div class="br-section-wrapper">

            <div class="text-center mb-4">
                    <h1 class="">Add Agent</h1>
                    <br>
                </div>

                <form action="/storeAgentData" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Agent Name : </label>
                                <input type="text"
                                class="form-control" name="name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Agent Email : </label>
                                <input type="email"
                                class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>License Number : </label>
                                <input type="text"
                                class="form-control" name="license">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mobile Number : </label>
                                <input type="number"
                                class="form-control" name="phone">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Education : </label>
                                <input type="text"
                                class="form-control" name="education">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Specialist : </label>
                                <input type="text"
                                class="form-control" name="specialist">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Agent Password : </label>
                                <input type="password" placeholder="Type a password for this agent"
                                class="form-control" name="password">
                            </div>
                        </div>
                    </div>

                        {{-- <div class="col-md-4">
                            <label for="">Status : </label>
                            <select name="status" id="" class="form-control">
                                <option value="">-----Select Status-------</option>
                                <option value="active">Active</option>
                                <option value="deactive">Deactive</option>
                            </select>
                        </div>
                    </div> --}}
                    {{-- <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description : </label>
                                <textarea id="editor"
                                class="form-control" name="description"></textarea>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <button type="submit" class="btn btn-info"
                        style="width:30%;margin:0 auto; margin-top:30px;">
                        Create a new agent</button>
                    </div>
                </form>
        <!-- </div> -->

    </div>
</div>
@stop

