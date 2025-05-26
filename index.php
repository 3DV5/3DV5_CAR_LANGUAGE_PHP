<?php
require_once 'nucleo.php';

// Configurações iniciais
$diretorioProjetos = 'projetos/';
$conteudo = '';
$arquivoAtual = 'novo.3dv5';

// Carrega conteúdo se um arquivo for solicitado
if (isset($_GET['carregar'])) {
    $arquivoCarregar = basename($_GET['carregar']);
    $caminhoCompleto = $diretorioProjetos . $arquivoCarregar;
    
    if (file_exists($caminhoCompleto)) {
        $conteudo = file_get_contents($caminhoCompleto);
        $arquivoAtual = $arquivoCarregar;
    }
}

// Carrega conteúdo padrão se nenhum arquivo foi carregado
if (empty($conteudo) && file_exists($diretorioProjetos . 'novo.3dv5')) {
    $conteudo = file_get_contents($diretorioProjetos . 'novo.3dv5');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IDE .3DV5 - Editor Avançado</title>
    <style>
/* Reset e estilos base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', 'Ubuntu', 'Helvetica Neue', sans-serif;
    line-height: 1.4;
    background-color: #1e1e1e;
    color: #cccccc;
    padding: 0;
    height: 100vh;
    overflow: hidden;
}

/* Header/Title Bar */
h1 {
    background-color: #323233;
    color: #ffffff;
    margin: 0;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 400;
    text-align: left;
    border-bottom: 1px solid #2d2d30;
    display: flex;
    align-items: center;
    height: 35px;
}

h1::before {
    content: "●●●";
    color: #ff5f56;
    margin-right: 12px;
    font-size: 12px;
    letter-spacing: 2px;
}

/* Main Container */
.main-container {
    display: flex;
    height: calc(100vh - 35px);
    background-color: #1e1e1e;
}

/* Sidebar */
.sidebar {
    width: 240px;
    background-color: #252526;
    border-right: 1px solid #2d2d30;
    padding: 8px;
    overflow-y: auto;
}

.sidebar h3 {
    color: #cccccc;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 16px 8px 8px 8px;
    padding: 0;
}

/* Editor Container */
.editor-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #1e1e1e;
}

/* Tab Bar */
.tab-bar {
    background-color: #2d2d30;
    border-bottom: 1px solid #2d2d30;
    padding: 0;
    height: 35px;
    display: flex;
    align-items: center;
}

.tab {
    background-color: #2d2d30;
    color: #969696;
    padding: 8px 16px;
    border-right: 1px solid #1e1e1e;
    font-size: 13px;
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: center;
    height: 35px;
}

.tab.active {
    background-color: #1e1e1e;
    color: #ffffff;
}

.tab::before {
    content: "📄";
    margin-right: 6px;
    font-size: 12px;
}

/* Container principal */
.container {
    display: flex;
    flex: 1;
    gap: 0;
    height: calc(100% - 75px);
}

/* Editor de código */
.editor {
    flex: 2;
    background-color: #1e1e1e;
    position: relative;
}

.editor textarea {
    width: 100%;
    height: 100%;
    background-color: #1e1e1e;
    color: #d4d4d4;
    border: none;
    padding: 16px 16px 16px 70px;
    font-family: 'Consolas', 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.6;
    resize: none;
    outline: none;
    tab-size: 4;
}

.editor textarea::selection {
    background-color: #264f78;
}

.editor textarea:focus {
    outline: none;
}

/* Line numbers simulation */
.editor::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 60px;
    height: 100%;
    background-color: #1e1e1e;
    border-right: 1px solid #2d2d30;
    pointer-events: none;
}

/* Área de saída/Terminal */
.saida {
    flex: 1;
    background-color: #181818;
    border-left: 1px solid #2d2d30;
    display: flex;
    flex-direction: column;
    min-width: 300px;
}

.terminal-header {
    background-color: #2d2d30;
    color: #cccccc;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-bottom: 1px solid #1e1e1e;
    display: flex;
    align-items: center;
    height: 35px;
}

.terminal-header::before {
    content: "▶";
    margin-right: 8px;
    color: #007acc;
}

.terminal-content {
    flex: 1;
    padding: 12px;
    overflow-y: auto;
    font-family: 'Consolas', 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.4;
    color: #cccccc;
}

/* Botões toolbar */
.toolbar {
    background-color: #2d2d30;
    padding: 8px 16px;
    border-bottom: 1px solid #1e1e1e;
    display: flex;
    gap: 8px;
    align-items: center;
    height: 40px;
}

button {
    background-color: #0e639c;
    color: #ffffff;
    border: none;
    padding: 6px 14px;
    border-radius: 2px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 400;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

button:hover {
    background-color: #1177bb;
}

button:active {
    background-color: #005a9e;
}

button.secondary {
    background-color: #5a5a5a;
    color: #ffffff;
}

button.secondary:hover {
    background-color: #6a6a6a;
}

/* Input fields */
input[type="text"] {
    background-color: #3c3c3c;
    color: #cccccc;
    border: 1px solid #464647;
    padding: 6px 8px;
    border-radius: 2px;
    font-size: 13px;
    font-family: 'Segoe UI', sans-serif;
    width: 200px;
}

input[type="text"]:focus {
    outline: none;
    border-color: #007acc;
    box-shadow: 0 0 0 1px #007acc;
}

input[type="text"]::placeholder {
    color: #969696;
}

/* Lista de arquivos */
ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

li {
    color: #cccccc;
    padding: 4px 8px;
    margin: 1px 0;
    border-radius: 3px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    cursor: pointer;
    transition: background-color 0.1s ease;
}

li:hover {
    background-color: #2a2d2e;
}

li.selected {
    background-color: #094771;
}

li::before {
    content: "📄";
    margin-right: 6px;
    font-size: 11px;
}

li a {
    color: #cccccc;
    text-decoration: none;
    padding: 2px 6px;
    border-radius: 2px;
    font-size: 12px;
    transition: background-color 0.1s ease;
}

li a:hover {
    background-color: #007acc;
    color: #ffffff;
}

li a.delete {
    color: #f85149;
}

li a.delete:hover {
    background-color: #f85149;
    color: #ffffff;
}

/* Mensagens de erro */
.error-message {
    background-color: #5a1d1d !important;
    color: #f85149 !important;
    padding: 12px;
    border-radius: 3px;
    margin: 8px 0;
    border-left: 4px solid #f85149;
    font-family: 'Consolas', monospace;
    font-size: 13px;
    white-space: pre-wrap;
}

/* Success messages */
.success-message {
    background-color: #1a5a1a;
    color: #4ac748;
    padding: 12px;
    border-radius: 3px;
    margin: 8px 0;
    border-left: 4px solid #4ac748;
    font-family: 'Consolas', monospace;
    font-size: 13px;
}

/* Status bar */
.status-bar {
    background-color: #007acc;
    color: #ffffff;
    padding: 4px 16px;
    font-size: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 22px;
}

/* Scrollbars */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #1e1e1e;
}

::-webkit-scrollbar-thumb {
    background: #424242;
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: #4f4f4f;
}

/* Responsividade para mobile */
@media (max-width: 768px) {
    .main-container {
        flex-direction: column;
    }
    
    .sidebar {
        width: 100%;
        height: 200px;
        border-right: none;
        border-bottom: 1px solid #2d2d30;
    }
    
    .container {
        flex-direction: column;
        height: calc(100% - 200px);
    }
    
    .editor, .saida {
        height: 50%;
        min-width: auto;
    }
    
    .saida {
        border-left: none;
        border-top: 1px solid #2d2d30;
    }
    
    input[type="text"] {
        width: 100%;
        margin-top: 8px;
    }
    
    .toolbar {
        flex-wrap: wrap;
        height: auto;
        min-height: 40px;
    }
}

/* Minimap simulation */
.editor::after {
    content: "";
    position: absolute;
    right: 0;
    top: 0;
    width: 80px;
    height: 100%;
    background-color: #1a1a1a;
    border-left: 1px solid #2d2d30;
    opacity: 0.8;
    pointer-events: none;
}
    </style>
</head>
<body>
    <h1>IDE .3DV5 - Editor Profissional</h1>

    <div class="main-container">
        <!-- Sidebar com arquivos -->
        <div class="sidebar">
            <h3>Explorer</h3>
            <ul>
                <?php
                $arquivos = glob($diretorioProjetos . '*.3dv5');
                foreach ($arquivos as $arquivo) {
                    $nome = basename($arquivo);
                    $isSelected = ($nome === $arquivoAtual) ? 'class="selected"' : '';
                    echo "<li $isSelected>
                            <span>$nome</span>
                            <div>
                                <a href='?carregar=$nome'>Abrir</a>
                                <a href='excluir.php?arquivo=$nome' class='delete'>×</a>
                            </div>
                          </li>";
                }
                ?>
            </ul>
        </div>

        <!-- Editor Container -->
        <div class="editor-container">
            <!-- Tab Bar -->
            <div class="tab-bar">
                <div class="tab active">
                    <?= htmlspecialchars($arquivoAtual) ?>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="toolbar">
                <form method="POST" id="formExecutar" style="display: flex; gap: 8px; align-items: center;">
                    <button type="submit" name="acao" value="executar">▶ Executar</button>
                </form>
                
                <form method="POST" action="exportar.php" id="formExportar" style="display: flex; gap: 8px; align-items: center;">
                    <input type="hidden" name="codigo" id="codigoExportar" value="<?= htmlspecialchars($conteudo) ?>">
                    <input type="text" name="nome_arquivo" placeholder="Nome do arquivo" required>
                    <button type="submit" class="secondary">💾 Exportar</button>
                </form>
            </div>

            <!-- Container principal -->
            <div class="container">
                <!-- Editor de Código -->
                <div class="editor">
                    <form method="POST" id="formEditorCode">
                        <textarea name="codigo" id="codigoEditor" placeholder="Escreva seu código .3dv5 aqui..."><?= htmlspecialchars($conteudo) ?></textarea>
                    </form>
                </div>

                <!-- Área de Saída/Terminal -->
                <div class="saida">
                    <div class="terminal-header">Terminal</div>
                    <div class="terminal-content">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'executar') {
                            try {
                                echo "<div class='success-message'>Executando código...</div>";
                                $tempFile = tempnam(sys_get_temp_dir(), '3dv5_');
                                file_put_contents($tempFile, $_POST['codigo']);
                                importar($tempFile);
                                unlink($tempFile);
                            } catch (Throwable $e) {
                                echo "<div class='error-message'>Erro: " . htmlspecialchars($e->getMessage()) . "</div>";
                            }
                        } else {
                            echo "<div style='color: #969696; font-style: italic;'>Clique em 'Executar' para ver a saída do seu código...</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="status-bar">
        <span>Linguagem: .3DV5</span>
        <span>Arquivo: <?= htmlspecialchars($arquivoAtual) ?></span>
    </div>

    <!-- Scripts de sincronização -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editor = document.getElementById('codigoEditor');
            const codigoExportar = document.getElementById('codigoExportar');
            const formExecutar = document.getElementById('formExecutar');
            const formEditorCode = document.getElementById('formEditorCode');

            // Sincroniza campos ao digitar
            editor.addEventListener('input', function() {
                codigoExportar.value = editor.value;
            });

            // Intercepta o submit do formulário de execução e adiciona o código
            formExecutar.addEventListener('submit', function(e) {
                // Cria um input hidden com o código atual
                const codigoInput = document.createElement('input');
                codigoInput.type = 'hidden';
                codigoInput.name = 'codigo';
                codigoInput.value = editor.value;
                formExecutar.appendChild(codigoInput);
            });

            // Atualiza campos ao carregar a página
            codigoExportar.value = editor.value;

            // Marca arquivo selecionado na sidebar
            const currentFile = '<?= $arquivoAtual ?>';
            const fileItems = document.querySelectorAll('.sidebar li');
            fileItems.forEach(item => {
                const fileName = item.querySelector('span').textContent;
                if (fileName === currentFile) {
                    item.classList.add('selected');
                }
            });

            // Adiciona suporte a Tab no textarea
            editor.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const end = this.selectionEnd;
                    
                    this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
                    this.selectionStart = this.selectionEnd = start + 4;
                    
                    // Atualiza o campo de exportação
                    codigoExportar.value = this.value;
                }
            });
        });
    </script>
</body>
</html>