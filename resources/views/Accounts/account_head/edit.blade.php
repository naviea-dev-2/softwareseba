@extends('inc.master')

@section('head')

<title>Edit Account Head</title>
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
                            <h3 class="card-title">Edit Account Head</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{ route('account_head.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">


                                <div class="row">


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Accounting Head </label>
                                            <input type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title') ?? $data->title}}" >
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
                                                @if(old('ac_type'))
                                                <option @if(old('ac_type')  == 1) selected @endif value="1">Assets</option>
                                                <option @if(old('ac_type')  == 2) selected @endif value="2">Liability</option>
                                                <option @if(old('ac_type')  == 3) selected @endif value="3">Equity</option>
                                                <option @if(old('ac_type')  == 4) selected @endif value="4">Income</option>
                                                <option @if(old('ac_type')  == 5) selected @endif value="5">Expense</option>
                                                @else
                                                <option @if($data->ac_type == 1) selected @endif value="1">Assets</option>
                                                <option @if($data->ac_type == 2) selected @endif value="2">Liability</option>
                                                <option @if($data->ac_type == 3) selected @endif value="3">Equity</option>
                                                <option @if($data->ac_type == 4) selected @endif value="4">Income</option>
                                                <option @if($data->ac_type == 5) selected @endif value="5">Expense</option>
                                                @endif
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
                                            <input type="number" name="opening_balance" class="form-control {{ $errors->has('opening_balance') ? 'is-invalid' : '' }}"  value="{{ old('opening_balance') ?? $data->transaction ? $data->transaction->amount : 0 }}">
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
                                            <textarea name="note"  cols="10" rows="2" class="form-control">{{ old('note') ?? $data->note }}</textarea>
                                        </div>
                                    </div>




                                </div>


                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- select -->
                                        <div class="form-group">
                                            <label> Status</label>
                                            <select class="form-control custom-select" name="status">
                                                <option value="1" @php if ($data['status'] == 1) { echo "selected"; } @endphp>Active</option>
                                                <option value="0" @php if ($data['status'] == 0) { echo "selected"; } @endphp>Inactive</option>
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

@endsection
@section('script')
@endsection
