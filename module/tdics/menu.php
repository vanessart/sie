<?php

/**
  2 	Administrador
 * //
 */
//Administrador
$menu[10] = [
    'Início' => ['url' => '/tdics/index'],
    'Run' => ['url' => '/tdics/run'],
];

//gerente
$menu[1] = [
    'Início' => ['url' => '/tdics/index'],
    'Gerenciar Alunos' => ['url' => '/tdics/alocaAlu'],
    'Presença' => [
        'page' => [
            'Chamada' => ['url' => '/tdics/chamada'],
            'Frequência' => ['url' => '/tdics/frequencia'],
        ]
    ],
    'Cadastro' => [
        'page' => [
            'Núcleo' => ['url' => '/tdics/poloCad'],
            'Cursos' => ['url' => '/tdics/cursoCad'],
            'Turmas' => ['url' => '/tdics/turmaCad'],
            'Alunos' => ['url' => '/tdics/alunoCad'],
        ]
    ],
    'Relatórios' => [
        'page' => [
            "Quadro de Alunos" => ['url' => '/tdics/quadro'],
            "Quadro de Vagas" => ['url' => '/tdics/vagas'],
            'Lanche' => ['url' => '/tdics/lanche', 'target' => 1],
            'Lista Piloto' => ['url' => '/tdics/listaPiloto'],
            'Termo de Matrícula' => ['url' => '/tdics/termoList'],
            'Alunos AEE' => ['url' => '/tdics/aee'],
            'Inscrições' => ['url' => '/tdics/inscricao'],
            'Cerfificados' => ['url' => '/tdics/certif']
        ]
    ],
    'Configurações' => ['url' => '/tdics/setup'],
];

//professor
$menu[24] = [
    'Início' => ['url' => '/tdics/index'],
    'Chamada' => ['url' => '/tdics/chamada'],
    'Lista Piloto' => ['url' => '/tdics/listaPiloto'],
];

//Núcleo Tdics
$menu[57] = [
    'Início' => ['url' => '/tdics/index'],
    'Lista Piloto' => ['url' => '/tdics/listaPiloto'],
];

//Call Center
$menu[39] = [
    'Início' => ['url' => '/tdics/index'],
    'Ausentes' => ['url' => '/tdics/freqCall'],
    'Lista Piloto' => ['url' => '/tdics/listaPiloto'],
];

//professor de informática
$menu[43] = [
    'Início' => ['url' => '/tdics/index'],
];

//escola
$menu[8] = [
    'Início' => ['url' => '/tdics/index'],
    'Gerenciar Alunos' => ['url' => '/tdics/alocaAlu'],
    'Relatórios' => [
        'page' => [
            'Lista de Alunos' => ['url' => '/tdics/pdf/listEsc', 'target' => 1],
            'Termo de Matrícula' => ['url' => '/tdics/termoList'],
            'Frequência' => ['url' => '/tdics/frequencia'],
            'Cerfificados' => ['url' => '/tdics/certif']
        ]
    ],
];

