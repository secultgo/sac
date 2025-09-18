<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Chamado;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Status;
use App\Models\StatusChamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraficoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Verifica se o usuário tem permissão
        if (!$user->isSuperAdmin() && !$user->isGestor()) {
            abort(403, 'Acesso negado');
        }
        
        // Se for gestor, filtra apenas o departamento dele
        // Se for super admin, mostra apenas departamentos que atendem chamados
        if ($user->isSuperAdmin()) {
            $departamentos = Departamento::whereIn('departamento_id', function($query) {
                $query->select('departamento_id')
                      ->from('chamado')
                      ->distinct();
            })->get();
        } else {
            $departamentos = Departamento::where('departamento_id', $user->departamento_id)->get();
        }
            
        // Buscar a data do primeiro chamado para definir data inicial
        $query = Chamado::query();
        if (!$user->isSuperAdmin()) {
            $query->where('chamado.departamento_id', $user->departamento_id);
        }
        
        $primeiroChamado = $query->orderBy('chamado_abertura', 'asc')->first();
        $dataInicial = $primeiroChamado 
            ? Carbon::parse($primeiroChamado->chamado_abertura)->format('Y-m-d')
            : Carbon::now()->subMonth()->format('Y-m-d');
            
        return view('painel.graficos.index', compact('departamentos', 'dataInicial'));
    }
    
    public function dadosGraficos(Request $request)
    {
        $user = auth()->user();
        $departamentoId = $request->get('departamento_id');
        $dataInicio = $request->get('data_inicio', Carbon::now()->subMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', Carbon::now()->format('Y-m-d'));
        
        // Query base
        $query = Chamado::whereBetween('chamado_abertura', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);
        
        // Filtro por departamento para gestores
        if (!$user->isSuperAdmin()) {
            $query->where('chamado.departamento_id', $user->departamento_id);
        } elseif ($departamentoId && $departamentoId != 'todos') {
            $query->where('chamado.departamento_id', $departamentoId);
        }
        
        return response()->json([
            'estatisticas' => $this->getEstatisticas($query),
            'chamados_por_status' => $this->getChamadosPorStatus($query),
            'chamados_por_departamento' => $this->getChamadosPorDepartamento($query),
            'chamados_por_problema' => $this->getChamadosPorProblema($query),
            'chamados_por_servico' => $this->getChamadosPorServico($query),
            'evolucao_temporal' => $this->getEvolucaoTemporal($query, $dataInicio, $dataFim),
            'performance' => $this->getPerformance($query),
            'avaliacoes' => $this->getAvaliacoes($query),
            'atendentes' => $this->getAtendentes($query)
        ]);
    }
    
    private function getEstatisticas($query)
    {
        $total = (clone $query)->count();
        
        // Status específicos (baseado nas cores dos pequenos boxes)
        $fechados = (clone $query)->where('status_chamado_id', 3)->count(); // Fechado
        $naoAvaliados = (clone $query)->where('status_chamado_id', 5)->count(); // Não Avaliado
        $pendentes = (clone $query)->where('status_chamado_id', 4)->count(); // Pendente
        $atendimento = (clone $query)->where('status_chamado_id', 2)->count(); // Em atendimento
        $abertos = (clone $query)->where('status_chamado_id', 1)->count(); // Aberto
        
        return [
            'total_chamados' => $total,
            'chamados_fechados' => $fechados,
            'chamados_nao_avaliados' => $naoAvaliados,
            'chamados_pendentes' => $pendentes,
            'chamados_atendimento' => $atendimento,
            'chamados_abertos' => $abertos
        ];
    }
    
    private function getChamadosPorStatus($query)
    {
        return (clone $query)
            ->select('status_chamado_id', DB::raw('count(*) as total'))
            ->with('statusChamado')
            ->groupBy('status_chamado_id')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->statusChamado->status_chamado_nome ?? 'Indefinido',
                    'total' => $item->total
                ];
            });
    }
    
    private function getChamadosPorDepartamento($query)
    {
        return (clone $query)
            ->select('chamado.departamento_id', DB::raw('count(*) as total'))
            ->with('departamento')
            ->groupBy('chamado.departamento_id')
            ->get()
            ->map(function ($item) {
                return [
                    'departamento' => $item->departamento->departamento_nome ?? 'Indefinido',
                    'total' => $item->total
                ];
            });
    }
    
    private function getEvolucaoTemporal($query, $dataInicio, $dataFim)
    {
        $inicio = Carbon::parse($dataInicio);
        $fim = Carbon::parse($dataFim);
        $diferenca = $inicio->diffInDays($fim);
        
        // Se for mais de 30 dias, agrupa por mês, senão por dia
        $formato = $diferenca > 30 ? '%Y-%m' : '%Y-%m-%d';
        
        return (clone $query)
            ->select(DB::raw("DATE_FORMAT(chamado_abertura, '$formato') as periodo"), DB::raw('count(*) as total'))
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get()
            ->map(function ($item) use ($diferenca) {
                return [
                    'periodo' => $diferenca > 30 
                        ? Carbon::createFromFormat('Y-m', $item->periodo)->format('M/Y')
                        : Carbon::createFromFormat('Y-m-d', $item->periodo)->format('d/m'),
                    'total' => $item->total
                ];
            });
    }
    
    private function getPerformance($query)
    {
        $dados = (clone $query)
            ->whereNotNull('chamado_resolvido')
            ->whereRaw('chamado_resolvido >= chamado_abertura')
            ->select(
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, chamado_abertura, chamado_resolvido)) as tempo_medio_minutos'),
                DB::raw('MIN(TIMESTAMPDIFF(MINUTE, chamado_abertura, chamado_resolvido)) as tempo_minimo_minutos'),
                DB::raw('MAX(TIMESTAMPDIFF(MINUTE, chamado_abertura, chamado_resolvido)) as tempo_maximo_minutos'),
                DB::raw('COUNT(*) as total_resolvidos')
            )
            ->havingRaw('tempo_minimo_minutos >= 0') // Garantir que não há tempos negativos
            ->first();
            
        // Buscar chamado com tempo mínimo
        $chamadoMinimo = (clone $query)
            ->whereNotNull('chamado_resolvido')
            ->whereRaw('chamado_resolvido >= chamado_abertura')
            ->orderByRaw('TIMESTAMPDIFF(MINUTE, chamado_abertura, chamado_resolvido) ASC')
            ->first();
            
        // Buscar chamado com tempo máximo
        $chamadoMaximo = (clone $query)
            ->whereNotNull('chamado_resolvido')
            ->whereRaw('chamado_resolvido >= chamado_abertura')
            ->orderByRaw('TIMESTAMPDIFF(MINUTE, chamado_abertura, chamado_resolvido) DESC')
            ->first();
            
        // Garantir valores não negativos
        $tempoMedio = max(0, round($dados->tempo_medio_minutos ?? 0));
        $tempoMinimo = max(0, $dados->tempo_minimo_minutos ?? 0);
        $tempoMaximo = max(0, $dados->tempo_maximo_minutos ?? 0);
            
        return [
            'tempo_medio_minutos' => $tempoMedio,
            'tempo_minimo_minutos' => $tempoMinimo,
            'tempo_maximo_minutos' => $tempoMaximo,
            'total_resolvidos' => $dados->total_resolvidos ?? 0,
            'tempo_medio_horas' => round($tempoMedio / 60, 1),
            'tempo_minimo_horas' => round($tempoMinimo / 60, 1),
            'tempo_maximo_horas' => round($tempoMaximo / 60, 1),
            'chamado_minimo_id' => $chamadoMinimo->chamado_id ?? null,
            'chamado_maximo_id' => $chamadoMaximo->chamado_id ?? null
        ];
    }
    
    private function getAvaliacoes($query)
    {
        return (clone $query)
            ->whereNotNull('chamado.avaliacao_chamado_id')
            ->join('avaliacao_chamado', 'chamado.avaliacao_chamado_id', '=', 'avaliacao_chamado.avaliacao_chamado_id')
            ->select('chamado.avaliacao_chamado_id', 'avaliacao_chamado.avaliacao_chamado_nome', DB::raw('count(*) as total'))
            ->groupBy('chamado.avaliacao_chamado_id', 'avaliacao_chamado.avaliacao_chamado_nome')
            ->orderBy('chamado.avaliacao_chamado_id')
            ->get()
            ->map(function ($item) {
                return [
                    'avaliacao' => $item->avaliacao_chamado_nome,
                    'total' => $item->total
                ];
            });
    }
    
    private function getAtendentes($query)
    {
        return (clone $query)
            ->whereNotNull('responsavel_id')
            ->join('usuario', 'chamado.responsavel_id', '=', 'usuario.usuario_id')
            ->select('responsavel_id', 'usuario.usuario_nome', DB::raw('count(*) as total'))
            ->groupBy('responsavel_id', 'usuario.usuario_nome')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'atendente' => $item->usuario_nome,
                    'total' => $item->total
                ];
            });
    }

    private function getChamadosPorProblema($query)
    {
        return (clone $query)
            ->join('problema', 'chamado.problema_id', '=', 'problema.problema_id')
            ->select('problema.problema_nome as problema', DB::raw('COUNT(*) as total'))
            ->groupBy('problema.problema_id', 'problema.problema_nome')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'problema' => $item->problema,
                    'total' => $item->total
                ];
            });
    }

    private function getChamadosPorServico($query)
    {
        return (clone $query)
            ->join('servico_chamado', 'chamado.servico_chamado_id', '=', 'servico_chamado.servico_chamado_id')
            ->select('servico_chamado.servico_chamado_nome as servico', DB::raw('COUNT(*) as total'))
            ->groupBy('servico_chamado.servico_chamado_id', 'servico_chamado.servico_chamado_nome')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'servico' => $item->servico,
                    'total' => $item->total
                ];
            });
    }
}
