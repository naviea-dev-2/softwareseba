<!--start header -->

<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand gap-3" style="justify-content: space-between;">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>
			<button class="d-none" style="border-radius: 5px;border: 1px solid #dee2e6;background:transparent;color: var(--header_font_color);padding: 5px 10px;font-size: 17px;display: flex;gap: 5px;">
				<span>Create</span>
				<i class="bx bx-caret-down-circle"></i>
			</button>
			<div class="dropdown-create-container">
				<ul class="dropdown-create-list">
					
				</ul>
			</div>
			@php
				$au_business = auth()->user()->business;
				$free_exipre = false;
				$pack_expire = false;
			@endphp
				{{-- <div class="search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
					<a href="avascript:;" class="btn d-flex align-items-center"><i class='bx bx-search'></i>Search</a>
				</div> --}}
				
			@if($au_business->user_type == 0)
				@php
					$results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
					$data = array();
					if($results){
						$dataObj = json_decode($results->option_value);
						$data['days'] = $dataObj->days;
					}else{
						$data['days'] = 0;
					}
					$user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays((int)$data['days']);
					$user_now_date = \Carbon\Carbon::now();
				
					if($user_now_date > $user_end_date){
						$free_exipre = true;
					}
				@endphp
				<input type="hidden" id="countdownDate" value="{{ $user_end_date }}">
				<!--<span>End Free Trial</span> <br/>-->
				<div class="countdown mt-0" id="countdown" style="font-size: 15px;">
					
					<span id="days">00</span> :
					<span id="hours">00</span> :
					<span id="minutes">00</span> :
					<span id="seconds">00</span>
				</div>
			@else
				@if(\Carbon\Carbon::now()->lte($au_business->pack_end_date) == false)
					@php
						$pack_expire = true;
					@endphp
					<div  style="background:red;color:white;padding:8px;border-radius:10px;">
						Package Expired
					</div>
				@endif
			@endif
			@if($au_business->business_type_id != 17)
				@if($free_exipre == false)
					@if($pack_expire == false)
					<div class="top-menu ms-auto">
						
						<ul class="navbar-nav align-items-center gap-1">
							{{-- <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
								<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
								</a>
							</li> --}}
							@if(can_p('pos.create'))
							<li>
								<a style="font-size: 12px;" href="{{route('pos.create')}}" class="btn border">POS</a>
							</li>
							@endif
							<li>
								<a style="font-size: 12px;" href="{{route('invoice.create_instant')}}" class="btn border">Instant Sale</a>
							</li>

							<li>
								<a style="font-size: 12px;" href="{{ route('invoice.create') }}" class="btn border">Create Sale</a>
							</li>
							<li>
								<a style="font-size: 12px;" href="{{ route('purchase.create') }}" class="btn border">Create Purchase</a>
							</li>
							<li class="nav-item dropdown dropdown-laungauge  d-sm-flex">
								<div id="google_translate_element2"></div>
								{{-- <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="avascript:;" data-bs-toggle="dropdown"><img src="{{ asset('public') }}/assets/images/county/02.png" width="22" alt="">
								</a>
								<ul class="dropdown-menu dropdown-menu-end">
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/01.png" width="20" alt=""><span class="ms-2">English</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/02.png" width="20" alt=""><span class="ms-2">Catalan</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/03.png" width="20" alt=""><span class="ms-2">French</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/04.png" width="20" alt=""><span class="ms-2">Belize</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/05.png" width="20" alt=""><span class="ms-2">Colombia</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/06.png" width="20" alt=""><span class="ms-2">Spanish</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/07.png" width="20" alt=""><span class="ms-2">Georgian</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{ asset('public') }}/assets/images/county/08.png" width="20" alt=""><span class="ms-2">Hindi</span></a>
									</li>
								</ul> --}}
							</li>
							{{-- <li class="nav-item dark-mode d-none d-sm-flex">
								<a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
								</a>
							</li> --}}


							<!--<li class="nav-item dropdown dropdown-large">-->
							<!--	<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">7</span>-->
							<!--		<i class='bx bx-bell'></i>-->
							<!--	</a>-->
							<!--	<div class="dropdown-menu dropdown-menu-end">-->
							<!--		<a href="javascript:;">-->
							<!--			<div class="msg-header">-->
							<!--				<p class="msg-header-title">Notifications</p>-->
							<!--				<p class="msg-header-badge">8 New</p>-->
							<!--			</div>-->
							<!--		</a>-->
							<!--		<div class="header-notifications-list">-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="user-online">-->
							<!--						<img src="{{ asset('public') }}/assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">Daisy Anderson<span class="msg-time float-end">5 sec-->
							<!--					ago</span></h6>-->
							<!--						<p class="msg-info">The standard chunk of lorem</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="notify bg-light-danger text-danger">dc-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min-->
							<!--					ago</span></h6>-->
							<!--						<p class="msg-info">You have recived new orders</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="user-online">-->
							<!--						<img src="{{ asset('public') }}/assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14-->
							<!--					sec ago</span></h6>-->
							<!--						<p class="msg-info">Many desktop publishing packages</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="notify bg-light-success text-success">-->
							<!--						<img src="{{ asset('public') }}/assets/images/app/outlook.png" width="25" alt="user avatar">-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">Account Created<span class="msg-time float-end">28 min-->
							<!--					ago</span></h6>-->
							<!--						<p class="msg-info">Successfully created new email</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="notify bg-light-info text-info">Ss-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">New Product Approved <span-->
							<!--					class="msg-time float-end">2 hrs ago</span></h6>-->
							<!--						<p class="msg-info">Your new product has approved</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="user-online">-->
							<!--						<img src="{{ asset('public') }}/assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15-->
							<!--					min ago</span></h6>-->
							<!--						<p class="msg-info">Making this the first true generator</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs-->
							<!--					ago</span></h6>-->
							<!--						<p class="msg-info">Successfully shipped your item</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="notify bg-light-primary">-->
							<!--						<img src="{{ asset('public') }}/assets/images/app/github.png" width="25" alt="user avatar">-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day-->
							<!--					ago</span></h6>-->
							<!--						<p class="msg-info">24 new authors joined last week</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--			<a class="dropdown-item" href="javascript:;">-->
							<!--				<div class="d-flex align-items-center">-->
							<!--					<div class="user-online">-->
							<!--						<img src="{{ asset('public') }}/assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">-->
							<!--					</div>-->
							<!--					<div class="flex-grow-1">-->
							<!--						<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs-->
							<!--					ago</span></h6>-->
							<!--						<p class="msg-info">It was popularised in the 1960s</p>-->
							<!--					</div>-->
							<!--				</div>-->
							<!--			</a>-->
							<!--		</div>-->
							<!--		<a href="javascript:;">-->
							<!--			<div class="text-center msg-footer">-->
							<!--				<button class="btn btn-primary w-100">View All Notifications</button>-->
							<!--			</div>-->
							<!--		</a>-->
							<!--	</div>-->
							<!--</li>-->

						</ul>
					</div>
					@endif
				@endif
			@endif
			<div class="user-box dropdown px-3">
				<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="{{ auth()->user()->image_show }}" class="user-img" alt="user avatar">
					<div class="user-info">
						<p class="user-name mb-0">{{ auth()->user()->name }}</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					@if($free_exipre == false)
					@if($au_business->business_type_id == 17)
					@if(auth()->user()->user_type == 0)
					<li><a class="dropdown-item d-flex align-items-center" href="{{route('bussiness.user.edit',auth()->user()->id)}}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
					</li>
					@else
					<li><a class="dropdown-item d-flex align-items-center" href="{{route('user.member.edit',auth()->user()->id)}}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
					</li>
					@endif
					@else
					<li><a class="dropdown-item d-flex align-items-center" href="{{route('bussiness.user.edit',auth()->user()->id)}}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
					</li>
					@endif
					<li><a class="dropdown-item d-flex align-items-center" href="{{route('bussiness.profile.change_password',auth()->user()->id)}}"><i class="bx bx-user fs-5"></i><span>Change Password</span></a>
					</li>
					@if(auth()->user()->user_type == 0)
					<li><a class="dropdown-item d-flex align-items-center" href="{{route('business_setting')}}"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
					</li>
					@endif
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
					</li>
					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					@endif
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('sign_out') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>
<!--end header -->
