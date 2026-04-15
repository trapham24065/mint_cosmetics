@extends('admin.layouts.app')

@section('content')
@php
$galleryImages = [];

if (!empty($product->list_image)) {
if (is_array($product->list_image)) {
$galleryImages = array_values(array_filter($product->list_image));
} elseif (is_string($product->list_image)) {
$decoded = json_decode($product->list_image, true);
if (is_array($decoded)) {
$galleryImages = array_values(array_filter($decoded));
} elseif (trim($product->list_image) !== '') {
$galleryImages = [trim($product->list_image)];
}
}
}
@endphp
<div class="container-xxl">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div id="productImageCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            @if($product->image)
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{$product->name}}"
                                    class="img-fluid bg-light rounded"
                                    style="object-fit: contain; max-height: 300px; width: 100%;">
                            </div>
                            @endif
                            @if(!empty($galleryImages))
                            @foreach($galleryImages as $galleryImage)
                            <div class="carousel-item @if(!$product->image && $loop->first) active @endif">
                                <img src="{{ asset('storage/' . $galleryImage) }}"
                                    alt="{{$product->name}} gallery" class="img-fluid bg-light rounded"
                                    style="object-fit: contain; max-height: 300px; width: 100%;">
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="carousel-indicators m-0 mt-2 d-flex position-static h-100 overflow-auto">
                            @if($product->image)
                            <button type="button" data-bs-target="#productImageCarousel" data-bs-slide-to="0"
                                class="w-auto h-auto rounded bg-light active border" aria-current="true">
                                <img src="{{ asset('storage/' . $product->image) }}" class="d-block avatar-xl"
                                    alt="indicator" style="object-fit: contain;">
                            </button>
                            @endif
                            @if(!empty($galleryImages))
                            @foreach($galleryImages as $galleryImage)
                            <button type="button" data-bs-target="#productImageCarousel"
                                data-bs-slide-to="{{ $loop->index + ($product->image ? 1 : 0) }}"
                                class="w-auto h-auto rounded bg-light border @if(!$product->image && $loop->first) active @endif">
                                <img src="{{ asset('storage/' . $galleryImage) }}" class="d-block avatar-xl"
                                    alt="indicator" style="object-fit: contain;">
                            </button>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100"><i
                                    class="bx bx-pencil fs-18"></i> Chỉnh sửa</a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.products.index') }}"
                                class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100">Trở
                                lại
                                danh sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @if($product->active)
                    <span class="badge bg-success text-light fs-14 py-1 px-2">Hoạt động</span>
                    @else
                    <span class="badge bg-secondary text-light fs-14 py-1 px-2">Không hoạt động</span>
                    @endif

                    <p class="mb-1 mt-2">
                        <a href="#" class="fs-24 text-dark fw-medium text-decoration-none">{{ $product->name }}</a>
                    </p>
                    <p class="text-muted">
                        Loại: <strong>{{ $product->category->name ?? 'N/A' }}</strong> | Nhãn hiệu:
                        <strong>{{ $product->brand->name ?? 'N/A' }}</strong>
                    </p>

                    <h4 class="text-dark fw-medium mt-4">Biến thể :</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>SKU</th> <!-- Thêm cột SKU -->
                                    <th>Biến thể</th>
                                    <th>Giá</th>
                                    <th>Giá khuyến mãi</th>
                                    <th>Tồn kho</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->variants as $variant)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $variant->sku ?? 'N/A' }}</td>
                                    <!-- Hiển thị SKU -->
                                    <td>
                                        {{ $variant->attributeValues->map(fn($val) => $val->value)->implode(' / ') ?: 'Default' }}
                                    </td>
                                    <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ $variant->discount_price ? number_format($variant->discount_price, 0, ',', '.') . ' VNĐ' : '-' }}</td>
                                    <td>
                                        @if($variant->stock > 0)
                                        <span class="text-success fw-bold">{{ $variant->stock }}</span>
                                        @else
                                        <span class="text-danger fw-bold">Hết hàng</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Sản phẩm này không có biến thể
                                        nào được xác định.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <h4 class="text-dark fw-medium mt-3">Miêu tả :</h4>
                    <div class="text-muted border rounded p-3 bg-light">
                        {!! $product->description !!}
                    </div>
                    <div class="mt-3 text-muted small">
                        <ul class="list-unstyled mb-0">
                            <li><strong>Được tạo vào lúc:</strong> {{ $product->created_at->format('H:i, d-m-Y') }}
                            </li>
                            <li><strong>Cập nhật lần cuối:</strong> {{ $product->updated_at->format('H:i, d-m-Y') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection