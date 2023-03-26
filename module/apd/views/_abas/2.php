<?php
if (!defined('ABSPATH'))
    exit;

if ($id_pessoa_apd) {
    $aluno = $model->alunoGet($id_pessoa_apd);
    $up = sql::get('apd_up', '*', 'WHERE apd_up.fk_id_pessoa =' . $id_pessoa_apd);
    if ($up) {
        $sqlkey = formErp::token('apd_up', 'delete');
        foreach ($up as $k => $v) {

            $up[$k]['docx'] = formErp::submit('Visualizar', null, null, HOME_URI . '/pub/apd/' . $v['link'], 1);
            if (!empty($v['fk_id_pessoa_anexa'])) {
               $up[$k]['pessoa_anexa'] = toolErp::n_pessoa($v['fk_id_pessoa_anexa']); 
            }
            //$up[$k]['del'] = formErp::submit('Apagar', $sqlkey, ['1[id_up]' => $v['id_up'], 'activeNav' => 2, 'id_pessoa_apd' => $id_pessoa_apd,'id_pessoa' => $id_pessoa,'n_turma' => $n_turma,'id_turma' => $id_turma]);
        }
        $form['array'] = $up;
        $form['fields'] = [
            'Tipo de Documento' => 'tipo',
            'Nome do Arquivo' => 'n_up',
            'Responsável pelo Anexo' => 'pessoa_anexa',
            '||1' => 'docx',
            //'||2' => 'del'
        ];
    }
    if (!empty($aluno)) {?>
        <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
            <div class="row" style="padding-bottom: 15px;">
                <div class="col">
                   <b>Aluno:</b> <?= $aluno['n_pessoa'] ?>
                </div>
                <div class="col">
                  <b>RSE:</b> <?= $aluno['id_pessoa'] ?>
                </div>
            </div>
            <div class="row" style="padding-bottom: 15px;">
                <div class="col">
                   <b>Deficiência:</b> <?= $aluno['def'] ?>
                </div>
                <div class="col">
                   <b>Turma:</b> <?= $aluno['n_turma'] ?> - <?= $aluno['n_inst'] ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    
    <div class="row">
        <div class="col">
            <form action="<?= HOME_URI ?>/apd/def/formAlunoUp.php" target="frame" method="POST">
                <?=
                formErp::hidden([
                    'id_pessoa' => $id_pessoa,
                    'id_pessoa_apd' => $id_pessoa_apd,
                    'id_inst' => $id_inst,
                    'n_turma' => $n_turma,
                    'id_turma' => $id_turma,
                ])
                ?>
                <button class="btn btn-info" onclick="$('#myModal').modal('show');$('.form-class').val('');">
                    Novo Upload
                </button>
            </form>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    toolErp::modalInicio();
    ?>
    <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
        <?php
        toolErp::modalFim();
        ?>

    <?php
}
