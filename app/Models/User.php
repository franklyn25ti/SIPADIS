<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function getNamaAttribute(): string
    {
        return $this->name;
    }

    public function pasokanTbs(): HasMany
    {
        return $this->hasMany(PasokanTbs::class, 'user_id');
    }

    public function biayaOperasional(): HasMany
    {
        return $this->hasMany(BiayaOperasional::class, 'user_id');
    }
}
