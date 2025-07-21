<?php

namespace App\Http\Controllers\painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    public function index()
    {
        return view('painel.atendimento.index');
    }

    public function ver(){
        return view('painel.atendimento.ver');
    }
}
