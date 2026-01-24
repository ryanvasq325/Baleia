<?php
session_start();
#Diretorio raiz da applicação WEB
define('ROOT', dirname(__FILE__, 3));
#Extensão padrão da camada de interação com usuário front-end.
define('EXT_VIEW', '.html');
#Diretorio do arquivos de template da view.
define('DIR_VIEW', ROOT . '/app/view');
#$_SERVER['HTTP_HOST'] : Indica o domínio (host) que foi chamado na URL pelo navegador. Domínio principal meusite.com ou localhost
#$_SERVER['HTTP_CF_VISITOR'] : Indica o protocolo usado na requisição atual. podendo ser http ou https
#Criamos uma constante chamada HOME que guarda automaticamente o endereço principal do site.
define('HOME', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);