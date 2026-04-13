<?php
namespace App\Services;

use App\Models\Configuracion;
use App\Models\Socio;
use App\Models\Tenant;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class GeneradorCredencial
{
    public static function generar(Socio $socio): string
    {
        if (!tenancy()->initialized) {
            $tenant = Tenant::first();
            if ($tenant) tenancy()->initialize($tenant);
        }

        $zonas   = Configuracion::zonasDefault();
        $manager = new ImageManager(new Driver());

        try {
            $config = Configuracion::instancia();
            if ($config->zonas_credencial) {
                $zonas = $config->zonas_credencial;
            }
        } catch (\Exception $e) {
            // usar zonas default
        }

        // Crear lienzo base
        $img = $manager->create(800, 500);
        $img->fill('#1a1a1a');

        // Cargar plantilla si existe
        $plantillaPath = storage_path('app/private/' . ($config->plantilla_credencial ?? ''));
        if (!empty($config->plantilla_credencial) && file_exists($plantillaPath)) {
            $img = $manager->read($plantillaPath);
            $img->resize(800, 500);
        }

        // Foto del socio
       $fotoPath = storage_path('app/private/' . ($socio->fotografia ?? ''));
        if (!empty($socio->fotografia) && file_exists($fotoPath)) {
            try {
                $z = $zonas['foto'];
                $w = (int)$z['w'];
                $h = (int)$z['h'];

                // Cargar y redimensionar foto con GD
                $fotoImg  = $manager->read($fotoPath)->cover($w, $h);
                $fotoGd = $fotoImg->core()->native();

                // Crear lienzo transparente
                $circular = imagecreatetruecolor($w, $h);
                imagealphablending($circular, false);
                imagesavealpha($circular, true);
                $transparente = imagecolorallocatealpha($circular, 0, 0, 0, 127);
                imagefilledrectangle($circular, 0, 0, $w, $h, $transparente);

                // Dibujar círculo recortando la foto
                imagealphablending($circular, true);
                imagesetthickness($circular, 1);
                for ($y2 = 0; $y2 < $h; $y2++) {
                    for ($x2 = 0; $x2 < $w; $x2++) {
                        $cx = $w / 2;
                        $cy = $h / 2;
                        if ((($x2 - $cx) ** 2 + ($y2 - $cy) ** 2) <= ($cx * $cy)) {
                            $color = imagecolorat($fotoGd, $x2, $y2);
                            imagesetpixel($circular, $x2, $y2, $color);
                        }
                    }
                }

                // Guardar temporal y colocar en la credencial
                $tmpCircular = storage_path('app/private/foto_circular_tmp_' . $socio->id . '.png');
                imagepng($circular, $tmpCircular);
                imagedestroy($circular);
                imagedestroy($fotoGd);

                $fotoCircular = $manager->read($tmpCircular);
                $img->place($fotoCircular, 'top-left', (int)$z['x'], (int)$z['y']);

                // Borde dorado
                $cx = (int)$z['x'] + $w / 2;
                $cy = (int)$z['y'] + $h / 2;
                $img->drawEllipse($cx, $cy,
                    function (\Intervention\Image\Geometry\Factories\EllipseFactory $ellipse) use ($w, $h) {
                        $ellipse->size($w + 4, $h + 4);
                        $ellipse->border('#f59e0b', 4);
                    }
                );

                if (file_exists($tmpCircular)) unlink($tmpCircular);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Foto error: ' . $e->getMessage());
            }
        }
        // Generar QR como PNG temporal
    try {
            $z         = $zonas['qr'];
            $qrSize    = (int)$z['w'];
            $qrMatrix  = (new QRCode)->getMatrix('GYM-SOCIO-' . $socio->id);
            $modules   = $qrMatrix->matrix();
            $count     = count($modules);
            $modSize   = (int)floor($qrSize / $count);
            $qrCanvas  = $manager->create($qrSize, $qrSize)->fill('#ffffff');

            foreach ($modules as $y => $row) {
                foreach ($row as $x => $module) {
                    if ($qrMatrix->isDark($module)) {
                        $qrCanvas->drawRectangle(
                            $x * $modSize,
                            $y * $modSize,
                            function (\Intervention\Image\Geometry\Factories\RectangleFactory $rect) use ($modSize) {
                                $rect->size($modSize, $modSize);
                                $rect->background('#000000');
                            }
                        );
                    }
                }
            }

            $img->place($qrCanvas, 'top-left', (int)$z['x'], (int)$z['y']);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QR error: ' . $e->getMessage());
        }
        // Textos — usar fuente nativa de GD si no hay TTF
        $fontPath = public_path('fonts/arial.ttf');
        $tieneFuente = file_exists($fontPath);

        // Nombre
        $z = $zonas['nombre'];
        if ($tieneFuente) {
            $img->text($socio->nombre_completo, (int)$z['x'], (int)$z['y'], function ($font) use ($z, $fontPath) {
                $font->filename($fontPath);
                $font->size((int)$z['size']);
                $font->color($z['color']);
            });
        }

        // Folio
        $z = $zonas['folio'];
        if ($tieneFuente) {
            $img->text(str_pad($socio->id, 4, '0', STR_PAD_LEFT), (int)$z['x'], (int)$z['y'], function ($font) use ($z, $fontPath) {
                $font->filename($fontPath);
                $font->size((int)$z['size']);
                $font->color($z['color']);
            });
        }

        // Membresía
        $membresia = strtoupper($socio->membresiaActiva?->plan?->nombre ?? 'SIN MEMBRESÍA');
        $z = $zonas['membresia'];
        if ($tieneFuente) {
            $img->text($membresia, (int)$z['x'], (int)$z['y'], function ($font) use ($z, $fontPath) {
                $font->filename($fontPath);
                $font->size((int)$z['size']);
                $font->color($z['color']);
            });
        }

        // Vencimiento
        $vencimiento = $socio->membresiaActiva?->fecha_fin?->format('d/m/Y') ?? 'N/A';
        $z = $zonas['vencimiento'];
        if ($tieneFuente) {
            $img->text('Vence: ' . $vencimiento, (int)$z['x'], (int)$z['y'], function ($font) use ($z, $fontPath) {
                $font->filename($fontPath);
                $font->size((int)$z['size']);
                $font->color($z['color']);
            });
        }

        // Guardar como JPEG (más compatible que PNG con GD)
        $carpeta = storage_path('app/private/socios/credenciales');
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $ruta     = 'socios/credenciales/credencial-' . $socio->id . '.jpg';
        $rutaFull = storage_path('app/private/' . $ruta);

      $encoded = $img->toJpeg(90);
        file_put_contents($rutaFull, $encoded->toString());

        return $ruta;
    }
}