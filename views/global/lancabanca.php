<?php
$aval = sql::get('global_aval', '*', ['id_gl' => @$_POST['id_gl']], 'fetch');

$val__ = unserialize($aval['val']);

$perc = unserialize($aval['perc']);
$id_rt = sql::get('global_relat', 'id_rt', ['fk_id_turma' => @$_POST['id_turma'], 'fk_id_gl' => @$_POST['id_gl']], 'fetch')['id_rt'];
?>
<div style="width: 95%; margin: 0 auto">
    <form target="_parent" action="<?php echo HOME_URI ?>/global/banca#l<?php echo @$_POST['id_pessoa'] ?>" method="POST">
        <div style="width: 100%;">
            <?php
            $aluno = sql::get('global_respostas', '*', ['fk_id_pessoa' => @$_POST['id_pessoa'], 'fk_id_gl' => @$_POST['id_gl']], 'fetch');
            for ($c = 1; $c <= $aval['quest']; $c++) {
                $par_ = @$aluno['q' . str_pad($c, 2, "0", STR_PAD_LEFT)];
                @$tpn += (empty($par_) ? $val__[2] : $val__[$par_]);
            }
            ?>
            <div style="text-align: center; font-weight: bold; font-size: 20px">
                <?php
                echo 'Alun' . tool::sexoArt(@$_POST['sexo']) . ' ' . @$_POST['n_pessoa'] . ', Nº ' . @$_POST['chamada'];
                ?>
                <br />
                <?php
                echo $aval['n_gl'];
                if (!empty($aval['vernota'])) {
                    ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Nota: 
                    <input id="notaSet" style="width: 80px; text-align: center; font-size: 25px; border: none" type="text"  value="<?php echo ($tpn) ?>" />
                    <?php
                }
                ?>
            </div>
            <?php
            ?>
            <table style="width: 100%">
                <tr>
                    <?php
                    if (empty($aval['naofez'])) {
                        $hidden = "display: none";
                    } else {
                        $hidden = NULL;
                    }
                    ?>
                    <td style="<?php echo @$hidden ?>">
                        <label style="font-size: 15px; padding: 18px;text-align: center">
                            <input <?php echo @$aluno['nfez'] == 'n' ? 'checked' : '' ?> onclick="fez('n')" id="n" type="checkbox" name="nfez" value="n" />
                            <br />Ausente
                        </label>
                    </td>
                    <?php
                    if (empty($aval['branco'])) {
                        $hidden = "display: none";
                    } else {
                        $hidden = NULL;
                    }
                    ?>
                    <td <td style="<?php echo @$hidden ?>">
                        <label style="font-size: 15px; padding: 18px;text-align: center">
                            <input <?php echo @$aluno['nfez'] == 'b' ? 'checked' : '' ?> onclick="fez('b')" id="b" type="checkbox" name="nfez" value="b" />
                            <br />Em Branco
                        </label>
                    </td>
                    <?php
                    if (empty($aval['nulo'])) {
                        $hidden = "display: none";
                    } else {
                        $hidden = NULL;
                    }
                    ?>
                    <td style="<?php echo $aval['fk_id_disc'] != 29 ? 'display: none' : @$hidden ?>">
                        <label style="font-size: 15px; padding: 18px;text-align: center">
                            <input <?php echo @$aluno['nfez'] == 'a' ? 'checked' : '' ?> onclick="fez('a')" id="a" type="checkbox" name="nfez" value="a" />
                            <br />Anulada
                        </label>
                    </td>
                    <td style="<?php echo @$hidden ?>">
                        <label style="font-size: 15px; padding: 18px;text-align: center">
                            <input <?php echo @$aluno['nfez'] == 'l' ? 'checked' : '' ?> onclick="fez('l')" id="l" type="checkbox" name="nfez" value="l" />
                            <br />Adaptação Curricular
                        </label>
                    </td>
                    <!-- --------------------------------------------------------------------------- -->
                    <td style="<?php echo (!in_array($aval['ciclos'], [1, 2])) ? 'display: none' : @$hidden ?>">
                        <label style="font-size: 15px; padding: 18px;text-align: center">
                            <input <?php echo @$aluno['nfez'] == 't' ? 'checked' : '' ?> onclick="fez('t')" id="t" type="checkbox" name="nfez" value="t" />
                            <br />Transferido
                        </label>
                    </td>
                    <?php
/**
 * (in_array($aval['ciclos'], [1, 2]) || $aval['fk_id_disc'] != 29) ? 'display: none' : @$hidden
 */
?>
                    <td style="<?php echo NULL ?>">
                        <label style="font-size: 15px; padding: 18px;text-align: center">
                            <input <?php echo @$aluno['nfez'] == 'r' ? 'checked' : '' ?> onclick="fez('r')" id="r" type="checkbox" name="nfez" value="r" />
                            <br />Reclassificado/Transferido
                        </label>
                    </td>
                    <?php
                    /**
                     * (in_array($aval['ciclos'], [1, 2]) || $aval['fk_id_disc'] != 29) ? 'display: none' : @$hidden
                     */
                    ?>
                    <td style="<?php echo NULL ?>">
                        <label style="font-size: 15px; padding: 18px;text-align: center">
                            <input <?php echo @$aluno['nfez'] == 'e' ? 'checked' : '' ?> onclick="fez('e')" id="e" type="checkbox" name="nfez" value="e" />
                            <br />Estrangeiro
                        </label>
                    </td>
                    <!-- --------------------------------------------------------------------------- -->
                </tr>
            </table>
            <?php
            if ((in_array($aval['ciclos'], [6, 7, 8, 9]) && $aval['fk_id_disc'] == 29)) {
                ?>
                <div id="lapis" style="text-align: center">
                    <label>
                        <input <?php echo @$aluno['lapis'] == 1 ? 'checked' : '' ?> type="checkbox" name="lapis" value="1" />
                        Escrita a Lápis
                    </label>
                </div>
                <br />
                <?php
            }
            if (!empty($aval['escrita'])) {
                $escrita = ['Pré-silábico', 'Silábico sem valor', 'Silábico com valor', 'Silábico-alfabético', 'Alfabético', 'Alfabético-ortográfico']
                ?>
                <div id="ne" class="alert alert-success">
                    <div style="text-align: center;font-size: 15px">
                        Níveis de Escrita
                    </div>
                    <table style="width: 100%">
                        <tr>
                            <?php
                            foreach ($escrita as $es) {
                                ?>
                                <td>
                                    <label style="font-size: 18px; padding: 20px">
                                        <input <?php echo @$aluno['escrita'] == $es ? 'checked' : '' ?> type="radio" name="escrita" value="<?php echo $es ?>" required />
                                        <?php echo $es ?>
                                    </label>
                                </td>
                                <?php
                            }
                            ?>

                        </tr>
                    </table>
                </div>
                <br />
                <?php
            }
            if (NULL) {
//            if (!empty($aval['titulo'])) {
                ?>
                <div id="ti" class="alert alert-info" style="font-weight: bold;font-size: 15px; padding: 20px">
                    <pre><?php echo $aval['titulo'] ?></pre>
                </div>
                <br />
                <?php
            }
            ?>

            <table style="width: 100%">
                <tr>
                    <td valign="top">
                        <table style="display: <?php echo empty($aluno['nfez']) ? '' : 'none' ?>" id="lanca" class="table table-bordered table-hover table-condensed">
                            <?php
                            for ($c = 1; $c <= $aval['quest']; $c++) {
                                ?>
                                <tr>
                                    <td style="font-weight: bold; font-size: 16px;">
                                        <?php
                                        if (empty($aval['perc'])) {
                                            ?>
                                            Questão <?php echo $c ?>
                                            <?php
                                        } elseif ($aval['numerar'] == 1) {
                                            echo $c . ' - ' . $perc[$c];
                                        } else {
                                            echo $perc[$c];
                                        }
                                        ?>

                                    </td>
                                    <?php
                                    foreach (explode(',', $aval['valores']) as $k => $v) {
                                        ?>
                                        <td>
                                            <label style="font-weight: bold; font-size: 15px">
                                                <input id="<?php echo $c ?>_<?php echo $k ?>" onclick="calcula()" <?php echo @$aluno['q' . str_pad($c, 2, "0", STR_PAD_LEFT)] == ($k + 1) ? 'checked' : (empty(@$aluno['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]) && $k + 1 == $aval['resposta_default'] ? 'checked' : '') ?> type="radio" name="resp[<?php echo $c ?>]" value="<?php echo $k + 1 ?>" />
                                                <?php echo $v ?>
                                            </label>    
                                        </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <input type="hidden" name="id_gl" value="<?php echo @$_POST['id_gl'] ?>" />
        <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
        <input type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
        <input type="hidden" name="id_pessoa" value="<?php echo @$_POST['id_pessoa'] ?>" />
        <input type="hidden" name="n_pessoa" value="<?php echo @$_POST['n_pessoa'] ?>" />
        <input type="hidden" name="sexo" value="<?php echo @$_POST['sexo'] ?>" />
        <input type="hidden" name="chamada" value="<?php echo @$_POST['chamada'] ?>" />
        <input type="hidden" name="n_gl" value="<?php echo @$_POST['n_gl'] ?>" />
        <input type="hidden" name="ciclos" value="<?php echo @$_POST['ciclos'] ?>" />
        <input type="hidden" name="quest" value="<?php echo @$_POST['quest'] ?>" />
        <input type="hidden" name="valores" value="<?php echo @$_POST['valores'] ?>" />
        <input type="hidden" name="id_resp" value="<?php echo @$aluno['id_resp'] ?>" />
        <input type="hidden" name="porcent" value="<?php echo @$_POST['porcent'] ?>" />
        <input type="hidden" name="id_rt" value="<?php echo @$id_rt ?>" />
        <input type="hidden" name="professor" value="<?php echo @$_POST['professor'] ?>" />
        <?php echo DB::hiddenKey('lacarNota') ?>
        <input type="hidden" name="lacarNota" value="1" />
        <br /><br />
        <div class="row">
            <div class="col-md-6 text-center" style="font-size: 18px">
                <?php
                if (!empty($aluno['id_resp'])) {
                    ?>
                    <input onclick="limp()" class="btn btn-warning" type="button" value="Limpar Dados" />
                    <?php
                }
                ?>
            </div>
            <div class="col-md-6 text-center" style="font-size: 18px">
                <input onclick="alterar_div1()" class="btn btn-<?php echo empty($aluno) ? 'warning' : 'success' ?> " type="submit" value="Salvar" />
            </div>
        </div>
        <br /><br />
    </form>
    <form target="_parent" action="<?php echo HOME_URI ?>/global/banca#l<?php echo @$_POST['id_pessoa'] ?>"  id="apaga" method="POST">
        <input type="hidden" name="id_gl" value="<?php echo @$_POST['id_gl'] ?>" />
        <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
        <input type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
        <input type="hidden" name="id_pessoa" value="<?php echo @$_POST['id_pessoa'] ?>" />
        <input type="hidden" name="n_pessoa" value="<?php echo @$_POST['n_pessoa'] ?>" />
        <input type="hidden" name="sexo" value="<?php echo @$_POST['sexo'] ?>" />
        <input type="hidden" name="chamada" value="<?php echo @$_POST['chamada'] ?>" />
        <input type="hidden" name="n_gl" value="<?php echo @$_POST['n_gl'] ?>" />
        <input type="hidden" name="ciclos" value="<?php echo @$_POST['ciclos'] ?>" />
        <input type="hidden" name="quest" value="<?php echo @$_POST['quest'] ?>" />
        <input type="hidden" name="valores" value="<?php echo @$_POST['valores'] ?>" />
        <input type="hidden" name="id_resp" value="<?php echo @$aluno['id_resp'] ?>" />
        <input type="hidden" name="porcent" value="<?php echo @$_POST['porcent'] ?>" />
        <input type="hidden" name="id_rt" value="<?php echo @$id_rt ?>" />
        <input type="hidden" name="apagarNota" value="1" />
        <input type="hidden" name="professor" value="<?php echo @$_POST['professor'] ?>" />
    </form>
</div>
<script>
    function fez(opt) {

        if (document.getElementById('a').checked || document.getElementById('n').checked || document.getElementById('b').checked || document.getElementById('l').checked || document.getElementById('t').checked || document.getElementById('r').checked || document.getElementById('e').checked) {
            document.getElementById("lanca").style.display = "none";
<?php
if (!empty($aval['escrita'])) {
    ?>
                $("[name='escrita']").removeAttr("required");
    <?php
}
if ((in_array($aval['ciclos'], [6, 7, 8, 9]) && $aval['fk_id_disc'] == 29)) {
    ?>
                document.getElementById("lapis").style.display = "none";
    <?php
}
if (!empty($aval['escrita'])) {
    ?>
                document.getElementById("ne").style.display = "none";
    <?php
}
?>
            // document.getElementById("ti").style.display = "none";
        } else {
            document.getElementById("lanca").style.display = "";
<?php
if ((in_array($aval['ciclos'], [6, 7, 8, 9]) && $aval['fk_id_disc'] == 29)) {
    ?>
                document.getElementById("lapis").style.display = "";
    <?php
}
if (!empty($aval['escrita'])) {
    ?>
                document.getElementById("ne").style.display = "";
    <?php
}
?>
            //  document.getElementById("ti").style.display = "";
        }
        if (opt == 'a') {
            document.getElementById('b').checked = false;
            document.getElementById('n').checked = false;
            document.getElementById('l').checked = false;
            document.getElementById('t').checked = false;
            document.getElementById('r').checked = false;
            document.getElementById('e').checked = false;
        } else if (opt == 'b') {
            document.getElementById('a').checked = false;
            document.getElementById('n').checked = false;
            document.getElementById('l').checked = false;
            document.getElementById('t').checked = false;
            document.getElementById('r').checked = false;
            document.getElementById('e').checked = false;
        } else if (opt == 'n') {
            document.getElementById('a').checked = false;
            document.getElementById('b').checked = false;
            document.getElementById('l').checked = false;
            document.getElementById('t').checked = false;
            document.getElementById('r').checked = false;
            document.getElementById('e').checked = false;
        } else if (opt == 'l') {
            document.getElementById('a').checked = false;
            document.getElementById('b').checked = false;
            document.getElementById('n').checked = false;
            document.getElementById('t').checked = false;
            document.getElementById('r').checked = false;
            document.getElementById('e').checked = false;
        } else if (opt == 'r') {
            document.getElementById('a').checked = false;
            document.getElementById('b').checked = false;
            document.getElementById('n').checked = false;
            document.getElementById('t').checked = false;
            document.getElementById('l').checked = false;
            document.getElementById('e').checked = false;
        } else if (opt == 't') {
            document.getElementById('a').checked = false;
            document.getElementById('b').checked = false;
            document.getElementById('n').checked = false;
            document.getElementById('l').checked = false;
            document.getElementById('r').checked = false;
            document.getElementById('e').checked = false;
        } else if (opt == 'e') {
            document.getElementById('a').checked = false;
            document.getElementById('b').checked = false;
            document.getElementById('n').checked = false;
            document.getElementById('t').checked = false;
            document.getElementById('r').checked = false;
            document.getElementById('l').checked = false;
        }
    }

    function limp() {
        if (confirm("Esta ação apagará os dados deste aluno")) {
            document.getElementById("apaga").submit();
        }
    }

    function calcula() {
        nota = 0;
<?php
for ($c = 1; $c <= $aval['quest']; $c++) {
    foreach (explode(',', $aval['valores']) as $k => $v) {
        ?>
                if (document.getElementById('<?php echo $c ?>_<?php echo $k ?>').checked == true) {
                            nota = nota + <?php echo empty($val__[$k + 1]) ? 0 : $val__[$k + 1] ?>;
                        }
        <?php
    }
}
?>
                document.getElementById('notaSet').value = nota.toFixed(2);
            }
</script>

<script>

    function alterar_div1() {
        $.ajax({
            type: "POST",
            url: "<?php echo HOME_URI ?>/global",
            data: {
                nome_usuario: $('#seu_nome').val()
            },
            success: function (data) {
                $('#conteudo').html(data);
            }
        });
    }
</script>
