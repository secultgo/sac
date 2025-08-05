<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Chamado;
use App\Models\ComentarioChamado;
use App\Models\StatusChamado;
use App\Models\Problema;
use App\Models\Departamento;
use App\Models\Local;
use App\Models\ServicoChamado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChamadoController extends Controller
{
    /**
     * Exibe o formulário de criação de chamado.
     */
    public function create()
    {
        $problemas = Problema::orderBy('problema_nome')->get();
        $departamentos = Departamento::orderBy('departamento_nome')->get();
        $locais = Local::orderBy('local_nome')->get();
        $servicos = ServicoChamado::orderBy('servico_chamado_nome')->get();
        return view('painel.chamados.create', compact('problemas', 'departamentos', 'locais', 'servicos'));
    }

    /**
     * Armazena um novo chamado.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chamado_descricao' => 'required|string',
            'problema_id' => 'required|exists:problema,problema_id',
            'departamento_id' => 'required|exists:departamento,departamento_id',
            'local_id' => 'required|exists:local,local_id',
            'servico_chamado_id' => 'required|exists:servico_chamado,servico_chamado_id',
            'chamado_ip' => 'nullable|string|max:15',
            'chamado_anexo' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt',
        ]);

        $chamado = new Chamado($validated);
        $chamado->usuario_id = Auth::user()->usuario_id;
        $chamado->lotacao_id = Auth::user()->departamento_id;
        $chamado->status_chamado_id = 1;
        $chamado->chamado_ip = $request->ip(); // Captura o IP do usuário

        // Upload do arquivo se fornecido
        if ($request->hasFile('chamado_anexo')) {
            $file = $request->file('chamado_anexo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/chamado'), $fileName);
            $chamado->chamado_anexo = $fileName;
        }

        $chamado->save();

        // Calcular posição na fila (chamados abertos para o mesmo departamento)
        $posicaoFila = Chamado::where('departamento_id', $request->departamento_id)
                             ->where('status_chamado_id', 1) // Status 1 = Aberto
                             ->count();

        return redirect()->route('painel.dashboard')
                        ->with('success', 'Chamado criado com sucesso!')
                        ->with('chamado_id', $chamado->chamado_id)
                        ->with('posicao_fila', $posicaoFila);
    }

    /**
     * Exibe os chamados onde o usuário logado é o responsável.
     */
    public function meusAtendimentos(Request $request)
    {
        $statusFiltro = $request->get('status', 2); // Status padrão: 2 (Em Atendimento)
        
        $query = Chamado::with(['problema', 'departamento', 'local', 'usuario', 'servicoChamado', 'statusChamado'])
                        ->where('responsavel_id', Auth::user()->usuario_id);
        
        if ($statusFiltro) {
            $query->where('status_chamado_id', $statusFiltro);
        }
        
        $chamados = $query->orderBy('chamado_abertura', 'desc')->get();
        
        // Contar chamados por status para os badges
        $contadores = [
            'atendimento' => Chamado::where('responsavel_id', Auth::user()->usuario_id)->where('status_chamado_id', 2)->count(),
            'fechados' => Chamado::where('responsavel_id', Auth::user()->usuario_id)->where('status_chamado_id', 3)->count(),
            'pendentes' => Chamado::where('responsavel_id', Auth::user()->usuario_id)->where('status_chamado_id', 4)->count(),
            'resolvidos' => Chamado::where('responsavel_id', Auth::user()->usuario_id)->where('status_chamado_id', 5)->count(),
            'aguardando_usuario' => Chamado::where('responsavel_id', Auth::user()->usuario_id)->where('status_chamado_id', 6)->count(),
        ];
        
        return view('painel.chamados.meus-atendimentos', compact('chamados', 'contadores', 'statusFiltro'));
    }

    /**
     * Exibe os detalhes de um chamado específico.
     */
    public function show($id)
    {
        $chamado = Chamado::with([
            'usuario', 
            'responsavel', 
            'problema', 
            'departamento', 
            'local', 
            'servicoChamado', 
            'statusChamado',
            'comentarios.usuario'
        ])->findOrFail($id);
        
        return view('painel.chamados.show', compact('chamado'));
    }

    /**
     * Retorna os problemas de um departamento (AJAX).
     */
    public function problemasPorDepartamento($departamentoId)
    {
        $problemas = Problema::where('departamento_id', $departamentoId)->orderBy('problema_nome')->get(['problema_id', 'problema_nome']);
        return response()->json($problemas);
    }

    /**
     * Retorna os serviços de um problema (AJAX).
     */
    public function servicosPorProblema($problemaId)
    {
        $servicos = ServicoChamado::where('problema_id', $problemaId)->orderBy('servico_chamado_nome')->get(['servico_chamado_id', 'servico_chamado_nome']);
        return response()->json($servicos);
    }

    /**
     * Adiciona um comentário ao chamado.
     */
    public function adicionarComentario(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string|max:1000',
            'anexo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,txt|max:5120' // 5MB max
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado não está fechado
        if ($chamado->status_chamado_id == StatusChamado::FECHADO) {
            return redirect()->back()->with('error', 'Não é possível adicionar comentários em chamados fechados.');
        }

        $anexo = null;
        if ($request->hasFile('anexo')) {
            $arquivo = $request->file('anexo');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $arquivo->move(public_path('uploads/chamado'), $nomeArquivo);
            $anexo = $nomeArquivo;
        }

        ComentarioChamado::create([
            'comentario_chamado_comentario' => $request->comentario,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'comentario_chamado_anexo' => $anexo,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Comentário adicionado com sucesso!');
    }

    /**
     * Coloca o chamado em pendência
     */
    public function colocarPendencia(Request $request, $id)
    {
        $request->validate([
            'motivo_pendencia' => 'required|string|max:1000'
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado está em atendimento para poder colocar em pendência
        if ($chamado->status_chamado_id != StatusChamado::ATENDIMENTO) {
            return redirect()->back()->with('error', 'Apenas chamados em atendimento podem ser colocados em pendência.');
        }

        // Atualiza o status para Pendente (4)
        $chamado->status_chamado_id = StatusChamado::PENDENTE;
        $chamado->save();

        // Adiciona o comentário do usuário como motivo da pendência
        ComentarioChamado::create([
            'comentario_chamado_comentario' => $request->motivo_pendencia,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Chamado colocado em pendência com sucesso!');
    }

    /**
     * Coloca o chamado em atendimento (de pendente para atendimento)
     */
    public function atenderChamado($id)
    {
        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado está pendente para poder atender
        if ($chamado->status_chamado_id != StatusChamado::PENDENTE) {
            return redirect()->back()->with('error', 'Apenas chamados pendentes podem ser colocados em atendimento.');
        }

        // Atualiza o status para Atendimento (2)
        $chamado->status_chamado_id = StatusChamado::ATENDIMENTO;
        $chamado->save();

        // Adiciona um comentário automático
        ComentarioChamado::create([
            'comentario_chamado_comentario' => 'Chamado retomado do status pendente para atendimento por ' . Auth::user()->name,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Chamado colocado em atendimento com sucesso!');
    }

    /**
     * Transfere o chamado para outro departamento
     */
    public function transferirDepartamento(Request $request, $id)
    {
        $request->validate([
            'novo_departamento_id' => 'required|exists:departamento,departamento_id',
            'motivo_transferencia' => 'required|string|max:1000'
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado não está fechado
        if ($chamado->status_chamado_id == StatusChamado::FECHADO) {
            return redirect()->back()->with('error', 'Chamados fechados não podem ser transferidos.');
        }

        // Verifica se não está tentando transferir para o mesmo departamento
        if ($chamado->departamento_id == $request->novo_departamento_id) {
            return redirect()->back()->with('error', 'O chamado já está no departamento selecionado.');
        }

        $departamentoAntigo = $chamado->departamento->departamento_nome;
        $departamentoNovo = Departamento::find($request->novo_departamento_id)->departamento_nome;

        // Atualiza o departamento do chamado
        $chamado->departamento_id = $request->novo_departamento_id;
        $chamado->responsavel_id = null; // Remove o responsável atual
        
        // Se não estava em atendimento, volta para aberto
        if ($chamado->status_chamado_id == StatusChamado::ATENDIMENTO || $chamado->status_chamado_id == StatusChamado::PENDENTE) {
            $chamado->status_chamado_id = StatusChamado::ABERTO;
        }
        
        $chamado->save();

        // Adiciona o comentário da transferência
        ComentarioChamado::create([
            'comentario_chamado_comentario' => $request->motivo_transferencia,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        // Adiciona comentário automático sobre a transferência
        ComentarioChamado::create([
            'comentario_chamado_comentario' => "Chamado transferido de {$departamentoAntigo} para {$departamentoNovo} por " . Auth::user()->name,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->route('painel.dashboard')->with('success', 'Chamado transferido com sucesso!');
    }
}
