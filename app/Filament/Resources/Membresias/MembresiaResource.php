<?php

namespace App\Filament\Resources\Membresias;

use App\Filament\Resources\Membresias\Pages\CreateMembresia;
use App\Filament\Resources\Membresias\Pages\EditMembresia;
use App\Filament\Resources\Membresias\Pages\ListMembresias;
use App\Filament\Resources\Membresias\Pages\ViewMembresia;
use App\Filament\Resources\Membresias\Schemas\MembresiaForm;
use App\Filament\Resources\Membresias\Tables\MembresiasTable;
use App\Models\Membresia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MembresiaResource extends Resource
{
    protected static ?string $model = Membresia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'estado';

    protected static ?string $navigationLabel = 'Membresías';
    protected static ?string $modelLabel = 'Membresía';
    protected static ?string $pluralModelLabel = 'Membresías';

    public static function form(Schema $schema): Schema
    {
        return MembresiaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MembresiasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMembresias::route('/'),
            'create' => CreateMembresia::route('/create'),
            'view'   => ViewMembresia::route('/{record}'),
            'edit'   => EditMembresia::route('/{record}/edit'),
        ];
    }
}