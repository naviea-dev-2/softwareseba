@if (session('status'))
    <div class="alert">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert error">{{ $errors->first() }}</div>
@endif
