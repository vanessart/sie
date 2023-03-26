<?php
if (empty($editar)) {
    @$cadam = current(cadamp::cadampesPorEscola($id_inst, $id_cargo, $diaSemana . $periodo, NULL, NULL, implode(',', $notin)));
} else {
    @$cadam = cadamp::get(@$id_cad);
    $convocacao = sql::get('cadam_convoca', '*', ['id_con' => @$_POST['id_con']], 'fetch');
}
?>
                                <pre>
                                    <?php
                                    print_r($_POST)
                                    ?>
                                </pre>
                                <?php
if (empty($_POST['aulas'])) {
    if (!empty($idturmas)) {
        if (!is_array($_POST['idturmas'])) {
            $idturmas = unserialize(str_replace('|', '"', @$_POST['idturmas']));
        } else {
            $idturmas = @$_POST['idturmas'];
        }
        foreach ($idturmas as $i) {
            foreach ($i as $ii) {
                $aulas[] = $ii;
            }
        }
    }
    $aulas = implode(',', $aulas);
} else {
    $aulas = $_POST['aulas'];
}

$confereLanc = cadamp::confereaLocado($id_cargo, $dia, $mesSet, $periodo, $id_inst, NULL, $cadam['id_cad'], $aulas);

if (!empty($cadam)) {
    $id_cad = @$cadam['id_cad'];
    $reservado = cadamp::reservado($mesSet, NULL, $dia, $periodo, $id_cad);
    ?>
    <br /><br />
    <div class="fieldBorder2">
        <form style="text-align: right" method="POST">
            <?php echo formulario::hidden($hidden) ?>
            <input class="btn btn-warning" type="submit" value="Fechar" />
        </form>
        <form method="POST">
            <?php echo formulario::hidden($hidden) ?>
            <div style="text-align: center; font-size: 18px">
                Próximo Cadampe  disponível para <?php echo $nomeDiaSemana[$diaSemana] ?>, dia <?php echo $dia ?> de <?php echo data::mes($mesSet) ?> de <?php echo date("Y") ?>
            </div>
            <br /><br />
            <table style="font-size: 18px; width: 100%">
                <tr>
                    <th>
                        Cad. Municipal
                    </th>
                    <th>
                        Nome
                    </th>
                    <th>
                        Telefones
                    </th>
                    <th>
                        E-mail
                    </th>
                </tr>
                <tr>
                    <td>
                        <?php echo $cadam['cad_pmb'] ?>
                    </td>
                    <td>
                        <?php echo $cadam['n_pessoa'] ?>
                    </td>
                    <td>
                        <?php echo $cadam['tel1'] . ' - ' . @$cadam['tel2'] . ' - ' . @$cadam['tel3'] ?>
                    </td>
                    <td>
                        <?php echo $cadam['email'] ?>
                    </td>
                </tr>
            </table>
            <br /><br />

            <?php
            if (empty($confereLanc)) {
                if (!empty($reservado) && @$reservado != $rm) {
                    $pot = 3;
                } else {
                    $pot = 1;
                }
            } else {
                $pot = 2;
            }
            if ($pot == 1) {
                ?>
                <div class="row">
                    <div class="col-sm-3 text-center">
                        <label>
                            <input required  <?php echo @$convoca['contato'] == 1 ? 'checked' : '' ?> onclick="document.getElementById('just').style.display = 'none';" type="radio" name="contato" value="1" />
                            Contactado com sucesso e aceitou as aulas
                        </label>
                    </div>
                    <div class="col-sm-3 text-center">
                        <label>
                            <input required <?php echo @$convoca['contato'] == 2 ? 'checked' : '' ?> onclick="document.getElementById('just').style.display = '';" type="radio" name="contato" value="2" />
                            Não foi possível Contactar
                        </label>
                    </div>
                    <div class="col-sm-3 text-center">
                        <label>
                            <input required <?php echo @$convoca['contato'] == 4 ? 'checked' : '' ?> onclick="document.getElementById('just').style.display = '';" type="radio" name="contato" value="4" />
                            Já está alocado
                        </label>
                    </div>
                    <div class="col-sm-3 text-center">
                        <label>
                            <input required <?php echo @$convoca['contato'] == 3 ? 'checked' : '' ?> onclick="document.getElementById('just').style.display = '';" type="radio" name="contato" value="3" />
                            Contactado com sucesso, mas recusou as aulas
                        </label>
                    </div>
                </div>
                <?php
            } elseif ($pot == 2) {
                ?>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <label>
                            <input checked onclick="document.getElementById('just').style.display = '';" type="radio" name="contato" value="4" />
                            Já está alocado
                        </label>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <label>
                            <input checked onclick="document.getElementById('just').style.display = '';" type="radio" name="contato" value="5" />
                            Está reservado para um período
                        </label>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="row">
                <div  id="just" style="display: none" class="col-sm-12">
                    No cadastro das tentativas fracassadas deverão constar ainda: solicitante, data, horário, números chamados, sms, ou outros meios de comunicação tentados.
                    <br />
                    <textarea name="justifica" style="width: 100%; height: 40px"><?php echo @$convocacao['justifica'] ?></textarea>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div style="text-align: center" class="col-sm-12">
                    <?php
                    foreach ($reservadoSet as $k => $v) {
                        if (($k == $id_cad ) && ($v == $rm)) {
                            ?>
                            <input type="hidden" name="rm_reservado" value="<?php echo $rm ?>" />
                            <?php
                        }
                    }
                    ?>
                    <input type="hidden" name="aulas" value="<?php echo $_POST['aulas'] ?>" />
                    <input type="hidden" name="uniqid" value="<?php echo $uniqid ?>" />
                    <input type="hidden" name="id_cad" value="<?php echo @$id_cad ?>" />
                    <input type="hidden" name="id_con" value="<?php echo @$_POST['id_con'] ?>" />
                    <input class="btn btn-success" name="solicitarCapampe" type="submit" value="Continuar" />
                </div>
            </div>
        </form>
    </div>
    <br /><br />
    <?php
} else {
    ?>
    <div class="alert alert-danger" style="text-align: center; font-size: 18px">
        <form style="text-align: right" method="POST">
            <?php echo formulario::hidden($hidden) ?>
            <input class="btn btn-warning" type="submit" value="Fechar" />
        </form>
        Não Há CADAMPE disponível
    </div>
    <?php
}    