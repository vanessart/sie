<?php
$id_pr = filter_input(INPUT_POST, 'id_pr', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_pr)) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $id_cargo = filter_input(INPUT_POST, 'id_cargo', FILTER_SANITIZE_NUMBER_INT);
    $periodo = filter_input(INPUT_POST, 'periodo', FILTER_UNSAFE_RAW);
    $inicio = filter_input(INPUT_POST, 'inicio');
    $fim = filter_input(INPUT_POST, 'fim');
    $hidden['id_inst'] = $id_inst;
    $hidden['id_cargo'] = $id_cargo;
    $hidden['periodo'] = $periodo;
    $hidden1['inicio'] = $inicio;
    $hidden1['fim'] = $fim;
    $id_cad = filter_input(INPUT_POST, 'id_cad', FILTER_SANITIZE_NUMBER_INT);
    $t = @$_POST['t'];
} else {
    $pr = sql::get('cadam_prolongado', '*', ['id_pr' => $id_pr], 'fetch');

    $id_cad = $pr['fk_id_cad'];
    $hidden['id_cargo'] = $id_cargo = $pr['fk_id_cargo'];
    $hidden['id_inst'] = $id_inst = $pr['fk_id_inst'];
    $hidden['periodo'] = $periodo = $pr['periodo'];
    $hidden1['inicio'] = $inicio = $pr['dt_inicio'];
    $hidden1['fim'] = $fim = $pr['dt_fim'];
    $t = explode('|', $pr['turmas']);
}
if ($t) {
    foreach ($t as $y) {
        $hidden1['t[' . $y . ']'] = $y;
    }
}
?>
<div class="fieldBody">
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-6">
                <?php
                echo formulario::select('id_inst', escolas::idInst(), 'Escola', $id_inst, ' required ');
                ?>
            </div>
            <div class="col-sm-6">
                <?php
                formulario::selectDB('cadam_cargo', 'id_cargo', 'Cargo', $id_cargo, ' required ');
                ?>
            </div>
            <br /><br />

            <div class="col-sm-3">
                <?php
                echo formulario::select('periodo', ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'G' => 'Geral'], 'Período', $periodo, NULL, NULL, ' required ');
                ?>
            </div>
            <?php
            if (empty($id_inst)) {
                ?>
                <div class="col-sm-2">
                    <input class="btn btn-info" type="submit" value="Continuar" />
                </div> 
                <?php
            }
            ?>
        </div>
    </form>
    <form method="POST">
        <?php
        if (!empty($id_inst) && !empty($id_cargo)) {
            $escola = new escola($id_inst);
            $turmas = $escola->turmas()
            ?>
            <br /><br />
            <div class="row">
                <div class="col-sm-3">
                    <?php echo formulario::input('inicio', 'Início', NULL, @$inicio, formulario::dataConf('1') . ' required ') ?>
                </div>
                <div class="col-sm-3">
                    <?php echo formulario::input('fim', 'Final', NULL, @$fim, formulario::dataConf('2') . ' required ') ?>
                </div>
            </div>
            <br />

            <div class="row">
                <?php
                $c = 1;
                foreach ($turmas as $v) {
                    if ($periodo == $v['periodo']) {
                        ?>
                        <div class="col-sm-2">
                            <label>
                                <input <?php echo @in_array($v['id_turma'], @$t) ? 'checked' : '' ?> type="checkbox" name="t[<?php echo $v['id_turma'] ?>]" value="<?php echo $v['id_turma'] ?>" />
                                <?php echo $v['n_turma'] ?>
                            </label>

                        </div>
                        <?php
                        if ($c++ % 6 == 0) {
                            ?>
                        </div>
                        <br /><br />
                        <div class="row">
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <br /><br />
            <div style="text-align: center">
                <?php
                echo formulario::hidden($hidden);
                if (empty($t)) {
                    ?>
                    <input class="btn btn-info" type="submit" value="Continuar" />
                    <?php
                }
                ?>
            </div>   
        </form>
        <br /><br />
        <?php
        if (!empty($t)) {
            ?>
        <form target="_parent" action="<?php echo HOME_URI ?>/cadam/freqmult" method="POST">
                <?php
                $cad = cadamp::cadampesPorEscola($id_inst, $id_cargo);
                foreach ($cad as $v) {
                    $dias = explode('|', $v['dia']);
                    $disponivel = 0;
                    foreach ($dias as $d) {
                        if (substr($d, 1) == $periodo) {
                            $disponivel = 1;
                        }
                    }
                    if ($disponivel == 1) {
                        $cadamp[$v['id_cad']] = $v;
                        $cadampOpt[$v['id_cad']] = $v['n_pessoa'];
                    }
                }
                if (empty($id_cad)) {
                    $cadOpt = $cad[0];
                } else {
                    $cadOpt = $cadamp[$id_cad];
                }
                ?>
                <div class="row">
                    <div class="col-sm-6">
                        <table style="width: 100%">
                            <tr>
                                <td style="padding: 10px; font-weight: bold">
                                    Cadampe: 
                                </td>
                                <td>
                                    <select style="width: 100%" name="1[fk_id_cad]" onchange="fcadd(this.options[this.selectedIndex].text)">
                                        <?php
                                        foreach ($cadampOpt as $k => $v) {
                                            $js = "'" . $cadamp[$k]['tel1'] . "; " . $cadamp[$k]['tel2'] . "; " . $cadamp[$k]['tel3'] . "', '" . $cadamp[$k]['email'] . "', '" . $cadamp[$k]['cad_pmb'] . "'";
                                            ?>
                                            <option <?php echo $id_cad == $k ? 'selected' : '' ?> onclick="cadamSet(<?php echo $js ?>)" value="<?php echo $k ?>"><?php echo $v ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <div id="tel">
                            Telefones: <?php echo $cadOpt['tel1'] . '; ' . $cadOpt['tel2'] . '; ' . $cadOpt['tel3'] ?>
                        </div>
                        <br />
                        <div id="em">
                            E-mail: <?php echo $cadOpt['email'] ?>
                        </div>
                        <br />
                        <div id="mun">
                            Cad. Municipal: <?php echo $cadOpt['cad_pmb'] ?>
                        </div>
                    </div>
                </div>
                <br /><br />
                <?php
                ?>
                <br /><br />
                <div style="text-align: center">
                    <?php echo DB::hiddenKey('cadam_prolongado', 'replace') ?>
                    <input type="hidden" name="1[id_pr]" value="<?php echo @$id_pr ?>" />
                    <input type="hidden" name="1[fk_id_inst]" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="1[fk_id_cargo]" value="<?php echo $id_cargo ?>" />
                    <input type="hidden" name="1[periodo]" value="<?php echo $periodo ?>" />
                    <input type="hidden" name="1[dt_inicio]" value="<?php echo $inicio ?>" />
                    <input type="hidden" name="1[dt_fim]" value="<?php echo $fim ?>" />
                    <input type="hidden" name="1[turmas]" value="|<?php echo implode('|', $t) ?>|" />
                    <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>" />
                    <input type="submit" value="Salvar" />
                </div>
            </form>

            <?php
        }
    }
    ?>
</div>
<script>
    function cadamSet(t, email, cad) {
        document.getElementById('tel').innerHTML = 'Telefones: ' + t;
        document.getElementById('em').innerHTML = 'E-mail: ' + email;
        document.getElementById('mun').innerHTML = 'Cad. Municipal: : ' + cad;
    }
</script>
 