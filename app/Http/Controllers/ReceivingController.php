<?php

namespace App\Http\Controllers;

use App\Models\Receiving;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceivingController extends Controller
{
    public function index()
    {
        // Menampilkan data urut berdasarkan nomor penerimaan terkecil di atas
        $receivings = Receiving::with(['purchaseOrder', 'warehouse'])
                        ->orderBy('receiving_number', 'asc')
                        ->get();
        return view('receivings.index', compact('receivings'));
    }

    public function create()
    {
        // Hanya mengambil PO yang statusnya APPROVED dan diurutkan
        $purchaseOrders = PurchaseOrder::where('status', 'APPROVED')
                            ->orderBy('po_number', 'asc') 
                            ->get();
        
        $warehouses = Warehouse::orderBy('warehouse_name', 'asc')->get();
        
        // Generate nomor penerimaan otomatis
        $count = Receiving::whereDate('created_at', now())->count() + 1;
        $nextNumber = 'RCV-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return view('receivings.create', compact('purchaseOrders', 'warehouses', 'nextNumber'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'receiving_number' => 'required|unique:receivings,receiving_number',
            'po_id'            => 'required|exists:purchase_orders,po_id',
            'receiving_date'   => 'required|date',
            'warehouse_id'     => 'required|exists:warehouses,warehouse_id',
            'status'           => 'required|in:PARTIAL,COMPLETE',
        ]);

        try {
            DB::beginTransaction();

            // 2. Simpan Header Penerimaan
            $receiving = Receiving::create($request->all());

            // 3. Ambil rincian barang dari PO yang dipilih
            $poDetails = DB::table('purchase_order_details')
                ->where('po_id', $request->po_id)
                ->get();

            // 4. Salin item PO ke rincian penerimaan agar tabel rincian tidak kosong
            foreach ($poDetails as $item) {
                DB::table('receiving_details')->insert([
                    'receiving_id'      => $receiving->receiving_id,
                    'material_id'       => $item->material_id,
                    'quantity_received' => $item->quantity, // Mengasumsikan diterima penuh sesuai PO
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('receivings.index')->with('success', 'Penerimaan barang dan rinciannya berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Mengambil data utama penerimaan
        $receiving = Receiving::with(['purchaseOrder', 'warehouse'])->findOrFail($id);

        // Mengambil rincian material dengan join ke tabel raw_materials untuk menampilkan nama
        $details = DB::table('receiving_details')
            ->join('raw_materials', 'receiving_details.material_id', '=', 'raw_materials.material_id')
            ->where('receiving_details.receiving_id', $id)
            ->select(
                'raw_materials.material_name', 
                'raw_materials.unit', 
                'receiving_details.quantity_received'
            )
            ->get();

        return view('receivings.show', compact('receiving', 'details'));
    }

    public function edit($id)
    {
        $receiving = Receiving::findOrFail($id);
        
        $purchaseOrders = PurchaseOrder::where('status', 'APPROVED')
                            ->orWhere('po_id', $receiving->po_id)
                            ->orderBy('po_number', 'asc') 
                            ->get();
                            
        $warehouses = Warehouse::orderBy('warehouse_name', 'asc')->get();
        
        return view('receivings.edit', compact('receiving', 'purchaseOrders', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $receiving = Receiving::findOrFail($id);

        $request->validate([
            'receiving_number' => 'required|unique:receivings,receiving_number,' . $id . ',receiving_id',
            'po_id'            => 'required|exists:purchase_orders,po_id',
            'receiving_date'   => 'required|date',
            'warehouse_id'     => 'required|exists:warehouses,warehouse_id',
            'status'           => 'required|in:PARTIAL,COMPLETE',
        ]);

        $receiving->update($request->all());

        return redirect()->route('receivings.index')->with('success', 'Penerimaan barang berhasil diupdate.');
    }

    public function destroy($id)
    {
        $receiving = Receiving::findOrFail($id);
        $receiving->delete();
        return redirect()->route('receivings.index')->with('success', 'Penerimaan barang berhasil dihapus.');
    }
}