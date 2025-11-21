@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Coupon: {{ $coupon->code }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Include the shared form --}}
                    @include('admin.management.coupons._form')

                    <div class="text-end">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
