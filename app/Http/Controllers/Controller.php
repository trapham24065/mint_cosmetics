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
            if (strpos($e->getMessage(), 'unique') !== false) {
                return 'This record already exists. Please use different values.';
            }
            return 'Duplicate entry. Please check your input.';
        }

        // Handle foreign key constraint violations (1452 = foreign key fails)
        if ($sqlState === '23000' && $errorCode === 1452) {
            if (str_contains($e->getMessage(), 'category_id') || str_contains($e->getMessage(), 'categories')) {
                return 'The selected category is invalid or has been deleted.';
            }
            if (str_contains($e->getMessage(), 'brand_id') || str_contains($e->getMessage(), 'brands')) {
                return 'The selected brand is invalid or has been deleted.';
            }
            if (str_contains($e->getMessage(), 'attribute')) {
                return 'The selected attribute is invalid or has been deleted.';
            }
            return 'One or more related records are invalid.';
        }

        // Handle delete restriction violations (1451 = cannot delete/update)
        if ($sqlState === '23000' && $errorCode === 1451) {
            return 'Cannot delete this record because it is referenced by other records.';
        }

        // Default database error
        return 'A database error occurred. Please try again.';
    }

}
