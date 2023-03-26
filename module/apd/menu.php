<?php

/**
  2 	Administrador
 * //
 */
//Gerente
$menu[10] = [
    'Início'=>['url' => '/apd/index'],
    //dropdawn
    "Lista de Alunos" => ['url' => '/apd/apd'],
    "Controle de Atendimentos" => ['url' => '/apd/atendFaltas'],
    "Remanejar Aluno" => ['url' => '/apd/contraturno'],
    'Protocolos' => ['url' => '/apd/protocoloList'],
    'Acompanhar Protocolos Deferidos' => ['url' => '/apd/deferidoList'],
];
//coordenador
$menu[48] = [
    'Início'=>['url' => '/apd/index'],
    "Lista de Alunos" => ['url' => '/apd/apd'],
    "Boletim" => ['url' => '/apd/boletim'],
    "Controle de Atendimentos" => ['url' => '/apd/atendFaltas'],
    'Acompanhar Protocolos Deferidos' => ['url' => '/apd/deferidoList'],
];

//professor
$menu[24] = [
    'Início'=>['url' => '/apd/index'],
    "Preencher Documentação" => ['url' => '/apd/doc'],
    "Documentações" => ['url' => '/apd/docRel'],
    "Controle de Atendimentos" => ['url' => '/apd/atendFaltas'],
    "Alunos Novos" => ['url' => '/apd/alunoNovoList'],
    'Termo de Recusa' => ['url' => '/apd/protocoloListProf'],
    1=>['url' => '/apd/termoAceite'],
    2=>['url' => '/apd/termoRecusa'],
    3=>['url' => '/protocolo/termoAceite'],
    4=>['url' => '/protocolo/termoRecusa'],
];

//Avaliaçao In Loco
$menu[18] = [
    'Início'=>['url' => '/apd/index'],
    "Documentações" => ['url' => '/apd/apd'],
];

//Escola
    $menu[8] = [
    'Início' => ['url' => '/apd/index'],
    'Protocolos' => ['url' => '/apd/protocoloList'],
    1=>['url' => '/protocolo/termoRecusa'],
];
