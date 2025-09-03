<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https'); // Força HTTPS em produção
        }

        // Gate para verificar se o usuário é super admin
        Gate::define('super-admin', function ($user) {
            return $user->isSuperAdmin();
        });

        // Gate para verificar se o usuário é gestor
        Gate::define('gestor', function ($user) {
            return $user->isGestor();
        });

        // Gate para verificar se o usuário pode atender
        Gate::define('atender', function ($user) {
            return $user->podeAtender();
        });

        // Gate para verificar se o usuário NÃO é apenas usuário comum (nível 4)
        Gate::define('nao-usuario-comum', function ($user) {
            return !$user->isUsuarioComum();
        });
    }
}
