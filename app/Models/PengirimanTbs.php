<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengirimanTbs extends Model
{
    protected $table = 'pengiriman_tbs';

    protected $fillable = [
        'no_surat_jalan',
        'tanggal_kirim',
        'kendaraan',
        'sopir',
        'pks_id',
        'status_pengiriman',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kirim' => 'date',
        ];
    }

    /**
     * Generate kode pengiriman otomatis dengan format PGM-YYYYMMDD-XXX.
     */
    public static function generateNoSuratJalan(?string $tanggal = null): string
    {
        $datePart = $tanggal ? date('Ymd', strtotime($tanggal)) : now()->format('Ymd');
        $prefix = "PGM-{$datePart}-";

        $lastRecord = static::where('no_surat_jalan', 'like', "{$prefix}%")
            ->orderByDesc('no_surat_jalan')
            ->first();

        $nextNumber = 1;
        if ($lastRecord) {
            $lastSequence = (int) substr($lastRecord->no_surat_jalan, strlen($prefix));
            $nextNumber = $lastSequence + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getTotalBeratDimuatAttribute(): float
    {
        return (float) $this->detailPengiriman->sum('berat_dimuat');
    }

    public function pks(): BelongsTo
    {
        return $this->belongsTo(PksPembeli::class, 'pks_id');
    }

    public function detailPengiriman(): HasMany
    {
        return $this->hasMany(DetailPengiriman::class, 'pengiriman_id');
    }

    public function hasilTimbangPks(): HasOne
    {
        return $this->hasOne(HasilTimbangPks::class, 'pengiriman_id');
    }
}
