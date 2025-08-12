<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chamado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Ldap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $statusFiltro = $request->get('status', 1);
        
        $query = Chamado::with(['problema', 'departamento', 'local', 'usuario', 'responsavel', 'servicoChamado', 'statusChamado'])
                        ->where('departamento_id', Auth::user()->departamento_id);
        
        // Aplicar filtro de status - se for status 1 (Abertos), incluir também status 8 (Reabertos)
        if ($statusFiltro == 1) {
            $query->whereIn('status_chamado_id', [1, 8]);
        } else {
            $query->where('status_chamado_id', $statusFiltro);
        }
        
        $chamados = $query->orderBy('chamado_abertura', 'asc')->get();
        
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
}
