<?php
ini_set('memory_limit', '500M');
$dia = professores::diaHac();
$n_disc = disciplina::discId();
$disc_ = $model->pegadisciplinasarea();
//$disc_ = disciplina::disc();

inst::autocomplete();

?>

<div class="fieldBody">
    <div class="fieldTop">
        Consulta de H.T.P.C.
    </div>
    <br /><br />
    <div class="fieldMain">
        <form method="POST">
            <div class="row">
                <div class="col-lg-3">
                    <?php echo formulario::select('hac_dia', $dia, 'Dia da Semana') ?>
                </div>
                <div class="col-lg-2">
                    <?php echo formulario::select('hac_periodo', ['Noite' => 'Noite', 'Tarde' => 'Tarde'], 'Periodo') ?>
                </div>
                <div class="col-lg-4">
                    <?php echo formulario::input('n_inst', 'Escola', NULL, NULL, 'id="n_inst" onkeypress="completeInst()"', @$_POST['n_inst']) ?>
                    <input id="id_inst" type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                </div>
                <div class="col-lg-3">
                    <?php echo formulario::selectDB('ge_ciclos', 'ciclo', 'Ciclo', @$_POST['ciclo']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 text-center">
                    <a href="" class="btn btn-danger">
                        Limpar
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    <input class="btn btn-success" type="submit" value="Pesquisar" name="pesq" />
                </div>
            </div>
            <fieldset>
                <legend>
                    Disciplinas
                </legend>
                <div class="row">
                    <?php
                    foreach ($disc_ as $v) {
                        if (!empty($_POST['disc'])) {
                            if (in_array($v['id_disc'], $_POST['disc'])) {
                                $checked = "checked";
                            } else {
                                $checked = NULL;
                            }
                        }
                        ?>
                        <div class="col-lg-4">
                            <label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <input <?php echo @$checked ?> type="checkbox" name="disc[]" value="<?php echo $v['id_disc'] ?>" />
                                    </span>
                                    <span class="input-group-addon">
                                        <?php echo ($v['n_disc'] == 'Ativ. Informática')?'Informática': $v['n_disc'] ?>
                                    </span>

                                </div>
                            </label>
                        </div>

                        <?php
                    }
                    if (!empty($_POST['disc'])) {
                        if (in_array('nc', $_POST['disc'])) {
                            $checkedNc = "checked";
                        } else {
                            $checkedNc = NULL;
                        }
                    }
                    ?>
                    <div class="col-lg-4">
                        <label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <input <?php echo @$checkedNc ?> type="checkbox" name="disc[]" value="nc" />
                                </span>
                                <span class="input-group-addon">
                                    Professor Polivalente
                                </span>

                            </div>
                        </label>
                    </div>
                </div>
            </fieldset>

        </form>
    </div>
    <br /><br />
    <?php
    if (!empty($_POST['pesq'])) {
        $prof = professores::profDiscClasse(@$_POST['disc'], @$_POST['hac_dia'], @$_POST['hac_periodo'], @$_POST['id_inst'], @$_POST['ciclo']);

        if (!empty($prof)) {
            ?>
            <div class="row">
                <div class="col-lg-6 text-center">
                    Total de professores:   
                    <?php echo intval(count($prof)) ?>
                </div>
                <div class="col-lg-6 text-center">
                    <form target="_blank" method="POST" action="<?php echo HOME_URI ?>/app/excel/doc/hac.php">
                        <?php
                        if (!empty($_POST['disc'])) {
                            foreach ($_POST['disc'] as $dc) {
                                ?>
                                <input type="hidden" name="$disc_i[]" value="<?php echo @$dc ?>" />
                                <?php
                            }
                        }
                        ?>
                        <input type="hidden" name="hac_dia" value="<?php echo @$_POST['hac_dia'] ?>" />
                        <input type="hidden" name="hac_periodo" value="<?php echo @$_POST['hac_periodo'] ?>" />
                        <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                        <input type="hidden" name="ciclo" value="<?php echo @$_POST['ciclo'] ?>" />
                        <input class="btn btn-info" type="submit" value="Exportar" />
                    </form>
                </div>


            </div>
            <br /><br />
            <div class="fieldMain">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <td>
                                Matricula
                            </td>
                            <td>
                                Nome
                            </td>
                            <td>
                                E-mail
                            </td>
                            <td>
                                Disciplina
                            </td>
                            <td>
                                Manhã
                            </td>
                            <td>
                                Tarde
                            </td>
                            <td>
                                Integral
                            </td>
                            <td>
                                Noite
                            </td>
                            <td>
                                Dia
                            </td>
                            <td>
                                Periodo
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($prof as $k => $v) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo @$k ?>
                                </td>
                                <td>
                                    <?php echo @$v['nome'] ?>
                                </td>
                                <td>
                                    <?php echo @$v['emailgoogle'] ?>
                                </td>
                                <td>
                                    <?php echo @$v['disciplinas'] ?>
                                </td>
                                <td>
                                    <?php echo @$v['classesPorPeriodo']['M'] ?>
                                </td>
                                <td>
                                    <?php echo @$v['classesPorPeriodo']['T'] ?>
                                </td>
                                <td>
                                    <?php echo @$v['classesPorPeriodo']['I'] ?>
                                </td>
                                <td>
                                    <?php echo @$v['classesPorPeriodo']['N'] ?>
                                </td>
                                <td style="width: 60px">
                                    <?php echo @$dia[$v['dia']] ?>
                                </td>
                                <td>
                                    <?php echo @$v['periodo'] ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            ?>

            <div class="alert alert-danger text-center">
                Não há resultado com este filtro
            </div>
            <?php
        }
    }
    ?>
</div>

