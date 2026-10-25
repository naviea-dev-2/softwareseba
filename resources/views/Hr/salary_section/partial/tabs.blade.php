@php
    $tabs = [
       
        ['salary.*', 'salary.index', 'Salary'],
        ['advances.*', 'advances.index', 'Advance'],
        ['payments.*', 'payments.index', 'Salary Payment'],
        ['pay-assignments.*', 'pay-assignments.index', 'Payroll Assign'],
        ['salary-heads.*', 'salary-heads.index', 'Salary Head'],
    ];
@endphp
<nav class="tabs">
    @foreach ($tabs as [$pattern, $route, $label])
        <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'on' : '' }}">{{ $label }}</a>
    @endforeach
</nav>
