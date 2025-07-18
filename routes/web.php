<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Painel\DepartamentoController;
use App\Http\Controllers\Painel\ProblemaController;
use App\Http\Controllers\Painel\ServicoChamadoController;
use App\Http\Controllers\Painel\LocalController;
use App\Http\Controllers\Painel\UserController;
use App\Http\Controllers\Painel\LdapController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('painel')->group(function () {
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('problemas', ProblemaController::class);
    Route::put('problemas/{problema}/desativar', [ProblemaController::class, 'desativar'])->name('problemas.desativar');
    Route::put('problemas/{problema}/ativar', [ProblemaController::class, 'ativar'])->name('problemas.ativar');

    Route::resource('servicos', ServicoChamadoController::class)->parameters(['servicos' => 'servicosChamado']);
    Route::resource('locais', LocalController::class)->parameters(['locais' => 'local']);

    Route::get('/usuarios/{usuario}/edit-nivel', [UserController::class, 'edit_nivel'])->name('usuarios.edit_nivel');
    Route::put('/usuarios/{usuario}/nivel', [UserController::class, 'updateNivel'])->name('usuarios.update_nivel');

    Route::resource('usuarios', UserController::class)->names('usuarios')->except(['show']);
    Route::get('/painel/usuarios', [UserController::class, 'index'])->name('painel.usuarios.index');

    Route::put('/usuarios/{usuario}/ativar', [UserController::class, 'ativar'])->name('usuarios.ativar');
    Route::put('/usuarios/{usuario}/desativar', [UserController::class, 'desativar'])->name('usuarios.desativar');
    Route::get('/usuarios/importar-ldap', [UserController::class, 'importFromLdap'])->name('usuarios.importar.ldap');

    Route::resource('ldap', LdapController::class);
});

// ('/teste', function () {
//     return view('painel.dashboard.index');
// });

// Route::get('/dashboard', function () {
//     return view('painel.dashboard.index');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
