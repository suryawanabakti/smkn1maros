<?php

namespace App\Filament\Resources\PelanggaranSiswaResource\Pages;

use App\Filament\Resources\PelanggaranSiswaResource;
use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Services\Fonnte;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePelanggaranSiswa extends CreateRecord
{
    protected static string $resource = PelanggaranSiswaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $siswa = Siswa::with('user')->where('id', $data['siswa_id'])->first();
        $pelanggaran = Pelanggaran::where('id', $data['pelanggaran_id'])->first();
        $pointSekarang = $siswa->point - $pelanggaran->point_pengurangan;
        $siswa->update([
            'point' => $pointSekarang
        ]);

        Fonnte::sendWa($siswa->nowa_orangtua, "Siswa dibawah ini telah melakukan pelanggaran \nNama : {$siswa->user->name} \nNis: {$siswa->nim} \nPelanggaran: {$pelanggaran->nama_pelanggaran}\nSisa Point : {$pointSekarang}");

        return $data;
    }
}
