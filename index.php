<?php
session_start();

// Incluir o arquivo de configuração
include_once './config/config.php';

// Receber a URL amigável
$url = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
$url = $url ? $url : 'login';

// Converter para array
$url = array_filter(explode('/', $url));

// Montar o caminho do arquivo
$arquivo = 'paginas/' . $url[0] . '.php';

// Verificar se existe
if (is_file($arquivo)) {
    include $arquivo;
} else {
    http_response_code(404);
    include 'paginas/404.php';
}