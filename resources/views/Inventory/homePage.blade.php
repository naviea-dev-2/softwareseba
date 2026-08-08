@extends('Inventory.master')
 @section('content')
<div class="content-area" style="margin-top:60px;padding:20px;">
    <h2>Inventory Dashboard</h2>
          <div class="row row-cards-one">

            <!-- <div class="col-md-12 col-sm-12 col-lg-12  " >
                <div class="date">
                   <form>
                       <div class="input-group mb-3 input-group-md">
                         <div class="input-group-prepend">
                           <span class="input-group-text">
                               <i class="fas fa-search"></i>
                           </span>
                         </div>
                         <input type="date" class="form-control">
                       </div>
                     </form>

               </div>
             </div> -->
              <div class="col-md-12 col-lg-6 col-xl-4">
                  <div class="mycard bg1">
                      <div class="left">
                          <h5 class="title">Salary Payment</h5>
                          <span class="number">373</span>
                          <a href="#" class="link">View All</a>
                      </div>
                      <div class="right d-flex align-self-center">
                          <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-12 col-lg-6 col-xl-4">
                  <div class="mycard bg2">
                      <div class="left">
                          <h5 class="title">Employee Manage</h5>
                          <span class="number">0</span>
                          <a href="#" class="link">View All</a>
                      </div>
                      <div class="right d-flex align-self-center">
                          <div class="icon">
                            <i class="fas fa-user-plus"></i>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-12 col-lg-6 col-xl-4">
                  <div class="mycard bg3">
                      <div class="left">
                          <h5 class="title">Department Manage</h5>
                          <span class="number">16</span>
                          <a href="{{route('department.view')}}" class="link">View All</a>
                      </div>
                      <div class="right d-flex align-self-center">
                          <div class="icon">
                            <i class="fas fa-archway"></i>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg7">
                    <div class="left">
                        <h5 class="title">Designation Manage</h5>
                        <span class="number">16</span>
                        <a href="{{route('designation.view')}}" class="link">View All</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                          <i class="fas fa-cog"></i>
                        </div>
                    </div>
                </div>
            </div>
              <div class="col-md-12 col-lg-6 col-xl-4">
                  <div class="mycard bg4">
                      <div class="left">
                          <h5 class="title">Month Manage</h5>
                          <span class="number">52</span>
                          <a href="#" class="link">View All</a>
                      </div>
                      <div class="right d-flex align-self-center">
                          <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-12 col-lg-6 col-xl-4">
                  <div class="mycard bg5">
                      <div class="left">
                          <h5 class="title">Salary Report</h5>
                          <span class="number">129</span>
                          <a href="#" class="link">View All</a>
                      </div>
                      <div class="right d-flex align-self-center">
                          <div class="icon">
                            <i class="fas fa-money-check-alt"></i>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg8">
                    <div class="left">
                        <h5 class="title">Payment Range</h5>
                        <span class="number">15</span>
                        <a href="{{route('paymentRange.view')}}" class="link">View All</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon"><i class="fas fa-drafting-compass"></i>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-md-12 col-lg-6 col-xl-4">
                  <div class="mycard bg6">
                      <div class="left">
                          <h5 class="title">User Role</h5>
                          <span class="number">15</span>
                          <a href="#" class="link">View All</a>
                      </div>
                      <div class="right d-flex align-self-center">
                          <div class="icon"><i class="fas fa-user"></i>
                          </div>
                      </div>
                  </div>
              </div>


          </div>

        </div>
      </div>

    <!-- Main Content Area End -->
    </div>
  </div>
@endsection
