<?php
$diretorioProjetos = 'projetos/';
$nomeArquivo = isset($_GET['arquivo']) ? basename($_GET['arquivo']) : '';

if (!empty($nomeArquivo) && file_exists($diretorioProjetos . $nomeArquivo)) {
    unlink($diretorioProjetos . $nomeArquivo);
    header('Location: index.php');
    exit;
}
?>