<?php

/**
  2 	Administrador
 * //
 */
//gerente
$menu[10] = [
    'Início' => ['url' => '/lab/index'],
    'Chromebook - Escola' => [
        'page' => [
            'Controle' => ['url' => '/lab/chrome'],
            'Empréstimo' => ['url' => '/lab/emprestimo'],
            'Movimentação' => ['url' => '/lab/mov'],
            'Histórico de Empréstimo' => ['url' => '/lab/histEmpr'],
            'Quadro Geral - Escola' => ['url' => '/lab/quadrogeral'],
        ]
    ],
    'Chromebook - Rede' => [
        'page' => [
            'Consulta Geral' => ['url' => '/lab/chromerede'],
            'Resumo' => ['url' => '/lab/chromeResumo'],
            'Empréstimo - Rede' => ['url' => '/lab/emprestRede'],
            // 'Histórico' => ['url' => '/lab/historico'],
            'Quadro Geral' => ['url' => '/lab/quadro'],
            //'Modem' => ['url' => '/lab/modem'],
            'Movimentação Adm' => ['url' => '/lab/movLog'],
            'Cadastro em Lote' => ['url' => '/lab/cadLote'],
        ]
    ],
    'Manutenção e Baixa' => [
        'page' => [
            'Movimentação' => ['url' => '/lab/manut'],
        ]
    ],
    'Últimos Cadastros' => ['url' => '/lab/bloqueio'],
    1 => ['url' => '/lab/emprestRede'],
    2 => ['url' => '/lab/emprestimo'],
];

//professor de informativa
$menu[43] = [
    'Início' => ['url' => '/lab/index'],
    //dropdawn
    'Controle' => ['url' => '/lab/chrome'],
    'Empréstimo' => ['url' => '/lab/emprestimo'],
    'Movimentação' => ['url' => '/lab/mov'],
];

//professor
$menu[24] = [
    'Início' => ['url' => '/lab/index'],
        //'Chromebook' => ['url' => '/lab/chromeProf'],
];

