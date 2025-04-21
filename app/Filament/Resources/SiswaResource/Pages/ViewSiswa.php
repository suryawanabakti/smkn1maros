<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\Siswa2Resource;
use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name')->label('Nama Siswa'),
                TextEntry::make('kelas.nama_kelas')->label('Kelas'),
                TextEntry::make('user.email')->label('Email'),
                TextEntry::make('nim')->label('NIS'),
                TextEntry::make('nowa_orangtua')->label('Nomor Whatsapp Orang Tua'),
                TextEntry::make('point'),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
