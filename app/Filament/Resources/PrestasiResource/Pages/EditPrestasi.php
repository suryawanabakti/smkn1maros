<?php

namespace App\Filament\Resources\PrestasiResource\Pages;

use App\Filament\Resources\PrestasiResource;
use App\Services\Fonnte;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPrestasi extends EditRecord
{
    protected static string $resource = PrestasiResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!auth()->user()->hasRole('Siswa')) {
            $message = "Assalamualaikum orang tua dari anak:\nNama: {$record->user?->name}\nJudul Prestasi: " . $data['judul'] . "\n\nTelah " . $data['status'];
            Fonnte::sendWa($record->user->siswa->nowa_orangtua, $message);
        }
        $record->update($data);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
