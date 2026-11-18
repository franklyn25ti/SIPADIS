<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    protected $fillable = ['nama_petani', 'no_hp', 'alamat', 'luas_lahan'];
}