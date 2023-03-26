<?php
$n_pessoa = trim(@$_POST['n_pessoa']);
$cargo = filter_input(INPUT_POST, 'cargo', FILTER_SANITIZE_NUMBER_INT);
$aval = filter_input(INPUT_POST, 'aval', FILTER_SANITIZE_NUMBER_INT);
$infantil = [19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 34, 35];
?>
<br /><br /><br />
<form method="POST">
    <div class="row">
        <div class="col-sm-6">
            <?php echo form::input('n_pessoa', 'Nome', $n_pessoa) ?>
        </div>
        <div class="col-sm-3">
            <?php echo form::select('cargo', [1 => 'Professores - Infantil, Polivalente, Inglês e E. Física', 2 => 'Professor de Informática', 3 => 'ADI'], 'Cargo', $cargo) ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            echo form::button('Enviar');
            echo form::hidden(['activeNav' => 2])
            ?>
        </div>
    </div>
</form>
<br /><br />
<?php
if (!empty($n_pessoa) && !empty($cargo)) {
    if ($cargo == 1) {
        $prof = $model->prof12(NULL, $n_pessoa);
    } elseif ($cargo == 2) {
        $fk_id_ciclo = 'info';
        $prof = $model->informatica(NULL, $n_pessoa);
    } elseif ($cargo == 3) {
        $fk_id_ciclo = 'adi';
        $prof = $model->adi(NULL, $n_pessoa);
    }
    ?>
    <div class="fieldBorder2">
        <?php
        if (!empty($prof)) {
            foreach ($prof as $k => $v) {
                $rm[$k] = $v['rm'];
            }
            @$avaliou = $model->avaliouRm($rm);

            foreach ($prof as $k => $v) {
                if (!empty($v['id_psc']) && (@$v['id_psc'] != 1)) {
                    $prof[$k]['n_pessoa'] = '<div style="color: red">' . $v['n_pessoa'] . ' (' . $v['n_psc'] . ')</div>';
                }
                if (!empty($fk_id_ciclo)) {
                    $v['fk_id_ciclo'] = $fk_id_ciclo;
                }
                $v['n_pessoa'] = $n_pessoa;
                $v['cargo'] = $cargo;
                $v['activeNav'] = 2;
                $v['aval'] = 1;
                $prof[$k]['a1'] = $model->buttonSet(1, $v, @$avaliou);
                if ($cargo != 3 && !in_array($v['fk_id_ciclo'], $infantil)) {
                    $v['aval'] = 2;
                    $prof[$k]['a2'] = $model->buttonSet(2, $v, @$avaliou);
                    $v['aval'] = 3;
                    $prof[$k]['a3'] = $model->buttonSet(3, $v, @$avaliou);
                }
            }
            $form['array'] = $prof;
            if ($cargo == 3) {
                $form['fields'] = [
                    'Funcionário' => 'n_pessoa',
                    'Matrícula' => 'rm',
                    'Escola' => 'n_inst',
                    '||1' => 'a1',
                    '||2' => 'a2',
                    '||3' => 'a3',
                ];
            } else {
                $form['fields'] = [
                    'Funcionário' => 'n_pessoa',
                    'Matrícula' => 'rm',
                    'Disciplina' => 'n_disc',
                    'Escola' => 'n_inst',
                    '1° Avaliação' => 'a1',
                    '2° Avaliação' => 'a2',
                    '3° Avaliação' => 'a3',
                ];
            }


            tool::relatSimples($form);
        } else {
            echo 'Funcionário não encontrado';
        }
        ?>
    </div>
    <?php
}
if (!empty($aval)) {
    tool::modalInicio();
    include ABSPATH . '/views/prod/_inloco/lancar.php';
    tool::modalFim();
}
?>