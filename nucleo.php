<?php
function importar($arquivo) {
    if (!file_exists($arquivo)) {
        echo "Arquivo '$arquivo' não encontrado.";
        return;
    }

    $codigo = file_get_contents($arquivo);

    $substituicoes = [
        // Variáveis (ex: @var → $var)
        'variaveis' => [
            'padrao' => '/@(\w+)/',
            'substituicao' => '\$$1'
        ],
        
        // Estruturas de Controle
        'keywords' => [
            'gol'          => 'if',
            'voyage'       => 'else',
            'polo'         => 'elseif',
            'corsa'        => 'echo',
            'celta'        => 'function',
            'uno'          => 'return',
            'corvete'      => 'for',
            '350z'         => 'while',
            'mustang'      => 'foreach',
            'silvia'       => 'as',
            'charger'      => 'array'
        ],
        
        // Operadores
        'operadores' => [
            'verdadeiro'  => 'true',
            'falso'       => 'false',
            'nulo'        => 'null'
        ]
    ];
    
    $codigo = preg_replace(
    '/paracada\s*\(\s*@(\w+)\s+como\s+@(\w+)\s*\)/',
    'foreach ($2 as $1)', // Inverte @$1 e @$2
    $codigo
    );

    // 1. Substitui variáveis (@var → $var)
    $codigo = preg_replace(
        $substituicoes['variaveis']['padrao'],
        $substituicoes['variaveis']['substituicao'],
        $codigo
    );

    // 2. Substitui palavras-chave e operadores
    foreach ($substituicoes['keywords'] as $custom => $php) {
        $codigo = preg_replace(
            "/\b" . preg_quote($custom, '/') . "\b/",
            $php,
            $codigo
        );
    }

    foreach ($substituicoes['operadores'] as $custom => $php) {
        $codigo = preg_replace(
            "/\b" . preg_quote($custom, '/') . "\b/",
            $php,
            $codigo
        );
    }

    // Execução do código
    eval($codigo);
}
?>