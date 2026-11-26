<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PasokanTbs extends Model
{
    protected $table = 'pasokan_tbs';

    protected $fillable = [
        'no_transaksi',
        'tanggal',
        'petani_id',
        'berat_timbang',
        'potongan',
        'berat_netto',
        'harga_beli',
        'nilai_pembelian',
        'status_pembayaran',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'berat_timbang' => 'decimal:2',
            'potongan' => 'decimal:2',
            'berat_netto' => 'decimal:2',
            'harga_beli' => 'decimal:2',
            'nilai_pembelian' => 'decimal:2',
        ];
    }

    /**
     * Generate nomor transaksi otomatis dengan format PSK-YYYYMMDD-XXX.
     */
    public static function generateNoTransaksi(?string $tanggal = null): string
    {
        $datePart = $tanggal ? date('Ymd', strtotime($tanggal)) : now()->format('Ymd');
        $prefix = "PSK-{$datePart}-";

        $lastRecord = static::where('no_transaksi', 'like', "{$prefix}%")
            ->orderByDesc('no_transaksi')
            ->first();

        $nextNumber = 1;
        if ($lastRecord) {
            $lastSequence = (int) substr($lastRecord->no_transaksi, strlen($prefix));
            $nextNumber = $lastSequence + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function petani(): BelongsTo
    {
        return $this->belongsTo(Petani::class, 'petani_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPengiriman(): HasMany
    {
        return $this->hasMany(DetailPengiriman::class, 'pasokan_id');
    }

    public function pembayaranPetani(): HasMany
    {
        return $this->hasMany(PembayaranPetani::class, 'pasokan_id');
    }
}
