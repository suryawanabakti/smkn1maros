<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
