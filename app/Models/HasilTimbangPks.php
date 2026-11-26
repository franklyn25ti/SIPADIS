<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilTimbangPks extends Model
{
    protected $table = 'hasil_timbang_pks';

    protected $fillable = [
        'no_nota_timbang',
        'pengiriman_id',
        'bruto',
        'tara',
        'netto',
        'rafaksi',
        'berat_dibayar',
        'harga_jual',
        'nilai_penjualan',
        'status_penerimaan',
        'tanggal_terima_dana',
        'jumlah_diterima',
    ];

    protected function casts(): array
    {
        return [
            'bruto' => 'decimal:2',
            'tara' => 'decimal:2',
            'netto' => 'decimal:2',
            'rafaksi' => 'decimal:2',
            'berat_dibayar' => 'decimal:2',
            'harga_jual' => 'decimal:2',
            'nilai_penjualan' => 'decimal:2',
            'tanggal_terima_dana' => 'date',
            'jumlah_diterima' => 'decimal:2',
        ];
    }

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(PengirimanTbs::class, 'pengiriman_id');
    }

    public function getSisaPiutangAttribute(): float
    {
        return round(max(0, (float) $this->nilai_penjualan - (float) $this->jumlah_diterima), 2);
    }
}
