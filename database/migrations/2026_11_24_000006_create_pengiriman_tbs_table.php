<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman_tbs', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan')->unique();
            $table->date('tanggal_kirim')->index();
            $table->string('kendaraan');
            $table->string('sopir');
            $table->foreignId('pks_id')->constrained('pks_pembeli')->cascadeOnDelete();
            $table->enum('status_pengiriman', ['dikirim', 'selesai_timbang'])->default('dikirim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman_tbs');
    }
};
