<?php

namespace App\Filament\Resources\WaliKelasResource\Pages;

use App\Filament\Resources\WaliKelasResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWaliKelas extends CreateRecord
{
    protected static string $resource = WaliKelasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['nomor'],
            'password' => bcrypt($data['nomor']),
            'email' => $data['email']
        ]);

        $user->assignRole('Wali Kelas');
        $data['user_id'] = $user->id;
        unset($data['name'], $data['email']);

        return $data;
    }
}
