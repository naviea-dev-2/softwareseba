@extends('inc.master')

@section('head')

<title>Edit Employee</title>
<style>
    /* label{
        font-size: 1.2rem;
    } */
    .select2-container .select2-selection--single{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear{
        height: 40px!important;
    }
</style>
@endsection

@section('content')

<div class="br-mainpanel">

      <!-- <div class="br-pagetitle">
        <i class="fa-duotone fa-screen-users"></i>
        <div>
          <h4>Edit Information</h4>
          <p class="mg-b-0">Edit Employee Information</p>
        </div>
      </div>d-flex -->


        <!-- <div class="p-5"> -->

            <div class="br-section-wrapper" style="background:#fff;">

                <div class="text-center mb-2">
                    <h5 class="">Edit Employee Information</h5>
                </div>
                {{-- @foreach ($employeeData as $data ) --}}
                @php
                    $data= $employeeData;
                @endphp
                <div class="border p-3">
                    <form action="{{ route('updateEmployee',$data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" value="0" name="id"  >
                            <div class="col-md-3 mt-2">
                                <label class=" form-control-label">Shift:</label>
                                <input type="hidden" name="h_shift" id="h_shift" value="{{ old('h_shift',$data->shift?->shiftName) }}"/>
                                <div class="{{ $errors->has('shift') ? 'is-invalid' : '' }}">
                                    <select  class="form-control" name="shift" id="shift">
                                        <option value="">Select Shift</option>

                                    </select>
                                </div>
                                @if ($errors->has('shift'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('shift') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3 mt-2">
                                <label class=" form-control-label">Department:</label>
                                <input type="hidden" name="h_department" id="h_department" value="{{ old('h_department',$data->designation?->name) }}"/>
                                <div class="{{ $errors->has('department') ? 'is-invalid' : '' }}">
                                    <select  class="form-control" name="department" id="department">
                                        <option value="">Select Department</option>

                                    </select>
                                </div>
                                @if ($errors->has('department'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('department') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3 mt-2">
                                <label class=" form-control-label">Designation:</label>
                                <input type="hidden" name="h_designation" id="h_designation" value="{{ old('h_designation',$data->department?->name) }}"/>
                                <div class="{{ $errors->has('designation') ? 'is-invalid' : '' }}">
                                    <select  class="form-control" name="designation" id="designation">
                                        <option value="">Select Designation</option>

                                    </select>
                                </div>
                                @if ($errors->has('designation'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('designation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        

                            <div class="col-md-3 mt-2">
                                <label for=""> Employee Name *</label>
                                <input type="text" class=" form-control {{ $errors->has('empName') ? 'is-invalid' : '' }}" name="empName" value="{{ old("empName",$data->employee_name) }}" autocomplete="off" >
                                @if ($errors->has('empName'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('empName') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for=""> Father's Name *</label>
                                <input type="text" class=" form-control {{ $errors->has('fName') ? 'is-invalid' : '' }}" name="fName" value="{{ old("empName",$data->father_name) }}" autocomplete="off" >
                                @if ($errors->has('fName'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('fName') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for=""> Mother's Name *</label>
                                <input type="text" class=" form-control {{ $errors->has('mName') ? 'is-invalid' : '' }}" name="mName" value="{{ old("mName",$data->mother_name) }}" autocomplete="off" >
                                @if ($errors->has('mName'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('mName') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Current Address *</label>
                                <textarea class="form-control {{ $errors->has('cAddress') ? 'is-invalid' : '' }}" name="cAddress" cols="10"  placeholder="Current Address" rows="1" >
                                    {{ old("cAddress",$data->cAddress) }}
                                </textarea>
                                @if ($errors->has('cAddress'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('cAddress') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Permanent Address *</label>
                                <textarea class="form-control {{ $errors->has('pAddress') ? 'is-invalid' : '' }}" name="pAddress" cols="10" placeholder="Permanent Address" rows="1" >
                                    {{ old("pAddress",$data->pAddress) }}
                                </textarea>
                                @if ($errors->has('pAddress'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('pAddress') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Date of Birth *</label>
                                <input onclick="this.showPicker()" type="date" class=" form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" value="{{ old("dob",$data->date_of_birth) }}" name="dob" >
                                @if ($errors->has('dob'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for=""> Nationality *</label>
                                <input type="text" class=" form-control {{ $errors->has('nationality') ? 'is-invalid' : '' }}" name="nationality" value="{{ old("nationality",$data->nationality) }}" autocomplete="off" >
                                @if ($errors->has('nationality'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('nationality') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for=""> Religion *</label>
                                <select class="form-control select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion">
                                    <option value="">Select Religion</option>
                                    <option @if(old('religion',$data->religion) == "Islam") selected @endif value="Islam">Islam</option>
                                    <option @if(old('religion',$data->religion) == "Hinduism") selected @endif value="Hinduism">Hinduism</option>
                                    <option @if(old('religion',$data->religion) == "Buddhism") selected @endif value="Buddhism">Buddhism</option>
                                    <option @if(old('religion',$data->religion) == "Christianity") selected @endif value="Christianity">Christianity</option>
                                    <option @if(old('religion',$data->religion) == "Other") selected @endif value="Other">Other</option>
                                </select>
                                {{-- <input type="text" class=" form-control  {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion" value="{{ old('religion') }}" autocomplete="off"> --}}
                                @if ($errors->has('religion'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('religion') }}</strong>
                                    </span>
                                @endif
                            </div>
                            

                            <div class="col-md-3  mt-2">
                                <label for=""> NID *</label>
                                <input type="text" class=" form-control {{ $errors->has('nid') ? 'is-invalid' : '' }}" name="nid" autocomplete="off" value="{{ old("nid",$data->nid_number) }}" >
                                @if ($errors->has('nid'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('nid') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="">Blood Group </label>
                                <select class="form-control select2 {{ $errors->has('bloodGroup') ? 'is-invalid' : '' }}" name="bloodGroup">
                                    <option value="">Select blood group</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "A+") selected @endif value="A+">A+</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "A−") selected @endif value="A−">A−</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "B+") selected @endif value="B+">B+</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "B−") selected @endif value="B−">B−</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "AB+") selected @endif value="AB+">AB+</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "AB−") selected @endif value="AB−">AB−</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "O+") selected @endif value="O+">O+</option>
                                    <option @if(old('bloodGroup',$data->bloodGroup) == "O−") selected @endif value="O−">O−</option>
                                </select>
                                {{-- <input value="{{ old('bloodGroup') }}" type="text" class=" form-control  {{ $errors->has('bloodGroup') ? 'is-invalid' : '' }}" name="bloodGroup" autocomplete="off"> --}}
                                @if ($errors->has('bloodGroup'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('bloodGroup') }}</strong>
                                    </span>
                                @endif
                            </div>
                        

                            <div class="col-md-3  mt-2">
                                <label for="" > Marital Status *</label>
                                <select class="form-control select2 {{ $errors->has('maritalStatus') ? 'is-invalid' : '' }}" name="maritalStatus" >
                                    <option value="">Select Marital Status</option>
                                    <option @if(old("maritalStatus",$data->maritalStatus) == "Unmarried") selected @endif  value="Unmarried">Unmarried</option>
                                    <option @if(old("maritalStatus",$data->maritalStatus) == "Married") selected @endif value="Married">Married</option>
                                </select>
                                @if ($errors->has('maritalStatus'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('maritalStatus') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="" > Gender *</label>
                                <select class="form-control select2 {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" >
                                    <option value="">Select Gender</option>
                                    <option  @if(old("gender",$data->gender) == "Male") selected @endif value="Male">Male</option>
                                    <option  @if(old("gender",$data->gender) == "Female") selected @endif value="Female">Female</option>
                                    <option  @if(old("gender",$data->gender) == "Other") selected @endif value="Other">Other</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Mobile *</label>
                                <input type="number" class=" form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" name="mobile" value="{{ old("mobile",$data->mobile) }}" autocomplete="off" >
                                @if ($errors->has('mobile'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Office Phone </label>
                                <input type="number" value="{{ old("officePhone",$data->officePhone) }}" class=" form-control {{ $errors->has('officePhone') ? 'is-invalid' : '' }}" name="officePhone" autocomplete="off">
                                @if ($errors->has('officePhone'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('officePhone') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-sm-6 mt-2">
                                <label for="">Email *</label>
                                <input type="email" class=" form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"  name="email" value="{{ old("email",$data->email) }}" autocomplete="off" >
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Employee ID *</label>
                                <input type="text" class=" form-control {{ $errors->has('empID') ? 'is-invalid' : '' }}" name="empID" value="{{ old("empID",$data->employee_id) }}" autocomplete="off" >
                                @if ($errors->has('empID'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('empID') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Basic Salary *</label>
                                <input type="number" class=" form-control {{ $errors->has('salary') ? 'is-invalid' : '' }}"  name="salary" value="{{ old("salary",$data->salary) }}" step="any" autocomplete="off" >
                                @if ($errors->has('salary'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('salary') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Join Date *</label>
                                <input onclick="this.showPicker()" type="date" class=" form-control {{ $errors->has('joinDate') ? 'is-invalid' : '' }}"  name="joinDate" value="{{ old("joinDate",$data->join_date) }}" autocomplete="off" >
                                @if ($errors->has('joinDate'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('joinDate') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Regine Date </label>
                                <input onclick="this.showPicker()" type="date" class=" form-control {{ $errors->has('rejineDate') ? 'is-invalid' : '' }}" name="rejineDate" value="{{ old("rejineDate",$data->rejineDate) }}" autocomplete="off">
                                @if ($errors->has('rejineDate'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('rejineDate') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Image *</label>
                                <input type="file" class=" form-control"  name="image"  autocomplete="off" >
                            </div>


                            <div class="col-sm-6 mt-2">
                                <label for="">Note </label>
                                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note"  cols="10" placeholder="Permanent Address" rows="2">
                                    {{ old("note",$data->note) }}
                                </textarea>
                            </div>


                            {{-- <div class="col-md-3">
                                <br/>
                                <button class="btn btn-sm btn-primary mt-4 ">
                                    <i class="fa fa-save pr-2"></i>Save
                                </button>
                            </div> --}}
                        </div>

                        <div class="row">
                            <button type="submit" class="btn btn-info"
                            style="width:20%;margin:0 auto; margin-top:30px;">
                            Update Employee</button>
                        </div>
                    </form>
                </div>
                {{-- @endforeach --}}
            </div>

    <!-- </div> -->
</div>
@endsection
@section('script')

<script>
    function select2Employee(select_id,url,placeholder,con_id="shift",con_id1="department"){
        $('#'+select_id).select2({
            placeholder: placeholder,
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    shift_id:$('#'+con_id).val(),
                    dept_id:$('#'+con_id1).val(),
                    value: $.trim(params.term),
                };
                },
                processResults: function (response) {

                return {
                    results: response
                };
                },
                cache: true
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;
            $('#h_'+select_id).val(data.text);

        });
    }
    function selectOption(id,d_name,d_id){
        if(d_name){
            var data_option = new Option(d_name,d_id, true, true);
            $('#'+id).append(data_option).trigger('change');
        }

    }
    select2Employee("shift",'{{route('select2.shift')}}','Select Shift');
    select2Employee("department",'{{route('select2.department')}}','Select Shift');
    select2Employee("designation",'{{route('select2.designation')}}','Select Shift',"department");
    @if(old('shift'))
        selectOption('shift',"{{ old('h_shift') }}","{{ old('shift') }}");
    @else
        @if($data->shift)
            selectOption('shift',"{{ $data->shift->shiftName }}","{{ $data->shift_id }}");
        @endif
    @endif
    @if(old('designation'))
        selectOption('designation',"{{ old('h_designation') }}","{{ old('designation') }}");
    @else
        @if($data->designation)
            selectOption('designation',"{{ $data->designation->name }}","{{ $data->designation_id }}"); 
        @endif
    @endif
    @if(old('department'))
        selectOption('department',"{{ old('h_department') }}","{{ old('department') }}");
    @else
        @if($data->department)
            selectOption('department',"{{ $data->department->name }}","{{ $data->department_id }}"); 
        @endif
    @endif

   
    
  
    
    
</script>
@endsection

