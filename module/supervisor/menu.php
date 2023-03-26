<?php

/**
  2 	Administrador
 * //
 */
//Administrador
$menu[2] = [
    'Início' => ['url' => '/supervisor/index'],
    //dropdawn
    'Cadastros' => [
        'page' => [
            "Escola Particular" => ['url' => '/supervisor/escolaParticularPesq'],
            "Áreas Responsáveis" => ['url' => '/supervisor/areaResponsavelPesq'],
            "Itens de Ocorrências" => ['url' => '/supervisor/itemOcorrenciaPesq'],
            "Setores e atribuição de escolas" => ['url' => '/supervisor/setoresAtribuicaoEscolaPesq'],
        ]
    ],
    // 'Relatórios' => [
    //     'page' => [
    //         'Relatorio para saber quem nao fez agenda' => ['url' => '/supervisor/relatorioSemAgenda'],
    //         'Cruzamento para validar visitas versus justificativa de ausencia' => ['url' => '/supervisor/relatorioJustificativaPorAusencia'],
    //         'Escolas menos visitadas' => ['url' => '/supervisor/relatorioPoucasVisitas'],
    //         'Departamentos mais requisitados' => ['url' => '/supervisor/relatorioDepartamentosMaisRequisitados'],
    //         'Departamentos com mais ocorencias nao resolvidas' => ['url' => '/supervisor/relatorioDepartamentosBaixaResolutiva'],
    //         'Departamentos com maior tempo de resolução' => ['url' => '/supervisor/relatorioDepartamentoMaiorSLA'],
    //     ]
    // ],
    1 => ['url' => '/supervisor/pag'], //permissão de página fora do menu
];
//Supervisor
$menu[22] = [
    'Início' => ['url' => '/supervisor/index'],
    //dropdawn
    /**
      'Cadastro' => [
      'page' => [
      //"Supervisor" => ['url' => '/supervisor/supervisor'], //'target'=>1
      "Instituições" => ['url' => '/supervisor/supervisorInstituicaoPesq'], //'target'=>1
      "Visitas" => ['url' => '/supervisor/supervisorVisitasPesq'], //'target'=>1
      ]
      ],
     * 
     */
    'Plano de Aula' => [
        'page' => [
            'Planos por Escola' => ['url' => '/supervisor/planoAula'],
            'Professores por Escola' => ['url' => '/supervisor/profList'],
        ]
    ],
    'Projetos' => ['url' => '/supervisor/projetoCoord'],
    'Diário' => [
        'page' => [
            'Consolidado' => ['url' => '/supervisor/consolidadoCoord'],
            'Controle de Aulas Registradas por Dia' => ['url' => '/supervisor/controDiario'],
            'Faltas' => ['url' => '/supervisor/faltasRede'],
        ]
    ],
    'Habilidades - Aplicação' => ['url' => '/supervisor/coorFund'],
    'Documentações AEE' => ['url' => '/supervisor/apd'],
];
