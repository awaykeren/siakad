<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    protected $table = 'dosen';

    protected $fillable = [
        'nidn',
        'nama',
        'prodi',
    ];

    // Satu dosen memiliki banyak mata kuliah
    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'dosen_id');
    }
}
