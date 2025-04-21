<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pest\Preset;

class Siswa extends Model
{
    public $table = 'siswa';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pelanggaranSiswa()
    {
        return $this->hasMany(PelanggaranSiswa::class);
    }


    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'user_id', 'user_id');
    }
}
