<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa';



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nim')
                    ->label('Nis')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nowa_orangtua')
                    ->label('Nomor Whatsapp Orang Tua')
                    ->required()
                    ->numeric(),

                TextInput::make('name')
                    ->label('Nama Siswa')
                    ->required()
                    ->afterStateHydrated(function ($component, $state, $record) {
                        // Load the user's name when editing
                        if ($record && $record->user) {
                            $component->state($record->user->name);
                        }
                    }),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->afterStateHydrated(function ($component, $state, $record) {
                        // Load the user's email when editing
                        if ($record && $record->user) {
                            $component->state($record->user->email);
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nim')
            ->columns([
                Tables\Columns\TextColumn::make('nim')->label('Nis'),
                Tables\Columns\TextColumn::make('user.name')->label('Nama Siswa')->description(fn($record) => $record->user->email ?? 'Belum ada'),
                Tables\Columns\TextColumn::make('nowa_orangtua'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $user = User::create([
                            'name' => $data['name'],
                            'username' => $data['nim'],
                            'password' => bcrypt($data['nim']),
                            'email' => $data['email'],
                        ]);

                        // Assign a role to the user
                        $user->assignRole('Siswa');

                        // Associate the user with the siswa record
                        $data['user_id'] = $user->id;

                        // Remove 'name' and 'email' from the data array as they belong to the user
                        unset($data['name'], $data['email']);
                        $data['point'] = 250;
                        return $data;
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, Model $record): array {
                        // Update the user record
                        if (isset($data['name']) && isset($data['email'])) {
                            $user = User::find($record->user_id);
                            if ($user) {
                                $user->update([
                                    'name' => $data['name'],
                                    'email' => $data['email'],
                                ]);
                            }

                            // Remove name and email from the data array
                            unset($data['name'], $data['email']);
                        }

                        return $data;
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
