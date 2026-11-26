<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_timbang_pks', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota_timbang')->unique();
            $table->foreignId('pengiriman_id')->constrained('pengiriman_tbs')->cascadeOnDelete();
            $table->decimal('bruto', 10, 2);
            $table->decimal('tara', 10, 2);
            $table->decimal('netto', 10, 2);
            $table->decimal('rafaksi', 10, 2)->default(0);
            $table->decimal('berat_dibayar', 10, 2);
            $table->decimal('harga_jual', 12, 2);
            $table->decimal('nilai_penjualan', 15, 2);
            $table->enum('status_penerimaan', ['belum_diterima', 'diterima_sebagian', 'lunas'])->default('belum_diterima');
            $table->date('tanggal_terima_dana')->nullable();
            $table->decimal('jumlah_diterima', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_timbang_pks');
    }
};
