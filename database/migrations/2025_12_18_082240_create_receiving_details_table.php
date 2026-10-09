<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receiving_details', function (Blueprint $table) {
            $table->id('receiving_detail_id');
            $table->foreignId('receiving_id')->constrained('receivings', 'receiving_id')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('raw_materials', 'material_id');
            $table->decimal('quantity_received', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_details');
    }
};
