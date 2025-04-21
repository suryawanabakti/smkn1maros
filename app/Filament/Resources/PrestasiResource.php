<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrestasiResource\Pages;
use App\Filament\Resources\PrestasiResource\RelationManagers;
use App\Models\Prestasi;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrestasiResource extends Resource
{
    protected static ?string $model = Prestasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $pluralModelLabel = 'Prestasi';

    public static function canDelete(Model $record): bool
    {
        return $record->status  === 'proses' && $record->user->hasRole('Siswa');
    }

    public static function form(Form $form): Form
    {
        if (request()->user()->hasRole('Wali Kelas')) {
            $users = User::whereHas('siswa', fn($q) => $q->where('kelas_id', request()->user()->walikelas->kelas_id));
        } else {
            $users = User::all();
        }
        return $form
            ->schema([
                auth()->user()->hasRole('super_admin') ?  Select::make('user_id')
                    ->options($users->pluck('name', 'id'))
                    ->label('Siswa')
                    ->searchable()
                    ->columnSpanFull()
                    ->disabled(fn($record) => $record !== null) : TextInput::make('user_id')->default(auth()->id())->hidden(),
                FileUpload::make('file')->directory('prestasi')->columnSpanFull()->required(),
                TextInput::make('judul')->required(),
                Select::make('status')->options([
                    'proses' => 'proses',
                    'diterima' => 'diterima',
                    'ditolak' => 'ditolak',
                ])->visible(!auth()->user()->hasRole('Siswa')),
            ]);
    }

    public static function table(Table $table): Table
    {
        $query = Prestasi::query();
        if (request()->user()->hasRole('Siswa')) {
            $query->where('user_id', auth()->id());
        }

        if (request()->user()->hasRole('Wali Kelas')) {
            $query->whereHas('user.siswa.kelas', fn($q) => $q->where('kelas_id', request()->user()->walikelas->kelas_id));
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('file')->formatStateUsing(fn($record) => "<a target='_blank' href='/storage/$record->file'>Download</a>")->html(),
                TextColumn::make('judul')->searchable(),
                TextColumn::make('status')->badge()->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPrestasis::route('/'),
            'create' => Pages\CreatePrestasi::route('/create'),
            'edit' => Pages\EditPrestasi::route('/{record}/edit'),
        ];
    }
}
