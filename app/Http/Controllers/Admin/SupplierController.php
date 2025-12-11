<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\StoreSupplierRequest;
use App\Http\Requests\Suppliers\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Supplier Management';

        return view('admin.management.supplier.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Supplier';
        return view('admin.management.supplier.create', compact('title'));
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        Supplier::create($data);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier): View
    {
        $title = 'Supplier Details: '.$supplier->name;

        $purchaseOrders = $supplier->purchaseOrders()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        $totalImportValue = $supplier->purchaseOrders()
            ->where('status', 'completed')
            ->sum('total_amount');

        return view(
            'admin.management.supplier.show',
            compact('supplier', 'title', 'purchaseOrders', 'totalImportValue')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier): View
    {
        $title = 'Edit Supplier';
        return view('admin.management.supplier.edit', compact('supplier', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified supplier in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->purchaseOrders()->exists()) {
            return back()->with(
                'error',
                'Cannot delete supplier with existing purchase orders. Please deactivate them instead.'
            );
        }
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function getDataForGrid(): JsonResponse
    {
        $suppliers = Supplier::latest()->get();

        $data = $suppliers->map(function ($supplier) {
            return [
                'id'             => $supplier->id,
                'name'           => $supplier->name,
                'email'          => $supplier->email ?? 'N/A',
                'phone'          => $supplier->phone ?? 'N/A',
                'contact_person' => $supplier->contact_person ?? 'N/A',
                'is_active'      => (bool)$supplier->is_active,
                'created_at'     => $supplier->created_at->format('d M, Y'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:activate,deactivate,delete',
            'ids'    => 'required|array',
            'ids.*'  => 'exists:suppliers,id',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = count($ids);

        if ($action === 'delete') {
            Supplier::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => "$count suppliers deleted successfully."]);
        }

        $isActive = $action === 'activate';
        Supplier::whereIn('id', $ids)->update(['is_active' => $isActive]);

        $statusText = $isActive ? 'activated' : 'deactivated';
        return response()->json(['success' => true, 'message' => "$count suppliers $statusText successfully."]);
    }

}
