<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreOrUpdateProductsRequest;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = [
            0 => [
                'id' => 1,
                'product_name' => 'Produto 1',
                'product_code' => '123456',
                'product_description' => 'Descrição do produto 1',
                'price' => 10.00
            ],
            1 => [
                'id' => 2,
                'product_name' => 'Produto 2',
                'product_code' => '123456',
                'product_description' => 'Descrição do produto 2',
                'price' => 20.00
            ],
            

        ];

        return view('products.index', ["products" => $products]);
        //return redirect()->route('products.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrUpdateProductsRequest $request)
    {
        //Recebendo todos os dados do formulário
        $data = $request->all();
        //dd($data); dd é para exibir o que está dentro da variável $data

 
        dd($data);

        return redirect()->back()->with('error', 'Erro ao cadastrar produto');



        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = [
            0 => [
                'id' => 1,
                'product_name' => 'Produto 1',
                'product_code' => '123456',
                'product_description' => 'Descrição do produto 1',
                'price' => 10.00
            ],
            1 => [
                'id' => 2,
                'product_name' => 'Produto 2',
                'product_code' => '123456',
                'product_description' => 'Descrição do produto 2',
                'price' => 20.00
            ],
            

        ];

        return view('products.show', ["products" => $products[$id]]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $products = [
            0 => [
                'id' => 1,
                'product_name' => 'Produto 1',
                'product_code' => '123456',
                'product_description' => 'Descrição do produto 1',
                'price' => 10.00
            ],
            1 => [
                'id' => 2,
                'product_name' => 'Produto 2',
                'product_code' => '123456',
                'product_description' => 'Descrição do produto 2',
                'price' => 20.00
            ],
            

        ];

        return view('products.edit', ["products" => $products[$id]]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrUpdateProductsRequest $request, string $id)
    {
        $data = $request->all();
        dd($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
