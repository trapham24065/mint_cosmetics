{{-- This variable helps simplify the logic in the form --}}
@php
    $isUsed = isset($coupon) && $coupon->times_used > 0;
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="coupon-code" class="form-label">Coupon Code</label>
            <input type="text" id="coupon-code" name="code" class="form-control @error('code') is-invalid @enderror"
                   value="{{ old('code', $coupon->code ?? '') }}" required>
            @error('code')
            <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="coupon-type" class="form-label">Discount Type @if($isUsed)
                    <small class="text-danger">(Locked)</small>
                @endif</label>
            <select id="coupon-type" name="type" class="form-select @error('type') is-invalid @enderror"
                    {{ $isUsed ? 'disabled' : '' }} required>
                @foreach($types as $type)
                    <option value="{{ $type->value }}" @selected(old('type', $coupon->type ?? '') == $type)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            {{-- If disabled, send the original value as a hidden input to pass validation --}}
            @if($isUsed)
                <input type="hidden" name="type" value="{{ $coupon->type->value }}">
            @endif
            @error('type')
            <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="coupon-value" class="form-label">Discount Value @if($isUsed)
                    <small class="text-danger">(Locked)</small>
                @endif</label>
            <input type="number" step="any" id="coupon-value" name="value"
                   class="form-control @error('value') is-invalid @enderror"
                   value="{{ old('value', $coupon->value ?? '') }}" {{ $isUsed ? 'disabled' : '' }} required>
            @if($isUsed)
                <input type="hidden" name="value" value="{{ $coupon->value }}">
            @endif
            @error('value')
            <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="min-purchase" class="form-label">Minimum Purchase Amount (Optional)</label>
            <input type="number" step="any" id="min-purchase" name="min_purchase_amount"
                   class="form-control @error('min_purchase_amount') is-invalid @enderror"
                   value="{{ old('min_purchase_amount', $coupon->min_purchase_amount ?? '') }}">
            @error('min_purchase_amount')
            <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="max-uses" class="form-label">Max Uses (Optional)</label>
            <input type="number" id="max-uses" name="max_uses"
                   class="form-control @error('max_uses') is-invalid @enderror"
                   value="{{ old('max_uses', $coupon->max_uses ?? '') }}">
            @error('max_uses')
            <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="starts-at" class="form-label">Start Date</label>
            <input type="date" id="starts-at" name="starts_at"
                   class="form-control @error('starts_at') is-invalid @enderror"
                   value="{{ old('starts_at', isset($coupon->starts_at) ? $coupon->starts_at->format('Y-m-d') : '') }}"
                   required>
            @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="expires-at" class="form-label">End Date</label>
            <input type="date" id="expires-at" name="expires_at"
                   class="form-control @error('expires_at') is-invalid @enderror"
                   value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d') : '') }}"
                   required>
            @error('expires_at')
            <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <div class="form-check form-switch form-switch-success">
                <input class="form-check-input" type="checkbox" id="is-active" name="is_active" value="1"
                       @if(old('is_active', $coupon->is_active ?? true)) checked @endif>
                <label class="form-check-label" for="is-active">Active</label>
            </div>
        </div>
    </div>
</div>
