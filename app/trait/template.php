<?php

namespace app\trait;

use Slim\Views\Twig;

trait Template
{
    public function getTwig()
    {
        try {
            $twig = Twig::create(DIR_VIEW);
            #Adicionamos uma varaivel de template Global acessivel de qualquer template
            $twig->getEnvironment()->addGlobal('EMPRESA', 'Gambiarra&CIA');
            return $twig;
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage());
        }
    }
    public function setView($name)
    {
        return $name . EXT_VIEW;
    }
    public function SendJson($response, array $data = [], int $statusCode = 200)
    {
        #Converte o arrya do PHP para formato JSON
        $payload = json_encode($data);
        #Retorna uma resposta em formato JSON
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
    public function getHtml(
        string $templateName = '',
        array $data = []
    ): string {
        $viewsPath = DIR_VIEW . '/reports/';
        $safeData = is_array($data) ? $data : [];
        $twig = Twig::create($viewsPath, $safeData);
        #Remove erros de string
        $html = $twig->fetch($templateName, $safeData);
        #$html = $twig('/>\s+</', '><', $html);
        return $html;
    }
}