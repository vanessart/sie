<?php ?>

<?php
if (!defined('ABSPATH'))
    exit;
if (user::session('id_nivel') != 10) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$aDeferimento = filter_input(INPUT_POST, 'aDeferimento', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="fieldBody">
    <div class="fieldTop">
        Alunos Cadastrados/Consulta
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-10">
            <form method="POST">
                <?php
                if (user::session('id_nivel') == 10) {
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?php echo form::select('id_inst', escolas::idInst(), 'Escola', $id_inst) ?>
                        </div>
                    </div>
                    <br /><br />
                    <?php
                }
                ?>

                <div class="col-sm-6">
                    <?php echo form::input('nome', 'Nome ou RA', $nome) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form::checkbox('aDeferimento', 1, 'Aguardando Deferimento', $aDeferimento) ?>
                </div>
                <div class="col-sm-2">
                    <input type="hidden" name="pesq" value="1" />
                    <button type="submit" class="btn btn-info">
                        Pesquisar
                    </button>
                </div>
                <br /><br />
            </form>
        </div> 
        <div class="col-sm-2">
            <form method="POST">
                <div class="col-sm-2">
                    <input type="hidden" name="consulta" value="1" />
                    <button type="submit" class="btn btn-info">
                        Alunos Aguardando Def.
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (!empty($_POST['pesq'])) {
        $alu = $model->alunoPesq($id_inst, NULL, '0');

        $form['array'] = $alu;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Aluno' => 'n_pessoa',
            'Dt. Sol' => 'dt_solicita',
            'Turma' => 'n_turma',
            'Status' => 'n_sa'
        ];

        tool::relatSimples($form);
    }

    if (!empty($_POST['consulta'])) {

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

            tool::relatSimples($form);
        } else {
            tool::alert("Não exitem dados para relatório");
        }
    }
    ?>
</div>