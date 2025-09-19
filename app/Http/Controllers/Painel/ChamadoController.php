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
use App\Models\AvaliacaoChamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChamadoController extends Controller
{
    /**
     * Exibe o formulário de criação de chamado.
     */
    public function create()
    {
        // Verificar se o usuário tem perfil completo (departamento e telefone)
        if (!Auth::user()->perfilCompleto()) {
            return redirect()->route('usuarios.completar-perfil')
                ->with('warning', 'Para abrir chamados, você precisa completar as informações do seu perfil.');
        }

        // Verificar se o usuário tem chamados não avaliados
        $chamadosNaoAvaliados = Chamado::where('usuario_id', Auth::user()->usuario_id)
            ->where('status_chamado_id', StatusChamado::NAO_AVALIADO)
            ->count();

        // Se houver chamados não avaliados, redirecionar para o dashboard com mensagem
        if ($chamadosNaoAvaliados > 0) {
            return redirect()->route('painel.dashboard')
                ->with('chamadosNaoAvaliados', $chamadosNaoAvaliados)
                ->with('mostrarModalAvaliacao', true);
        }

        $problemas = Problema::where('status_id', 1)->orderBy('problema_nome')->get();
        $departamentos = Departamento::where('excluido_id', 2)
            ->where('departamento_chamado', true)
            ->orderBy('departamento_nome')
            ->get();
        $locais = Local::orderBy('local_nome')->get();
        $servicos = ServicoChamado::orderBy('servico_chamado_nome')->get();

        return view('painel.chamados.create', compact('problemas', 'departamentos', 'locais', 'servicos'));
    }

    /**
     * Armazena um novo chamado.
     */
    public function store(Request $request)
    {
        // Verificar se o usuário tem perfil completo (departamento e telefone)
        if (!Auth::user()->perfilCompleto()) {
            return redirect()->route('usuarios.completar-perfil')
                ->with('warning', 'Para criar chamados, você precisa completar as informações do seu perfil.');
        }

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
        $chamado->chamado_abertura = now();

        // Upload do arquivo se fornecido
        if ($request->hasFile('chamado_anexo')) {
            $file = $request->file('chamado_anexo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/chamado'), $fileName);
            $chamado->chamado_anexo = $fileName;
        }

        $chamado->save();

        // Calcular posição na fila (chamados abertos, reabertos, em atendimento e devolvidos ao usuário para o mesmo departamento)
        $posicaoFila = Chamado::where('departamento_id', $request->departamento_id)
                             ->whereIn('status_chamado_id', [1, 2, 6, 8]) // Aberto, Em Atendimento, Aguardando Usuário, Reaberto
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
        
        // Limitar descrição a 200 caracteres
        $chamados->transform(function ($chamado) {
            if (strlen($chamado->chamado_descricao) > 200) {
                $chamado->chamado_descricao = substr($chamado->chamado_descricao, 0, 200) . '...';
            }
            return $chamado;
        });
        
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

        // Se o chamado estava aguardando resposta do usuário e quem está comentando é o solicitante,
        // volta o status para atendimento
        if ($chamado->status_chamado_id == StatusChamado::AGUARDANDO_USUARIO && 
            $chamado->usuario_id == Auth::user()->usuario_id) {
            
            $chamado->status_chamado_id = StatusChamado::ATENDIMENTO;
            $chamado->save();
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
        
        // Verifica se o chamado está em atendimento ou reaberto para poder colocar em pendência
        if (!in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::REABERTO])) {
            return redirect()->back()->with('error', 'Apenas chamados em atendimento ou reabertos podem ser colocados em pendência.');
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
     * Inicia o atendimento de um chamado (de aberto para atendimento)
     */
    public function iniciarAtendimento($id)
    {
        $chamado = Chamado::findOrFail($id);
        $statusOriginal = $chamado->status_chamado_id;
        
        // Verifica se o chamado está aberto ou reaberto para poder iniciar atendimento
        if (!in_array($statusOriginal, [StatusChamado::ABERTO, StatusChamado::REABERTO])) {
            return redirect()->back()->with('error', 'Apenas chamados abertos ou reabertos podem ter o atendimento iniciado.');
        }

        // Atualiza o status para Atendimento (2) e define o responsável
        $chamado->status_chamado_id = StatusChamado::ATENDIMENTO;
        $chamado->responsavel_id = Auth::user()->usuario_id;
        
        // Define a data de atendimento apenas se for um chamado aberto (não reaberto)
        if ($statusOriginal == StatusChamado::ABERTO) {
            $chamado->chamado_atendimento = now();
        }
        
        $chamado->save();

        // Adiciona um comentário automático
        $acao = ($statusOriginal == StatusChamado::ABERTO) ? 'Atendimento iniciado' : 'Atendimento reiniciado';
        ComentarioChamado::create([
            'comentario_chamado_comentario' => $acao . ' por ' . Auth::user()->name,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Atendimento do chamado iniciado com sucesso!');
    }

    /**
     * Devolve o chamado ao usuário (aguardando resposta do usuário)
     */
    public function devolverUsuario(Request $request, $id)
    {
        $request->validate([
            'motivo_devolucao' => 'required|string|max:1000'
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado está em atendimento, pendente ou reaberto para poder devolver
        if (!in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::PENDENTE, StatusChamado::REABERTO])) {
            return redirect()->back()->with('error', 'Apenas chamados em atendimento, pendentes ou reabertos podem ser devolvidos ao usuário.');
        }

        // Atualiza o status para Aguardando resposta usuário (6)
        $chamado->status_chamado_id = StatusChamado::AGUARDANDO_USUARIO;
        $chamado->save();

        // Adiciona o comentário do usuário como motivo da devolução
        ComentarioChamado::create([
            'comentario_chamado_comentario' => $request->motivo_devolucao,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        // Adiciona comentário automático sobre a devolução
        ComentarioChamado::create([
            'comentario_chamado_comentario' => 'Chamado devolvido ao usuário por ' . Auth::user()->name . ' - Aguardando resposta do usuário.',
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Chamado devolvido ao usuário com sucesso!');
    }

    /**
     * Resolve o chamado (marca como resolvido)
     */
    public function resolverChamado(Request $request, $id)
    {
        $request->validate([
            'solucao' => 'required|string|max:1000'
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado pode ser resolvido
        if (!in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::PENDENTE, StatusChamado::AGUARDANDO_USUARIO, StatusChamado::REABERTO])) {
            return redirect()->back()->with('error', 'Apenas chamados em atendimento, pendentes, aguardando usuário ou reabertos podem ser resolvidos.');
        }

        // Atualiza o status para Resolvido (5)
        $chamado->status_chamado_id = StatusChamado::RESOLVIDO;
        $chamado->chamado_resolvido = now();
        $chamado->save();

        // Adiciona o comentário com a solução
        ComentarioChamado::create([
            'comentario_chamado_comentario' => $request->solucao,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        // Adiciona comentário automático sobre a resolução
        ComentarioChamado::create([
            'comentario_chamado_comentario' => 'Chamado resolvido por ' . Auth::user()->name,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Chamado resolvido com sucesso!');
    }

    /**
     * Altera o responsável do chamado
     */
    public function alterarResponsavel(Request $request, $id)
    {
        $request->validate([
            'novo_responsavel_id' => 'required|exists:usuario,usuario_id',
            'motivo_alteracao' => 'required|string|max:1000'
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado pode ter o responsável alterado
        if (!in_array($chamado->status_chamado_id, [StatusChamado::ATENDIMENTO, StatusChamado::PENDENTE, StatusChamado::AGUARDANDO_USUARIO, StatusChamado::REABERTO])) {
            return redirect()->back()->with('error', 'Apenas chamados em atendimento, pendentes, aguardando usuário ou reabertos podem ter o responsável alterado.');
        }

        // Verifica se não está tentando alterar para o mesmo responsável
        if ($chamado->responsavel_id == $request->novo_responsavel_id) {
            return redirect()->back()->with('error', 'O chamado já está com o responsável selecionado.');
        }

        $responsavelAntigo = $chamado->responsavel ? $chamado->responsavel->name : 'Nenhum';
        $responsavelNovo = User::find($request->novo_responsavel_id)->name;

        // Atualiza o responsável do chamado
        $chamado->responsavel_id = $request->novo_responsavel_id;
        $chamado->save();

        // Adiciona o comentário do motivo da alteração
        ComentarioChamado::create([
            'comentario_chamado_comentario' => $request->motivo_alteracao,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        // Adiciona comentário automático sobre a alteração
        ComentarioChamado::create([
            'comentario_chamado_comentario' => "Responsável alterado de {$responsavelAntigo} para {$responsavelNovo} por " . Auth::user()->name,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Responsável alterado com sucesso!');
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

    /**
     * Exibe os chamados criados pelo usuário logado
     */
    public function meusChamados(Request $request)
    {
        $statusFiltro = $request->get('status');
        
        $query = Chamado::with(['problema', 'departamento', 'local', 'responsavel', 'servicoChamado', 'statusChamado'])
                        ->where('usuario_id', Auth::user()->usuario_id);
        
        // Aplica filtro de status se fornecido
        if ($statusFiltro) {
            $query->where('status_chamado_id', $statusFiltro);
        }
        
        $chamados = $query->orderBy('chamado_abertura', 'desc')->get();
        
        // Limitar descrição a 200 caracteres
        $chamados->transform(function ($chamado) {
            if (strlen($chamado->chamado_descricao) > 200) {
                $chamado->chamado_descricao = substr($chamado->chamado_descricao, 0, 200) . '...';
            }
            return $chamado;
        });
        
        // Contar chamados por status para os badges (apenas do usuário logado)
        $contadores = [
            'abertos' => Chamado::where('usuario_id', Auth::user()->usuario_id)->where('status_chamado_id', 1)->count(),
            'atendimento' => Chamado::where('usuario_id', Auth::user()->usuario_id)->where('status_chamado_id', 2)->count(),
            'fechados' => Chamado::where('usuario_id', Auth::user()->usuario_id)->where('status_chamado_id', 3)->count(),
            'pendentes' => Chamado::where('usuario_id', Auth::user()->usuario_id)->where('status_chamado_id', 4)->count(),
            'resolvidos' => Chamado::where('usuario_id', Auth::user()->usuario_id)->where('status_chamado_id', 5)->count(),
            'aguardando_usuario' => Chamado::where('usuario_id', Auth::user()->usuario_id)->where('status_chamado_id', 6)->count(),
            'cancelados' => Chamado::where('usuario_id', Auth::user()->usuario_id)->where('status_chamado_id', 7)->count(),
        ];
        
        return view('painel.chamados.meus-chamados', compact('chamados', 'contadores', 'statusFiltro'));
    }

    /**
     * Avalia um chamado resolvido
     */
    public function avaliarChamado(Request $request, $id)
    {
        // Validação básica
        $rules = [
            'avaliacao' => 'required|integer|exists:avaliacao_chamado,avaliacao_chamado_id',
            'comentario_avaliacao' => 'nullable|string|max:1000'
        ];

        // Se a avaliação for Regular (3) ou Ruim (4), comentário é obrigatório
        if (in_array($request->avaliacao, [3, 4])) {
            $rules['comentario_avaliacao'] = 'required|string|min:10|max:1000';
        }

        $request->validate($rules, [
            'comentario_avaliacao.required' => 'Por favor, deixe um comentário explicando sua avaliação.',
            'comentario_avaliacao.min' => 'O comentário deve ter pelo menos 10 caracteres.',
            'avaliacao.exists' => 'Avaliação inválida.'
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verifica se o chamado está resolvido e se o usuário logado é quem abriu o chamado
        if ($chamado->status_chamado_id != StatusChamado::RESOLVIDO) {
            return redirect()->back()->with('error', 'Apenas chamados resolvidos podem ser avaliados.');
        }

        if ($chamado->usuario_id != Auth::user()->usuario_id) {
            return redirect()->back()->with('error', 'Você só pode avaliar seus próprios chamados.');
        }

        // Atualiza o chamado com a avaliação
        $chamado->avaliacao_chamado_id = $request->avaliacao;
        $chamado->status_chamado_id = StatusChamado::FECHADO;
        $chamado->chamado_fechado = now();
        $chamado->save();

        // Adiciona comentário da avaliação se houver
        if ($request->comentario_avaliacao) {
            ComentarioChamado::create([
                'comentario_chamado_comentario' => 'Avaliação do usuário: ' . $request->comentario_avaliacao,
                'comentario_chamado_data' => now(),
                'chamado_id' => $id,
                'usuario_id' => Auth::user()->usuario_id
            ]);
        }

        // Busca o nome da avaliação do banco
        $avaliacaoNome = AvaliacaoChamado::find($request->avaliacao)->avaliacao_chamado_nome ?? 'Avaliação';

        // Adiciona comentário automático sobre o fechamento
        ComentarioChamado::create([
            'comentario_chamado_comentario' => 'Chamado avaliado como "' . $avaliacaoNome . '" e fechado automaticamente pelo usuário ' . Auth::user()->name . '.',
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Avaliação enviada com sucesso! Obrigado pelo seu feedback.');
    }

    /**
     * Reabre um chamado resolvido.
     */
    public function reabrirChamado(Request $request, $id)
    {
        $validated = $request->validate([
            'motivo_reabertura' => 'required|string|max:1000',
        ]);

        $chamado = Chamado::findOrFail($id);

        // Verifica se o usuário pode reabrir o chamado (deve ser o dono do chamado e estar resolvido)
        if ($chamado->usuario_id !== Auth::user()->usuario_id || $chamado->status_chamado_id !== StatusChamado::RESOLVIDO) {
            return redirect()->back()->with('error', 'Você não pode reabrir este chamado.');
        }

        // Atualiza o status para "Reaberto" 
        $chamado->status_chamado_id = StatusChamado::REABERTO;
        $chamado->chamado_resolvido = null; // Remove a data de resolução
        $chamado->save();

        // Adiciona comentário da reabertura
        ComentarioChamado::create([
            'comentario_chamado_comentario' => 'Chamado reaberto pelo usuário ' . Auth::user()->name . '. Motivo: ' . $validated['motivo_reabertura'],
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Chamado reaberto com sucesso! O departamento responsável foi notificado.');
    }

    /**
     * Retorna os usuários do departamento que podem atender chamados
     */
    public function usuariosDepartamento($id)
    {
        $chamado = Chamado::findOrFail($id);
        
        // Buscar usuários do mesmo departamento que podem atender
        $usuarios = User::whereHas('nivelUsuarios', function($query) {
                $query->whereIn('nivel_id', [1, 2, 3]); // Super Admin, Gestor ou Atendente
            })
            ->where('departamento_id', $chamado->departamento_id)
            ->where('status_id', 1) // Usuários ativos
            ->orderBy('usuario_nome')
            ->get(['usuario_id', 'usuario_nome']);

        return response()->json($usuarios);
    }

    /**
     * Atribui um responsável ao chamado e inicia o atendimento
     */
    public function atribuirResponsavel(Request $request, $id)
    {
        // Verificar se o usuário é gestor
        if (!Auth::user()->isGestor()) {
            return redirect()->back()->with('error', 'Você não tem permissão para atribuir responsáveis.');
        }

        $validated = $request->validate([
            'responsavel_id' => 'required|exists:usuario,usuario_id',
        ]);

        $chamado = Chamado::findOrFail($id);
        
        // Verificar se o chamado está aberto ou reaberto
        if (!in_array($chamado->status_chamado_id, [StatusChamado::ABERTO, StatusChamado::REABERTO])) {
            return redirect()->back()->with('error', 'Só é possível atribuir responsável para chamados abertos ou reabertos.');
        }

        // Verificar se o responsável é do mesmo departamento
        $responsavel = User::findOrFail($validated['responsavel_id']);
        if ($responsavel->departamento_id !== $chamado->departamento_id) {
            return redirect()->back()->with('error', 'O responsável deve ser do mesmo departamento do chamado.');
        }

        // Verificar se o responsável pode atender
        if (!$responsavel->podeAtender()) {
            return redirect()->back()->with('error', 'O usuário selecionado não possui permissão para atender chamados.');
        }

        // Atualizar o chamado
        $chamado->responsavel_id = $validated['responsavel_id'];
        $chamado->status_chamado_id = StatusChamado::ATENDIMENTO;
        $chamado->chamado_atendimento = now();
        $chamado->save();

        // Adicionar comentário sobre a atribuição
        $comentario = 'Responsável atribuído por ' . Auth::user()->usuario_nome . ': ' . $responsavel->usuario_nome;

        ComentarioChamado::create([
            'comentario_chamado_comentario' => $comentario,
            'comentario_chamado_data' => now(),
            'chamado_id' => $id,
            'usuario_id' => Auth::user()->usuario_id
        ]);

        return redirect()->back()->with('success', 'Responsável atribuído e atendimento iniciado com sucesso!');
    }
}
