<?php
if (!defined('ABSPATH'))
    exit();
if (toolErp::id_nilvel() == 48) {
    $id_inst = toolErp::id_inst();
} elseif (toolErp::id_nilvel() == 10) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}

if ($id_inst) {
    $ap_aluno = $model->alunosEscBoletim($id_inst, null);
    if ($ap_aluno) {
        foreach ($ap_aluno as $k => $v) {
            $adaptacao = sql::get('apd_aluno_adaptacao', 'id_aluno_adaptacao, qt_letiva', ['fk_id_pessoa' => $v["id_pessoa"]]);
            $bimestres = "";
            if (!empty($adaptacao)) {
                foreach ($adaptacao as $key => $value) {
                    $bim = $value['qt_letiva'];
                    //$bimestres = $bimestres.'<button class="btn btn-outline-info" onclick="edit(' . $value['id_aluno_adaptacao'] . ',' . $value['qt_letiva'] . ',' . $v['id_pessoa'] . ', \'' . $v['n_turma'] . '\' ,\'' . $v['n_pessoa'] . '\' )">' . $value['qt_letiva'] . ' Bimestre</button>';
                $ap_aluno[$k][$bim] = '<button class="btn btn-outline-info" onclick="edit(' . $value['id_aluno_adaptacao'] . ',' . $value['qt_letiva'] . ',' . $v['id_pessoa'] . ', \'' . $v['n_turma'] . '\' ,\'' . $v['n_pessoa'] . '\' )">' . $value['qt_letiva'] . ' º Bimestre</button>';
                }
            }
        }
        if (toolErp::id_nilvel() == 48) {
            $form['array'] = $ap_aluno;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                'Turma' => 'n_turma',
                'Deficiência' => 'n_ne',
                '||1' => '1',
                '||2' => '2',
                '||3' => '3',
                '||4' => '4'
            ];
        }

        if (toolErp::id_nilvel() == 10) {
            $form['array'] = $ap_aluno;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                'Adaptação Currícular' => 'n_porte',
                '||1' => '1',
                '||2' => '2',
                '||3' => '3',
                '||4' => '4',
                '||5' => 'pdf',
            ];
        }
    }
}

if (toolErp::id_nilvel() == 10) {
    ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_inst', escolas::idEscolas(), 'Escolas', $id_inst, 1) ?>
        </div>
    </div>
    <br />

    <?php
}
if ($id_inst) {
    
}?>
<style type="text/css"> 
    .modal_blue{
        color: blue;
        font-weight: bold;
        text-align: center;
    }
</style>

<div class="body">
    <div class="fieldTop">
        Imprimir Boletim - Anexo III
    </div>
</div>

<?php

if (!empty($form)) {
    report::simple($form);
    ?>
    <form id="form" method="POST" target="_blank" action="">
        <input type="hidden" name="id_pessoa" id="id_pessoa"  />
        <input type="hidden" name="id_adapt" id="id_adapt"  />
        <input type="hidden" name="n_pessoa" id="n_pessoa"  />
        <input type="hidden" name="n_turma" id="n_turma"  />
        <input type="hidden" name="bimestre" id="bimestre"  />
        <?=
        formErp::hidden(['id_inst' => $id_inst]);
        ?>
    </form>
    <?php
}?>

<script>
    function edit(id_adapt,bimestre,id,n_turma,n_pessoa){
        if (id){
            document.getElementById("id_adapt").value = id_adapt;
            document.getElementById("id_pessoa").value = id;
            document.getElementById("bimestre").value = bimestre;
            document.getElementById("n_turma").value = n_turma;
            document.getElementById("n_pessoa").value = n_pessoa;
            document.getElementById("form").action = '<?= HOME_URI ?>/apd/boletimPDF';
            document.getElementById("form").submit();
        }
    }
</script>