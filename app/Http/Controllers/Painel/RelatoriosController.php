<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Chamado;
use App\Models\Departamento;
use App\Models\Usuario;
use App\Models\Local;
use App\Models\Problema;
use App\Models\ServicoChamado;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller
{
    public function todos()
    {
        // Apenas super admin pode ver todos os relatórios
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Acesso negado');
        }
        
        $titulo = 'Relatórios - Todos os Chamados';
        $subtitulo = 'Todos os Chamados';
        $tipo = 'todos';
        
        return view('painel.relatorios.index', compact('titulo', 'subtitulo', 'tipo'));
    }

    public function departamento($departamento_id)
    {
        $user = Auth::user();
        
        // Super admin pode ver qualquer departamento
        // Gestor só pode ver seu próprio departamento
        if (!$user->isSuperAdmin() && (!$user->isGestor() || $user->departamento_id != $departamento_id)) {
            abort(403, 'Acesso negado');
        }

        $departamento = Departamento::findOrFail($departamento_id);
        
        $titulo = 'Relatórios - ' . $departamento->departamento_nome . ' (' . $departamento->departamento_sigla . ')';
        $subtitulo = 'Chamados do Departamento: ' . $departamento->departamento_sigla;
        $tipo = 'departamento';
        
        return view('painel.relatorios.index', compact('titulo', 'subtitulo', 'tipo', 'departamento_id'));
    }

    public function apiTodos(Request $request)
    {
        // Apenas super admin pode ver todos os relatórios
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        return $this->processDataTablesRequest($request);
    }

    public function apiDepartamento(Request $request, $departamento_id)
    {
        $user = Auth::user();
        
        // Super admin pode ver qualquer departamento
        // Gestor só pode ver seu próprio departamento
        if (!$user->isSuperAdmin() && (!$user->isGestor() || $user->departamento_id != $departamento_id)) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        return $this->processDataTablesRequest($request, $departamento_id);
    }

    private function processDataTablesRequest(Request $request, $departamento_id = null)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $orderColumn = $request->get('order')[0]['column'] ?? 0;
        $orderDir = $request->get('order')[0]['dir'] ?? 'desc';

        // Colunas disponíveis para ordenação
        $columns = [
            'chamado.chamado_id',
            'chamado.chamado_abertura',
            'chamado.chamado_atendimento',
            'chamado.chamado_resolvido',
            'chamado.chamado_fechado',
            'user.usuario_nome',
            'dep.departamento_nome',
            'local.local_nome',
            'problema.problema_nome',
            'servico_chamado.servico_chamado_nome',
            'chamado.usuario_fone_residencial',
            'resp.usuario_nome',
            'departamento.departamento_nome',
            'status_chamado.status_chamado_nome',
            'avaliacao_chamado.avaliacao_chamado_nome'
        ];

        $query = $this->buildBaseQuery($departamento_id);

        // Busca global
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('chamado.chamado_id', 'like', "%{$search}%")
                  ->orWhere('user.usuario_nome', 'like', "%{$search}%")
                  ->orWhere('departamento.departamento_nome', 'like', "%{$search}%")
                  ->orWhere('problema.problema_nome', 'like', "%{$search}%")
                  ->orWhere('servico_chamado.servico_chamado_nome', 'like', "%{$search}%")
                  ->orWhere('resp.usuario_nome', 'like', "%{$search}%")
                  ->orWhere('status_chamado.status_chamado_nome', 'like', "%{$search}%");
            });
        }

        // Total de registros sem filtro
        $totalRecords = $this->buildBaseQuery($departamento_id)->count();
        
        // Total de registros com filtro
        $filteredRecords = $query->count();

        // Aplicar ordenação
        $orderColumn = $columns[$orderColumn] ?? 'chamado.chamado_id';
        $query->orderBy($orderColumn, $orderDir);

        // Aplicar paginação
        $data = $query->skip($start)->take($length)->get();

        // Formatar dados para o DataTables
        $formattedData = $data->map(function($chamado) {
            return [
                $chamado->chamado_id,
                $chamado->chamado_abertura ? \Carbon\Carbon::parse($chamado->chamado_abertura)->format('d/m/Y H:i:s') : '',
                $chamado->chamado_atendimento ? \Carbon\Carbon::parse($chamado->chamado_atendimento)->format('d/m/Y H:i:s') : '',
                $chamado->chamado_resolvido ? \Carbon\Carbon::parse($chamado->chamado_resolvido)->format('d/m/Y H:i:s') : '',
                $chamado->chamado_fechado ? \Carbon\Carbon::parse($chamado->chamado_fechado)->format('d/m/Y H:i:s') : '',
                $chamado->solicitante_nome ?? '',
                $chamado->lotacao_nome ?? '',
                $chamado->local_nome ?? '',
                $chamado->problema_nome ?? '',
                $chamado->servico_chamado_nome ?? '',
                $chamado->usuario_fone_residencial ?? '',
                $chamado->responsavel_nome ?? '',
                $chamado->departamento_nome ?? '',
                $chamado->status_chamado_nome ?? '',
                $chamado->avaliacao_chamado_nome ?? ''
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $formattedData
        ]);
    }

    private function buildBaseQuery($departamento_id = null)
    {
        $query = DB::table('chamado')
            ->select([
                'chamado.*',
                'resp.usuario_nome as responsavel_nome',
                'status_chamado.status_chamado_nome',
                'user.usuario_nome as solicitante_nome',
                'departamento.departamento_nome',
                'departamento.departamento_sigla',
                'problema.problema_nome',
                'servico_chamado.servico_chamado_nome',
                'dep.departamento_nome as lotacao_nome',
                'local.local_nome',
                'avaliacao_chamado.avaliacao_chamado_nome'
            ])
            ->leftJoin('usuario as resp', 'chamado.responsavel_id', '=', 'resp.usuario_id')
            ->leftJoin('status_chamado', 'chamado.status_chamado_id', '=', 'status_chamado.status_chamado_id')
            ->leftJoin('usuario as user', 'chamado.usuario_id', '=', 'user.usuario_id')
            ->leftJoin('departamento', 'chamado.departamento_id', '=', 'departamento.departamento_id')
            ->leftJoin('problema', 'chamado.problema_id', '=', 'problema.problema_id')
            ->leftJoin('servico_chamado', 'chamado.servico_chamado_id', '=', 'servico_chamado.servico_chamado_id')
            ->leftJoin('departamento as dep', 'chamado.lotacao_id', '=', 'dep.departamento_id')
            ->leftJoin('local', 'chamado.local_id', '=', 'local.local_id')
            ->leftJoin('avaliacao_chamado', 'chamado.avaliacao_chamado_id', '=', 'avaliacao_chamado.avaliacao_chamado_id');

        if ($departamento_id) {
            $query->where('chamado.departamento_id', $departamento_id);
        }

        return $query;
    }
}
