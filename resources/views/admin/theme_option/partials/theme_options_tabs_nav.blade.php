<ul class="tabs-nav">
	<li><a @if(Route::is('backend.theme_options')) class="active" @endif href="{{ route('backend.theme_options') }}"><i class="fa fa-cog"></i>{{ __('Logo') }}</a></li>
	<li><a @if(Route::is('backend.social-media')) class="active" @endif href="{{ route('backend.social-media') }}"><i class="fa fa-cog"></i>{{ __('Social Media') }}</a></li>
    <li><a @if(Route::is('backend.service_list')) class="active" @endif href="{{ route('backend.service_list') }}"><i class="fa fa-cog"></i>{{ __('Software Service') }}</a></li>
	<li><a @if(Route::is('backend.theme-options-color')) class="active" @endif href="{{ route('backend.theme-options-color') }}"><i class="fa fa-cog"></i>{{ __('Color') }}</a></li>
	<li><a @if(Route::is('backend.theme-options-seo')) class="active" @endif href="{{ route('backend.theme-options-seo') }}"><i class="fa fa-cog"></i>{{ __('SEO') }}</a></li>
	<li><a @if(Route::is('backend.theme-options-facebook')) class="active" @endif href="{{ route('backend.theme-options-facebook') }}"><i class="fa fa-cog"></i>{{ __('Facebook APP ID') }}</a></li>
	<li><a @if(Route::is('backend.theme-options-facebook-pixel')) class="active" @endif href="{{ route('backend.theme-options-facebook-pixel') }}"><i class="fa fa-cog"></i>{{ __('Facebook Pixel') }}</a></li>
	<li><a @if(Route::is('backend.theme-options-twitter')) class="active" @endif href="{{ route('backend.theme-options-twitter') }}"><i class="fa fa-cog"></i>{{ __('Twitter') }}</a></li>
	<li><a @if(Route::is('backend.google-analytics')) class="active" @endif href="{{ route('backend.google-analytics') }}"><i class="fa fa-cog"></i>{{ __('Google Analytics') }}</a></li>
	<li><a @if(Route::is('backend.google-tag-manager')) class="active" @endif href="{{ route('backend.google-tag-manager') }}"><i class="fa fa-cog"></i>{{ __('Google Tag Manager') }}</a></li>
	<li><a @if(Route::is('backend.custom-css')) class="active" @endif href="{{ route('backend.custom-css') }}"><i class="fa fa-cog"></i>{{ __('Custom CSS') }}</a></li>
	<li><a @if(Route::is('backend.custom-js')) class="active" @endif href="{{ route('backend.custom-js') }}"><i class="fa fa-cog"></i>{{ __('Custom JS') }}</a></li>
	<li><a @if(Route::is('backend.free_user_limit')) class="active" @endif href="{{ route('backend.free_user_limit') }}"><i class="fa fa-cog"></i>{{ __('Free User Limit') }}</a></li>
</ul>
