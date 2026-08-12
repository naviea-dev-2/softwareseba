@extends('inc.master')

@section('head')

<title>Add Employee</title>
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
          <h4>Add an Employee</h4>
          <p class="mg-b-0">Add Employee Information</p>
        </div>
      </div>d-flex -->


        <!-- <div class="p-5"> -->

            <div class="br-section-wrapper" style="background:#fff;">

                <div class="text-center mb-2">
                    <h5 class="">Add Employee Information</h5>
                </div>
                <div class="border p-3">
                    <form action="{{ route("storeEmployee") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" value="0" name="id"  >
                            <div class="col-md-3 mt-2">
                                <label class=" form-control-label">Shift:</label>
                                <input type="hidden" name="h_shift" id="h_shift" value="{{ old('h_shift') }}"/>
                                <div>
                                    <select  class="form-control {{ $errors->has('shift') ? 'is-invalid' : '' }}" name="shift" id="shift">
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
                                <input type="hidden" name="h_department" id="h_department" value="{{ old('h_department') }}"/>
                                <div class="{{ $errors->has('department') ? 'is-invalid' : '' }}">
                                <select  class="form-control {{ $errors->has('department') ? 'is-invalid' : '' }}" name="department" id="department">
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
                                <input type="hidden" name="h_designation" id="h_designation" value="{{ old('h_designation') }}"/>
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
                                <input value="{{ old('empName') }}" type="text" class=" form-control {{ $errors->has('empName') ? 'is-invalid' : '' }}" name="empName" autocomplete="off" >
                                @if ($errors->has('empName'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('empName') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for=""> Father's Name *</label>
                                <input type="text" class=" form-control {{ $errors->has('fName') ? 'is-invalid' : '' }}" name="fName" autocomplete="off" value="{{ old('fName') }}">
                                @if ($errors->has('fName'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('fName') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for=""> Mother's Name *</label>
                                <input type="text" class=" form-control {{ $errors->has('mName') ? 'is-invalid' : '' }}" name="mName" value="{{ old('mName') }}" autocomplete="off">
                                @if ($errors->has('fName'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('fName') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Current Address *</label>
                                <textarea class="form-control {{ $errors->has('cAddress') ? 'is-invalid' : '' }}" name="cAddress" cols="10" placeholder="Current Address" rows="1" >{{ old('cAddress') }}</textarea>
                                @if ($errors->has('cAddress'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('cAddress') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Permanent Address *</label>
                                <textarea class="form-control {{ $errors->has('pAddress') ? 'is-invalid' : '' }}" name="pAddress" cols="10" placeholder="Permanent Address" rows="1">{{ old('pAddress') }}</textarea>
                                @if ($errors->has('pAddress'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('pAddress') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Date of Birth *</label>
                                <input onclick="this.showPicker()" type="date" class=" form-control  {{ $errors->has('dob') ? 'is-invalid' : '' }}" name="dob" value="{{ old('dob') }}">
                                @if ($errors->has('dob'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for=""> Nationality *</label>
                                <input type="text" class=" form-control {{ $errors->has('nationality') ? 'is-invalid' : '' }}" name="nationality" value="{{ old('nationality') }}" autocomplete="off">
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
                                    <option @if(old('religion') == "Islam") selected @endif value="Islam">Islam</option>
                                    <option @if(old('religion') == "Hinduism") selected @endif value="Hinduism">Hinduism</option>
                                    <option @if(old('religion') == "Buddhism") selected @endif value="Buddhism">Buddhism</option>
                                    <option @if(old('religion') == "Christianity") selected @endif value="Christianity">Christianity</option>
                                    <option @if(old('religion') == "Other") selected @endif value="Other">Other</option>
                                </select>
                                {{-- <input type="text" class=" form-control  {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion" value="{{ old('religion') }}" autocomplete="off"> --}}
                                @if ($errors->has('religion'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('religion') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for=""> NID *</label>
                                <input value="{{old('nid')  }}" type="text" class=" form-control  {{ $errors->has('nid') ? 'is-invalid' : '' }}" name="nid" autocomplete="off" >
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
                                    <option @if(old('bloodGroup') == "A+") selected @endif value="A+">A+</option>
                                    <option @if(old('bloodGroup') == "A−") selected @endif value="A−">A−</option>
                                    <option @if(old('bloodGroup') == "B+") selected @endif value="B+">B+</option>
                                    <option @if(old('bloodGroup') == "B−") selected @endif value="B−">B−</option>
                                    <option @if(old('bloodGroup') == "AB+") selected @endif value="AB+">AB+</option>
                                    <option @if(old('bloodGroup') == "AB−") selected @endif value="AB−">AB−</option>
                                    <option @if(old('bloodGroup') == "O+") selected @endif value="O+">O+</option>
                                    <option @if(old('bloodGroup') == "O−") selected @endif value="O−">O−</option>
                                </select>
                                {{-- <input value="{{ old('bloodGroup') }}" type="text" class=" form-control  {{ $errors->has('bloodGroup') ? 'is-invalid' : '' }}" name="bloodGroup" autocomplete="off"> --}}
                                @if ($errors->has('bloodGroup'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('bloodGroup') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="" > Marital Status *</label>
                                <select class="form-control select2 {{ $errors->has('maritalStatus') ? 'is-invalid' : '' }}" name="maritalStatus">
                                    <option value="">Select Marital Status</option>
                                    <option @if(old('maritalStatus') == "Unmarried") selected @endif value="Unmarried">Unmarried</option>
                                    <option @if(old('maritalStatus') == "Married") selected @endif value="Married">Married</option>
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
                                    <option @if(old('gender') == "Male") selected @endif value="Male">Male</option>
                                    <option @if(old('gender') == "Female") selected @endif value="Female">Female</option>
                                    <option @if(old('gender') == "Other") selected @endif value="Other">Other</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif

                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Mobile *</label>
                                <input value="{{ old('mobile') }}" type="number" class=" form-control  {{ $errors->has('mobile') ? 'is-invalid' : '' }}" name="mobile" autocomplete="off">
                                @if ($errors->has('mobile'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Office Phone </label>
                                <input value="{{ old('officePhone') }}" type="number" class=" form-control  {{ $errors->has('officePhone') ? 'is-invalid' : '' }}" name="officePhone" autocomplete="off">
                                @if ($errors->has('officePhone'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('officePhone') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Email *</label>
                                <input value="{{ old('email') }}" type="email" class=" form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}"  name="email" autocomplete="off" >
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Employee ID *</label>
                                <input value="{{ old('empID') }}" type="text" class=" form-control  {{ $errors->has('empID') ? 'is-invalid' : '' }}" name="empID" autocomplete="off">
                                @if ($errors->has('empID'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('empID') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Basic Salary *</label>
                                <input value="{{ old('salary',0) }}" type="number" class=" form-control  {{ $errors->has('salary') ? 'is-invalid' : '' }}"  name="salary" step="any" autocomplete="off">
                                @if ($errors->has('salary'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('salary') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Join Date *</label>
                                <input onclick="this.showPicker()" value="{{ old('joinDate') }}" type="date" class=" form-control  {{ $errors->has('joinDate') ? 'is-invalid' : '' }}"  name="joinDate" autocomplete="off">
                                @if ($errors->has('joinDate'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('joinDate') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Re-join Date </label>
                                <input onclick="this.showPicker()" value="{{ old('rejineDate') }}" type="date" class=" form-control  {{ $errors->has('rejineDate') ? 'is-invalid' : '' }}" name="rejineDate" autocomplete="off">
                                @if ($errors->has('rejineDate'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('rejineDate') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 mt-2">
                                <label for="">Image *</label>
                                <input type="file" class=" form-control  {{ $errors->has('file') ? 'is-invalid' : '' }}"  name="image" autocomplete="off">
                                @if ($errors->has('file'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="col-sm-6 mt-2">
                                <label for="">Note </label>
                                <textarea class="form-control  {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note"  cols="10" placeholder="Note" rows="2">{{ old('note') }}</textarea>
                                @if ($errors->has('note'))
                                    <span class="invalid-feedback mb-0">
                                    <strong>{{ $errors->first('note') }}</strong>
                                    </span>
                                @endif
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
                            Save</button>
                        </div>
                    </form>
                </div>
        <!-- </div> -->

    </div>

    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
    @endif
    @if(old('designation'))
        selectOption('designation',"{{ old('h_designation') }}","{{ old('designation') }}");
    @endif
    @if(old('department'))
        selectOption('department',"{{ old('h_department') }}","{{ old('department') }}");
    @endif
    
</script>
@endsection