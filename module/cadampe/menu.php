<?php

/**
  2 	Administrador
 * //
 */
//Administrador
$menu[2] = [
    'Início' => ['url' => '/cadampe/index'],
    "Listar Solicitações" => ['url' => '/cadampe/solicitarList']
];
//Gerente
$menu[10] = [
    'Início' => ['url' => '/cadampe/index'],
    "Protocolos" => ['url' => '/cadampe/protocolosList'],
    "Inativar Cadampe" => ['url' => '/cadampe/inativaCadampeList'],
    "Listar Cadampes" => ['url' => '/cadampe/listCadampe'],
    "Listar Convocados" => ['url' => '/cadampe/convocadosList'],
    "Listar Professores Substituídos" => ['url' => '/cadampe/professorSubst']
];
//escola
$menu[8] = [
    'Início' => ['url' => '/cadampe/index'],
    "Listar Solicitações" => ['url' => '/cadampe/solicitarList'],
    "Relatório Convocados" => ['url' => '/cadampe/convocadosList']
];

//call center
$menu[39] = [
    'Início' => ['url' => '/cadampe/index'],
    "Listar Protocolos" => ['url' => '/cadampe/solicitarListcall'],
    "Listar Cadampes" => ['url' => '/cadampe/listCadampe'],
    "Listar Convocados" => ['url' => '/cadampe/convocadosList'],
    "Relatório de Protocolos" => ['url' => '/cadampe/protocolosList']
];

