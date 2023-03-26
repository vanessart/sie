<?php

/**
  2 	Administrador
 * //
 */
//Administrador
$menu[2] = [
    'Início'=>['url' => '/transporte/index'],
    //dropdawn
    'Cadastro' => [ 
        'page' => [
            "Nome da Página" => ['url' => '/transporte/exemplo']
        ]
    ],
    1=>['url' => '/transporte/pag'], //permissão de página fora do menu
];

//Escola
$menu[8] = [
    'Início'=>['url' => '/transporte/index'],
    //dropdawn
    'Alocar' => [ 
        'page' => [
            'Aluno-Linha' => ['url' => '/transporte/aloca'],
            'Linha-Linha' => ['url' => '/transporte/troca'],
            // 'Escola Deferimento' => ['url' => '/transporte/deferir'],
        ]
    ],
    'Cadastro' => [
        'page' => [
            'Alunos' => ['url' => '/transporte/aluno'],
            'Faltas' => ['url' => '/transporte/falta'],
        ]
    ],
    'Relatórios' => [
        'page' => [
            'Lista Escola' => ['url' => '/transporte/selrelatorio'],
            'Lista de Espera' => ['url' => '/transporte/selrelatorioespera'],
            'Crachá' => ['url' => '/transporte/carteirinha'],
        ]
    ],
    1=>['url' => '/transporte/pag'], //permissão de página fora do menu
];

//Gerente
$menu[10] = [
    'Início'=>['url' => '/transporte/index'],
    //dropdawn
    'Configurações' => ['url' => '/transporte/setup'],
    'Cadastro' => [
        'page' => [
            'Veículos' => ['url' => '/transporte/cadveiculo'],
            'Linhas' => ['url' => '/transporte/cadlinha'],
            'Empresa' => ['url' => '/transporte/empresa'],
            'Alunos' => ['url' => '/transporte/aluno'],
            'Faltas' => ['url' => '/transporte/falta'],
        ]
    ],
    'Alocar' => [
        'page' => [
            'Aluno-Linha' => ['url' => '/transporte/aloca'],
            'Linha-Linha' => ['url' => '/transporte/troca'],
            'Lista Branca' => ['url' => '/transporte/listabranca'],
            'Escola Deferimento' => ['url' => '/transporte/deferir'],
        ]
    ],
    'Relatórios' => [
        'page' => [
            'Lista Escola' => ['url' => '/transporte/selrelatorio'],
            'Lista Rede' => ['url' => '/transporte/selrelatoriorede'],
            'Lista de Espera' => ['url' => '/transporte/selrelatorioespera'],
            'Distribuição Demográfica' => ['url' => '/info/heat'],
            'Consulta Veículo' => ['url' => '/transporte/selrelveiculo'],
            'Crachá' => ['url' => '/transporte/carteirinha'],
        ]
    ],
    'Adaptado' => [
        'page' => [
            'Cadastro' => ['url' => '/transporte/cadadaptado'],
            'Alocar' => ['url' => '/transporte/alocaadaptado'],
            'Lista Escola Adaptado' => ['url' => '/transporte/selrelatorioadap'],
        ]
    ],
    1=>['url' => '/transporte/pag'], //permissão de página fora do menu
    1=>['url' => '/transporte/transpexcluido'], 
];

//Diretor
$menu[17] = [
    'Início'=>['url' => '/transporte/index'],
    //dropdawn
    'Cadastro' => [ 
        'page' => [
            "Nome da Página" => ['url' => '/transporte/exemplo']
        ]
    ],
    1=>['url' => '/transporte/pag'], //permissão de página fora do menu
];

//Terceirizado
$menu[41] = [
    'Início'=>['url' => '/transporte/index'],
    1=>['url' => '/transporte/pag'], //permissão de página fora do menu
];