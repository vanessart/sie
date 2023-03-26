<?php
if (!defined('ABSPATH'))
    exit;
if (user::session('id_nivel') != 10) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$fk_id_sa = filter_input(INPUT_POST, 'fk_id_sa', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="body">
    <div class="fieldTop">
        Alunos Cadastrados/Consulta
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-12">
            <form method="POST">
                <?php if (user::session('id_nivel') == 10) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo formErp::select('id_inst', escolas::idInst(), 'Escola', $id_inst) ?>
                    </div>
                </div>
                <br />
                <?php } ?>

                <div class="row">
                    <div class="col-sm-6">
                        <?php echo formErp::input('nome', 'Nome ou RA', $nome) ?>
                    </div>

                    <div class="col-sm-3">
                        <?php
                            $options = $model->getStatusAluno();
                            echo formErp::select('fk_id_sa', $options, 'Status', $fk_id_sa);
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <input type="hidden" name="pesq" value="1" />
                        <button type="submit" class="btn btn-info">
                            Pesquisar
                        </button>
                    </div>
                    <br />
                </div>
            </form>
        </div> 
        <!--div class="col-sm-2">
            <form method="POST">
                <div class="col-sm-2">
                    <input type="hidden" name="consulta" value="1" />
                    <button type="submit" class="btn btn-info">
                        Alunos Aguardando Def.
                    </button>
                </div>
            </form>
        </div-->
    </div>
    <br /><br />
    <?php
    if (!empty($_POST['pesq'])) {
        $alu = $model->alunoPesq($id_inst, $nome, $fk_id_sa);

        $form['array'] = $alu;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Aluno' => 'n_pessoa',
            'Linha' => 'n_li_v',
            'Dt. Sol' => 'dt_solicita',
            'Turma' => 'n_turma',
            'Status' => 'n_sa'
        ];

        toolErp::relatSimples($form);
    }

    /*if (!empty($_POST['consulta'])) {

        $alu = $model->consultaaluno();

        if (!empty($alu)) {
            foreach ($alu as $k => $v) {
                $alu[$k]['ra'] = $v['ra'] . '-' . $v['ra_dig'];
            }

            $form['array'] = $alu;
            $form['fields'] = [
                'RA' => 'ra',
                'Aluno' => 'n_pessoa',
                'Dt. Sol' => 'dt_solicita',
                'Turma' => 'n_turma',
                'Distância' => 'distancia_esc',
                'Status' => 'n_sa'
            ];

            toolErp::relatSimples($form);
        } else {
            toolErp::alert("Não exitem dados para relatório");
        }
    }*/
    ?>
</div>