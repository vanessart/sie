<?php
 @$id_curso = empty(@$_REQUEST['id_curso_'])?@$turma['id_curso']:@$_REQUEST['id_curso_'];
if (!empty($id_curso)) {
 //   formulario::selectDB('ge_ciclos', 'id_ciclo_', 'Ciclo: ', @$turma['id_ciclo']);
    formulario::selectDB('ge_ciclos', 'id_ciclo_', 'Ciclo: ', @$turma['id_ciclo'], 'disabled', NULL, $_REQUEST, NULL, ['fk_id_curso'=>@$id_curso]);
}
javaScript::divDinanmica('id_ciclo_', 'formu', HOME_URI . '/gt/form');


