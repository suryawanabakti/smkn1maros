<?php

namespace App\Filament\Resources\PelanggaranSiswaResource\Widgets;

use App\Models\PelanggaranSiswa;
use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        if (auth()->user()->hasRole('Siswa')) {
            return [
                Stat::make('Jumlah Pelanggaran Saya', PelanggaranSiswa::where('siswa_id', auth()->user()->siswa->id)->count()),
                Stat::make('Jumlah Prestasi Saya', Prestasi::where('user_id', auth()->id())->count()),
                Stat::make('Sisa Point', Siswa::where('user_id', auth()->id())->first()->point),
            ];
        }

        if (auth()->user()->hasRole('Wali Kelas')) {
            return [
                Stat::make('Jumlah siswa', Siswa::where('kelas_id', request()->user()->walikelas->kelas_id)->count()),
                Stat::make('Jumlah Pelanggaran Siswa', PelanggaranSiswa::whereHas('siswa', function ($query) {
                    $query->where('kelas_id', request()->user()->walikelas->kelas_id);
                })->count()),
                Stat::make('Jumlah Prestasi ', Prestasi::whereHas('user.siswa', function ($query) {
                    $query->where('kelas_id', request()->user()->walikelas->kelas_id);
                })->count()),
            ];
        }

        return [
            Stat::make('Jumlah siswa', Siswa::count()),
            Stat::make('Jumlah Pelanggaran Siswa', PelanggaranSiswa::query()->count()),
            Stat::make('Jumlah Prestasi ', Prestasi::query()->count()),
        ];
    }
}
