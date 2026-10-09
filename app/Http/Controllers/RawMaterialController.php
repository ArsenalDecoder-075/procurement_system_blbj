<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RawMaterialController extends Controller
{
    public function index()
    {
        $materials = RawMaterial::orderBy('material_name')->get();
        return view('raw-materials.index', compact('materials'));
    }

    public function create()
    {
        // Generate kode material otomatis
        $lastMaterial = RawMaterial::orderBy('material_id', 'desc')->first();
        $nextCode = 'MAT001';

        if ($lastMaterial) {
            $lastCode = $lastMaterial->material_code;
            if (preg_match('/(\d+)$/', $lastCode, $matches)) {
                $lastNumber = (int) $matches[1];
                $nextNumber = $lastNumber + 1;
                $nextCode = 'MAT' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $nextCode = 'MAT001';
            }
        }

        return view('raw-materials.create', compact('nextCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_code' => 'required|string|max:20|unique:raw_materials',
            'material_name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
        ]);

        RawMaterial::create($validated);

        return redirect()->route('raw-materials.index')
            ->with('success', 'Bahan baku berhasil ditambahkan');
    }

    public function edit(RawMaterial $rawMaterial)
    {
        return view('raw-materials.edit', compact('rawMaterial'));
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $validated = $request->validate([
            'material_code' => 'required|string|max:20|unique:raw_materials,material_code,' . $rawMaterial->material_id . ',material_id',
            'material_name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
        ]);

        $rawMaterial->update($validated);

        return redirect()->route('raw-materials.index')
            ->with('success', 'Bahan baku berhasil diperbarui');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        // Cek apakah material digunakan di tabel lain
        $usedInStock = DB::table('stocks')->where('material_id', $rawMaterial->material_id)->exists();
        $usedInPurchase = DB::table('purchase_order_details')->where('material_id', $rawMaterial->material_id)->exists();
        $usedInReceiving = DB::table('receiving_details')->where('material_id', $rawMaterial->material_id)->exists();
        $usedInSupplier = DB::table('supplier_materials')->where('material_id', $rawMaterial->material_id)->exists();

        if ($usedInStock || $usedInPurchase || $usedInReceiving || $usedInSupplier) {
            return redirect()->route('raw-materials.index')
                ->with('error', 'Tidak dapat menghapus bahan baku yang sudah digunakan dalam transaksi');
        }

        $rawMaterial->delete();

        return redirect()->route('raw-materials.index')
            ->with('success', 'Bahan baku berhasil dihapus');
    }
}
