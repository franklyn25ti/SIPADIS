<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PksPembeli extends Model
{
    protected $table = 'pks_pembeli';

    protected $fillable = [
        'nama',
        'alamat',
        'no_kontak',
        'keterangan',
    ];

    public function pengirimanTbs(): HasMany
    {
        return $this->hasMany(PengirimanTbs::class, 'pks_id');
    }
}
