<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Event;
use App\Models\Departamento;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Busca departamentos que atendem chamados
            try {
                $departamentos = Departamento::whereIn('departamento_id', function($query) {
                    $query->select('departamento_id')
                          ->from('chamado')
                          ->distinct();
                })->orderBy('departamento_sigla')->get();

                \Log::info('MenuServiceProvider executado. Departamentos encontrados: ' . $departamentos->count());

                // Adiciona itens ao menu usando o builder na posição correta
                // Adiciona antes do menu "Departamentos"
                $event->menu->addBefore('departamentos', [
                    'key' => 'relatorios_dynamic',
                    'text' => 'Relatórios',
                    'icon' => 'fas fa-chart-bar',
                    'can' => 'gestor',
                    'submenu' => [
                        [
                            'text' => 'Todos',
                            'url' => 'painel/relatorios/todos',
                            'icon' => 'fas fa-list',
                            'can' => 'super-admin',
                        ],
                    ],
                ]);

                // Adiciona departamentos dinamicamente ao submenu de relatórios
                foreach ($departamentos as $departamento) {
                    if (!empty($departamento->departamento_sigla)) {
                        \Log::info('Adicionando departamento: ' . $departamento->departamento_sigla);
                        $event->menu->addIn('relatorios_dynamic', [
                            'text' => $departamento->departamento_sigla,
                            'url' => 'painel/relatorios/departamento/' . $departamento->departamento_id,
                            'icon' => 'fas fa-building',
                            'can' => function() use ($departamento) {
                                $user = auth()->user();
                                if (!$user) return false;
                                
                                // Super admin vê todos os departamentos
                                if ($user->isSuperAdmin()) {
                                    return true;
                                }
                                
                                // Gestor vê apenas seu departamento
                                return $user->isGestor() && $user->departamento_id == $departamento->departamento_id;
                            },
                        ]);
                    }
                }

                \Log::info('Menu dinâmico de relatórios criado com sucesso');
            } catch (\Exception $e) {
                \Log::error('Erro no MenuServiceProvider: ' . $e->getMessage());
                // Em caso de erro (ex: durante migrations), mantém o menu básico
            }
        });
    }
}
