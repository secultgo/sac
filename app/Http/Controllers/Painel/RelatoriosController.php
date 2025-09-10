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

        $chamados = $this->getChamadosCompletos();
        
        $titulo = 'Relatórios - Todos os Chamados';
        $subtitulo = 'Todos os Chamados';
        $mensagem_vazia = 'Nenhum chamado encontrado.';
        
        return view('painel.relatorios.index', compact('chamados', 'titulo', 'subtitulo', 'mensagem_vazia'));
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
        $chamados = $this->getChamadosCompletos($departamento_id);
        
        $titulo = 'Relatórios - ' . $departamento->departamento_nome . ' (' . $departamento->departamento_sigla . ')';
        $subtitulo = 'Chamados do Departamento: ' . $departamento->departamento_sigla;
        $mensagem_vazia = 'Nenhum chamado encontrado para este departamento.';
        
        return view('painel.relatorios.index', compact('chamados', 'titulo', 'subtitulo', 'mensagem_vazia'));
    }

    private function getChamadosCompletos($departamento_id = null)
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

        return $query->orderBy('chamado.chamado_id', 'desc')->get();
    }
}
