<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('petanis', function (Blueprint $table) {
            $table->boolean('status_aktif')->default(true)->after('luas_lahan');
        });
    }

    public function down(): void
    {
        Schema::table('petanis', function (Blueprint $table) {
            $table->dropColumn('status_aktif');
        });
    }
};
