<div class="row">

    {{-- Code --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Code <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="code"
               value="{{ old('code', $superDepot->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror"
               required>

        @error('code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Name --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Name <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="name"
               value="{{ old('name', $superDepot->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror"
               required>

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Manager --}}
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
                    @selected(old('manager_id', $superDepot->manager_id ?? '') == $manager->id)>

                    {{ $manager->name }}

                </option>

            @endforeach

        </select>

        @error('manager_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Phone --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Phone
        </label>

        <input type="text"
               name="phone"
               value="{{ old('phone', $superDepot->phone ?? '') }}"
               class="form-control @error('phone') is-invalid @enderror">

        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Email --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email
        </label>

        <input type="email"
               name="email"
               value="{{ old('email', $superDepot->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror">

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Division --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Division
        </label>

        <input type="text"
               name="division"
               value="{{ old('division', $superDepot->division ?? '') }}"
               class="form-control @error('division') is-invalid @enderror">

        @error('division')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- District --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            District
        </label>

        <input type="text"
               name="district"
               value="{{ old('district', $superDepot->district ?? '') }}"
               class="form-control @error('district') is-invalid @enderror">

        @error('district')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Status --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Status
        </label>

        <select name="status"
                class="form-select @error('status') is-invalid @enderror">

            <option value="1"
                @selected(old('status', $superDepot->status ?? 1) == 1)>
                Active
            </option>

            <option value="0"
                @selected(old('status', $superDepot->status ?? 1) == 0)>
                Inactive
            </option>

        </select>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Address --}}
    <div class="col-md-12 mb-3">

        <label class="form-label">
            Address
        </label>

        <textarea name="address"
                  class="form-control @error('address') is-invalid @enderror"
                  rows="3">{{ old('address', $superDepot->address ?? '') }}</textarea>

        @error('address')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Notes --}}
    <div class="col-md-12 mb-3">

        <label class="form-label">
            Notes
        </label>

        <textarea name="notes"
                  class="form-control @error('notes') is-invalid @enderror"
                  rows="3">{{ old('notes', $superDepot->notes ?? '') }}</textarea>

        @error('notes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>