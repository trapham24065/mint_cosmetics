<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GlobalSearchController extends Controller
{

    public function search(Request $request): View
    {
        $query = $request->input('query');

        if (empty($query)) {
            return view('admin.search.index', [
                'query'      => $query,
                'products'   => collect(),
                'orders'     => collect(),
                'customers'  => collect(),
                'categories' => collect(),
                'brands'     => collect(),
            ]);
        }

        // 1. Tìm kiếm Sản phẩm (theo tên hoặc SKU trong variants)
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhereHas('variants', function ($q) use ($query) {
                $q->where('sku', 'LIKE', "%{$query}%");
            })
            ->with('variants') // Eager load để hiển thị giá/ảnh
            ->take(10)
            ->get();

        // 2. Tìm kiếm Đơn hàng (theo ID, Email, Tên, hoặc Mã giao dịch)
        $orders = Order::where('id', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orWhere('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            // SỬA LỖI: Dùng đúng tên cột transaction_id thay vì transaction_no
            ->orWhere('transaction_id', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();

        // 3. Tìm kiếm Khách hàng (theo Tên, Email, SĐT)
        $customers = Customer::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();

        // 4. Tìm kiếm Danh mục
        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get();

        // 5. Tìm kiếm Thương hiệu
        $brands = Brand::where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get();

        return view(
            'admin.search.index',
            compact(
                'query',
                'products',
                'orders',
                'customers',
                'categories',
                'brands'
            )
        );
    }

}
