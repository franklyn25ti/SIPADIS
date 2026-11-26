<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaBeli extends Model
{
    protected $table = 'harga_beli';

    protected $fillable = [
        'tanggal_berlaku',
        'harga_tbs',
        'satuan',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_berlaku' => 'date',
            'harga_tbs' => 'decimal:2',
        ];
    }

    /**
     * Mengambil data HargaBeli aktif terbaru berdasarkan tanggal (default: hari ini).
     */
    public static function getHargaAktif(?string $tanggal = null): ?self
    {
        $tanggal = $tanggal ?? now()->toDateString();

        return static::where('tanggal_berlaku', '<=', $tanggal)
            ->orderByDesc('tanggal_berlaku')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Mengambil nominal angka harga beli TBS aktif terbaru per kg.
     */
    public static function getNominalAktif(?string $tanggal = null): float
    {
        return (float) (static::getHargaAktif($tanggal)?->harga_tbs ?? 0);
    }
}
