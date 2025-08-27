<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chamado;
use Illuminate\Support\Facades\Auth;
use App\Models\Ldap;
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

        if (!$user) {
            return back()->withErrors([
                'usuario_email' => 'Usuário não encontrado.',
            ])->onlyInput('usuario_email');
        }

        // Se for usuário LDAP, valida no LDAP
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

            if (!@ldap_bind($ldapconn, $loginLdap, $senhaLdap)) {
                return back()->withErrors([
                'usuario_email' => 'As credenciais informadas não conferem.',
                ])->onlyInput('usuario_email');
            }
        }

        // Login bem-sucedido
        Auth::login($user);
        
        // Verificar se o usuário tem perfil completo (departamento e telefone)
        if (!$user->perfilCompleto()) {
            return redirect()->route('usuarios.completar-perfil')
                ->with('info', 'Para utilizar o sistema, você precisa completar as informações do seu perfil.');
        }
        
        // Verifica se é gestor e se há avaliações pendentes
        if ($user->isGestor()) {
            $avaliacoesPendentes = Chamado::where('departamento_id', $user->departamento_id)
                ->whereIn('avaliacao_chamado_id', [3, 4])
                ->whereNotNull('avaliacao_chamado_id')
                ->where('chamado_ciente_gestor', 0)
                ->count();
            
            if ($avaliacoesPendentes > 0) {
                session()->flash('avaliacoes_pendentes', $avaliacoesPendentes);
            }
        }
        
        $request->session()->regenerate();
        return redirect()->intended('/painel');
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
