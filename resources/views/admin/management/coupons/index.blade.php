@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="card bg-primary-subtle">
                    <div class="card-body">
                        <h4 class="mb-1">{{ $totalCoupons }} Total Coupons</h4>
                        <p>All coupons created in the system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card bg-success-subtle">
                    <div class="card-body">
                        <h4 class=" mb-1">{{ $activeCoupons }} Active Coupons</h4>
                        <p class="">Coupons that are currently valid for use.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card bg-danger-subtle">
                    <div class="card-body">
                        <h4 class=" mb-1">{{ $expiredCoupons }} Expired Coupons</h4>
                        <p class="">Coupons that are no longer valid.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Coupons List</h4>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-success">
                            <i class="bx bx-plus"></i> New Coupon
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                <tr>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Usage (Used/Max)</th>
                                    <th>Effective Dates</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($coupons as $coupon)
                                    <tr>
                                        <td><span
                                                class="badge bg-secondary-subtle text-secondary fs-13">{{ $coupon->code }}</span>
                                        </td>
                                        <td>{{ $coupon->type->name }}</td>
                                        <td>
                                            @if ($coupon->type === \App\Enums\CouponType::PERCENTAGE)
                                                {{ rtrim(rtrim($coupon->value, '0'), '.') }}%
                                            @else
                                                {{ number_format($coupon->value, 0, ',', '.') }} VNĐ
                                            @endif
                                        </td>
                                        <td>{{ $coupon->times_used }} / {{ $coupon->max_uses ?? '∞' }}</td>
                                        <td>
                                            {{ $coupon->starts_at?->format('d-m-Y') }}
                                            - {{ $coupon->expires_at?->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            @if ($coupon->isValid())
                                                <span class="badge text-success bg-success-subtle fs-12"><i
                                                        class="bx bx-check-double"></i> Active</span>
                                            @else
                                                <span class="badge text-danger bg-danger-subtle fs-12"><i
                                                        class="bx bx-x"></i> Inactive/Expired</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                                   class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken"
                                                                  class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.coupons.destroy', $coupon) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to permanently delete this coupon? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                      class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No coupons found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($coupons->hasPages())
                        <div class="card-footer border-top">
                            <nav>
                                {{ $coupons->appends(request()->query())->links('vendor.pagination.admin-paginnation') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
