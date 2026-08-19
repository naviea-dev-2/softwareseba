<div class="row">


    {{-- DEPOT --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Depot
            <span class="text-danger">*</span>

        </label>

        <select name="depot_id"
                class="form-select @error('depot_id') is-invalid @enderror"
                required>

            <option value="">
                -- Select Depot --
            </option>

            @foreach($depots as $depot)

                <option value="{{ $depot->id }}"
                    @selected(old(
                        'depot_id',
                        $dealer->depot_id ?? ''
                    ) == $depot->id)>

                    {{ $depot->name }}

                    @if($depot->code)
                        ({{ $depot->code }})
                    @endif

                </option>

            @endforeach

        </select>


        @error('depot_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- DEALER CODE --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Dealer Code
            <span class="text-danger">*</span>

        </label>

        <input type="text"
               name="code"
               value="{{ old('code', $dealer->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror"
               placeholder="Enter dealer code"
               required>


        @error('code')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- DEALER NAME --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Dealer Name
            <span class="text-danger">*</span>

        </label>

        <input type="text"
               name="name"
               value="{{ old('name', $dealer->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror"
               placeholder="Enter dealer name"
               required>


        @error('name')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- BUSINESS NAME --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Business Name
        </label>

        <input type="text"
               name="business_name"
               value="{{ old('business_name', $dealer->business_name ?? '') }}"
               class="form-control"
               placeholder="Enter business name">

    </div>



    {{-- OWNER --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Owner Name
        </label>

        <input type="text"
               name="owner_name"
               value="{{ old('owner_name', $dealer->owner_name ?? '') }}"
               class="form-control"
               placeholder="Owner name">

    </div>



    {{-- PHONE --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Phone
            <span class="text-danger">*</span>

        </label>

        <input type="text"
               name="phone"
               value="{{ old('phone', $dealer->phone ?? '') }}"
               class="form-control @error('phone') is-invalid @enderror"
               placeholder="Dealer phone"
               required>


        @error('phone')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- EMAIL --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email
        </label>

        <input type="email"
               name="email"
               value="{{ old('email', $dealer->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="Dealer email">


        @error('email')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- SALES PERSON --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Sales Person
        </label>

        <select name="sales_person_id"
                class="form-select">

            <option value="">
                -- Select Sales Person --
            </option>

            @foreach($salesPersons as $salesPerson)

                <option value="{{ $salesPerson->id }}"
                    @selected(old(
                        'sales_person_id',
                        $dealer->sales_person_id ?? ''
                    ) == $salesPerson->id)>

                    {{ $salesPerson->employee_name }}

                </option>

            @endforeach

        </select>

    </div>



    {{-- CREDIT LIMIT --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Credit Limit
        </label>

        <input type="number"
               name="credit_limit"
               value="{{ old('credit_limit', $dealer->credit_limit ?? 0) }}"
               class="form-control @error('credit_limit') is-invalid @enderror"
               step="0.01"
               min="0"
               placeholder="0.00">


        @error('credit_limit')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- PAYMENT TERMS --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Payment Terms
        </label>

        <select name="payment_terms"
                class="form-select">

            <option value="">
                -- Select Payment Terms --
            </option>

            <option value="cash"
                @selected(old(
                    'payment_terms',
                    $dealer->payment_terms ?? ''
                ) == 'cash')>

                Cash

            </option>

            <option value="7_days"
                @selected(old(
                    'payment_terms',
                    $dealer->payment_terms ?? ''
                ) == '7_days')>

                7 Days

            </option>

            <option value="15_days"
                @selected(old(
                    'payment_terms',
                    $dealer->payment_terms ?? ''
                ) == '15_days')>

                15 Days

            </option>

            <option value="30_days"
                @selected(old(
                    'payment_terms',
                    $dealer->payment_terms ?? ''
                ) == '30_days')>

                30 Days

            </option>

            <option value="45_days"
                @selected(old(
                    'payment_terms',
                    $dealer->payment_terms ?? ''
                ) == '45_days')>

                45 Days

            </option>

            <option value="60_days"
                @selected(old(
                    'payment_terms',
                    $dealer->payment_terms ?? ''
                ) == '60_days')>

                60 Days

            </option>

        </select>

    </div>



    {{-- NID --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            NID
        </label>

        <input type="text"
               name="nid"
               value="{{ old('nid', $dealer->nid ?? '') }}"
               class="form-control"
               placeholder="NID number">

    </div>



    {{-- TRADE LICENSE --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Trade License
        </label>

        <input type="text"
               name="trade_license"
               value="{{ old('trade_license', $dealer->trade_license ?? '') }}"
               class="form-control"
               placeholder="Trade license number">

    </div>



    {{-- DIVISION --}}

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Division
        </label>

        <input type="text"
               name="division"
               value="{{ old('division', $dealer->division ?? '') }}"
               class="form-control"
               placeholder="Division">

    </div>



    {{-- DISTRICT --}}

    <div class="col-md-4 mb-3">

        <label class="form-label">
            District
        </label>

        <input type="text"
               name="district"
               value="{{ old('district', $dealer->district ?? '') }}"
               class="form-control"
               placeholder="District">

    </div>



    {{-- AREA --}}

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Area
        </label>

        <input type="text"
               name="area"
               value="{{ old('area', $dealer->area ?? '') }}"
               class="form-control"
               placeholder="Area">

    </div>



    {{-- ADDRESS --}}

    <div class="col-md-12 mb-3">

        <label class="form-label">
            Address
        </label>

        <textarea name="address"
                  rows="3"
                  class="form-control"
                  placeholder="Dealer address">{{ old('address', $dealer->address ?? '') }}</textarea>

    </div>



    {{-- STATUS --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Status
        </label>

        <select name="status"
                class="form-select">

            <option value="1"
                @selected(old(
                    'status',
                    $dealer->status ?? 1
                ) == 1)>

                Active

            </option>

            <option value="0"
                @selected(old(
                    'status',
                    $dealer->status ?? 1
                ) == 0)>

                Inactive

            </option>

        </select>

    </div>



    {{-- NOTES --}}

    <div class="col-md-12 mb-3">

        <label class="form-label">
            Notes
        </label>

        <textarea name="notes"
                  rows="3"
                  class="form-control"
                  placeholder="Additional notes">{{ old('notes', $dealer->notes ?? '') }}</textarea>

    </div>


</div>