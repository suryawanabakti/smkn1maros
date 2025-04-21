<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers\PelanggaranSiswaRelationManager;
use App\Filament\Resources\SiswaResource\RelationManagers\PrestasiRelationManager;
use App\Models\Siswa;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'Siswa';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {

        return $form

            ->schema([
                Select::make('kelas_id')->relationship('kelas', 'nama_kelas')->required(),
                TextInput::make('nim')
                    ->label('NIS')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->numeric(),
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
                TextInput::make('email') // This represents the 'user' email, handled in logic
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->afterStateHydrated(function ($component, $state, $record) {
                        // Load the user's name when editing
                        if ($record && $record->user) {
                            $component->state($record->user->email);
                        }
                    }),
                // Visible only when editing,,
            ]);
    }

    public static function table(Table $table): Table
    {
        $siswas = Siswa::query();
        if (request()->user()->hasRole('Wali Kelas')) {
            $siswas->where('kelas_id', request()->user()->walikelas->kelas_id);
        }
        return $table
            ->query($siswas)
            ->columns([
                TextColumn::make('nim')->searchable()->label("NIS"),
                TextColumn::make('user.name')->searchable()->label('Nama Siswa'),
                TextColumn::make('user.email')->searchable()->label('Email'),
                TextColumn::make('nowa_orangtua')->searchable()->label('Whatsapp Orang Tua'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PelanggaranSiswaRelationManager::make(),
            PrestasiRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
            'view' => Pages\ViewSiswa::route('/{record}'),
        ];
    }
}
