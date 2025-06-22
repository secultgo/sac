<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Painel\DepartamentoController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('painel')
     ->group(function () {
         Route::resource('departamentos', DepartamentoController::class);
     });

//('/teste', function () {
    //return view('painel.dashboard.index');
//});

//Route::get('/dashboard', function () {
    //return view('painel.dashboard.index');
//})->middleware(['auth', 'verified'])->name('dashboard');

//Route::middleware('auth')->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

//require __DIR__.'/auth.php';

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
