<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$aval = filter_input(INPUT_POST, 'aval', FILTER_SANITIZE_NUMBER_INT);
$infantil = [19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 34, 35];
?>
<br /><br /><br />
<div class="row">
    <div class="col-sm-8">
        <?php echo form::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1) ?>
    </div>
    <div class="col-sm-4 btn-primary ">
        <?php
        if (!empty($id_inst)) {
            echo form::submit('Exportar Funcionários', NULL, ['id_inst' => $id_inst], HOME_URI . '/prod/inlocopdf', 1);
        }
        ?>
    </div>
</div>
<?php
if (!empty($id_inst)) {
    @$avaliou = $model->avaliouInst($id_inst);
    $notaFinal = $model->profNotaFinalInLoco();
    $prof = $model->prof12($id_inst);
    if (!empty($prof)) {
        ?>
        <br /><br />
        <div class="fieldBorder2">
            Professores -AEE, EJA, Infantil, Polivalente, Inglês e E. Física
            <br /><br />
            <?php
            foreach ($prof as $k => $v) {

                if ($v['id_psc'] != 1) {
                    $prof[$k]['n_pessoa'] = '<div style="color: red">' . $v['n_pessoa'] . ' (' . $v['n_psc'] . ')</div>';
                }
                $v['id_inst'] = $id_inst;
                $v['aval'] = 1;
                $prof[$k]['a1'] = $model->buttonSet(1, $v, @$avaliou);
                if (!in_array($v['fk_id_ciclo'], $infantil)) {
                    $v['aval'] = 2;
                    $prof[$k]['a2'] = $model->buttonSet(2, $v, @$avaliou);
                    $v['aval'] = 3;
                    $prof[$k]['a3'] = $model->buttonSet(3, $v, @$avaliou);
                }
                $prof[$k]['tt'] = '<button class="btn btn-info" style="width: 80px; color: black" >' . (round(@$notaFinal[$v['rm']], 2)) . '</button>';
                $prof[$k]['n_disc'] = (!empty($v['n_disc']) ? $v['n_disc'] : ($v['iddisc'] == 'nc' ? 'Polivalente' : ''));
            }
            $form['array'] = $prof;
            $form['fields'] = [
                'Funcionário' => 'n_pessoa',
                'Matrícula' => 'rm',
                'Classe' => 'n_turma',
                'Período' => 'periodo',
                'Disciplina' => 'n_disc',
                '1° Avaliação' => 'a1',
                '2° Avaliação' => 'a2',
                '3° Avaliação' => 'a3',
                'Nota Final' => 'tt',
            ];

            tool::relatSimples($form);
        }
        ?>
    </div>
    <?php
    $prof = $model->informatica($id_inst);
    if (!empty($prof)) {
        ?>
        <br /><br />
        <div class="fieldBorder2">
            Professor de Informática
            <br /><br />
            <?php
            foreach ($prof as $k => $v) {
                if ($v['id_psc'] != 1) {
                    $prof[$k]['n_pessoa'] = '<div style="color: red">' . $v['n_pessoa'] . ' (' . $v['n_psc'] . ')</div>';
                }
                $v['id_inst'] = $id_inst;
                $v['fk_id_ciclo'] = 'info';
                $v['aval'] = 1;
                $v['iddisc'] = 20;
                $prof[$k]['a1'] = $model->buttonSet(1, $v, @$avaliou);
                $v['aval'] = 2;
                $prof[$k]['a2'] = $model->buttonSet(2, $v, @$avaliou);
                $v['aval'] = 3;
                $prof[$k]['a3'] = $model->buttonSet(3, $v, @$avaliou);
                $prof[$k]['tt'] = '<button class="btn btn-info" style="width: 80px; color: black" >' . (round(@$notaFinal[$v['rm']], 2)) . '</button>';
            }
            $form['array'] = $prof;
            $form['fields'] = [
                'Funcionário' => 'n_pessoa',
                'Matrícula' => 'rm',
                'Disciplina' => 'n_disc',
   '1° Avaliação' => 'a1',
                '2° Avaliação' => 'a2',
                '3° Avaliação<br />(!º do Infantil)' => 'a3',
                'Nota Final' => 'tt',
            ];

            tool::relatSimples($form);
            ?>

        </div>
        <?php
    }
    $prof = $model->adi($id_inst);
    if (!empty($prof)) {
        ?>
        <br /><br /><br />
        <div class="fieldBorder2">
            ADI
            <br /><br />
            <?php
            foreach ($prof as $k => $v) {
                $v['fk_id_ciclo'] = 'adi';
                $v['id_inst'] = $id_inst;
                $v['aval'] = 1;
                $prof[$k]['a1'] = $model->buttonSet(1, $v, @$avaliou);
                $v['aval'] = 2;
                $prof[$k]['a2'] = $model->buttonSet(2, $v, @$avaliou);
                /**
                  $v['aval'] = 3;
                  $prof[$k]['a3'] = $model->buttonSet(3, $v, @$avaliou);
                 * 
                 */
                $prof[$k]['tt'] = '<button class="btn btn-info" style="width: 80px; color: black" >' . (round(@$notaFinal[$v['rm']], 2)) . '</button>';
            }
            $form['array'] = $prof;
            $form['fields'] = [
                'Funcionário' => 'n_pessoa',
                'Matrícula' => 'rm',
                'Avaliação In Loco' => 'a1',
                'Avaliação do Diretor' => 'a2',
                //'||3' => 'a3',
                'Nota Final' => 'tt',
            ];

            tool::relatSimples($form);
            ?>

        </div>
        <?php
    }
}
if (!empty($aval)) {
    tool::modalInicio();
    include ABSPATH . '/views/prod/_inloco/lancar.php';
    tool::modalFim();
}