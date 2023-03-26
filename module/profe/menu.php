<?php

$sql = "SELECT ci.fk_id_curso FROM ge_funcionario f "
        . " JOIN ge_aloca_prof ap on ap.rm = f.rm "
        . " JOIN ge_turmas t on t.id_turma = ap.fk_id_turma "
        . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
        . " WHERE `fk_id_pessoa` = " . toolErp::id_pessoa();

$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    foreach ($array as $v) {
        if (in_array($v['fk_id_curso'], [1, 5, 9])) {
            $seg[1] = 1;
        } elseif (in_array($v['fk_id_curso'], [3, 7, 8])) {
            $seg[2] = 2;
        }
    }
    if (!empty($seg)) {
        $profTp = implode('', $seg);
    } else {
        $profTp = null;
    }
} else {
    $profTp = null;
}
if (empty($_SESSION['userdata']['profTp'])) {
    $_SESSION['userdata']['profTp'] = $profTp;
}
/**
  2 	Administrador
 * //
 */
//Administrador
$menu[2] = [
    'Início' => ['url' => '/profe/index'],
    'Plano de Aula' => ['url' => '/profe/planoAula'],
    'Projeto' => ['url' => '/profe/projetoCoord'],
    'Professores por Escola' => ['url' => '/profe/prof'],
];
//gerente
$menu[10] = [
    'Início' => ['url' => '/profe/index'],
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
    'Avisos' => [
        'page' => [
            'Professores' => ['url' => '/profe/profMsg'],
            'Coordenadores' => ['url' => '/profe/coordMsg'],
        ]
    ],
    'Controle' => ['url' => '/profe/setup'],
    1 => ['url' => '/habili/index'],
];
//escola
$menu[8] = [
    'Início' => ['url' => '/profe/index'],
    'Controle de Faltas' => ['url' => '/profe/controlFalta'],
    1 => ['url' => '/profe/chamada'],
];
//Informações
$menu[31] = [
    'Início' => ['url' => '/profe/index'],
    'geral' => ['url' => '/profe/infoGeral'],
    1 => ['url' => '/profe/chamada'],
];

//professor
if ($profTp == 1) {
    $menu[24] = [
        'Início' => ['url' => '/profe/index'],
        'Controle de Pendências' => ['url' => '/profe/controlFaltaProf'],
        'Plano de Aula' => ['url' => '/profe/planoAula'],
        'Adaptação Curricular' => ['url' => '/profe/adaptCurriculo'],
        'Instrumentos Avaliativos' => ['url' => '/profe/relatorioNotaProf'],
        'Relatório Chamada' => ['url' => '/profe/relatorioChamadaProf'],
        'Lançamento de Notas' => ['url' => '/profe/lancNota'],
        'Consolidado' => ['url' => '/profe/consolidadoProf'],
        'Habilidades' => ['url' => '/profe/habilidadesList'],
        'Habilidades Aplicadas' => ['url' => '/profe/habilAplic'],
        'Relatórios por Turma' => ['url' => '/profe/relatProfTurma'],
        'Carômetro por Turma' => ['url' => '/profe/carometroProf'],
        1 => ['url' => '/profe/chamada'],
        2 => ['url' => '/habili/index'],
        3 => ['url' => '/profe/chamadaTabela'],
        4 => ['url' => '/profe/notaTabela'],
        5 => ['url' => '/profe/consolidado']
    ];
} elseif ($profTp == 2) {
    $menu[24] = [
        'Início' => ['url' => '/profe/index'],
        'Controle de Pendências' => ['url' => '/profe/controlFaltaProf'],
        'Projeto' => ['url' => '/profe/projetoProf'],
        // 'Relatório Chamada' => ['url' => '/profe/relatorioChamadaProf'],
        'Consolidado' => ['url' => '/profe/consolidadoProf'],
        'Acompanhamento de Aprendizagem' => ['url' => '/profe/acompApr'],
        'Habilidades' => ['url' => '/profe/habilidadesList'],
        'Relatórios por Turma' => ['url' => '/profe/relatProfTurma'],
        'Carômetro por Turma' => ['url' => '/profe/carometroProf'],
        1 => ['url' => '/profe/chamada'],
        2 => ['url' => '/habili/index'],
        3 => ['url' => '/profe/chamadaTabela'],
        4 => ['url' => '/profe/notaTabela'],
        5 => ['url' => '/profe/consolidado']
    ];
} elseif ($profTp == 12 || $profTp == 21) {
    $menu[24] = [
        'Início' => ['url' => '/profe/index'],
        'Controle de Pendências' => ['url' => '/profe/controlFaltaProf'],
        'Plano de Aula' => ['url' => '/profe/planoAula'],
        'Projeto' => ['url' => '/profe/projetoProf'],
        'Adaptação Curricular' => ['url' => '/profe/adaptCurriculo'],
        'Instrumentos Avaliativos' => ['url' => '/profe/relatorioNotaProf'],
        'Relatório Chamada' => ['url' => '/profe/relatorioChamadaProf'],
        'Lançamento de Notas' => ['url' => '/profe/lancNota'],
        'Consolidado' => ['url' => '/profe/consolidadoProf'],
        'Acompanhamento de Aprendizagem' => ['url' => '/profe/acompApr'],
        'Habilidades' => ['url' => '/profe/habilidadesList'],
        'Habilidades Aplicadas' => ['url' => '/profe/habilAplic'],
        'Habilidades Aplicadas' => ['url' => '/profe/habilAplic'],
        'Relatórios por Turma' => ['url' => '/profe/relatProfTurma'],
        'Carômetro por Turma' => ['url' => '/profe/carometroProf'],
        1 => ['url' => '/profe/chamada'],
        2 => ['url' => '/habili/index'],
        3 => ['url' => '/profe/chamadaTabela'],
        4 => ['url' => '/profe/notaTabela'],
        5 => ['url' => '/profe/consolidado']
    ];
} else {
    $menu[24] = [
        'Início' => ['url' => '/profe/index'],
        'Controle de Pendências' => ['url' => '/profe/controlFaltaProf'],
        'Plano de Aula' => ['url' => '/profe/planoAula'],
        'Projeto' => ['url' => '/profe/projetoProf'],
        'Adaptação Curricular' => ['url' => '/profe/adaptCurriculo'],
        'Instrumentos Avaliativos' => ['url' => '/profe/relatorioNotaProf'],
        'Relatório Chamada' => ['url' => '/profe/relatorioChamadaProf'],
        'Lançamento de Notas' => ['url' => '/profe/lancNota'],
        'Consolidado' => ['url' => '/profe/consolidadoProf'],
        'Acompanhamento de Aprendizagem' => ['url' => '/profe/acompApr'],
        'Habilidades' => ['url' => '/profe/habilidadesList'],
        'Habilidades Aplicadas' => ['url' => '/profe/habilAplic'],
        'Relatórios por Turma' => ['url' => '/profe/relatProfTurma'],
        'Carômetro por Turma' => ['url' => '/profe/carometroProf'],
        1 => ['url' => '/profe/chamada'],
        2 => ['url' => '/habili/index'],
        3 => ['url' => '/profe/chamadaTabela'],
        4 => ['url' => '/profe/notaTabela'],
        5 => ['url' => '/profe/consolidado']
    ];
}
//coordenador fundamental
$menu[55] = [
    'Início' => ['url' => '/profe/index'],
    //'Diário 2021' => ['url' => '/profe/diariotmp'],
    'Plano de Aula' => [
        'page' => [
            'Por Turma' => ['url' => '/profe/planoTurma'],
            'Por Professor' => ['url' => '/profe/planoAula'],
            'Controle por Calendário' => ['url' => '/profe/planoVerific'],
        ]
    ],
    'Diário' => [
        'page' => [
            // 'Por Professor' => ['url' => '/profe/diarioPorProf'],
            'Acesso' => ['url' => '/profe/diarioPorTurma'],
            'Relatório Chamada' => ['url' => '/profe/relatorioChamada'],
            'Consolidado' => ['url' => '/profe/consolidadoCoord'],
            'Gerar Link' => ['url' => '/profe/link'],
            'Adaptação Curricular' => ['url' => '/profe/adaptCurriculo'],
            'Controle de Aulas Registradas por Dia ' => ['url' => '/profe/controDiario'],
        //  'Relatório' => ['url' => '/profe/relatorio']
        ]
    ],
    'Menções e Faltas' => [
        'page' => [
            'Lançamento de Notas' => ['url' => '/profe/lancNotaCoord'],
            'Justificar Faltas' => ['url' => '/profe/justificaFaltas'],
        ],
    ],
    'Habilidades' => [
        'page' => [
            'Lista de Habilidades' => ['url' => '/profe/habilidadesList'],
            'Habilidades - Aplicação' => ['url' => '/profe/coorFund'],
        //        'Ranqueamento das Habilidades' => ['url' => '/profe/habilRanking'],
        ]
    ],
    //   'Projeto' => ['url' => '/profe/projetoCoord'],
    1 => ['url' => '/profe/chamada'],
    2 => ['url' => '/profe/diarioCoord'],
    3 => ['url' => '/profe/index'],
    4 => ['url' => '/habili/index'],
    5 => ['url' => '/profe/chamadaTabela'],
    6 => ['url' => '/profe/lancNota'],
    8 => ['url' => '/profe/consolidado']
];
// infatil (coordenador)

$menu[56] = [
    'Início' => ['url' => '/profe/index'],
    //'Diário 2021' => ['url' => '/profe/diariotmp'],
    'Diário' => [
        'page' => [
            // 'Por Professor' => ['url' => '/profe/diarioPorProf'],
            'Acesso' => ['url' => '/profe/diarioPorTurma'],
            'Relatório Chamada' => ['url' => '/profe/relatorioChamada'],
            'Relatório Ocorrências' => ['url' => '/profe/relatorioOcorrencias'],
            'Consolidado' => ['url' => '/profe/consolidadoCoord'],
            'Gerar Link' => ['url' => '/profe/link'],
            'Controle de Faltas' => ['url' => '/profe/relatFalta'],
            'Habilidades' => ['url' => '/profe/habilidadesList'],
            'Adaptação Curricular' => ['url' => '/profe/adaptCurriculo'],
            'Controle de Aulas Registradas por Dia ' => ['url' => '/profe/controDiario'],
        //  'Relatório' => ['url' => '/profe/relatorio']
        ],
    ],
    'Projeto' => ['url' => '/profe/projetoCoord'],
    'Acompanhamento de Aprendizagem' => ['url' => '/profe/acompApr'],
    1 => ['url' => '/profe/chamada'],
    2 => ['url' => '/profe/diarioCoord'],
    3 => ['url' => '/profe/index'],
    4 => ['url' => '/habili/index'],
    5 => ['url' => '/profe/chamadaTabela'],
    6 => ['url' => '/profe/lancNota'],
    8 => ['url' => '/profe/consolidado'],
    9 => ['url' => '/profe/projetoProf'],
];

//Avaliação In Loco
$menu[18] = [
    'Início' => ['url' => '/profe/index'],
    'Plano de Aula' => ['url' => '/profe/planoAula'],
    'Projeto' => ['url' => '/profe/projetoCoord'],
    'Professores por Escola' => ['url' => '/profe/profList'],
    'Professores - Suplementar' => ['url' => '/profe/profListSupl'],
    'Consolidado' => ['url' => '/profe/consolidadoCoord'],
    1 => ['url' => '/profe/consolidado'],
];

//Coordenadoria Infantil
$menu[53] = [
    'Início' => ['url' => '/profe/index'],
    'Controle de Faltas' => ['url' => '/profe/faltaSeq'],
    'Sondagem' => [
        'page' => [
            'Acompanhamento por Escola' => ['url' => '/profe/acompApr'],
            'Relatório Geral' => ['url' => '/profe/acompAprGr'],
        ]
    ],
    'Diário' => [
        'page' => [
            'Consolidado' => ['url' => '/profe/consolidadoCoord'],
            'Controle de Aulas Registradas por Dia ' => ['url' => '/profe/controDiario'],
        ],
    ],
    'Habilidades' => ['url' => '/profe/habilidadesList'],
    1 => ['url' => '/profe/index'],
    2 => ['url' => '/profe/relatFalta']
];

//Coordenadoria Fundamental
$menu[54] = [
    'Início' => ['url' => '/profe/index'],
    'Habilidades' => [
        'page' => [
            'Lista de Habilidades' => ['url' => '/profe/habilidadesList'],
            'Habilidades - Aplicação' => ['url' => '/profe/coorFund'],
            'Ranqueamento das Habilidades' => ['url' => '/profe/habilRanking'],
        ]
    ],
    'Diário' => [
        'page' => [
            'Consolidado' => ['url' => '/profe/consolidadoCoord'],
            'Controle de Aulas Registradas por Dia ' => ['url' => '/profe/controDiario'],
        ],
    ],
    'Professores por Escola' => ['url' => '/profe/profList'],
    'Relatórios' => [
        'page' => [
            'Faltas' => ['url' => '/profe/faltasRede']
        ]
    ],
    1 => ['url' => '/profe/consolidado'],
];
