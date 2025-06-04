<?php
$system = isset($this->sistema) ? $this->sistema : 'tdics';

/**
  2 	Administrador
 * //
 */
//Administrador
$menu[2] = [
    'Início' => ['url' => '/'.$system.'/index'],
    // 'Run' => ['url' => '/'.$system.'/run'],
    "Avaliações" => [
        'page' => [
            'Cadastro de Grupo' => ['url' => '/'.$system.'/avalGroup'],
            'Cadastro de Avaliação' => ['url' => '/'.$system.'/avalCad'],
            'Ficha de Avaliação' => ['url' => '/'.$system.'/aval'],
            'Relatório' => ['url' => '/'.$system.'/avalRelat'],
        ],
    ],
];

//gerente
$menu[10] = [
    'Início' => ['url' => '/'.$system.'/index'],
    'Gerenciar Alunos' => ['url' => '/'.$system.'/alocaAlu'],
    'Presença' => [
        'page' => [
            'Chamada' => ['url' => '/'.$system.'/chamada'],
            'Frequência' => ['url' => '/'.$system.'/frequencia'],
        ]
    ],
    'Cadastro' => [
        'page' => [
            'Núcleo' => ['url' => '/'.$system.'/poloCad'],
            'Cursos' => ['url' => '/'.$system.'/cursoCad'],
            'Turmas' => ['url' => '/'.$system.'/turmaCad'],
            'Alunos' => ['url' => '/'.$system.'/alunoCad'],
        ]
    ],
    'Relatórios' => [
        'page' => [
            "Quadro de Alunos" => ['url' => '/'.$system.'/quadro'],
            "Quadro de Vagas" => ['url' => '/'.$system.'/vagas'],
            'Lanche' => ['url' => '/'.$system.'/lanche', 'target' => 1],
            'Lista Piloto' => ['url' => '/'.$system.'/listaPiloto'],
            'Termo de Matrícula' => ['url' => '/'.$system.'/termoList'],
            'Alunos AEE' => ['url' => '/'.$system.'/aee'],
            'Inscrições' => ['url' => '/'.$system.'/inscricao'],
            'Cerfificados' => ['url' => '/'.$system.'/certif']
        ]
    ],
    'Configurações' => ['url' => '/'.$system.'/setup'],
];

//professor
$menu[24] = [
    'Início' => ['url' => '/'.$system.'/index'],
    'Gerenciar Alunos' => ['url' => '/'.$system.'/alocaAlu'],
    'Chamada' => ['url' => '/'.$system.'/chamada'],
    'Lista Piloto' => ['url' => '/'.$system.'/listaPiloto'],
];

//Núcleo Tdics
$menu[57] = [
    'Início' => ['url' => '/'.$system.'/index'],
    'Lista Piloto' => ['url' => '/'.$system.'/listaPiloto'],
];

//Call Center
$menu[39] = [
    'Início' => ['url' => '/'.$system.'/index'],
    'Ausentes' => ['url' => '/'.$system.'/freqCall'],
    'Lista Piloto' => ['url' => '/'.$system.'/listaPiloto'],
];

//professor de informática
$menu[43] = [
    'Início' => ['url' => '/'.$system.'/index'],
];

//escola
$menu[8] = [
    'Início' => ['url' => '/'.$system.'/index'],
    'Gerenciar Alunos' => ['url' => '/'.$system.'/alocaAlu'],
    'Relatórios' => [
        'page' => [
            'Lista de Alunos' => ['url' => '/'.$system.'/pdf/listEsc', 'target' => 1],
            'Termo de Matrícula' => ['url' => '/'.$system.'/termoList'],
            'Frequência' => ['url' => '/'.$system.'/frequencia'],
            'Cerfificados' => ['url' => '/'.$system.'/certif']
        ]
    ],
];

