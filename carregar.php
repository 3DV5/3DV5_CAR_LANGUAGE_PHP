<?php
$diretorioProjetos = 'projetos/';
$nomeArquivo = isset($_GET['carregar']) ? basename($_GET['carregar']) : '';

if (!empty($nomeArquivo) && file_exists($diretorioProjetos . $nomeArquivo)) {
    // Define o arquivo atual na sessão ou cookie (simplificado)
    setcookie('arquivo_atual', $nomeArquivo, time() + 3600);
    header('Location: index.php');
    exit;
}
?>