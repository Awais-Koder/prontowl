@if(request()->routeIs('filament.admin.pages.dashboard'))
<p>Welcome , @if(Auth::check()) {{Auth::user()->name}} @endif </p>
@endif
