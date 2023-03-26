<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
$id_pl = $setup['id_pl'];
if ($id_inst) {
    $maker = $model->escolaPolo($id_inst);
    $alunos = $model->alunosEscola($id_inst, $id_pl);

    $preenchido = @$alunos['total'][1] + @$alunos['total'][2];
    @$cota = $maker['cota_m'] + $maker['cota_t'] + $maker['cota_n'];
    @$diponivel = $cota - $preenchido;

    foreach (['m' => 'Manhã', 't' => 'Tarde', 'n' => 'Noite'] as $kp => $p) {
        if (!empty($maker['cota_' . $kp])) {
            $dispoPer[$kp] = $maker['cota_' . $kp] - @$alunos['totalPeriodo'][1][strtoupper($kp)] - @$alunos['totalPeriodo'][2][strtoupper($kp)];
        }
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Alunos
    </div>
    <?php
    if (toolErp::id_nilvel() != 8) {
        ?>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
            </div>
        </div>
        <br />
        <?php
    }
    ?>
    <br /><br />
    <?php
    if (empty($maker)) {
        ?>
        <div class="alert alert-danger">
            Sua escola não está configurada para participar das Salas Maker
        </div>
        <?php
    } elseif ($id_inst) {
        ?>
        <div class="col" style="font-weight: bold; font-size: 1.4em; text-align: center">
            <?= $maker['sede'] ?>
            <br /><br />
            Período Letivo:  <?= $setup['n_pl'] ?>
        </div>
        <br />
        <form target="frame" action="<?= HOME_URI ?>/maker/def/formAluno" method="POST">
            <?=
            formErp::hidden([
                'id_inst' => $id_inst,
                'id_pl' => $id_pl,
                'id_polo' => $maker['id_polo'],
                'cota_m' => $maker['cota_m'],
                'cota_t' => $maker['cota_t'],
                'cota_n' => $maker['cota_n'],
            ])
            . formErp::hidden($dispoPer);
            if ($setup['fila_espera'] == 1) {
                ?>
                <button class="btn btn-info" onclick="$('#myModal').modal('show');$('.form-class').val('')">
                    Incluir Aluno
                </button>
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    Inclusão de Alunos não está disponível no momento
                </div>
                <?php
            }
            ?>
        </form>
        <form method="POST">
            <?php
            foreach (['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite'] as $kp => $p) {
                if (!empty($alunos[3][$kp])) {
                    ?>
                    <table class="table table-bordered table-hover table-striped border">
                        <tr>
                            <td colspan="5" style="text-align: center; font-weight: bold">
                                Inscrições para o período da <?= $p ?> (<?= count($alunos[3][$kp]) ?> aluno<?= count($alunos[3][$kp]) > 1 ? 's' : '' ?>)
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                RSE
                            </td>
                            <td>
                                Nome
                            </td>
                            <td>
                                Ciclo
                            </td>
                            <td>
                                Dt Matrícula
                            </td>
                        </tr>
                        <?php
                        foreach ($alunos[3][$kp] as $v) {
                            ?>
                            <tr>
                                <td style="width: 20px">
                                    <button type="button" class="btn btn-danger" onclick="exclui(<?= $v['id_ma'] ?>)">
                                        X
                                    </button>
                                </td>
                                <td>
                                    <?= $v['id_pessoa'] ?>
                                </td>
                                <td>
                                    <?= $v['n_pessoa'] ?>
                                </td>
                                <td>
                                    <?= $v['n_mc'] ?>
                                </td>
                                <td>
                                    <?= data::converteBr(substr($v['times_stamp'], 0, 10)) ?> às <?= substr($v['times_stamp'], 11, 5) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <?php
                }
            }
            if (!empty($alunos[3])) {
                ?>
                <div style="text-align: center; padding: 30px">
                    <?=
                    formErp::hidden([
                        'id_inst' => $id_inst,
                        'diponivel' => $diponivel,
                    ])
                    ?>
                </div>
                <?php
            }
            ?>
        </form>     
        <?php
    }
    ?>

</div>
<?php
toolErp::modalInicio(0, null, null, 'Cadastro de Aluno');
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>
<form id="excluir" method="POST">
    <input type="hidden" name="1[id_ma]" id="id_ma" />
    <?=
    formErp::hidden([
        'id_inst' => $id_inst
    ])
    . formErp::hiddenToken('maker_aluno', 'delete')
    ?>
</form>
<script>
    function exclui(id) {
        if (confirm("Excluir o Aluno da Lista de Espera?")) {
            id_ma.value = id;
            excluir.submit();
        }
    }
</script>
