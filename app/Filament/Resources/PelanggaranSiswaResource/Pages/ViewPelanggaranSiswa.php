<?php

namespace App\Filament\Resources\PelanggaranSiswaResource\Pages;

use App\Filament\Resources\PelanggaranSiswaResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPelanggaranSiswa extends ViewRecord
{
    protected static string $resource = PelanggaranSiswaResource::class;
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('siswa.nim')->label('NIS'),
                TextEntry::make('siswa.user.name')->label('Nama Siswa'),
                TextEntry::make('siswa.point')->label('Sisa Point'),
                TextEntry::make('pelanggaran.nama_pelanggaran')->label('Pelanggaran'),
                TextEntry::make('pelanggaran.point_pengurangan')->label('Point Pengurangan'),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
