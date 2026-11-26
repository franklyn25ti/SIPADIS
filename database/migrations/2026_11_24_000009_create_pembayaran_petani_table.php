<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_petani', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasokan_id')->constrained('pasokan_tbs')->cascadeOnDelete();
            $table->date('tanggal_bayar')->index();
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_pembayaran', 50)->default('Tunai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_petani');
    }
};
