<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Chamado;
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
        $chamado->usuario_id = Auth::id();
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

        return redirect()->route('chamados.index')->with('success', 'Chamado criado com sucesso!');
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
}
