<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengiriman extends Model
{
    protected $table = 'detail_pengiriman';

    protected $fillable = [
        'pengiriman_id',
        'pasokan_id',
        'berat_dimuat',
    ];

    protected function casts(): array
    {
        return [
            'berat_dimuat' => 'decimal:2',
        ];
    }

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(PengirimanTbs::class, 'pengiriman_id');
    }

    public function pasokan(): BelongsTo
    {
        return $this->belongsTo(PasokanTbs::class, 'pasokan_id');
    }
}
