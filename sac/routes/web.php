<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix("/admin")->group(function(){
    Route::get("/cadastrar-produto", [ProductsController::class, "create"])->name("products.create");
    Route::post("/salvar-produto", [ProductsController::class, "store"])->name("products.store");
    Route::get("/produtos", [ProductsController::class, "index"])->name("products.index");
    Route::get("/produto/{id}", [ProductsController::class, "show"])->name("products.show");
    Route::get("/editar-produto/{id}", [ProductsController::class, "edit"])->name("products.edit");
    Route::post("/atualizar-produto/{id}", [ProductsController::class, "update"])->name("products.update");
});

//Route::redirect("/produtos", "/cadastrar-produto", 301);

