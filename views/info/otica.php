<?php
$h['id_inst'] = $id_inst = @$_POST['id_inst'];
if (!empty($id_inst)) {
    $id_turma = @$_POST['id_turma'];
    if (!empty($id_turma)) {
        $turma = turmas::classe($id_turma);
    }

    $id_pl = gtMain::periodoSet(@$_POST['periodoLetivo']);

    $turmas = gtTurmas::idNome($id_inst, $id_pl, '4,5,6,7,8,9');

    $periodos = gtMain::periodosPorSituacao();
}
?>

<br /><br />
<div class="Body">
    <div style="text-align: center; font-size: 16px; font-weight: bold">
        <?php echo formulario::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1) ?>
    </div>
    <br />
    <div class="row">
        <?php
            if (!empty($id_inst)) {
                                    ?>
        <div class="col-sm-3">
            <?php
            echo formulario::select('periodoLetivo', $periodos, 'Período Letivo', @$id_pl, 1, $h);
            ?>
        </div>
        <div class="col-sm-3" style="text-align: center; font-size: 18px">
            <?php echo formulario::select('id_turma', $turmas, 'Classe', @$_POST['id_turma'], 1, ['periodoLetivo' => $id_pl, 'id_inst'=> $id_inst]) ?>
        </div>
    </div>
    <br /><br />
    <?php
            }
   if (!empty($id_turma)) {    
        foreach ($turma as $k => $v) {
            $hidden['inscricao'] = $v['id_pessoa'];
            $hidden['token'] = substr(($v['id_pessoa'] / 6 * 5), 0, 4);
            $turma[$k]['Acessar'] = formulario::submit('Acessar', NULL, $hidden, 'http://200.98.201.201', 1);
        }
        $form['array'] = $turma;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Num.' => 'chamada',
            'Nome' => 'n_pessoa',
            '||1' => 'Acessar'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>


