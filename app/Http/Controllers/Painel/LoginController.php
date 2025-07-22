<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function LoginForm()
    {
        return view('painel.login.login_form'); 
    }

    /**
     * Processa o login.
     */
    public function login(Request $request)
    {
        // Validação básica
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentativa de autenticação
        if (Auth::attempt($credentials)) {
            // Regenerar sessão para evitar fixação
            $request->session()->regenerate();

            // Redirecionar para o painel ou rota protegida
            return redirect()->intended('painel');
        }

        // Se falhar, volta com erro
        return back()->withErrors([
            'email' => 'As credenciais informadas não conferem.',
        ])->onlyInput('email');
    }

    /**
     * Faz logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
