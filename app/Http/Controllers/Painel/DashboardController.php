<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Ldap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        return view('painel.dashboard.index'); 
    }

    public function meus()
    {
        return view('painel.dashboard.meus'); 
    }
}
