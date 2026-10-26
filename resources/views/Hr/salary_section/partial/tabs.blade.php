@php
    $tabs = [
       
        ['salary.*', 'salary.index', 'Salary'],
        ['payments.*', 'payments.index', 'Salary Payment'],
        ['advances.*', 'advances.index', 'Advance'],
        ['bonuses.*', 'bonuses.index', 'Bonus'],
        ['pay-assignments.*', 'pay-assignments.index', 'Payroll Assign'],
        ['salary-heads.*', 'salary-heads.index', 'Salary Head'],
    ];
@endphp
<nav class="tabs">
    @foreach ($tabs as [$pattern, $route, $label])
        <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'on' : '' }}">{{ $label }}</a>
    @endforeach
</nav>
