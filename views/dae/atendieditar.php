<?php
javaScript::cep();

$dae_departamento = sql::idNome('dae_departamento');
$dae_motivo = sql::idNome('dae_motivo');
$dae_tipo = sql::idNome('dae_tipo_contato');
$dae_ensino = sql::idNome('dae_tipo_ensino');
$dae_status = sql::idNome('dae_status');

$at = sql::get('dae_atendimento', '*', ['<' => 'id_atendimento']);
$sqlkey = DB::sqlKey('dae_atendimento', 'delete');
?>
<br />
<?php
foreach ($at as $key => $v) {

//    $v['id_aten'] = $v['id_atendimento'];
  //  $v['l'] = 1;
    
    $at[$key]['dep'] = @$dae_departamento[$v['fk_id_departamento']];
    $at[$key]['mot'] = @$dae_motivo[$v['fk_id_motivo']];
    $at[$key]['tipo'] = @$dae_tipo[$v['fk_id_contato']];
    $at[$key]['ensin'] = @$dae_ensino[$v['fk_id_tipo_ensino']];
    $at[$key]['sta'] = @$dae_status[$v['fk_id_status']];
    $at[$key]['esc'] = @$dae_escola[$v['fk_id_inst']];
    
    $at[$key]['excluir'] = formulario::submit('Excluir', $sqlkey, ['1[id_atendimento]' => $v['id_atendimento']]);
    $at[$key]['aten'] = formulario::submit('Editar', null, $v, HOME_URI . '/dae/atendimento');
    $at[$key]['imp'] = formulario::submit('Imprimir', null, $v, HOME_URI . '/dae/protocolo', 1);
}
$form['array'] = $at;
$form['fields'] = [
    'Protocolo' => 'id_atendimento',
    'Solicitante' => 'solicitante',
    'Data' => 'dt_inicio',
    'Departamento' => 'dep',
    'Contato' => 'tipo',
    'Motivo Contato' => 'mot',
    'Status' => 'sta',
    '||1' => 'excluir',
    '||2' => 'aten',
    '||3' => 'imp'
];
tool::relatSimples($form);
?>


