<?php

namespace app\middleware;

class Auth
{
    public static function route()
    {
        $middleware = function ($request, $handler) {
            $response = $handler->handle($request);
            #CAPTURAMOS O METODOS DE REQUISIÇÃO.
            $method = $request->getMethod();
            #CAPTURAMOS A PAGINA SOLICITADA PELO USUÁRIO
            $pagina = $request->getRequestTarget();
            #CASO METODO SEJA GET VALIDAMOS O NIVEL DE ACESSO.
            if ($method == 'GET') {
                # SE O USUÁRIO ESTÁ LOGADO, REGENERA O ID DA SESSÃO PARA RENOVAR O TEMPO DE EXPIRAÇÃO DO COOKIE.
                if (isset($_SESSION['usuario']) && boolval($_SESSION['usuario']['logado'])) {
                    # O parâmetro 'true' remove o arquivo de sessão antigo do servidor.
                    session_regenerate_id(true);
                }
                #Se já está logado e tenta acessar /login, redireciona para HOME
                if ($pagina == '/login' && isset($_SESSION['usuario']) && boolval($_SESSION['usuario']['logado'])) {
                    return $response->withHeader('Location', HOME)->withStatus(302);
                }
                #Se não estiver logado e não está tentando acessar /login, redireciona para login
                if ((empty($_SESSION['usuario']) || !boolval($_SESSION['usuario']['logado'])) && ($pagina !== '/login')) {
                    session_destroy();
                    return $response->withHeader('Location', HOME . '/login')->withStatus(302);
                }
            }
            return $response;
        };
        return $middleware;
    }
}