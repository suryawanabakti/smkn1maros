<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaliKelasResource\Pages;
use App\Filament\Resources\WaliKelasResource\RelationManagers;
use App\Models\WaliKelas;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WaliKelasResource extends Resource
{
    protected static ?string $model = WaliKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'Wali Kelas';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kelas_id')->relationship('kelas', 'nama_kelas')->required(),
                TextInput::make('name') // This represents the 'user' name, handled in logic
                    ->label('Nama')
                    ->required()
                    ->visibleOn('create'), // Visible only when editing,
                TextInput::make('email') // This represents the 'user' email, handled in logic
                    ->label('Email')
                    ->email()
                    ->required()->unique(ignoreRecord: true)
                    ->visibleOn('create'), // Visible only when editing,,
                TextInput::make('nomor')->required()->numeric()->unique(ignoreRecord: true), // Abaikan validasi untuk record yang sedang diupdate,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')->copyable(),
                TextColumn::make('user.name')->label('Nama')->searchable(),
                TextColumn::make('user.email')->label('Email')->searchable()->copyable(),
                TextColumn::make('kelas.nama_kelas')->label('Kelas')->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWaliKelas::route('/'),
            'create' => Pages\CreateWaliKelas::route('/create'),
            'edit' => Pages\EditWaliKelas::route('/{record}/edit'),
        ];
    }
}
