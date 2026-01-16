<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Directiva Blade para @role
        Blade::if('role', function ($roles) {
            $roles = explode('|', $roles);

            // Opción A: Con Spatie
//            return auth()->check() && auth()->user()->hasAnyRole($roles);

            // Opción B: Sin Spatie (usando tipo_usuario)
             return auth()->check() && in_array(auth()->user()->tipo_usuario, $roles);
        });
    }
}



