<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class VerificarNivelAtendimento
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuarioId = Auth::user()->usuario_id;
        
        // Permite níveis: 1 (Super), 2 (Gestor), 3 (Atendente)
        $temPermissao = DB::table('nivel_usuario')
            ->where('usuario_id', $usuarioId)
            ->whereIn('nivel_id', [1, 2, 3])
            ->exists();

        if (!$temPermissao) {
            abort(403, 'Acesso negado. Você não tem permissão para acessar os atendimentos.');
        }

        return $next($request);
    }
}
