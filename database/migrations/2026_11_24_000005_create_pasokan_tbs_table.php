<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasokan_tbs', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal')->index();
            $table->foreignId('petani_id')->constrained('petanis')->cascadeOnDelete();
            $table->decimal('berat_timbang', 10, 2);
            $table->decimal('potongan', 10, 2)->default(0);
            $table->decimal('berat_netto', 10, 2);
            $table->decimal('harga_beli', 12, 2);
            $table->decimal('nilai_pembelian', 15, 2);
            $table->enum('status_pembayaran', ['belum_dibayar', 'dibayar_sebagian', 'lunas'])->default('belum_dibayar');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasokan_tbs');
    }
};
