<?php

//Administrador
$menu[2] = [
    'Início' => ['url' => '/admin/index'],
    'Pessoas' => ['url' => '/admin/pessoas'],
    'Grupos' => [
        'page' => [
            'Gerenciamento de Grupos e Sistemas' => ['url' => '/admin/grupoSet'],
            'Gerenciamento de Acesso a Grupos' => ['url' => '/admin/grupoUser'],
            'Gerenciamento de Acesso a Sistemas' => ['url' => '/admin/sistemaUser'],
            'Cadastro de Grupos' => ['url' => '/admin/grupoCad'],
        ]
    ],
];

