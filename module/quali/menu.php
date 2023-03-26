<?php

//$insc = $model->_formAdm;
//Administrador
//$menu[1] 1 = ID do perfil 
$menu[2] = [
    'Início' => ['url' => '/quali/index'],
    'Deferimento' => ['url' => '/quali/defere'],
    'Lista de aprovados' => ['url' => '/quali/deferido'],
    'Configurar Inscrição' => ['url' => '/quali/inscrSet'],
    'Exportação para Matrícula' => ['url' => '/quali/export'],
    1 => ['url' => '/quali/def/formInscrSet.php'],
    2 => ['url' => '/quali/def/formInscrEdit.php'],
    3 => ['url' => '/quali/pdf/aprovados.php'],
];
//gerente
$menu[13] = [
    'Início' => ['url' => '/quali/index'],
    'Matriculados' => [
        'page' => [
            'Exportar' => ['url' => '/quali/exportarMatric'],
            'Aproveitamento do Aluno' => ['url' => '/curso/aproveita'],
            'Quadro de Alunos' => ['url' => '/gt/quadro'],
            'Quadro de Alunos por Período Letivo' => ['url' => '/gt/quadroper'],
        ]
    ],
    'Inscrição' => [
        'page' => [
            'Deferimento' => ['url' => '/quali/defere'],
            'Lista de aprovados' => ['url' => '/quali/deferido'],
            'Configurar Inscrição' => ['url' => '/quali/inscrSet'],
        //   'Nova Inscrição'=> ['url'=>'/quali/inscr/9', 'target'=>1]
        ]
    ],
    'Relatórios' => [
        'page' => [
            'Geral' => ['url' => '/quali/relatorio'],
            'Aproveitamento do Aluno' => ['url' => '/curso/aproveita'],
            'Todos Participantes' => ['url' => '/quali/todosPart']
        ]
    ],
    1 => ['url' => '/quali/def/formInscrSet.php'],
    2 => ['url' => '/quali/def/formInscrEdit.php'],
    3 => ['url' => '/quali/pdf/aprovados.php'],
    4 => ['url' => '/gt/relatorios'],
    5 => ['url' => '/general/planilha/1'],
    6 => ['url' => 'gt//gt/relatorios'],
    7 => ['url' => '/quali/pdf/geral.php'],
    101 => ['url' => '/general/planilha/3'],
    102 => ['url' => '/general/planilha/4'],
    103 => ['url' => '/general/planilha/5'],
    104 => ['url' => '/general/planilha/8'],
    105 => ['url' => '/general/planilha/9'],
    106 => ['url' => '/general/planilha/10'],
    107 => ['url' => '/general/planilha/11'],
    108 => ['url' => '/general/planilha/12'],
    109 => ['url' => '/general/planilha/13'],
    110 => ['url' => '/curso/def/formPesq.php'],
    111 => ['url' => '/curso/def/formPesqQuest.php']
];
