@php
    $tabs = [
        ['shift.*', 'shift.index', 'Shift'],
        ['departments.*', 'departments.index', 'Department'],
        ['designations.*', 'designations.index', 'Designation'],
        ['late-rules.*', 'late-rules.index', 'Late Roll'],
        ['payroll-months.*', 'payroll-months.index', 'Month'],
        ['holidays.*', 'holidays.index', 'Holiday'],
        ['overtime.*', 'overtime.index', 'Overtime'],
        ['tax-settings.*', 'tax-settings.index', 'Tax Setting'],
    ];
@endphp
<nav class="tabs">
    @foreach ($tabs as [$pattern, $route, $label])
        <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'on' : '' }}">{{ $label }}</a>
    @endforeach
</nav>
