<?php

/**
  2 	Administrador
 * //
 */
//Gerente
$menu[10] = [
    'Início' => ['url' => '/cit/index'],
    'Pesquisa' => ['url' => '/cit/pesq'],
    'Importação de Funcionários' => [
        'page' => [
            'Gerar Token' => ['url' => '/cit/token'],
            'Importar Funcionários pelo CIT' => ['url' => '/cit/import'],
            'Importar Funcionários pelo RH' => ['url' => '/cit/importRh'],
            'Configurar Lotação/Instâcia' => ['url' => '/cit/idLotacao'],
        ]
    ],
    'Professores' => [
        'page' => [
            'Alocar' => ['url' => '/cit/alocaProf'],
            'Novo Alocar' => ['url' => '/cit/rh'],
            'HTPC' => ['url' => '/cit/htpc'],
        ]
    ],
    'E-mails' => [
        'page' => [
            'E-mails Google' => ['url' => '/cit/email'],
            'Resetar Google ID' => ['url' => '/cit/googleId'],
        ],
    ],
    'Testes' => ['url' => '/cit/teste'],
//    'Testes_2' => ['url' => '/cit/run'],
]; // Administrador Email Google 
$menu[46] = [
    'Início' => ['url' => '/cit/index'],
    'E-mails Google' => ['url' => '/cit/email'],
    'Resetar Google ID' => ['url' => '/cit/googleId'],
];

//suporte Técnico
$menu[49] = [
    'Início' => ['url' => '/cit/index'],
    'Gerenciamento de E-mails' => ['url' => '/cit/email'],
];
