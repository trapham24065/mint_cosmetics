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
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CouponController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Data for the main table (paginated)
        $coupons = Coupon::latest()->paginate(10);

        // Data for the summary cards
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->where('expires_at', '>=', now())
                    ->orWhereNull('expires_at');
            })->count();
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

    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $validatedData['is_active'] = $request->has('is_active');

        Coupon::create($validatedData);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        //
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
        if ($coupon->times_used > 0) {
            // If coupons are in use, prevent changing core financial fields.
            // Compare submitted type and value with the existing ones.
            if ($coupon->type->value !== $request->input('type') || (float)$coupon->value !== (float)$request->input(
                    'value'
                )) {
                return back()->withInput()
                    ->with('error', 'Cannot change Type or Value of a coupon that has already been used.');
            }
        }

        // Prevent setting max_uses to a value less than the current usage count
        if ($request->filled('max_uses') && $request->input('max_uses') < $coupon->times_used) {
            return back()->withInput()
                ->with(
                    'error',
                    "Max uses cannot be less than the current number of times used ({$coupon->times_used})."
                );
        }

        $validatedData = $request->validated();
        $validatedData['is_active'] = $request->has('is_active');

        $coupon->update($validatedData);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        // Check if the coupon has been used at least once.
        if ($coupon->times_used > 0) {
            // If it has been used, prevent deletion and return with an error.
            return redirect()->route('admin.coupons.index')
                ->with(
                    'error',
                    "Cannot delete coupon '{$coupon->code}'. It has already been used {$coupon->times_used} time(s). Please deactivate it instead."
                );
        }

        // If the check passes (coupon has never been used), proceed with deletion.
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

}
