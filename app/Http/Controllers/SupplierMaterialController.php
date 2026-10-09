<?php

namespace App\Http\Controllers;

use App\Models\SupplierMaterial;
use App\Models\Supplier;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SupplierMaterialsImport;

class SupplierMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierMaterial::with(['supplier', 'material'])
            ->orderBy('updated_at', 'desc');

        // Filter by supplier
        if ($request->has('supplier') && $request->supplier) {
            $query->where('supplier_id', $request->supplier);
        }

        // Filter by material
        if ($request->has('material') && $request->material) {
            $query->where('material_id', $request->material);
        }

        $supplierMaterials = $query->paginate(20);

        $suppliers = Supplier::orderBy('supplier_name')->get();
        $materials = RawMaterial::orderBy('material_name')->get();

        // Statistics
        $stats = [
            'totalAssignments' => SupplierMaterial::count(),
            'avgPrice' => SupplierMaterial::avg('default_price') ?? 0,
            'avgLeadTime' => SupplierMaterial::avg('lead_time_days') ?? 0,
            'uniqueSuppliers' => SupplierMaterial::distinct('supplier_id')->count('supplier_id'),
        ];

        return view('supplier-materials.index', compact(
            'supplierMaterials',
            'suppliers',
            'materials',
            'stats'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $materials = RawMaterial::orderBy('material_name')->get();

        return view('supplier-materials.create', compact('suppliers', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'material_id' => 'required|exists:raw_materials,material_id',
            'default_price' => 'required|numeric|min:0',
            'lead_time_days' => 'required|integer|min:1|max:90',
            'min_order_quantity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Check if combination already exists
        $exists = SupplierMaterial::where('supplier_id', $validated['supplier_id'])
            ->where('material_id', $validated['material_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kombinasi supplier dan material ini sudah ada.');
        }

        SupplierMaterial::create($validated);

        return redirect()->route('supplier-materials.index')
            ->with('success', 'Harga berhasil ditambahkan.');
    }

    public function edit(SupplierMaterial $supplierMaterial)
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $materials = RawMaterial::orderBy('material_name')->get();

        return view('supplier-materials.edit', compact('supplierMaterial', 'suppliers', 'materials'));
    }

    public function update(Request $request, SupplierMaterial $supplierMaterial)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'material_id' => 'required|exists:raw_materials,material_id',
            'default_price' => 'required|numeric|min:0',
            'lead_time_days' => 'required|integer|min:1|max:90',
            'min_order_quantity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Check if combination already exists (excluding current)
        $exists = SupplierMaterial::where('supplier_id', $validated['supplier_id'])
            ->where('material_id', $validated['material_id'])
            ->where('id', '!=', $supplierMaterial->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kombinasi supplier dan material ini sudah ada.');
        }

        // // Log price change if price changed
        // if ($validated['default_price'] != $supplierMaterial->default_price) {
        //     DB::table('price_histories')->insert([
        //         'supplier_material_id' => $supplierMaterial->id,
        //         'old_price' => $supplierMaterial->default_price,
        //         'new_price' => $validated['default_price'],
        //         'changed_by' => auth()->id(),
        //         'created_at' => now(),
        //     ]);
        // }

        $supplierMaterial->update($validated);

        return redirect()->route('supplier-materials.index')
            ->with('success', 'Harga berhasil diperbarui.');
    }

    public function destroy(SupplierMaterial $supplierMaterial)
    {
        // Check if used in purchase orders
        $usedInPO = DB::table('purchase_order_details')
            ->where('material_id', $supplierMaterial->material_id)
            ->exists();

        if ($usedInPO) {
            return redirect()->route('supplier-materials.index')
                ->with('error', 'Tidak dapat menghapus harga yang sudah digunakan dalam purchase order.');
        }

        $supplierMaterial->delete();

        return redirect()->route('supplier-materials.index')
            ->with('success', 'Harga berhasil dihapus.');
    }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls|max:2048',
    //     ]);

    //     try {
    //         Excel::import(new SupplierMaterialsImport($request->update_existing), $request->file('file'));

    //         return redirect()->route('supplier-materials.index')
    //             ->with('success', 'Data berhasil diimport.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Error importing file: ' . $e->getMessage());
    //     }
    // }

    // public function template()
    // {
    //     // Return Excel template for download
    //     return response()->download(storage_path('templates/supplier-materials-template.xlsx'));
    // }

    // public function history(SupplierMaterial $supplierMaterial)
    // {
    //     $histories = DB::table('price_histories')
    //         ->where('supplier_material_id', $supplierMaterial->id)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('supplier-materials.partials.history', compact('histories', 'supplierMaterial'));
    // }

    public function getPrice(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'material_id' => 'required|exists:raw_materials,material_id',
        ]);

        $price = SupplierMaterial::where('supplier_id', $request->supplier_id)
            ->where('material_id', $request->material_id)
            ->where('is_active', true)
            ->first();

        if ($price) {
            return response()->json([
                'success' => true,
                'price' => $price->default_price,
                'lead_time' => $price->lead_time_days,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Harga tidak ditemukan',
        ]);
    }
}
