<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petani extends Model
{
    protected $table = 'petanis';

    protected $fillable = [
        'nama_petani',
        'no_hp',
        'alamat',
        'luas_lahan',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'luas_lahan' => 'decimal:2',
            'status_aktif' => 'boolean',
        ];
    }

    public function getNamaAttribute(): ?string
    {
        return $this->nama_petani;
    }

    public function getNoKontakAttribute(): ?string
    {
        return $this->no_hp;
    }

    public function pasokanTbs(): HasMany
    {
        return $this->hasMany(PasokanTbs::class, 'petani_id');
    }
}
