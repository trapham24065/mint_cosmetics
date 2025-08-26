<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 4:56 PM
 */
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use App\Enums\CouponType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coupons\StoreCouponRequest;
use App\Http\Requests\Coupons\UpdateCouponRequest;
use App\Models\Coupon;
use App\Services\Admin\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CouponController extends Controller
{

    /**
     * The service for handling coupon logic.
     */
    protected CouponService $couponService;

    /**
     * Inject the service.
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $coupons = Coupon::latest()->paginate(10);
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)
            ->where(fn($query) => $query->where('expires_at', '>=', now())->orWhereNull('expires_at'))
            ->count();
        $expiredCoupons = Coupon::where('expires_at', '<', now())->count();

        return view(
            'admin.management.coupons.index',
            compact(
                'coupons',
                'totalCoupons',
                'activeCoupons',
                'expiredCoupons'
            )
        );
    }

    /**
     * Show the form for create the specified coupon.
     */
    public function create(): View
    {
        $types = CouponType::cases();
        return view('admin.management.coupons.create', compact('types'));
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $this->couponService->createCoupon($data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully.');
        } catch (\Exception $e) {
            Log::error('Coupon Creation Failed: '.$e->getMessage());
            return back()->withInput()->with('error', 'Failed to create coupon.');
        }
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon): View
    {
        $types = CouponType::cases();
        return view('admin.management.coupons.edit', compact('coupon', 'types'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $this->couponService->updateCoupon($coupon, $data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            Log::error('Coupon Update Failed: '.$e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        try {
            $this->couponService->deleteCoupon($coupon);
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Coupon Deletion Failed: '.$e->getMessage());
            return redirect()->route('admin.coupons.index')
                ->with('error', $e->getMessage());
        }
    }

}
