<?php

//Gerente
$menu[10] = [
    'Início' => ['url' => '/maker/index'],
    'Gerenciamento' => [
        'page' => [
            'Polos' => ['url' => '/maker/polos'],
            'Gestão de alunos' => ['url' => '/maker/alunoGest'],
            'Criar Turmas' => ['url' => '/maker/CriarTurma'],
            'permutar Turmas' => ['url' => '/maker/permutaTurma']
        ]
    ],
    'Visão das escolas' => [
        'page' => [
            // 'Inscrição de Alunos' => ['url' => '/maker/cadAlunos'],
            //'Matrícula' => ['url' => '/maker/matr'],
            //'Solicitação de Rematrícula' => ['url' => '/maker/rematricula'],
            'Gestão de Matrículas' => ['url' => '/maker/transf'],
            'Fichas Não Contempladas' => ['url' => '/maker/contempla'],
            'Permuta Direta' => ['url' => '/maker/permuta'],
            'Quadro de Alunos ' => ['url' => '/maker/quadro'],
            'Exportar Transporte   ' => ['url' => '/maker/transp'],
            'Transporte  ' => ['url' => '/maker/transporte'],
        ]
    ],
    'Relatorios' => [
        'page' => [
            'Lista de Parede Por Escola' => ['url' => '/maker/escolaList'],
            'Exportar Transporte' => ['url' => '/maker/transp'],
            'Lista Piloto' => ['url' => '/maker/piloto'],
            'Quadro de Alunos' => ['url' => '/maker/lache'],
            'Lanche' => ['url' => '/maker/lancheList', 'target' => 1],
            'Quadro de Transporte Geral' => ['url' => '/maker/transpGeral'],
            // 'Lista de Espera' => ['url' => '/maker/espera'],
            'Controle de Rematricula' => ['url' => '/maker/rematriculaRelat']
        ]
    ],
    'Presença' => [
        'page' => [
            'Chamada' => ['url' => '/maker/chamada'],
            // 'Confirmação de matrícula' => ['url' => '/maker/confirma'],
            'Frequência' => ['url' => '/maker/frequencia']
        ]
    ],
    'Configuração' => ['url' => '/maker/setup'],
    'Alocar script' => ['url' => '/maker/calcula']
        //   'Alocação' => ['url' => '/maker/aloca']
];

//Administrador
$menu[1] = [
    'Início' => ['url' => '/maker/index'],
    'Gerenciamento' => [
        'page' => [
            'Polos' => ['url' => '/maker/polos'],
            'Gestão de alunos' => ['url' => '/maker/alunoGest'],
            'Criar Turmas' => ['url' => '/maker/CriarTurma'],
            'permutar Turmas' => ['url' => '/maker/permutaTurma']
        ]
    ],
    'Visão das escolas' => [
        'page' => [
//'Inscrição de Alunos' => ['url' => '/maker/cadAlunos'],
//'Matrícula' => ['url' => '/maker/matr'],
            'Solicitação de Rematrícula' => ['url' => '/maker/rematricula'],
            'Gestão de Matrículas' => ['url' => '/maker/transf'],
            'Fichas Não Contempladas' => ['url' => '/maker/contempla'],
            'Permuta Direta' => ['url' => '/maker/permuta'],
            'Quadro de Alunos ' => ['url' => '/maker/quadro'],
            'Exportar Transporte   ' => ['url' => '/maker/transp'],
            'Transporte  ' => ['url' => '/maker/transporte'],
        ]
    ],
    'Relatorios' => [
        'page' => [
            'Lista de Parede Por Escola' => ['url' => '/maker/escolaList'],
            'Exportar Transporte' => ['url' => '/maker/transp'],
            'Lista Piloto' => ['url' => '/maker/piloto'],
            'Quadro de Alunos' => ['url' => '/maker/lache'],
            'Lanche' => ['url' => '/maker/lancheList', 'target' => 1],
            'Quadro de Transporte Geral' => ['url' => '/maker/transpGeral'],
            // 'Lista de Espera' => ['url' => '/maker/espera'],
            'Controle de Rematricula' => ['url' => '/maker/rematriculaRelat'],
            'Certificados' => ['url' => '/maker/certif'],
            'ranking Matrícula' => ['url' => '/maker/rankingMatr'],
        ]
    ],
    'Presença' => [
        'page' => [
            'Chamada' => ['url' => '/maker/chamada'],
            // 'Confirmação de matrícula' => ['url' => '/maker/confirma'],
            'Frequência' => ['url' => '/maker/frequencia']
        ]
    ],
        //'Configuração' => ['url' => '/maker/setup'],
//   'Alocação' => ['url' => '/maker/aloca']
];

//Escola
$menu[8] = [
    'Início' => ['url' => '/maker/index'],
    //descontinuado => 'inscrições' => ['url' => '/maker/cadAlunos'],
    //'Solicitação de Rematrícula' => ['url' => '/maker/rematricula'],
    //descontinuado => 'Matrícula' => ['url' => '/maker/matr'],
    'Gestão de Matrículas' => ['url' => '/maker/transf'],
    'Permuta Direta' => ['url' => '/maker/permuta'],
    'Fichas Não Contempladas' => ['url' => '/maker/contempla'],
    'Relatório' => [
        'page' => [
            'Lista Geral Interna por Turma' => ['url' => '/maker/turmasEscTur', 'target' => 1],
            'Lista Geral Interna' => ['url' => '/maker/turmasEsc', 'target' => 1],
            'Lista de Parede Por Polo' => ['url' => '/maker/escolaList'],
            'Quadro de Alunos ' => ['url' => '/maker/quadro'],
            //'Quadro de Transporte  ' => ['url' => '/maker/transporte'],
            //'Exportar Transporte   ' => ['url' => '/maker/transp'],
            'Certificados' => ['url' => '/maker/certif']
        ]
    ],
        //  'Confirmação de matrícula' => ['url' => '/maker/confirma']
];

//professor maker
$menu[52] = [
    'Início' => ['url' => '/maker/index'],
    'Chamada' => ['url' => '/maker/chamada'],
    'Lista Piloto' => ['url' => '/maker/piloto'],
    'Certificados' => ['url' => '/maker/certif']
];

//Transporte
$menu[51] = [
    'Início' => ['url' => '/maker/index'],
    'Dados dos Alunos' => ['url' => '/maker/transp'],
    'Lista Piloto' => ['url' => '/maker/piloto'],
    'Frequência' => ['url' => '/maker/frequencia'],
    'Quadro de Transporte Geral' => ['url' => '/maker/transpGeral'],
    'Transporte  ' => ['url' => '/maker/transporte'],
];
