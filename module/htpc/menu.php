<?php

/**
  2 	Administrador
 * //
 */
//Administrador
$menu[2] = [
    'Início'=>['url' => '/htpc/index'],
    //dropdawn
    'Cadastro' => [ 
        'page' => [
            "Visualizar " => ['url' => '/htpc/htpc'] //'target'=>1
        ]
    ],
    1=>['url' => '/htpc/pag'], //permissão de página fora do menu
];

//Coordenadoria
$menu[26] = [
    'Início'=>['url' => '/htpc/index'],
    //dropdawn
    'Propor Pautas' => ['url' => '/htpc/proporPauta'],
    1=>['url' => '/htpc/proporPautaUpload'],
];

//Coordenador
$menu[48] = [
    'Início'=>['url' => '/htpc/index'],
    //dropdawn
    'ATA' => ['url' => '/htpc/atas'],
    'Pautas' => ['url' => '/htpc/pautas'],
    'Pautas Propostas' => ['url' => '/htpc/proporPauta'],
    1=>['url' => '/htpc/pag'],
    2=>['url' => '/htpc/pautasCadastro'],
    3=>['url' => '/htpc/atasCadastro'],
    4=>['url' => '/htpc/ataVisualizar'],
    5=>['url' => '/htpc/presenca'],
    6=>['url' => '/htpc/presencaRemover'],
    7=>['url' => '/htpc/ementaCadastro'],
    8=>['url' => '/htpc/pautaDesativar'],
];

//Professor(a)
$menu[24] = [
    'Início'=>['url' => '/htpc/index'],
    //dropdawn
    'Pautas' => ['url' => '/htpc/pautas'],
    // 'ATA' => ['url' => '/htpc/atas'],
    1=>['url' => '/htpc/pag'], //permissão de página fora do menu
    2=>['url' => '/htpc/ataVisualizar'],
    3=>['url' => '/htpc/presenca'],
];