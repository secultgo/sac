<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Painel\DepartamentoController;
use App\Http\Controllers\Painel\ProblemaController;
use App\Http\Controllers\Painel\ServicoChamadoController;
use App\Http\Controllers\Painel\LocalController;
use App\Http\Controllers\Painel\UserController;
use App\Http\Controllers\Painel\LdapController;
use App\Http\Controllers\Painel\ChamadoController;
use App\Http\Controllers\Painel\LoginController;
use App\Http\Controllers\Painel\DashboardController;

Route::get('/', function () {
    return redirect('/painel');
});

Route::get('/login', [LoginController::class, 'LoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('painel')
     ->middleware('auth')
     ->group(function () {
         Route::get('/', [DashboardController::class, 'index'])->name('painel.dashboard');
         
         // Rotas de perfil
         Route::get('/usuarios/completar-perfil', [UserController::class, 'completarPerfil'])->name('usuarios.completar-perfil');
         Route::put('/usuarios/atualizar-perfil', [UserController::class, 'atualizarPerfil'])->name('usuarios.atualizar-perfil');
         
         Route::resource('departamentos', DepartamentoController::class);

         Route::resource('problemas', ProblemaController::class);
         Route::put('problemas/{problema}/desativar', [ProblemaController::class, 'desativar'])
            ->name('problemas.desativar');
         Route::put('problemas/{problema}/ativar', [ProblemaController::class, 'ativar'])
            ->name('problemas.ativar');

        Route::resource('servicos', ServicoChamadoController::class)->parameters(['servicos' => 'servicosChamado']);
        Route::get('servicos/problemas-por-departamento/{departamento}', [ServicoChamadoController::class, 'problemasPorDepartamento'])->name('servicos.problemasPorDepartamento');

        Route::resource('locais', LocalController::class)->parameters(['locais' => 'local']);

        Route::resource('usuarios', UserController::class)->names('usuarios')->except(['show']);
        Route::get('usuarios/{usuario}/edit-nivel', [UserController::class, 'edit_nivel'])->name('usuarios.edit_nivel')->middleware('can:gestor');
        Route::put('usuarios/{usuario}/nivel', [UserController::class, 'updateNivel'])->name('usuarios.update_nivel')->middleware('can:gestor');
        Route::put('usuarios/{usuario}/ativar', [UserController::class, 'ativar'])->name('usuarios.ativar');
        Route::put('usuarios/{usuario}/desativar', [UserController::class, 'desativar'])->name('usuarios.desativar');

        Route::get('usuarios/ldap', [UserController::class, 'importarLdap'])->name('usuarios.importar.ldap');
        Route::post('usuarios/importar-ldap', [UserController::class, 'importFromLdap'])->name('usuarios.importar.ldap.post');
        Route::resource('ldap', LdapController::class);

        Route::get('chamados/create', [ChamadoController::class, 'create'])->name('chamados.create');
        Route::post('chamados', [ChamadoController::class, 'store'])->name('chamados.store');
        Route::get('chamados/{chamado}', [ChamadoController::class, 'show'])->name('chamados.show');
        Route::post('chamados/{chamado}/comentarios', [ChamadoController::class, 'adicionarComentario'])->name('chamados.comentarios.store');
        Route::put('chamados/{chamado}/pendencia', [ChamadoController::class, 'colocarPendencia'])->name('chamados.pendencia');
        Route::put('chamados/{chamado}/atender', [ChamadoController::class, 'atenderChamado'])->name('chamados.atender');
        Route::put('chamados/{chamado}/iniciar-atendimento', [ChamadoController::class, 'iniciarAtendimento'])->name('chamados.iniciar');
        Route::put('chamados/{chamado}/devolver-usuario', [ChamadoController::class, 'devolverUsuario'])->name('chamados.devolver');
        Route::put('chamados/{chamado}/resolver', [ChamadoController::class, 'resolverChamado'])->name('chamados.resolver');
        Route::put('chamados/{chamado}/alterar-responsavel', [ChamadoController::class, 'alterarResponsavel'])->name('chamados.alterar-responsavel');
        Route::put('chamados/{chamado}/transferir', [ChamadoController::class, 'transferirDepartamento'])->name('chamados.transferir');
        Route::put('chamados/{chamado}/atribuir-responsavel', [ChamadoController::class, 'atribuirResponsavel'])->name('chamados.atribuir-responsavel');
        Route::get('chamados/{chamado}/usuarios-departamento', [ChamadoController::class, 'usuariosDepartamento'])->name('chamados.usuarios-departamento');
        Route::put('chamados/{chamado}/avaliar', [ChamadoController::class, 'avaliarChamado'])->name('chamados.avaliar');
        Route::put('chamados/{chamado}/reabrir', [ChamadoController::class, 'reabrirChamado'])->name('chamados.reabrir');
        Route::get('chamados', function() { return redirect()->route('painel.dashboard'); })->name('chamados.index');
        Route::get('meus-atendimentos', [ChamadoController::class, 'meusAtendimentos'])
            ->name('meus-atendimentos.index')
            ->middleware('nivel.atendimento');
        Route::get('equipe', [UserController::class, 'equipe'])
            ->name('equipe.index')
            ->middleware('can:gestor');
        Route::get('avaliacoes', [UserController::class, 'avaliacoes'])
            ->name('avaliacoes.index')
            ->middleware('can:gestor');
        Route::post('avaliacoes/{chamado}/ciente', [UserController::class, 'marcarCiente'])
            ->name('avaliacoes.ciente')
            ->middleware('can:gestor');
        Route::get('meus-chamados', [ChamadoController::class, 'meusChamados'])->name('meus-chamados.index');
        Route::get('chamados/problemas/{departamento}', [ChamadoController::class, 'problemasPorDepartamento'])->name('chamados.problemasPorDepartamento');
        Route::get('chamados/servicos/{problema}', [ChamadoController::class, 'servicosPorProblema'])->name('chamados.servicosPorProblema');
     });
