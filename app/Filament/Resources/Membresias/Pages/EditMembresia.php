<?php

namespace App\Filament\Resources\Membresias\Pages;

use App\Filament\Resources\Membresias\MembresiaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMembresia extends EditRecord
{
    protected static string $resource = MembresiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
