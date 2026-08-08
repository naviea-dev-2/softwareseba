@extends('HRandPayroll.master')        
 @section('content')
        <div class="content-area" style="padding-top:80px;">
            <!-- <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title  my-2">
                            <h4 class="my-2 mx-auto "><em> <b>New Employee Manage</b> </em></h4>
                        </div>
                    </div>
                </div>
            </div> -->


            <!-- <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12">
                    <form>
                        <div class="form-row">
                          <div class="col-sm-3">
                            <label for=""> Product Damage Code</label> 
                            <input type="text" class=" form-control form-control-sm" id="" name="">
                          </div>
                          <div class="col-sm-3">
                            <label for="" >Damage Product</label> 
                                <select class="form-control form-control-sm">
                                    <option>AAAAAA</option>
                                    <option>BBBBBB </option>
                                    </select>
                          </div>
                          <div class="col-sm-3">
                            <label for=""> Damage Rate</label> 
                            <input type="text" class=" form-control form-control-sm" id="" placeholder="0" name="">
    
                          </div>
                          
                          <div class="col-sm-3">
                            <label for="">Damage Quantity</label> 
                            <input type="number" class=" form-control form-control-sm" placeholder="0" id="" name="">
                          </div>
                          <div class="col-sm-3">
                            <label for=""> Damage Total</label> 
                            <input type="text" class=" form-control form-control-sm" placeholder="0" id="" name="">
                          </div>
                          <div class="col-sm-3">
                            <textarea name="" id="" class="form-control" placeholder="Damage Note.." cols="30" rows="2"></textarea>
                          </div>
                          <div class="col-sm-3">
                            <label for=""> Damage Date</label> 
                            <input type="date" class=" form-control form-control-sm" id="" name="">
                          </div>
                          <div class="col-sm-3">
                                <button class="btn btn-sm btn-primary mt-4 ">
                                    <i class="fa fa-save pr-2"></i>Save
                                </button>
                          </div>
                          
                          
                          
                        </div>
                      </form>
                    </div>
                </div>
            </div> -->
            
          
            <div class="container" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;margin-top:30px;">
                <div class="row row-card-one my-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">                  
                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>                              
                              <th>SN.</th>                      
                              <th>Name</th>
                              <th>Department Name</th> 
                              <th>Designation Name</th> 
                              <th>Basic Salary</th>   
                              <th>Bonus(%)</th>  
                              <th>Bonus Amount</th>                                                 
                            </tr>
                          </thead>
                          @php
                          	$total=0;
                          	$subtotal=0;
                          @endphp
                          <tbody>
                            @foreach($newEmployees as $key=>$newEmployee)

                            @php
                            	$subtotal=$newEmployee->salary*($payrollsetup->bonus/100);
                            	$total+=$subtotal;
                            @endphp
                            <tr class="{{$newEmployee->id}}">
                                <td>{{$key+1}}</td>                                
                                <td>{{$newEmployee->empName}}</td>                                
                                <td>{{$newEmployee->deptName}}</td>
                                <td>{{$newEmployee->desigName}}</td> 
                                <td>BDT {{$newEmployee->salary}}</td>
                                <td>{{$payrollsetup->bonus}}(%)</td>                           
                                <td>BDT {{$subtotal}}</td>    
                            </tr>
                           @endforeach
                              <tr>
                              	<td colspan="7"><span style="float:right;padding-right:100px">Total: BDT {{$total}}</span></td>
                              </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>     
        </div>
      
        <!-- Main Content Area End -->
    </div>
</div>
@endsection