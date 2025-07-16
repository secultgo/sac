<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Departamento;
use App\Models\NivelUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Models\Nivel;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        $departamentos = Departamento::all();
        $nivel_usuarios = NivelUsuario::all();
        $niveis = Nivel::all(); 
    
        return view('painel.usuarios.index', compact('usuarios', 'departamentos', 'nivel_usuarios', 'niveis'));
    }
    
    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            $usuario = User::create([
                'usuario_nome'     => $data['usuario_nome'],
                'usuario_email'    => $data['usuario_email'],
                'usuario_senha'    => bcrypt($data['usuario_senha']),
                'departamento_id'  => $data['departamento_id'],
                'usuario_ldap'     => $data['usuario_ldap'],
            ]);

            NivelUsuario::create([
                'usuario_id' => $usuario->usuario_id,
                'nivel_id'   => 3, 
            ]);

            return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public function edit(User $usuario)
    {
        return view('painel.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'usuario_nome'  => 'nullable|string|max:100',
            'usuario_email' => 'nullable|email|max:100',
            'usuario_nivel' => 'required|exists:nivel,nivel_id',
        ]);

        $usuario->update([
            'usuario_nome'  => $request->usuario_nome ?? $usuario->usuario_nome,
            'usuario_email' => $request->usuario_email ?? $usuario->usuario_email,
        ]);

        \App\Models\NivelUsuario::updateOrCreate(
            ['usuario_id' => $usuario->usuario_id],  
            ['nivel_id' => $request->usuario_nivel]  
        );

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuário deletado com sucesso!');
    }

    public function edit_nivel(User $usuario)
    {
        $nivel_usuarios = Nivel::all();
        return view('painel.usuarios.edit_nivel', compact('usuario', 'nivel_usuarios'));
    }

    public function create()
    {
        $departamentos = Departamento::all();
        return view('painel.usuarios.create', compact('departamentos'));
    }

    public function updateNivel(Request $request, User $usuario)
    {
        $request->validate([
            'usuario_nivel' => 'required|exists:nivel,nivel_id',
        ]);

        NivelUsuario::updateOrCreate(
            ['usuario_id' => $usuario->usuario_id],
            ['nivel_id' => $request->usuario_nivel]
        );

        return redirect()->route('usuarios.index')->with('success', 'Nível do usuário atualizado com sucesso!');
    }

    public function ativar(User $usuario)
    {
        $usuario->update(['status_id' => 1]); 
        return redirect()->route('usuarios.index')->with('success', 'Usuário ativado com sucesso!');
    }

    public function desativar(User $usuario)
    {
        $usuario->update(['status_id' => 2]); 
        return redirect()->route('usuarios.index')->with('success', 'Usuário desativado com sucesso!');
    }

    public function importFromLdap()
    {
        $ldapServer = env('LDAP_SERVER');
        $ldapUser = env('LDAP_USER');
        $ldapPass = env('LDAP_PASS');
        $ldapTree = env('LDAP_TREE');
        $ldapFilter = env('LDAP_FILTER');

        // Conectar ao servidor LDAP
        $ldapconn = ldap_connect($ldapServer);
        if (!$ldapconn) {
            return redirect()->route('admin.users.index')->with('error', 'Não foi possível conectar ao servidor LDAP.');
        }

        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        // Autenticação no servidor LDAP
        $ldapbind = ldap_bind($ldapconn, $ldapUser, $ldapPass);
        if (!$ldapbind) {
            return redirect()->route('admin.users.index')->with('error', 'Falha na autenticação com o servidor LDAP.');
        }

        // Realizar a consulta no LDAP
        $result = ldap_search($ldapconn, $ldapTree, $ldapFilter);
        if (!$result) {
            return redirect()->route('admin.users.index')->with('error', 'Erro na consulta LDAP: ' . ldap_error($ldapconn));
        }

        $entries = ldap_get_entries($ldapconn, $result);

        // Atualizar usuários LDAP existentes para inativos antes da importação
        User::where('is_ldap_user', true)->update(['status' => 0]);

        // Processar os dados do LDAP
        for ($i = 0; $i < $entries['count']; $i++) {
            $ldapUser = $entries[$i];

            DB::beginTransaction();
            try {
                // Verificar se o usuário já existe
                $user = User::where('ldap_id', $ldapUser['usncreated'][0])->first();

                // Dados a serem importados ou atualizados
                $userData = [
                    'usuario_nome' => $ldapUser['cn'][0], // Nome completo
                    'usuario_usurio' => $ldapUser['samaccountname'][0], // Nome de usuário
                    'usuario_email' => $ldapUser['mail'][0] ?? null, // Email
                    'usuario_cpf' => $ldapUser['description'][0] ?? null, // CPF (caso esteja no campo 'description' do LDAP)
                    'usuario_celular' => $ldapUser['telephonenumber'][0] ?? null, // Telefone
                    'status_id' => 1, // Ativo
                    'usuario_ldap' => true, // Indica que o usuário é LDAP
                    'nivel_id' => 4, // Nível de permissão padrão (exemplo: Usuário)
                    'ldap_id' => $ldapUser['usncreated'][0], // Identificador único do LDAP
                ];

                if (!$user) {
                    // Criar novo usuário
                    User::create($userData);
                } else {
                    // Atualizar usuário existente
                    $user->update($userData);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro ao importar usuário LDAP: ' . $e->getMessage());
            }
        }

        ldap_close($ldapconn);

        return redirect()->route('admin.users.index')->with('success', 'Importação de usuários LDAP concluída.');
    }

}
