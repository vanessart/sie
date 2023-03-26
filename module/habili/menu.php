<?php

/**
  //Administrador
 * 
 */
$menu[2] = [
    'Início' => ['url' => '/habili/index'],
    'Cadastro' => [
        'page' => [
            'Grupos de Habilidades' => ['url' => '/habili/grupo'],
            'Curso/Grupo' => ['url' => '/habili/habCurso'],
            'Objeto de Conhecimento' => ['url' => '/habili/objtCon'],
            'Unidade Temática' => ['url' => '/habili/unidTema'],
            'Campo de Experiência' => ['url' => '/habili/campExp'],
            'Habilidades' => ['url' => '/habili/habil'],
        ],
    ],
];
//Gerente
$menu[10] = [
    'Início' => ['url' => '/habili/index'],
    'Habilidades' => [
        'page' => [
            'Grupos' => ['url' => '/habili/grupo'],
            'Curso/Grupo' => ['url' => '/habili/habCurso'],
            'Objeto de Conhecimento' => ['url' => '/habili/objtCon'],
            'Unidade Temática' => ['url' => '/habili/unidTema'],
            'Campo de Experiência' => ['url' => '/habili/campExp'],
            'Cadastro de Habilidades' => ['url' => '/habili/habil'],
        ],
    ],
    1 => ['url' => '/habili/index'],
];

//professor
$menu[24] = [
    'Início' => ['url' => '/profe/index'],
    'Plano de Aula' => ['url' => '/habili/planoAula'],
    1 => ['url' => '/profe/chamada'],
    2 => ['url' => '/profe/index']
];

//Coordenador
$menu[48] = [
    'Início' => ['url' => '/profe/index'],
    'Plano de Aula' => ['url' => '/habili/planoAula'],
    1 => ['url' => '/profe/chamada'],
    2 => ['url' => '/profe/index']
];
