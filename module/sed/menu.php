<?php

$sql = "SELECT fk_id_curso FROM `sed_inst_curso` WHERE `fk_id_inst` = " . toolErp::id_inst();
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
$curso = array_column($array, 'fk_id_curso');
if (in_array(3, $curso) || in_array(7, $curso) || in_array(8, $curso)) {
    $ensino = 3; #infantil
} elseif (in_array(1, $curso) || in_array(5, $curso) || in_array(9, $curso)) {
    $ensino = 4; #fundamental
} else {
    $ensino = null;
}

$sql = " SELECT terceirizada FROM `instancia` WHERE `id_inst` = " . toolErp::id_inst();
$query = pdoSis::getInstance()->query($sql);
$terceirizada = $query->fetch(PDO::FETCH_ASSOC)['terceirizada'];

//Administrador
$menu[2] = [
    'Início' => ['url' => '/sed/index'],
    'SED' => [
        'page' => [
            'Sincronizar' => ['url' => '/sed/sincronizar'],
            'Consulta' => ['url' => '/sed/consulta']
        ]
    ],
    'CID' => ['url' => '/sed/cid'],
    'Import Manual' => ['url' => '/sed/import'],
    'Import Automatizada' => ['url' => '/sed/integracaoAlunos'],
];

//Informações
$menu[31] = [
    'Início' => ['url' => '/sed/index'],
    'Professor' => [
        'page' => [
            'Relatórios por Turma' => ['url' => '/sed/relatProfTurma'],
            'Carômetro por Turma' => ['url' => '/sed/carometroProf'],
        ]
    ],
];

//Professor de Informática
$menu[43] = [
    'Início' => ['url' => '/sed/index'],
    'Relatórios' => [
        'page' => [
            'Carômetro' => ['url' => '/sed/carometro'],
            'Escola' => ['url' => '/sed/escolaRelat'],
            'Turmas ' => ['url' => '/sed/turmaRelat'],
            'Professor' => ['url' => '/sed/relatProf'],
            'Quadro de Aluno' => ['url' => '/sed/quadroAluno'],
            'Carteira Municipal/Crachá' => ['url' => '/sed/carteirinha'],
        ]
    ],
];

//Vida Escolar
$menu[44] = [
    'Início' => ['url' => '/sed/index'],
    'Alunos Fora de Data' => ['url' => '/sed/foraData'],
    'Manutenções' => [
        'page' => [
            'Projeto Paternidade Responsável' => ['url' => '/sed/paternidade'],
        ]
    ],
];

//escola
$menu[8] = [
    'Início' => ['url' => '/sed/index'],
    'Cadastro' => [
        'page' => [
            'Turmas' => ['url' => '/sed/turmaSet'],
            'Nova Matrícula' => ['url' => '/sed/alunoNovo'],
            'Declaração Vagas/Comparecimento' => ['url' => '/sed/declaracaovaga'],
            'Convocação/Evento' => ['url' => '/sed/convocacao']
//           'Sala de Aula'=>['url'=>'/sed/salaAula']
        ]
    ],
    'Gerenciar' => [
        'page' => [
            'Turmas/Alunos' => ['url' => '/sed/turmas'],
            'Alunos' => ['url' => '/sed/alunoPesq'],
            // 'Senhas de Funcionários' => ['url' => '/sed/funcSenha'],
            'Prédio' => ['url' => '/sed/predio']
        ]
    ],
    'Professores' => [
        'page' => [
            'Cadastro' => ['url' => '/sed/profCad'],
            'Alocação' => ['url' => '/sed/alocaProf'],
            'Horário' => ['url' => '/sed/horario'],
            'Redefinir Senha e E-mail' => ['url' => '/sed/senhaProf']
        ]
    ],
    'Relatórios' => [
        'page' => [
            'Carômetro' => ['url' => '/sed/carometro'],
            'Escola' => ['url' => '/sed/escolaRelat'],
            'Turmas ' => ['url' => '/sed/turmaRelat'],
            'Professor' => ['url' => '/sed/relatProf'],
            'Quadro de Aluno' => ['url' => '/sed/quadroAluno'],
            'Carteira Municipal/Crachá' => ['url' => '/sed/carteirinha'],
            'Sistema Antigo de RM' => ['url' => '/sed/consultarm'],
        ]
    ],
    'Interação' => [
        'page' => [
            'Mural de Aviso' => ['url' => '/sed/mural'],
            'Grupos' => ['url' => '/sed/grupo'],
            'Convocação/Declaração' => ['url' => '/sed/convoca'],
        //  'Mala Direta' => ['url' => '/sed/mala']
        ]
    ],
    'Encaminhamentos' => [
        'page' => [
            'Gerar' => ['url' => '/sed/encaminhamento'],
            'Carta Individual' => ['url' => '/sed/encaminhamentocarta'],
            'Lista de Alunos Recebidos' => ['url' => '/sed/pdf/listadestpdf.php', 'target' => 1],
        ]
    ],
    /**
      'Manutenções' => [
      'page' => [
      'Projeto Paternidade Responsável' => ['url' => '/sed/paternidade'],
      ]
      ],
     * 
     */
    1 => ['url' => '/sed/def'],
    2 => ['url' => '/sed/historico'],
    3 => ['url' => '/historico/hist'],
    4 => ['url' => '/historico/index'],
];
if ($terceirizada) {
    $menu[8]['Cadastro']['page']['Funcionarios'] = ['url' => '/sed/cadFunc'];
} else {
    $menu[8]['Cadastro']['page']['Redefinir Senha'] = ['url' => '/sed/senhafunc'];
}
if ($ensino == 3) {
    $menu[8]['Relatórios']['page']['Alunos Fora da Data Base'] = ['url' => '/sed/foraDataEsc'];
}

//gerente
$menu[10] = [
    'Início' => ['url' => '/sed/index'],
    'Configurações' => [
        'page' => [
            'Escolas' => ['url' => '/sed/escolaSet'],
            'Calendário' => ['url' => '/sed/calendario'],
            'Período Letivo' => ['url' => '/sed/pl'],
            'Unidade Letiva' => ['url' => '/sed/unidLetiva'],
            'Dias não letivos' => ['url' => '/sed/feriado'],
            'Segmentos, Cursos e Ciclos' => ['url' => '/sed/ensino'],
            'Grades e Disciplinas' => ['url' => '/sed/disc'],
            'Carga Horária/Período Letivo' => ['url' => '/sed/cargaHoraria'],
            'Resetar Google ID' => ['url' => '/sed/googleId'],
            'Lista Branca professor/escola' => ['url' => '/sed/listaLiberadosProfEsc']
        ]
    ],
    'Quadro de avisos' => ['url' => '/sed/quadro'],
    'Relatórios' => [
        'page' => [
            'Ocupação das Salas de Aula' => ['url' => '/sed/salaAula']
        ]
    ],
    'Manutenções' => [
        'page' => [
            'Projeto Paternidade Responsável' => ['url' => '/sed/paternidadeMain'],
        ]
    ],
    1 => ['url' => '/sed/def'],
];

