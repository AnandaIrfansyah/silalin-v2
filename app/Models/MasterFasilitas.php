<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFasilitas extends Model
{
    protected $table = 'master_fasilitas';

    protected $fillable = ['nama_fasilitas', 'kode', 'deskripsi', 'is_active', 'urutan'];

    protected $casts = ['is_active' => 'boolean'];
}
