<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Pago;
use App\Observers\PagoObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Pago::observe(PagoObserver::class);

        // Modal de cámara global
            \Filament\Facades\Filament::registerRenderHook(
                'panels::body.end',
                fn() => view('components.camara-modal')
            );

        if (app()->environment('local')) {
            $tenant = \App\Models\Tenant::first();
            if ($tenant) {
                tenancy()->initialize($tenant);
            }
        }
    }
}