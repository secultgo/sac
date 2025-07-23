<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $credentials = $request->validate([
            'usuario_email' => ['required', 'email'],
            'usuario_senha' => ['required'],
        ]);

        // Procura o usuário pelo email
        $user = User::where('usuario_email', $credentials['usuario_email'])->first();

        // Verifica se o usuário existe e se a senha MD5 confere
        if ($user && $user->usuario_senha === md5($credentials['usuario_senha'])) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('painel/usuarios');
        }

        return back()->withErrors([
            'usuario_email' => 'As credenciais informadas não conferem.',
        ])->onlyInput('usuario_email');
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
