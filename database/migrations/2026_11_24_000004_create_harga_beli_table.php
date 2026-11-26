<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_beli', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_berlaku')->index();
            $table->decimal('harga_tbs', 12, 2);
            $table->string('satuan', 20)->default('kg');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_beli');
    }
};
