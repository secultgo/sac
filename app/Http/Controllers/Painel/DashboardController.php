<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Chamado;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Se for usuário comum (nível 4), redirecionar para meus chamados
        if (Auth::user()->isUsuarioComum()) {
            return redirect()->route('meus-chamados.index');
        }

        $statusFiltro = $request->get('status', 1);
        
        $query = Chamado::with(['problema', 'departamento', 'local', 'usuario', 'responsavel', 'servicoChamado', 'statusChamado'])
                        ->where('departamento_id', Auth::user()->departamento_id);
        
        // Aplicar filtro de status - se for status 1 (Abertos), incluir também status 8 (Reabertos)
        if ($statusFiltro == 1) {
            // Uma única query com ordenação customizada: reabertos primeiro, depois abertos
            $chamados = Chamado::with(['problema', 'departamento', 'local', 'usuario', 'responsavel', 'servicoChamado', 'statusChamado'])
                              ->where('departamento_id', Auth::user()->departamento_id)
                              ->whereIn('status_chamado_id', [1, 8])
                              ->orderBy('chamado_id', 'asc')
                              ->get();
        } else {
            $query->where('status_chamado_id', $statusFiltro);
            $chamados = $query->orderBy('chamado_id', 'asc')->get();
        }
        
        // Limitar descrição a 200 caracteres
        $chamados->transform(function ($chamado) {
            if (strlen($chamado->chamado_descricao) > 200) {
                $chamado->chamado_descricao = substr($chamado->chamado_descricao, 0, 200) . '...';
            }
            return $chamado;
        });
        
        // Contar chamados por status para os badges (apenas do departamento do usuário)
        $chamadosMesAtual = Chamado::where('departamento_id', Auth::user()->departamento_id)
                                  ->whereMonth('chamado_abertura', now()->month)
                                  ->whereYear('chamado_abertura', now()->year)
                                  ->count();
        
        $chamadosFechadosMes = Chamado::where('departamento_id', Auth::user()->departamento_id)
                                     ->where('status_chamado_id', 3)
                                     ->whereMonth('chamado_fechado', now()->month)
                                     ->whereYear('chamado_fechado', now()->year)
                                     ->whereNotNull('chamado_fechado')
                                     ->count();
        
        $percentualFechadosMes = $chamadosMesAtual > 0 ? round(($chamadosFechadosMes / $chamadosMesAtual) * 100, 1) : 0;
        
        $contadores = [
            'abertos' => Chamado::where('departamento_id', Auth::user()->departamento_id)->whereIn('status_chamado_id', [1, 8])->count(),
            'atendimento' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 2)->count(),
            'fechados' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 3)->count(),
            'pendentes' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 4)->count(),
            'resolvidos' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 5)->count(),
            'aguardando_usuario' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 6)->count(),
            'reabertos' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 8)->count(),
            'mes_atual' => $chamadosMesAtual,
            'percentual_fechados_mes' => $percentualFechadosMes,
        ];
        
        return view('painel.dashboard.index', compact('chamados', 'contadores', 'statusFiltro')); 
    }

    /*Função criada para que apenas os chamados com status "fechado" tenha vizualição especifica.
    * Foi copiada a parte de calculo de tempo da view : painel\chamados\show.blade.php
    */
    public function fechados(Request $request)
    {
        // Se for usuário comum (nível 4), redirecionar para meus chamados
        if (Auth::user()->isUsuarioComum()) {
            return redirect()->route('meus-chamados.index');
        }

        $statusFiltro = $request->get('status', 1);
        
        $query = Chamado::with(['problema', 'departamento', 'local', 'usuario', 'responsavel', 'servicoChamado', 'statusChamado'])
                        ->where('departamento_id', Auth::user()->departamento_id);
        
        // Aplicar filtro de status - se for status 1 (Abertos), incluir também status 8 (Reabertos)
        if ($statusFiltro == 1) {
            // Uma única query com ordenação customizada: reabertos primeiro, depois abertos
            $chamados = Chamado::with(['problema', 'departamento', 'local', 'usuario', 'responsavel', 'servicoChamado', 'statusChamado'])
                              ->where('departamento_id', Auth::user()->departamento_id)
                              ->whereIn('status_chamado_id', [1, 8])
                              ->orderBy('chamado_id', 'asc')
                              ->get();
        } else {
            $query->where('status_chamado_id', $statusFiltro);
            $chamados = $query->orderBy('chamado_id', 'asc')->get();
        }
        
        // Limitar descrição a 200 caracteres
        $chamados->transform(function ($chamado) {
            if (strlen($chamado->chamado_descricao) > 200) {
                $chamado->chamado_descricao = substr($chamado->chamado_descricao, 0, 200) . '...';
            }
            return $chamado;
        });
        
        // Contar chamados por status para os badges (apenas do departamento do usuário)
        $chamadosMesAtual = Chamado::where('departamento_id', Auth::user()->departamento_id)
                                  ->whereMonth('chamado_abertura', now()->month)
                                  ->whereYear('chamado_abertura', now()->year)
                                  ->count();
        
        $chamadosFechadosMes = Chamado::where('departamento_id', Auth::user()->departamento_id)
                                     ->where('status_chamado_id', 3)
                                     ->whereMonth('chamado_fechado', now()->month)
                                     ->whereYear('chamado_fechado', now()->year)
                                     ->whereNotNull('chamado_fechado')
                                     ->count();
        
        $percentualFechadosMes = $chamadosMesAtual > 0 ? round(($chamadosFechadosMes / $chamadosMesAtual) * 100, 1) : 0;
        
        $contadores = [
            'abertos' => Chamado::where('departamento_id', Auth::user()->departamento_id)->whereIn('status_chamado_id', [1, 8])->count(),
            'atendimento' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 2)->count(),
            'fechados' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 3)->count(),
            'pendentes' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 4)->count(),
            'resolvidos' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 5)->count(),
            'aguardando_usuario' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 6)->count(),
            'reabertos' => Chamado::where('departamento_id', Auth::user()->departamento_id)->where('status_chamado_id', 8)->count(),
            'mes_atual' => $chamadosMesAtual,
            'percentual_fechados_mes' => $percentualFechadosMes,
        ];

        $chamados->transform(function ($chamado) {
            $inicio = is_string($chamado->chamado_atendimento) ? Carbon::parse($chamado->chamado_atendimento) : $chamado->chamado_atendimento;
            $fim = $chamado->chamado_fechado
                ? (is_string($chamado->chamado_fechado) ? Carbon::parse($chamado->chamado_fechado) : $chamado->chamado_fechado)
                : now();
        
            $diff = $inicio->diff($fim);
        
            $tempoFormatado = '';
            if ($diff->d > 0) {
                $tempoFormatado .= $diff->d . ' dia' . ($diff->d > 1 ? 's' : '') . ' ';
            }
            if ($diff->h > 0) {
                $tempoFormatado .= $diff->h . 'h ';
            }
            if ($diff->i > 0) {
                $tempoFormatado .= $diff->i . 'min ';
            }
            if ($diff->s > 0 || empty($tempoFormatado)) {
                $tempoFormatado .= $diff->s . 's';
            }
        
            $chamado->tempo_atendimento = trim($tempoFormatado);
        
            return $chamado;
        });

        return view('painel.dashboard.fechado', compact('chamados', 'contadores', 'statusFiltro')); 
    }    
}
