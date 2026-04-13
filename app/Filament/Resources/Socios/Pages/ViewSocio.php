<?php

namespace App\Filament\Resources\Socios\Pages;

use App\Filament\Resources\Socios\SocioResource;
use App\Services\GeneradorCredencial;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewSocio extends ViewRecord
{
    protected static string $resource = SocioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editar')
                ->label('Editar')
                ->url(fn () => SocioResource::getUrl('edit', ['record' => $this->record])),
                
           Action::make('credencial')
                ->label('Generar credencial')
                ->color('success')
                ->action(function () {
                    try {
                        $ruta     = GeneradorCredencial::generar($this->record);
                        $rutaFull = storage_path('app/private/' . $ruta);

                        if (!file_exists($rutaFull)) {
                            throw new \Exception('Archivo no encontrado: ' . $rutaFull);
                        }

                        // Convertir a base64 y abrir en nueva pestaña
                        $base64   = base64_encode(file_get_contents($rutaFull));
                        $ext      = pathinfo($rutaFull, PATHINFO_EXTENSION);
                        $mime     = $ext === 'jpg' ? 'image/jpeg' : 'image/png';
                        $dataUrl  = "data:{$mime};base64,{$base64}";
                        $nombre   = 'credencial-' . str_pad($this->record->id, 4, '0', STR_PAD_LEFT) . '.' . $ext;

                        // Abrir en nueva pestaña y forzar descarga
                        $this->js("
                            const a = document.createElement('a');
                            a.href = '{$dataUrl}';
                            a.download = '{$nombre}';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        ");

                        Notification::make()
                            ->title('Credencial generada')
                            ->body('La descarga comenzará automáticamente.')
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}