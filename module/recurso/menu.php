<?php

/**
  2 	Professor
 * //
 */
//Professor
// $menu[24] = [
//     'Início'=>['url' => '/recurso/recurso'],
//     //dropdawn
// ];

//Gerente
$menu[10] = [
    'Início'=>['url' => '/recurso/index'],
    'Cadastro' => [
        'page' => [
            'Lote/Serial' => ['url' => '/recurso/cadLote'],
            'Modelo/Lote' => ['url' => '/recurso/cadEquipamento'],
            'Local' => ['url' => '/recurso/cadLocal'],
            'Categoria' => ['url' => '/recurso/cadCate'],
        ],
    ],
    'Controle'=>['url' => '/recurso/relGeral'],
    'Resumo'=>['url' => '/recurso/resumo'],
    'Movimentação' => [
        'page' => [
            'Empréstimo' => ['url' => '/recurso/empresta'],
            'Manutenção' => ['url' => '/recurso/manutencao'],
            'Ocorrência' => ['url' => '/recurso/ocorrencia'],
        ],
    ],
    'Relatórios' => [
        'page' => [
            'Empréstimo Funcionário Inativo' => ['url' => '/recurso/relEmprestFuncInat'],
            //'Movimentação' => ['url' => '/recurso/movimentRel'],
        ],
    ],

    //dropdawn
];

//Escola
$menu[8] = [
    'Início'=>['url' => '/recurso/index'],
    'Cadastro' => [
        'page' => [
            'Local' => ['url' => '/recurso/cadLocal'],
        ],
    ],
    'Controle'=>['url' => '/recurso/relGeral'],
    'Movimentação' => [
        'page' => [
            'Empréstimo' => ['url' => '/recurso/empresta'],
            'Ocorrência' => ['url' => '/recurso/ocorrencia'],
        ],
    ],
    // 'Relatórios' => [
    //     'page' => [
    //         'Equipamentos' => ['url' => '/recurso/relGeral'],
    //         'Movimentação' => ['url' => '/recurso/movimentRel'],
    //     ],
    // ],

    //dropdawn
];
