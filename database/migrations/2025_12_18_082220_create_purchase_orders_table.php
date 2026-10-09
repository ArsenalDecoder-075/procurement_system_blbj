<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('po_id');
            $table->string('po_number', 30)->unique();
            $table->foreignId('supplier_id')
                ->constrained('suppliers', 'supplier_id')
                ->onDelete('cascade');  // <- Tambahkan ini
            $table->date('po_date');
            $table->enum('status', ['DRAFT', 'APPROVED', 'CLOSED', 'CANCELLED'])->default('DRAFT');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
