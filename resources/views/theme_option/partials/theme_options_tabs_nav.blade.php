<ul class="tabs-nav">
	@if(can_p('business_setting'))
	<li><a @if(Route::is('business_setting')) class="active" @endif href="{{ route('business_setting') }}"><i class="fa fa-cog"></i>{{ __('Business Setting') }}</a></li>
	@endif
	@if(can_p('theme-options-color'))
	<li><a @if(Route::is('theme-options-color')) class="active" @endif href="{{ route('theme-options-color') }}"><i class="fa fa-cog"></i>{{ __('Color') }}</a></li>
	@endif
</ul>
