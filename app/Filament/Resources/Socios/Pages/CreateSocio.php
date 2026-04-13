<?php

namespace App\Filament\Resources\Socios\Pages;

use App\Filament\Resources\Socios\SocioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSocio extends CreateRecord
{
   protected static string $resource = SocioResource::class;

    protected static ?string $title = 'Registrar socio';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
