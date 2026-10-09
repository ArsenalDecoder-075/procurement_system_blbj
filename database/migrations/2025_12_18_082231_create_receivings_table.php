<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receivings', function (Blueprint $table) {
            $table->id('receiving_id');
            $table->string('receiving_number', 30)->unique();
            $table->foreignId('po_id')->constrained('purchase_orders', 'po_id');
            $table->date('receiving_date');
            $table->foreignId('warehouse_id')->constrained('warehouses', 'warehouse_id');
            $table->enum('status', ['PARTIAL', 'COMPLETE'])->default('PARTIAL');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivings');
    }
};
