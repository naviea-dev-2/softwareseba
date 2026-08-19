<div class="row">


    {{-- SUPER DEPOT --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Super Depot
            <span class="text-danger">*</span>

        </label>

        <select name="super_depot_id"
                class="form-select @error('super_depot_id') is-invalid @enderror"
                required>

            <option value="">
                -- Select Super Depot --
            </option>

            @foreach($superDepots as $superDepot)

                <option value="{{ $superDepot->id }}"
                    @selected(old(
                        'super_depot_id',
                        $depot->super_depot_id ?? ''
                    ) == $superDepot->id)>

                    {{ $superDepot->name }}

                    @if($superDepot->code)
                        ({{ $superDepot->code }})
                    @endif

                </option>

            @endforeach

        </select>


        @error('super_depot_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- CODE --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Depot Code
            <span class="text-danger">*</span>

        </label>

        <input type="text"
               name="code"
               value="{{ old('code', $depot->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror"
               placeholder="Enter depot code"
               required>


        @error('code')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- NAME --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Depot Name
            <span class="text-danger">*</span>

        </label>

        <input type="text"
               name="name"
               value="{{ old('name', $depot->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror"
               placeholder="Enter depot name"
               required>


        @error('name')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- MANAGER --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Manager
        </label>

        <select name="manager_id"
                class="form-select @error('manager_id') is-invalid @enderror">

            <option value="">
                -- Select Manager --
            </option>

            @foreach($managers as $manager)

                <option value="{{ $manager->id }}"
                    @selected(old(
                        'manager_id',
                        $depot->manager_id ?? ''
                    ) == $manager->id)>

                    {{ $manager->employee_name }}

                </option>

            @endforeach

        </select>


        @error('manager_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- PHONE --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Phone
        </label>

        <input type="text"
               name="phone"
               value="{{ old('phone', $depot->phone ?? '') }}"
               class="form-control"
               placeholder="Enter phone number">

    </div>



    {{-- EMAIL --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email
        </label>

        <input type="email"
               name="email"
               value="{{ old('email', $depot->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="Enter email">

        @error('email')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>



    {{-- DIVISION --}}

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Division
        </label>

        <input type="text"
               name="division"
               value="{{ old('division', $depot->division ?? '') }}"
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
               value="{{ old('district', $depot->district ?? '') }}"
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
               value="{{ old('area', $depot->area ?? '') }}"
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
                  placeholder="Enter depot address">{{ old('address', $depot->address ?? '') }}</textarea>

    </div>



    {{-- STATUS --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Status
        </label>

        <select name="status"
                class="form-select">

            <option value="1"
                @selected(old('status', $depot->status ?? 1) == 1)>
                Active
            </option>

            <option value="0"
                @selected(old('status', $depot->status ?? 1) == 0)>
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
                  placeholder="Additional notes">{{ old('notes', $depot->notes ?? '') }}</textarea>

    </div>

</div>