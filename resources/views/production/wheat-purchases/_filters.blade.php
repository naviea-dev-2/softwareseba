<form class="wheat-filter wheat-no-print" method="get" action="{{ $action }}">
<div class="wheat-field"><label>From Date</label><input type="date" name="from" value="{{ request('from') }}"></div>
<div class="wheat-field"><label>To Date</label><input type="date" name="to" value="{{ request('to') }}"></div>
<div class="wheat-field"><label>Supplier</label><input name="supplier" placeholder="Supplier name" value="{{ request('supplier') }}"></div>
<div class="wheat-field"><label>Slip / Vehicle / Driver</label><input name="search" placeholder="Search" value="{{ request('search') }}"></div>
<div class="wheat-actions wheat-full"><button class="wheat-btn">Apply Filters</button><a href="{{ $action }}" class="wheat-btn light">Reset</a></div></form>