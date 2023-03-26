<?php
$class = $model->sitCor();
$filtro = filter_input(INPUT_POST, 'filtro', FILTER_SANITIZE_NUMBER_INT);
if (in_array(user::session('id_nivel'), [10])) {
    $escola = $model->escolasOpt();
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = tool::id_inst();
}
if (!empty($id_inst)) {
    $chromes = $model->chromesEscola($id_inst, $filtro);
    if (!empty($chromes)) {
        foreach ($chromes as $num => $carrinho) {
            foreach ($carrinho as $serial => $v) {
                $filtroList[$v['id_ch']] = $v['serial'] . (!empty($v['n_pessoa']) ? ' - ' . $v['n_pessoa'] . ' (' . $v['fk_id_pessoa'] . ')' : '');
                if (!empty($v['fk_id_ms'])) {
                    if ($v['fk_id_ms'] == 3) {
                        $chromes[$num][$serial]['btn'] = 'btn btn-outline-danger';
                        $reparado[] = $v;
                        @$sitTotal[8]++;
                    } else {
                        $chromes[$num][$serial]['btn'] = 'btn btn-secondary';
                        @$sitTotal[4]++;
                    }
                } elseif (!empty($v['fk_id_pessoa'])) {
                    $chromes[$num][$serial]['btn'] = 'btn btn-info';
                    @$sitTotal[3]++;
                } elseif ($v['fk_id_cs'] == 2) {
                    $chromes[$num][$serial]['btn'] = 'btn btn-warning';
                    @$sitTotal[2]++;
                } elseif ($v['fk_id_cs'] == 1) {
                    $chromes[$num][$serial]['btn'] = 'btn btn-success';
                    @$sitTotal[1]++;
                } else {
                    $chromes[$num][$serial]['btn'] = 'btn btn-outline-danger';
                    @$sitTotal[0]++;
                }
            }
        }
    }
}
$sitTotal = !empty($sitTotal) ? $sitTotal : [];
?>
<div class="fieldTop">
    <div class="fieldTop">
        Controle de ChromeBooks (<?= array_sum(@$sitTotal) ?>)
    </div>
    <div class="row">
        <div class="col text-center">
            <?php
            if (!empty($escola)) {
                echo formErp::select('id_inst', $escola, 'Escola', $id_inst, 1);
            }
            ?>
        </div>
        <div class="col text-center">
            <?php
            if (!empty($id_inst)) {
                ?>
                <form target="_blank" action="<?= HOME_URI ?>/lab/relatEscola" method="POST">
                    <?=
                    formErp::hidden([
                        'id_inst' => $id_inst
                    ])
                    ?>
                    <button type="submit" class="btn btn-info">
                        Inventário
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
        <div class="col text-center">
            <?php
            if (!empty($id_inst)) {
                ?>
                <form target="_blank" action="<?= HOME_URI ?>/lab/pdf/plan.php" method="POST">
                    <?=
                    formErp::hidden([
                        'id_inst' => $id_inst
                    ])
                    ?>
                    <button type="submit" class="btn btn-info">
                        Exportar
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
        <div class="col text-center">
            <?php
            if (!empty($id_inst)) {
                ?>
                <form action="<?= HOME_URI ?>/lab/emprestimo">
                    <button type="submit" class="btn btn-primary">
                        Empréstimo de Chromebook
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<div class="body">
    <div class="border">
        <div class="row">
            <div class="col">
                <div  class="btn btn-success" style="width: 100%">
                    Regular (<?= intval(@$sitTotal[1]) ?>)
                </div>
            </div>
            <div class="col">
                <div  class="btn btn-secondary" style="width: 100%">
                    Em Manutenção (<?= intval(@$sitTotal[4]) ?>)
                </div>
            </div>
            <div class="col">
                <div  class="btn btn-warning" style="width: 100%">
                    Quebrado (<?= intval(@$sitTotal[2]) ?>)
                </div>
            </div>
            <div class="col">
                <div  class="btn btn-outline-danger" style="width: 100%">
                    Reparado S.E. (<?= intval(@$sitTotal[8]) ?>)
                </div>
            </div>
            <div class="col">
                <div  class="btn btn-info" style="width: 100%">
                    Emprestado (<?= intval(@$sitTotal[3]) ?>)
                </div>
            </div>
        </div>
    </div>
</div>
<br />

<?php
if (!empty($reparado)) {
    ?>
    <div class="alert alert-danger" style="font-weight: bold; font-size: 20px">
        Voce tem equipamento<?= count($reparado) > 1 ? 's' : '' ?> a retirar na Secretaria de Educação:
        <br />
        <?php
        $modelo = sql::idNome('lab_modem_modelo');
        foreach ($reparado as $k => $v) {
            ?>
            Nº de série: <?= $v['serial'] ?> Modelo: <?= $modelo[$v['fk_id_cm']] ?>
            <br />
            <?php
        }
        ?>
    </div>
    <?php
}
?>
<br />
<?php
if (!empty($chromes)) {
    ?>
    <div class="border">
        <div class="row">
            <div class="col">
                <?= formErp::select('filtro', $filtroList, 'Filtro', $filtro, 1, ['id_inst' => $id_inst]) ?>
            </div>
            <div class="col">
                <?= formErp::submit('Limpar Filtro', null, ['id_inst' => $id_inst]) ?>
            </div>
            <div class="col" style="font-weight: bold; font-size: 1.4em">
                <?php
                if (!empty($chromes)) {
                    $contaCh = 0;
                    foreach ($chromes as $num => $carrinho) {
                        $contaCh += count($carrinho);
                    }
                }
                echo $contaCh . ' Chromebooks'
                ?>
            </div>
        </div>
    </div>
    <?php
}
?>
<br />
<?php
if (!empty($id_inst)) {

    if (!empty($chromes)) {
        foreach ($chromes as $num => $carrinho) {
            ?>
            <div class="border" style="padding: 20px">
                <div>
                    <?php
                    if (empty($num)) {
                        ?>
                        Fora do Carrinho (<?= count($carrinho) ?> Chromebooks)
                        <?php
                    } else {
                        ?>
                        Carrinho <?php echo $num ?>  (<?= count($carrinho) ?> Chromebooks)
                        <?php
                    }
                    ?>

                    <br /><br />
                    <div class="row">  
                        <?php
                        foreach ($carrinho as $v) {
                            ?>
                            <div class="col" style="padding: 3px">
                                <?php
                                if ($v['fk_id_cs'] != 3) {
                                    ?>
                                    <button onclick="chromeEdit(<?= $v['id_ch'] ?>)" class="<?= $v['btn'] ?>" style="text-align: center; width: 100%">
                                        N/S: <?php echo $v['serial'] ?>
                                    </button>
                                    <?php
                                } else {
                                    ?>
                                    <div style="width: 100%" class="<?= $class[$v['fk_id_cs']] ?>">
                                        <table style="width: 100%" > 

                                            <tr>
                                                <td>
                                                    <div onclick="chromeEdit(<?= $v['id_ch'] ?>)" style="text-align: center; width: 100%">
                                                        N/S: <?php echo $v['serial'] ?>
                                                        <?= '<br />' . $v['n_pessoa'] . ' (' . $v['fk_id_pessoa'] . ')' ?>
                                                    </div>
                                                </td>
                                                <td class="btn btn-dark" onclick="document.getElementById('f<?= $v['id_ch'] ?>').submit()" >
                                                    P
                                                    <form id="f<?= $v['id_ch'] ?>" target="_blank" action="<?= HOME_URI ?>/lab/protAluno" method="POST">
                                                        <?=
                                                        formErp::hidden([
                                                            'id_inst' => $id_inst,
                                                            'id_pessoa' => $v['fk_id_pessoa'],
                                                            'serial' => $v['serial']
                                                        ])
                                                        ?>
                                                    </form>       
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <br /><br />
            <?php
        }
    } else {
        ?>
        <div class="alert alert-warning">
            Não há Chromebook relacionado a esta instância
        </div>
        <?php
    }
    ?>
    <?php
}
?>
<br /><br />
<form action="<?= HOME_URI ?>/lab/def/formChrome.php" target="Frame" id="formFrame" method="POST">
    <input id="id_ch" type="hidden" name="id_ch" value="" />
    <?= formErp::hidden(['id_inst' => $id_inst]) ?>
</form>
<form action="<?= HOME_URI ?>/lab/def/formChromeNovo.php" target="Frame" id="formFrameNovo" method="POST">
    <?= formErp::hidden(['id_inst' => $id_inst]) ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="Frame" style="border: none; width: 100%; height: 80vh"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function chromeEdit(id) {
        document.getElementById('id_ch').value = id;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('')
    }
    function novo() {
        document.getElementById('formFrameNovo').submit();
        $('#myModal').modal('show');
        $('.form-class').val('')
    }
</script>