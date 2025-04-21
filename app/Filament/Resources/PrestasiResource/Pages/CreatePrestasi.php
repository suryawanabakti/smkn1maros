<?php

namespace App\Filament\Resources\PrestasiResource\Pages;

use App\Filament\Resources\PrestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePrestasi extends CreateRecord
{
    protected static string $resource = PrestasiResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (request()->user()->hasRole('Siswa')) {
            $data['user_id'] = auth()->id();
        }
        $data['status'] = 'proses';
        return $data;
    }
}
