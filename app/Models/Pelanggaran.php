<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $guarded = ['id'];

    public function pelanggaranSiswa()
    {
        return $this->hasMany(PelanggaranSiswa::class);
    }
}
