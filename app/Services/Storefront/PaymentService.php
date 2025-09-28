<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/2/2025
 * @time 9:24 PM
 */
declare(strict_types=1);
namespace App\Services\Storefront;

use App\Models\Order;
use cuongnm\viet_qr_pay\QRPay;

class PaymentService
{

    /**
     * Generate a VietQR string for a given order.
     *
     * @param  Order  $order
     *
     * @return string
     */
    public function generateVietQrString(Order $order): string
    {
        // Get bank details from config to keep them secure and manageable
        $bankId = setting('vietqr_bank_id');
        $accountNumber = setting('vietqr_account_no');
        $paymentPrefix = setting('vietqr_prefix', 'DH');

        // Create the payment description
        $paymentDescription = $paymentPrefix.$order->id;

        // Initialize the QRPay object
        $qrPay = QRPay::initVietQR(
            $bankId,
            $accountNumber,
            (string)(int)$order->total_price,
            $paymentDescription
        );

        return $qrPay->build();
    }

}
