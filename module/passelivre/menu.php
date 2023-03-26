<?php

/**
  2 	Administrador
 * //
 */
//Administrador
$menu[2] = [
    'Início' => ['url' => '/passelivre/index'],
    //dropdawn
    'Cadastro' => [
        'page' => [
            "Requerimento" => ['url' => '/passelivre/passelivre'] //'target'=>1
        ]
    ],
    'Consulta' => ['url' => '/passelivre/consulta'],
];
//Escola
$menu[8] = [
    'Início' => ['url' => '/passelivre/index'],
    //dropdawn
    'Cadastro' => [
        'page' => [
            "Requerimento" => ['url' => '/passelivre/passelivre']   
        ]
    ],
    
    'Consulta' => ['url' => '/passelivre/consulta'],
    
    'Lançamento' => [
      'page' => [
          "Faltas" => ['url' => '/passelivre/lancamento']
      ] 
    ],
    
    'Relatório' => [
        'page' => [
            "Lista" => ['url' => '/passelivre/relatorio']        
        ]   
    ],
];


//Gerente
$menu[10] = [
    'Início' => ['url' => '/passelivre/index'],
    //dropdawn
    'Cadastro' => [
        'page' => [
            "Requerimento" => ['url' => '/passelivre/passelivre'],
            'Usuários' => ['url' => '/passelivre/userCad'],
            'Escola Externa' => ['url' => '/passelivre/escolaCad'],
            'Parâmetro' => ['url' => '/passelivre/parametro']
        ]
    ],
    
    'Consulta' => ['url' => '/passelivre/consulta'],
    
    'Lançamento' => [
      'page' => [
          "Faltas" => ['url' => '/passelivre/lancamento'],
          "Lote" => ['url' => '/passelivre/lote']
      ] 
    ],
    
    'Relatório' => [
        'page' => [
            "Lista" => ['url' => '/passelivre/relatorio'],
            "Resumo" => ['url' => '/passelivre/resumo']
        ]
    ],
];