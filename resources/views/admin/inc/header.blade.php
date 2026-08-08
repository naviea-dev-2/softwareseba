<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar gap-3" style="flex-wrap: nowrap;justify-content: flex-end;">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>


					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="{{ auth()->guard('admin')->user()->image_show }}" class="user-img" alt="user avatar">
							<div class="user-info">
								<p class="user-name mb-0">{{ auth()->guard('admin')->user()->name }}</p>
								{{-- <p class="designattion mb-0">Web Designer</p> --}}
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.user.edit',auth()->user()->id) }}"><i class="bx bx-user fs-5"></i><span>Profile</span></a></li>
							<li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile.change_password',auth()->user()->id) }}"><i class="bx bx-user fs-5"></i><span>Change Password</span></a></li>
							<li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
