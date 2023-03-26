<?php

$prof = funcionarios::rh(NULL, 'PROFESSOR DE EDUCACAO BASICA II(');

$form['array']=$prof;
$form['fields']=[
    'Nome'=>'n_pessoa',
    'Matrícula'=>'rm',
    'Escola'=>'n_inst',
    'Disciplina'=>'funcao',
    'Situação'=>'situacao'
];
tool::relatSimples($form);
?>

