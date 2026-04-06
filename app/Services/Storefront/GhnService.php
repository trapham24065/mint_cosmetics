<?php

declare(strict_types=1);

namespace App\Services\Storefront;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GhnService
{

    public function provinces(): array
    {
        return $this->request('GET', '/master-data/province', responseKey: 'data', defaultList: []);
    }

    public function districts(int $provinceId): array
    {
        return $this->request('GET', '/master-data/district', [
            'province_id' => $provinceId,
        ], responseKey: 'data', defaultList: []);
    }

    public function wards(int $districtId): array
    {
        return $this->request('GET', '/master-data/ward', [
            'district_id' => $districtId,
        ], responseKey: 'data', defaultList: []);
    }

    public function calculateFee(int $toDistrictId, string $toWardCode, int $weightGram): array
    {
        $serviceId = $this->getServiceId($toDistrictId);
        if (!$serviceId) {
            throw new RuntimeException('Không tìm thấy service_id phù hợp');
        }

        return $this->request('POST', '/v2/shipping-order/fee', [
            'service_id'       => $serviceId, // 🔥 QUAN TRỌNG NHẤT
            'from_district_id' => (int)config('services.ghn.from_district_id'),
            'from_ward_code'   => (string)config('services.ghn.from_ward_code'),
            'to_district_id'   => $toDistrictId,
            'to_ward_code'     => $toWardCode,
            'weight'           => max(500, $weightGram),
            'length'           => 20,
            'width'            => 15,
            'height'           => 10,
            'insurance_value'  => 0,
        ], responseKey: 'data', defaultList: []);
    }

    public function createOrder(Order $order, array $shippingData, int $weightGram): array
    {
        // Mock create order khi ở local environment để test không tốn tiền
        if (app()->environment('local') && filter_var(env('GHN_MOCK_CREATE_ORDER'), FILTER_VALIDATE_BOOL)) {
            return [
                'order_code'     => 'DEMO-' . $order->id . '-' . floor(microtime(true) * 1000),
                'sort_code'      => 'DEMO-' . substr(md5((string)$order->id), 0, 8),
                'transaction_id' => floor(microtime(true) * 1000),
            ];
        }

        return $this->request('POST', '/v2/shipping-order/create', [
            'payment_type_id'   => 2,
            'note'              => trim((string)($order->notes ?? '')),
            'required_note'     => 'KHONGCHOXEMHANG',
            'from_name'         => (string)config('services.ghn.from_name', config('app.name', 'Mint Cosmetics')),
            'from_phone'        => (string)config('services.ghn.from_phone', ''),
            'from_address'      => (string)config('services.ghn.from_address', config('app.name', 'Mint Cosmetics')),
            'from_district_id'  => (int)config('services.ghn.from_district_id'),
            'from_ward_code'    => (string)config('services.ghn.from_ward_code'),
            'to_name'           => trim($order->first_name . ' ' . $order->last_name),
            'to_phone'          => $order->phone,
            'to_address'        => $order->address,
            'to_district_id'    => (int)$shippingData['district_id'],
            'to_ward_code'      => (string)$shippingData['ward_code'],
            'service_type_id'   => (int)config('services.ghn.service_type_id', 2),
            'cod_amount'        => (int)round((float)$order->total_price),
            'content'           => 'Đơn hàng Mint Cosmetics #' . $order->id,
            'weight'            => max(500, $weightGram),
            'length'            => 20,
            'width'             => 15,
            'height'            => 10,
            'client_order_code' => 'MINT-' . $order->id,
        ], responseKey: 'data', defaultList: []);
    }

    public function estimateWeight(array $items): int
    {
        $quantity = collect($items)->sum('quantity');

        return max(500, $quantity * 500);
    }

    private function request(
        string $method,
        string $path,
        array $payload = [],
        string $responseKey = 'data',
        array $defaultList = []
    ): array {
        $request = Http::baseUrl(rtrim((string)config('services.ghn.base_url'), '/'))
            ->withHeaders([
                'Token'        => (string)config('services.ghn.token'),
                'ShopId'       => (string)config('services.ghn.shop_id'),
                'Content-Type' => 'application/json',
            ]);

        if (!filter_var(config('services.ghn.verify_ssl'), FILTER_VALIDATE_BOOL)) {
            $request = $request->withoutVerifying();
        }

        $response = $request
            ->send($method, ltrim($path, '/'), $method === 'GET' ? ['query' => $payload] : ['json' => $payload]);

        $data = $response->json();

        Log::debug('GHN API Response', [
            'method'   => $method,
            'path'     => $path,
            'payload'  => $payload,
            'status'   => $response->status(),
            'response' => $data,
        ]);

        if (!$response->successful()) {
            throw new RuntimeException($data['message'] ?? $data['code_message'] ?? 'GHN request failed.');
        }

        if (isset($data['code']) && (int)$data['code'] !== 200) {
            throw new RuntimeException($data['message'] ?? $data['code_message'] ?? 'GHN request failed.');
        }

        $responseData = $data[$responseKey] ?? $defaultList;

        return is_array($responseData) ? $responseData : $defaultList;
    }

    public function getServiceId(int $toDistrictId): ?int
    {
        $data = $this->request('GET', '/v2/shipping-order/available-services', [
            'shop_id'       => (int)config('services.ghn.shop_id'),
            'from_district' => (int)config('services.ghn.from_district_id'),
            'to_district'   => $toDistrictId,
        ], responseKey: 'data', defaultList: []);

        Log::debug('GHN available services', [
            'from_district' => (int)config('services.ghn.from_district_id'),
            'to_district'   => $toDistrictId,
            'services'      => $data,
        ]);

        return $data[0]['service_id'] ?? null;
    }
}
