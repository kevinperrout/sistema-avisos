<?php
$file = __DIR__ . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (is_file($file)) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    
    $types = [
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'css'  => 'text/css',
        'js'   => 'application/javascript',
    ];

    if (isset($types[$ext])) {
        $contentType = $types[$ext];
        header("Content-Type: " . $contentType);
    }

    readfile($file);
    exit; // Finaliza a execução para que o Slim não tente processar o arquivo.
}

// Se não for um arquivo estático, passa a requisição para o Slim Framework.
require __DIR__ . '/index.php';