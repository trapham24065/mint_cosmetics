<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;

abstract class Controller
{

    /**
     * Convert database query exceptions to user-friendly messages
     */
    protected function getQueryExceptionMessage(QueryException $e): string
    {
        $sqlState = $e->errorInfo[0] ?? null;
        $errorCode = $e->errorInfo[1] ?? null;

        // Handle integrity constraint violations (1062 = duplicate key)
        if ($sqlState === '23000' && $errorCode === 1062) {
            if (str_contains($e->getMessage(), 'unique')) {
                return 'Bản ghi này đã tồn tại. Vui lòng sử dụng các giá trị khác..';
            }
            return 'Nhập liệu trùng lặp. Vui lòng kiểm tra lại thông tin đã nhập.';
        }

        // Handle foreign key constraint violations (1452 = foreign key fails)
        if ($sqlState === '23000' && $errorCode === 1452) {
            if (str_contains($e->getMessage(), 'category_id') || str_contains($e->getMessage(), 'categories')) {
                return 'Danh mục đã chọn không hợp lệ hoặc đã bị xóa.';
            }
            if (str_contains($e->getMessage(), 'brand_id') || str_contains($e->getMessage(), 'brands')) {
                return 'Thương hiệu đã chọn không hợp lệ hoặc đã bị xóa.';
            }
            if (str_contains($e->getMessage(), 'attribute')) {
                return 'Thuộc tính đã chọn không hợp lệ hoặc đã bị xóa.';
            }
            return 'Một hoặc nhiều bản ghi liên quan không hợp lệ.';
        }

        // Handle delete restriction violations (1451 = cannot delete/update)
        if ($sqlState === '23000' && $errorCode === 1451) {
            return 'Không thể xóa bản ghi này vì nó được các bản ghi khác tham chiếu đến.';
        }

        // Default database error
        return 'Đã xảy ra lỗi cơ sở dữ liệu. Vui lòng thử lại.';
    }

}
