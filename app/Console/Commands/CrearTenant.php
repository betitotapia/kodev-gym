<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;  // ← agregar esta línea
use App\Models\Tenant;

class CrearTenant extends Command
{
    protected $signature = 'tenant:crear {nombre} {dominio}';
    protected $description = 'Crea un nuevo gimnasio tenant con su BD y migraciones';

    public function handle(): void
    {
        $nombre  = $this->argument('nombre');
        $dominio = $this->argument('dominio');

        $this->info("Creando tenant: $nombre");

        $tenant = Tenant::create([
            'data' => ['nombre' => $nombre],
        ]);

        $tenant->domains()->create(['domain' => $dominio]);

        $this->info("Tenant creado con ID: {$tenant->id}");
        $this->info("Dominio: $dominio");

        tenancy()->initialize($tenant);

        $this->info("BD del tenant: " . DB::connection()->getDatabaseName());
        $this->info("Listo.");
    }
}