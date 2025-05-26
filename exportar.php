<?php
// Verifica se o código foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'], $_POST['nome_arquivo'])) {
    $codigo = $_POST['codigo'];
    $nomeArquivo = basename($_POST['nome_arquivo']); // Remove caracteres perigosos
    
    // Força a extensão .3dv5
    if (!str_ends_with($nomeArquivo, '.3dv5')) {
        $nomeArquivo .= '.3dv5';
    }
    
    // Configura os headers para download
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
    
    // Envia APENAS o código puro
    echo $codigo;
    exit;
} else {
    die("Erro: Dados incompletos.");
}
?>