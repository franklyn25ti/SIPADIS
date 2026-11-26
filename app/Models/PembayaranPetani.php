<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPetani extends Model
{
    protected $table = 'pembayaran_petani';

    protected $fillable = [
        'pasokan_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'jumlah_bayar' => 'decimal:2',
        ];
    }

    public function pasokan(): BelongsTo
    {
        return $this->belongsTo(PasokanTbs::class, 'pasokan_id');
    }
}
