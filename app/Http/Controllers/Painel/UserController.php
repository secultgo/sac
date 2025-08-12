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
use App\Models\Ldap;
use Illuminate\Support\Facades\DB;


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
    
    function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            if (!empty($data['usuario_id'])) {
                $usuario = User::create([
                    'usuario_id' => $data['usuario_id'],
                    'usuario_nome'     => $data['usuario_nome'],
                    'usuario_email'    => $data['usuario_email'],
                    'usuario_cpf'      => $data['usuario_cpf'],
                    'usuario_senha'    => md5($data['usuario_senha']),
                    'departamento_id'  => $data['departamento_id'],
                    'usuario_ldap'     => $data['usuario_ldap'],
                    'status_id' => $data['status_id'],
                ]);
    
                NivelUsuario::create([
                    'usuario_id' => $usuario->usuario_id,
                    'nivel_id'   => 3, 
                ]);
    
            } else {
                $usuario = User::create([
                    'usuario_nome'     => $data['usuario_nome'],
                    'usuario_email'    => $data['usuario_email'],
                    'usuario_cpf'      => $data['usuario_cpf'],
                    'usuario_senha'    => md5($data['usuario_senha']),
                    'departamento_id'  => $data['departamento_id'],
                    'usuario_ldap'     => $data['usuario_ldap'],
                ]);
    
                NivelUsuario::create([
                    'usuario_id' => $usuario->usuario_id,
                    'nivel_id'   => 3, 
                ]);
    
            }
           
            return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    } 


    public function edit(User $usuario)
    {
        $departamentos = Departamento::all();
        $niveis = Nivel::all();
        $nivelUsuario = NivelUsuario::where('usuario_id', $usuario->usuario_id)->first();
        return view('painel.usuarios.edit', compact('usuario', 'departamentos', 'niveis', 'nivelUsuario'));
    }

    public function update(Request $request, User $usuario)
    {
        
        try {
            $request->validate([
                'usuario_nome'     => 'required|string|max:100',
                'usuario_email'    => 'required|email|max:100|unique:usuario,usuario_email,' . $usuario->usuario_id . ',usuario_id',
                'usuario_cpf'      => ['nullable', 'string', 'max:14', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', 'unique:usuario,usuario_cpf,' . $usuario->usuario_id . ',usuario_id'],
                'departamento_id'  => 'required|exists:departamento,departamento_id',
                'usuario_ldap'     => 'required|in:0,1',
                'usuario_nivel'    => 'required|exists:nivel,nivel_id',
            ]);

            $usuario->update([
                'usuario_nome'     => $request->usuario_nome,
                'usuario_email'    => $request->usuario_email,
                'usuario_cpf'      => $request->usuario_cpf,
                'departamento_id'  => $request->departamento_id,
                'usuario_ldap'     => $request->usuario_ldap,
                'status_id' => $request->status_id,
            ]);

            \App\Models\NivelUsuario::updateOrCreate(
                ['usuario_id' => $usuario->usuario_id],
                ['nivel_id'   => $request->usuario_nivel]
            );

            return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public function destroy(User $usuario)
    {
        $usuario->nivelUsuarios()->delete();
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

    public function importarLdap()
    {
        $ldaps = Ldap::pluck('ldap_server');
        return view('painel.usuarios.ldap', compact('ldaps'));
    }

    public function importFromLdap(Request $request)
    {
        $ldapServer = $request->input('ldap_server');
    
        $ldapConfig = Ldap::where('ldap_server', $ldapServer)->first();
        if (!$ldapConfig) {
            return redirect()->back()->withErrors('Servidor LDAP não encontrado no banco.');
        }

        $ldapServer = $ldapConfig->ldap_server;
        $ldapUser = $ldapConfig->ldap_user;
        $ldapPass = $ldapConfig->ldap_pass;
        $ldapTree = $ldapConfig->ldap_tree;
        $ldapFilter = $ldapConfig->ldap_filter;

    
        $ldapconn = ldap_connect($ldapServer);
    
        if (!$ldapconn) {
            return redirect()->route('usuarios.index')->with('error', 'Não foi possível conectar ao servidor LDAP.');
        }
    
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
    
        if (!ldap_bind($ldapconn, $ldapUser, $ldapPass)) {
            return redirect()->route('usuarios.index')->with('error', 'Falha na autenticação com o servidor LDAP.');
        }
    
        $result = ldap_search($ldapconn, $ldapTree, $ldapFilter);
    
        if (!$result) {
            return redirect()->route('usuarios.index')->with('error', 'Erro na consulta LDAP: ' . ldap_error($ldapconn));
        }
    
        $entries = ldap_get_entries($ldapconn, $result);
    
        // Desativar todos os usuários LDAP existentes (equivale ao desativarGeral() do CI)
        User::where('usuario_ldap', true)->update(['status_id' => 2]);
        
        $usuariosProcessados = 0;
        $usuariosAtualizados = 0;
        $usuariosCriados = 0;
        $erros = 0;
        
        // Iterar sobre os dados do LDAP
        for ($i = 0; $i < $entries['count']; $i++) {

            $ldapUser = $entries[$i];
            $usuariosProcessados++;
    
            DB::beginTransaction();
            try {
                // Verificar se o usuário já existe pelo usuario_id
                $usuario = User::where('usuario_id', $ldapUser['usncreated'][0])->first();
                
                if (!$usuario) {
                    // Criar novo usuário (seguindo a estrutura do CI original)
                    $userData = [
                        'usuario_id'        => (int) $ldapUser['usncreated'][0],
                        'usuario_nome'      => $ldapUser['cn'][0],
                        'usuario_usuario'   => $ldapUser['samaccountname'][0],
                        'usuario_email'     => isset($ldapUser['mail'][0]) ? $ldapUser['mail'][0] : '',
                        'status_id'         => 1,
                        'usuario_ldap'      => 1,
                    ];
                    
                    $usuario = User::create($userData);
                    
                    // Criar nível de usuário (nível 4 como no CI original)
                    NivelUsuario::create([
                        'usuario_id' => $usuario->usuario_id,
                        'nivel_id'   => 4,
                    ]);
                    
                    $usuariosCriados++;
                } else {
                    // Atualizar usuário existente (seguindo a estrutura do CI original)
                    $updateData = [
                        'usuario_nome'      => $ldapUser['cn'][0],
                        'usuario_usuario'   => $ldapUser['samaccountname'][0],
                        'usuario_email'     => isset($ldapUser['mail'][0]) ? $ldapUser['mail'][0] : '',
                        'status_id'         => 1,
                    ];
                    
                    $usuario->update($updateData);
                    $usuariosAtualizados++;
                }
    
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $erros++;
            }
        }
    
        ldap_close($ldapconn);
    
        return redirect()->route('usuarios.index')->with('success', 
            "Importação LDAP concluída. Processados: {$usuariosProcessados}, Criados: {$usuariosCriados}, Atualizados: {$usuariosAtualizados}, Erros: {$erros}"
        );
    }
    

}
