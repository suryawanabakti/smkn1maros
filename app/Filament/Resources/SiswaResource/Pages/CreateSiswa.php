<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Create the user record
        if (auth()->user()->hasRole('Wali Kelas')) {
            $data['kelas_id'] = auth()->user()->walikelas->kelas_id;
        }

        $user = User::create([
            'name' => $data['name'], // Extracted from form input
            'username' => $data['nim'],
            'password' => bcrypt($data['nim']),
            'email' => $data['email'], // Extracted from form input
        ]);

        // Assign a role to the user
        $user->assignRole('Siswa');

        // Associate the user with the siswa record
        $data['user_id'] = $user->id;

        // Remove 'name' and 'email' from the data array as they belong to the user
        unset($data['name'], $data['email']);
        $data['point'] = 250;
        return $data;
    }
}
