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

        if ($user) {
            Auth::login($user);
            return redirect()->intended('/painel');
        } else {
            return back()->withErrors([
                'usuario_email' => 'Usuário não encontrado.',
            ])->onlyInput('usuario_email');
        }

        if ($user->usuario_ldap) {
            $ldapConfig = Ldap::first();
            if (!$ldapConfig) {
                return back()->withErrors([
                    'usuario_email' => 'Configuração do LDAP não encontrada.',
                ]);
            }

            $ldapconn = ldap_connect($ldapConfig->ldap_server);
            if (!$ldapconn) {
                return back()->withErrors([
                    'usuario_email' => 'Erro ao conectar ao servidor LDAP.',
                ]);
            }

            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            // Usar o email completo como login LDAP
            $loginLdap = trim($user->usuario_email);
            $senhaLdap = trim($credentials['usuario_senha']);

            Log::debug("Tentando login LDAP com: {$loginLdap}");

            if (@ldap_bind($ldapconn, $loginLdap, $senhaLdap)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended('painel/usuarios');
            } else {
                return back()->withErrors([
                'usuario_email' => 'As credenciais informadas não conferem.',
                ])->onlyInput('usuario_email');
            }
        }
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
