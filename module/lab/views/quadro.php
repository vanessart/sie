
<style>
    button{
        width: 100%;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;

$atualizar = filter_input(INPUT_POST, 'atualizar', FILTER_SANITIZE_NUMBER_INT);
$inst = sql::idNome('instancia');

if ($atualizar) {

    $sql = "SELECT ap.iddisc, f.fk_id_pessoa, ap.rm FROM `ge_aloca_prof` ap "
            . " LEFT JOIN ge_funcionario f on f.rm = ap.rm ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    $profSeg = [];
    foreach ($array as $v) {

        if (!empty($v['fk_id_pessoa'])) {
            if ($v['iddisc'] == 27) {
                $profSeg['infantil'][$v['fk_id_pessoa']] = 'Infantil';
            }
            if ($v['iddisc'] == 28) {
                $profSeg['aee'][$v['fk_id_pessoa']] = 'AEE';
            }
            if ($v['iddisc'] == 26) {
                $profSeg['libras'][$v['fk_id_pessoa']] = 'Libras';
            }
            if ($v['iddisc'] == 'nc') {
                $profSeg['peb1'][$v['fk_id_pessoa']] = 'PEBI';
            }
            if (!in_array($v['iddisc'], ['nc', 27, 28, 26])) {
                $profSeg['peb2'][$v['fk_id_pessoa']] = 'PEBII';
            }
        }
    }

    $sql = " SELECT "
            . " DISTINCT p.id_pessoa, p.n_pessoa, p.emailgoogle, e.fk_id_ch id_ch, f.funcao, f.situacao, f.rm, i.id_inst "
            . " FROM ge_funcionario f "
            . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
            . " JOIN instancia i on i.id_inst = f.fk_id_inst "
            . " left join lab_chrome_emprestimo e on e.fk_id_pessoa = f.fk_id_pessoa "
            . " WHERE f.funcao like 'profes%' "
            . " and (f.situacao like '%Ativo' or situacao is null) ";

    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        if (is_numeric($v['rm'])) {

            @$colunas
                    [trim(preg_replace('/( )+/', ' ',
                                    @$profSeg['infantil'][$v['id_pessoa']] . ' '
                                    . @$profSeg['peb1'][$v['id_pessoa']] . ' '
                                    . @$profSeg['peb2'][$v['id_pessoa']] . ' '
                                    . @$profSeg['aee'][$v['id_pessoa']] . ' '
                                    . @$profSeg['libras'][$v['id_pessoa']] . ' '
                    ))] = 1;
            @$profS[$v['id_inst']]['profs']
                    [trim(preg_replace('/( )+/', ' ',
                                    @$profSeg['infantil'][$v['id_pessoa']] . ' '
                                    . @$profSeg['peb1'][$v['id_pessoa']] . ' '
                                    . @$profSeg['peb2'][$v['id_pessoa']] . ' '
                                    . @$profSeg['aee'][$v['id_pessoa']] . ' '
                                    . @$profSeg['libras'][$v['id_pessoa']] . ' '
                    ))][] = $v['id_pessoa'];
            @$total['profs']
                    [trim(preg_replace('/( )+/', ' ',
                                    @$profSeg['infantil'][$v['id_pessoa']] . ' '
                                    . @$profSeg['peb1'][$v['id_pessoa']] . ' '
                                    . @$profSeg['peb2'][$v['id_pessoa']] . ' '
                                    . @$profSeg['aee'][$v['id_pessoa']] . ' '
                                    . @$profSeg['libras'][$v['id_pessoa']] . ' '
                    ))]++;

            if (empty($v['id_ch'])) {
                @$profS[$v['id_inst']]['profSemCh']
                        [trim(preg_replace('/( )+/', ' ',
                                        @$profSeg['infantil'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb1'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb2'][$v['id_pessoa']] . ' '
                                        . @$profSeg['aee'][$v['id_pessoa']] . ' '
                                        . @$profSeg['libras'][$v['id_pessoa']] . ' '
                        ))][] = $v['id_pessoa'];

                @$total['profSemCh']
                        [trim(preg_replace('/( )+/', ' ',
                                        @$profSeg['infantil'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb1'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb2'][$v['id_pessoa']] . ' '
                                        . @$profSeg['aee'][$v['id_pessoa']] . ' '
                                        . @$profSeg['libras'][$v['id_pessoa']] . ' '
                        ))]++;
            } else {
                @$profS[$v['id_inst']]['profComCh']
                        [trim(preg_replace('/( )+/', ' ',
                                        @$profSeg['infantil'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb1'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb2'][$v['id_pessoa']] . ' '
                                        . @$profSeg['aee'][$v['id_pessoa']] . ' '
                                        . @$profSeg['libras'][$v['id_pessoa']] . ' '
                        ))][] = $v['id_pessoa'];

                @$total['profComCh']
                        [trim(preg_replace('/( )+/', ' ',
                                        @$profSeg['infantil'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb1'][$v['id_pessoa']] . ' '
                                        . @$profSeg['peb2'][$v['id_pessoa']] . ' '
                                        . @$profSeg['aee'][$v['id_pessoa']] . ' '
                                        . @$profSeg['libras'][$v['id_pessoa']] . ' '
                        ))]++;
            }
        }
    }
    $totalJs = json_encode($total);
    $profSJs = json_encode($profS);
    $colunasJs = json_encode($colunas);
    $sql = "REPLACE INTO `lab_quadro` (`id_q`, `sql_q`, `dat_hora`, `colunas`, total) VALUES (1, '$profSJs', CURRENT_TIMESTAMP, '$colunasJs', '$totalJs');";
    $query = pdoSis::getInstance()->query($sql);
}
################################################################################
$sql = "SELECT * FROM `lab_quadro` WHERE `id_q` = 1 ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetch(PDO::FETCH_ASSOC);
$profS = json_decode($array['sql_q'], true);
$colunas = json_decode($array['colunas'], true);
$total = json_decode($array['total'], true);
?>
<div class="body">
    <div class="row">
        <div class="col-8 fieldTop">
            Quadro de Chromebook/Professores
            <br /><br />
            Última Atualização: <?= data::porExtenso($array['dat_hora']) ?> as <?= substr($array['dat_hora'], -8, -3) ?> horas
        </div>
        <div class="col-4">
            <br /><br />
            <form method="POST">
                <input type="hidden" name="atualizar" value="1" />
                <button class=" btn btn-warning">
                    Atualizar (esta ação pode demorar até um minuto)
                </button>
            </form>
        </div>
    </div>
    <br />
    <table class="table table-bordered table-hover table-striped">
        <tr style="font-weight: bold">
            <td colspan="2">
                Escola
            </td>
            <?php
            foreach ($colunas as $k => $v) {
                ?>
                <td>
                    <?php
                    if (!empty($k)) {
                        echo str_replace(' ', '<br>', $k);
                    } else {
                        echo 'Sem classe atribuída';
                    }
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr style="font-weight: bold">
            <td rowspan="3">
                Totalizador
            </td>
            <td style="background: #6495ED">
                Total
            </td>
            <?php
            foreach ($colunas as $ky => $y) {
                ?>
                <td style="background: #6495ED; width: 5%">
                    <?php
                    if (!empty($total['profs'][$ky])) {
                        echo $total['profs'][$ky];
                    } else {
                        echo '---';
                    }
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr style="font-weight: bold">
            <td style="background: #00BFFF; width: 5%">
                Com Chromebook
            </td>
            <?php
            foreach ($colunas as $ky => $y) {
                ?>
                <td style="background: #00BFFF">
                    <?php
                    if (!empty($total['profComCh'][$ky])) {
                        echo $total['profComCh'][$ky];
                    } else if (!empty($total['profs'][$ky])) {
                        echo '0';
                    } else {
                        echo '---';
                    }
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr style="font-weight: bold">
            <td style="background: #ADD8E6; width: 5%">
                Sem Chromebook
            </td>
            <?php
            foreach ($colunas as $ky => $y) {
                ?>
                <td style="background: #ADD8E6">
                    <?php
                    if (!empty($total['profSemCh'][$ky])) {
                        echo $total['profSemCh'][$ky];
                    } else if (!empty($total['profs'][$ky])) {
                        echo 0;
                    } else {
                        echo '---';
                    }
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
        ksort($profS);
        foreach ($profS as $k => $v) {
            ?>
            <tr>
                <td rowspan="3">
                    <?= $inst[$k] ?>
                </td>
                <td style="background: #6495ED">
                    Total
                </td>
                <?php
                foreach ($colunas as $ky => $y) {
                    ?>
                    <td style="background: #6495ED; width: 5%">
                        <?php
                        if (!empty($v['profs'][$ky])) {
                            ?>
                            <button onclick="list('<?= implode(',', $v['profs'][$ky]) ?>')" type="button" class="btn btn-light">
                                <?= count($v['profs'][$ky]) ?>
                            </button>
                            <?php
                        } else {
                            echo '---';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td style="background: #00BFFF; width: 5%">
                    Com Chromebook
                </td>
                <?php
                foreach ($colunas as $ky => $y) {
                    ?>
                    <td style="background: #00BFFF">
                        <?php
                        if (!empty($v['profComCh'][$ky])) {
                            ?>
                            <button onclick="list('<?= implode(',', $v['profComCh'][$ky]) ?>')" type="button" class="btn btn-light">
                                <?= count($v['profComCh'][$ky]) ?>
                            </button>
                            <?php
                        } else if (!empty($v['profs'][$ky])) {
                            ?>
                            <button type="button" class="btn btn-light">
                                0
                            </button>
                            <?php
                        } else {
                            echo '---';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td style="background: #ADD8E6; width: 5%">
                    Sem Chromebook
                </td>
                <?php
                foreach ($colunas as $ky => $y) {
                    ?>
                    <td style="background: #ADD8E6">
                        <?php
                        if (!empty($v['profSemCh'][$ky])) {
                            ?>
                            <button onclick="list('<?= implode(',', $v['profSemCh'][$ky]) ?>')" type="button" class="btn btn-light">
                                <?= count($v['profSemCh'][$ky]) ?>
                            </button>
                            <?php
                        } else if (!empty($v['profs'][$ky])) {
                            ?>
                            <button type="button" class="btn btn-light">
                                0
                            </button>
                            <?php
                        } else {
                            echo '---';
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<form id="formIds" target="frameIds" action="<?= HOME_URI ?>/lab/def/listUsers.php" method="POST">
    <input type="text" name="ids" id="ids" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 85vh; border: none" name="frameIds"></iframe>
    <?php
    toolErp::modalFim();
    ?>

<script>
    function list(ids) {
        document.getElementById('ids').value = ids;
        document.getElementById('formIds').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');

    }
</script>