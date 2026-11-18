<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('petanis', function (Blueprint $table) {
        $table->id();
        $table->string('nama_petani');
        $table->string('no_hp', 20)->nullable();
        $table->text('alamat')->nullable();
        $table->decimal('luas_lahan', 8, 2)->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petanis');
    }
};
