<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\SupplierMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('supplierMaterials')->orderBy('supplier_name')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        // Generate kode supplier otomatis
        $lastSupplier = Supplier::orderBy('supplier_id', 'desc')->first();
        $nextCode = 'SUP001';

        if ($lastSupplier) {
            $lastCode = $lastSupplier->supplier_code;
            if (preg_match('/(\d+)$/', $lastCode, $matches)) {
                $lastNumber = (int) $matches[1];
                $nextNumber = $lastNumber + 1;
                $nextCode = 'SUP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $nextCode = 'SUP001';
            }
        }

        return view('suppliers.create', compact('nextCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_code' => 'required|string|max:20|unique:suppliers,supplier_code',
            'supplier_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    // Halaman untuk kelola harga material
    public function prices(Supplier $supplier)
    {
        // Material yang sudah ada harganya untuk supplier ini
        $existingMaterials = $supplier->supplierMaterials()->with('material')->get();

        // Material yang belum ada harganya untuk supplier ini
        $materialIdsWithPrice = $existingMaterials->pluck('material_id')->toArray();
        $availableMaterials = RawMaterial::whereNotIn('material_id', $materialIdsWithPrice)
            ->orderBy('material_name')
            ->get();

        return view('suppliers.prices', compact(
            'supplier',
            'existingMaterials',
            'availableMaterials'
        ));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_code' => 'required|string|max:20|unique:suppliers,supplier_code,' . $supplier->supplier_id . ',supplier_id',
            'supplier_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        // Cek apakah supplier memiliki purchase order
        if ($supplier->purchaseOrders()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Tidak dapat menghapus supplier yang memiliki purchase order. Hapus purchase order terlebih dahulu.');
        }

        // Hapus juga data supplier_materials terkait
        DB::table('supplier_materials')->where('supplier_id', $supplier->supplier_id)->delete();

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus');
    }

    // ==================== METHODS UNTUK KELOLA HARGA MATERIAL ====================
    public function addMaterialPrice(Request $request, $supplier_id)
    {
        $request->validate([
            'material_id' => 'required|exists:raw_materials,material_id',
            'default_price' => 'required|numeric|min:0',
            'lead_time_days' => 'required|integer|min:1|max:90'
        ], [
            'material_id.required' => 'Pilih material terlebih dahulu',
            'material_id.exists' => 'Material tidak ditemukan',
            'default_price.required' => 'Harga harus diisi',
            'default_price.numeric' => 'Harga harus berupa angka',
            'default_price.min' => 'Harga tidak boleh negatif',
            'lead_time_days.required' => 'Lead time harus diisi',
            'lead_time_days.integer' => 'Lead time harus berupa angka',
            'lead_time_days.min' => 'Lead time minimal 1 hari',
            'lead_time_days.max' => 'Lead time maksimal 90 hari'
        ]);

        try {
            // Cek apakah sudah ada harga untuk material ini
            $existing = SupplierMaterial::where('supplier_id', $supplier_id)
                ->where('material_id', $request->material_id)
                ->first();

            if ($existing) {
                return redirect()
                    ->back()
                    ->with('error', 'Material ini sudah memiliki harga untuk supplier ini')
                    ->withInput();
            }

            // Simpan data
            SupplierMaterial::create([
                'supplier_id' => $supplier_id,
                'material_id' => $request->material_id,
                'default_price' => $request->default_price,
                'lead_time_days' => $request->lead_time_days
            ]);

            // Gunakan route yang sama dengan halaman saat ini
        return redirect()
        ->back()
        ->with('success', 'Harga material berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan harga: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Controller untuk menghapus harga material (remove-material-price)
    public function removeMaterialPrice(Request $request, $supplier_id)
    {
        $request->validate([
            'material_id' => 'required|exists:raw_materials,material_id'
        ]);

        try {
            SupplierMaterial::where('supplier_id', $supplier_id)
                ->where('material_id', $request->material_id)
                ->delete();

            return redirect()
                ->back()
                ->with('success', 'Harga material berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus harga: ' . $e->getMessage());
        }
    }
}
