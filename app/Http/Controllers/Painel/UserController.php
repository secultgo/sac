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
use Illuminate\Support\Facades\Log;


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
           
            return redirect()->route('painel.usuarios.index')->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    } 


    public function edit(User $usuario)
    {
        $departamentos = Departamento::all();
        $niveis = Nivel::all();
        return view('painel.usuarios.edit', compact('usuario', 'departamentos', 'niveis'));
    }

    public function update(Request $request, User $usuario)
    {
        
        try {
            $request->validate([
                'usuario_nome'     => 'required|string|max:100',
                'usuario_email'    => 'required|email|max:100|unique:usuario,usuario_email,' . $usuario->usuario_id . ',usuario_id',
                'usuario_cpf'      => ['required', 'string', 'max:14', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', 'unique:usuario,usuario_cpf,' . $usuario->usuario_id . ',usuario_id'],
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
            return redirect()->route('painel.usuarios.index')->with('error', 'Não foi possível conectar ao servidor LDAP.');
        }

    
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
    
        if (!ldap_bind($ldapconn, $ldapUser, $ldapPass)) {
            return redirect()->route('painel.usuarios.index')->with('error', 'Falha na autenticação com o servidor LDAP.');
        }
    
        $result = ldap_search($ldapconn, $ldapTree, $ldapFilter);
    
        if (!$result) {
            return redirect()->route('painel.usuarios.index')->with('error', 'Erro na consulta LDAP: ' . ldap_error($ldapconn));
        }
    
        $entries = ldap_get_entries($ldapconn, $result);
    
        User::where('usuario_ldap', true)->update(['status_id' => 2]);
    
        for ($i = 0; $i < $entries['count']; $i++) {
            $ldapUser = $entries[$i];
    
            DB::beginTransaction();
            try {
                $userData = [
                    'usuario_id'        => $ldapUser['usncreated'][0],
                    'usuario_nome'     => $ldapUser['cn'][0],
                    'usuario_usuario'  => $ldapUser['samaccountname'][0],
                    'usuario_email'    => $ldapUser['mail'][0] ?? null,
                    'departamento_id'  => $ldapUser['department'][0] ?? null,
                    'usuario_cpf'      => $ldapUser['description'][0] ?? null,
                    'usuario_celular'  => $ldapUser['telephonenumber'][0] ?? null,
                    'status_id'        => 1,
                    'usuario_ldap'     => 1,
                    'excluido_id'      => 2,
                ];
    
                $usuario = User::where('usuario_id', $ldapUser['usncreated'][0])->first();
                if ($usuario) {
                    $usuario->update($userData);
                } else {
                    $usuario = User::create($userData);
                }

    
                if (!$usuario) {
                    throw new \Exception('Falha ao criar o usuário.');
                }
    
                NivelUsuario::create([
                    'usuario_id' => $usuario->usuario_id,
                    'nivel_id'   => 3,
                ]);
    
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                logger('Erro LDAP:', [$e->getMessage()]);
            }
        }
    
        ldap_close($ldapconn);
    
        return redirect()->route('painel.usuarios.index')->with('success', 'Importação de usuários LDAP concluída.');
    }
    

}
