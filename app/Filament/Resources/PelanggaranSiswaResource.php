<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelanggaranSiswaResource\Pages;
use App\Filament\Resources\PelanggaranSiswaResource\RelationManagers;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PelanggaranSiswaResource extends Resource
{
    protected static ?string $model = PelanggaranSiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $pluralModelLabel = 'Pelanggaran Siswa';

    public static function form(Form $form): Form
    {
        if (request()->user()->hasRole('Wali Kelas')) {
            $users = Siswa::with('user')->where('kelas_id', request()->user()->walikelas->kelas_id);
        } else {
            $users = Siswa::with('user');
        }
        return $form
            ->schema([
                Forms\Components\Select::make('pelanggaran_id')
                    ->relationship('pelanggaran', 'nama_pelanggaran')
                    ->searchable(),
                Forms\Components\Select::make('siswa_id')
                    ->label('Siswa')
                    ->options($users->get()->mapWithKeys(fn($data) => [
                        $data->id => $data->user->name
                    ])->toArray())
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $pelanggaranSiswa = PelanggaranSiswa::query();
        if (auth()->user()->hasRole('Wali Kelas')) {
            $pelanggaranSiswa->whereHas('siswa', fn($q) => $q->where('kelas_id', auth()->user()->walikelas->kelas_id));
        }

        if (auth()->user()->hasRole('Siswa')) {
            $pelanggaranSiswa->where('siswa_id', auth()->user()->siswa->id);
        }

        return $table
            ->query($pelanggaranSiswa)
            ->columns([

                TextColumn::make('siswa.nim')->searchable()->label('Nis'),
                TextColumn::make('siswa.user.name')->searchable()->label('Nama Siswa'),
                TextColumn::make('pelanggaran.nama_pelanggaran')->label('Pelanggaran')->searchable(),
                TextColumn::make('pelanggaran.point_pengurangan')->label('- Point')->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(request()->user()->hasRole(['Wali Kelas', 'super-admin'])),
                Tables\Actions\DeleteAction::make()->visible(request()->user()->hasRole(['Wali Kelas', 'super-admin'])),
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
            'index' => Pages\ListPelanggaranSiswas::route('/'),
            'create' => Pages\CreatePelanggaranSiswa::route('/create'),
            'edit' => Pages\EditPelanggaranSiswa::route('/{record}/edit'),
            'view' => Pages\ViewPelanggaranSiswa::route('/{record}'),
        ];
    }
}
