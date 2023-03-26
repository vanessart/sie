<?php

//Administrador
$menu[2] = [
    'Início' => ['url' => '/avalia/index'],
];

//Escola
$menu[8] = [
    'Início' => ['url' => '/avalia/index'],
    'Boletim' => ['url' => '/avalia/boletim'],
    'Gráficos' => [
        'page' => [
            'Geral e por Ciclo' => ['url' => '/avalia/grafGeral'],
            'Por Turma' => ['url' => '/avalia/grafTurma'],
            'Por Rendimento' => ['url' => '/avalia/grafPizza', 'target' => 1],
        ]
    ],
];

//Coordenador Fundamental
$menu[55] = [
    'Início' => ['url' => '/avalia/index'],
    'Boletim' => ['url' => '/avalia/boletim'],
    'Atas' => ['url' => '/avalia/atas'],
    'Gráficos' => [
        'page' => [
            'Geral e por Ciclo' => ['url' => '/avalia/grafGeral'],
            'Por Turma' => ['url' => '/avalia/grafTurma'],
            'Por Rendimento' => ['url' => '/avalia/grafPizza', 'target' => 1],
        ]
    ],
];

//Vida Escolar
$menu[44] = [
    'Início' => ['url' => '/avalia/index'],
];

