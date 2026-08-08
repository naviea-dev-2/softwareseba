@extends('inc.master')

@section('head')

<title>Add Account Head</title>
<style>
    /* label{
        font-size: 1.2rem;
    } */
</style>
@endsection
@section('content')



    <!-- Main content -->
    <section class="content-head">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="card-header">
                            <h3 class="card-title">Add Account Head</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('account_head.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">


                                <div class="row">


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Accounting Head </label>
                                            <input type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" id="exampleInputEmail1" value="{{ old('title') }}">
                                            @if ($errors->has('title'))
                                            <span class="invalid-feedback mb-0">
                                            <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                	<div class="col-md-6">
		                                <div class="form-group">
                                            <label for="exampleInputEmail1">Top Level Head</label>
                                            <select class="form-control selectric {{ $errors->has('ac_type') ? 'is-invalid' : '' }}" name="ac_type" required="">
                                                <option value="">Select Top Head</option>
                                                <option @if(old('ac_type')  == 1) selected @endif value="1">Assets</option>
                                                <option @if(old('ac_type')  == 2) selected @endif value="2">Liability</option>
                                                <option @if(old('ac_type')  == 3) selected @endif value="3">Equity</option>
                                                <option @if(old('ac_type')  == 4) selected @endif value="4">Income</option>
                                                <option @if(old('ac_type')  == 5) selected @endif value="5">Expense</option>

                                            </select>
                                            @if ($errors->has('ac_type'))
                                            <span class="invalid-feedback mb-0">
                                            <strong>{{ $errors->first('ac_type') }}</strong>
                                            </span>
                                            @endif
		                                </div>
                                	</div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Opening Balance </label>
                                            <input type="number" name="opening_balance" class="form-control {{ $errors->has('opening_balance') ? 'is-invalid' : '' }}" id="exampleInputEmail1" value="{{ old('opening_balance') }}">
                                            @if ($errors->has('opening_balance'))
                                            <span class="invalid-feedback mb-0">
                                            <strong>{{ $errors->first('opening_balance') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Note</label>
                                            <textarea name="note"  cols="10" rows="2" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Second Level Head</label>
                                            <select class="form-control selectric" name="parent_id">
                                                <option value="0">-- Select 2nd Level Head--</option>
                                                @foreach($sub_heads as $item)
                                                    <option value="{{$item->id}}">{{$item->account_title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  --}}


                                </div>


                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- select -->
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control custom-select" name="status">
                                                <option @if(old('status')  == '1') selected @endif value="1">Active</option>
                                                <option @if(old('status')  == '0') selected @endif value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@section('script')

@endsection
