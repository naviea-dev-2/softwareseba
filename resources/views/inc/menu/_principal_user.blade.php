{{-- partials/menu/_principal_user.blade.php --}}
<li>
    <a href="{{ route('dashboard') }}">
        <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
        <div class="menu-title">Dashboard</div>
    </a>
</li>
<li>
    <a href="{{ route('property.index_user') }}">
        <div class="parent-icon"><i class='bx bx-building-house'></i></div>
        <div class="menu-title">Land Plots</div>
    </a>
</li>
<li>
    <a href="{{ route('user_deposit.create') }}">
        <div class="parent-icon"><i class='bx bx-dollar-circle'></i></div>
        <div class="menu-title">Add New Deposit</div>
    </a>
</li>
<li>
    <a href="{{ route('user_deposit.index') }}">
        <div class="parent-icon"><i class='bx bx-money'></i></div>
        <div class="menu-title">Manage Deposit</div>
    </a>
</li>