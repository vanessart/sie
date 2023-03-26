<div class="fieldBody">

    <?php
    $contaFalta = 1;
    $d = disciplina::disc();
    foreach ($d as $dd) {
        $disciplinas[$dd['id_disc']] = $dd['n_disc'];
    }
    inst::autocomplete();
    if (is_numeric(@$_POST['id_pessoa'])) {
        $backup = @$_POST['backup'];
        if (empty($_POST['ano'])) {
            $ano = date("Y");
        } else {
            $ano = $_POST['ano'];
        }
        $id_pessoa = $_POST['id_pessoa'];
        $escola = new escola();
        $aluno = new aluno($id_pessoa);
        $aluno->vidaEscolar(NULL, tool::id_inst());
        $qtUnidLetiva = sql::get('ge_cursos', 'qt_letiva', ['id_curso' => $aluno->_id_curso], 'fetch')['qt_letiva'];
        if ($aluno->_situacaoAtual == 'Frequente') {
            $sql = "select * from aval_mf_" . $aluno->_id_curso . "_" . $aluno->_id_pl . " "
                    . " where fk_id_pessoa = $id_pessoa ";
            $query = pdoHab::getInstance()->query($sql);
            $parcx = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($parcx as $v) {
                foreach ($v as $kk => $vv) {
                    if (substr($kk, 0, 6) == 'media_') {
                        $parcial[$v['atual_letiva']][substr($kk, 6)] = $vv;
                    } elseif (substr($kk, 0, 6) == 'falta_') {
                        @$faltaParc[substr($kk, 6)] += $vv;
                    }
                }
            }
        }
        $histOld = new historico($id_pessoa, @$backup);

        if ($histOld->_situacao == 1) {
            $readonly = "readonly";
        } else {
            $readonly = NULL;
        }

        $hist = (array) $histOld->_dadosAntigos;
        ?>
        <div class="fieldWhite">
            <div class="row">
                <div class="col-md-5">
                    <form id="ano" method="POST">
                        <input type="hidden" name="id_pessoa" value="<?php echo $_POST['id_pessoa'] ?>" />
                        Histórico Até: 
                        <select name="ano" onchange="document.getElementById('ano').submit();">
                            <option></option>
                            <?php
                            foreach ($hist['serieAno'] as $k => $v) {
                                ?>
                                <option <?php echo $k == $ano ? "selected" : '' ?> value="<?php echo $k ?>"><?php echo $v . ' - ' . @$hist['escola'][$k] ?></option>
                                <?php
                            }
                            ?>

                        </select>
                        <input type="hidden" name="backup" value="<?php echo @$backup ?>" />
                    </form>
                </div>
                <div class="col-md-5">
                    <?php
                    $bk = sql::get('hist_bk', 'id_hist, dt_hist, n_pessoa', ['idpessoa_aluno' => $id_pessoa, '<' => 'id_hist']);
                    if (!empty($bk)) {
                        ?>
                        <form id="backup" method="POST">
                            <input type="hidden" name="id_pessoa" value="<?php echo $_POST['id_pessoa'] ?>" />
                            <input type="hidden" name="ano" value="<?php echo $_POST['ano'] ?>" />
                            Backup: 
                            <select name="backup" onchange="document.getElementById('backup').submit();">
                                <option value="">Não</option>
                                <?php
                                foreach ($bk as $v) {
                                    ?>
                                    <option <?php echo $v['id_hist'] == $backup ? "selected" : '' ?> value="<?php echo $v['id_hist'] ?>"><?php echo data::converteBr($v ['dt_hist']) . ' ' . substr($v ['dt_hist'], 11) . ' - ' . $v['n_pessoa'] ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                        </form>
                        <?php
                    }
                    ?>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary" onclick="document.getElementById('histpessoa').submit()">
                        <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
                        Voltar
                    </button>
                    <form id="histpessoa" action="<?php echo HOME_URI ?>/hist/histpessoa" method="POST">
                        <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                    </form>
                </div>
            </div>
            <div class="row" style="display: none">
                <form method="POST">
                    <div class="col-md-8">
                        <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                        <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo $id_pessoa ?>" />
                        <?php
                        echo DB::hiddenKey('hist_esc', 'replace');
                        $options = escolas::idInst('%|4|%', 'fk_id_tp_ens');
                        asort($options);
                        formulario::select('1[fk_id_inst]', $options, 'Permitir que as seguintes escolas editem este histórico:', NULL, NULL, NULL, ' required ');
                        ?>
                    </div>
                    <div class="col-md-2">
                        <input class="btn btn-info" type="submit" value="incluir" />
                    </div>
                </form>
            </div>
            <div class="row">
                <?php
                $esc = sql::get(['hist_esc', 'instancia'], 'id_inst, n_inst, id_he', ['fk_id_pessoa' => $id_pessoa]);
                foreach ($esc as $v) {
                    ?>
                    <div class="col-md-6" style="text-align: center; padding: 20px">
                        <form method="POST">
                            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                            <?php echo DB::hiddenKey('deleteInclui') ?>
                            <input type="hidden" name="1[id_he]" value="<?php echo $v['id_he'] ?>" />
                            <input type="hidden" name="n_inst" value="<?php echo $v['n_inst'] ?>" />
                            <button style="width: 80%" type="submit" class="btn btn-warning" aria-label="Left Align">
                                <?php echo $v['n_inst'] ?>
                                &nbsp;&nbsp;&nbsp;
                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <br /><br />
        <form method="POST">
            <div style="width: 1300px; margin: 0 auto; ">
                <table class="table">
                    <tr>
                        <td  style="width: 50px">
                            <img style="width: 150px" src="<?php echo HOME_URI ?>/views/_images/brasao.png"/>

                        </td>
                        <td>
                            <div style="font-size: 20px">
                                Prefeitura Municipal de Barueri
                                <br />
                                SE - Secretaria de Educação
                            </div>
                            <div>
                                http://portal.barueri.sp.gov.br E-Mail: edu.gabinete@barueri.sp.gov.br
                                <br />
                            </div>
                            <div style="font-size: 16px">
                                <?php echo $escola->_nome ?>
                            </div>
                            <div>
                                <?php echo $escola->_email ?>
                                <br />
                                <?php echo $escola->enderecoEstruturado(1) ?>
                            </div>
                            <div>
                                <strong>
                                    Ato de Criação:
                                </strong>
                                <?php echo $escola->_ato_cria ?>
                            </div>
                            <div>
                                <strong>
                                    Ato de Municipalização:
                                </strong>
                                <?php echo $escola->_ato_municipa ?>
                            </div>
                        </td>
                        <td style="width: 134px">
                            <?php
                            if (file_exists(ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg')) {
                                ?>
                                <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $id_pessoa; ?>.jpg" width="134" height="157" alt="360306"/>
                                <?php
                            } else {
                                ?>
                                <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="150" height="180" alt="foto"/>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                <div class="fieldTop">
                    HISTÓRICO ESCOLAR
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-md-8">
                        Nome d<?php echo tool::sexoArt($aluno->_sexo) ?> Alun<?php echo tool::sexoArt($aluno->_sexo) ?>:
                        <?php echo $aluno->_nome ?>
                    </div>
                    <div class="col-md-2">
                        R.S.E:
                        <?php echo $_POST['id_pessoa'] ?>
                    </div>
                    <div class="col-md-2">
                        R.A.:
                        <?php echo $aluno->_ra ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        Dt. Nascimento:
                        <?php echo data::converteBr($aluno->_nasc) ?>
                    </div>
                    <div class="col-md-3">
                        Cidade Nasc.:
                        <?php echo $aluno->_nascCidade ?>
                    </div>
                    <div class="col-md-3">
                        Sexo:
                        <?php echo tool::sexo($aluno->_sexo) ?>
                    </div>
                    <div class="col-md-3">
                        R.G.:
                        <?php echo $aluno->_rg . ' - ' . $aluno->_rgOe . ' - ' . $aluno->_rgUf ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        Cert. Nascimento ( antiga:Termo-Livro-Folha):
                        <?php echo $aluno->_certidao ?>
                    </div>
                </div>

                <br /><br />
                <div style="border: solid #000000 2px; width: 100%; text-align: center">
                    RESULTADO DOS ESTUDOS REALIZADOS NO ENSINO FUNDAMENTAL
                </div>
                <br />
                <?php
                @$bas[3] = count(@$hist['disciplinas'][3]);
                $linhas = 18 + $bas[3];
                ?>
                <table border=1 cellspacing=0 cellpadding=1>
                    <tr>
                        <td rowspan="<?php echo $linhas ?>" style="width: 5px; background-color: white">
                            <img style="border: none" src="<?php echo HOME_URI ?>/views/_images/hiscorico1.png"/>
                        </td>
                        <td rowspan="3" colspan="2" style="font-weight: bolder;text-align: center">
                            COMPONENTES CURRICULARES
                        </td>
                        <td colspan="4">
                            Ano Letivo Atual (<?php echo $aluno->_codigo_classe ?>)
                        </td>
                        <td colspan="11">
                            Programa Padrão Ensino Fundamental
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            Anos Iniciais
                        </td>
                        <td colspan="4" style="border-left: solid black 2px">
                            Anos Finais
                        </td>
                        <td colspan="4" style="border-left: solid black 2px">
                            Notas Bimestrais
                        </td>
                        <td colspan="2" rowspan="2" style="text-align: center">
                            Faltas Aula
                        </td>
                    </tr>
                    <tr>
                        <td>
                            1o Ano
                        </td>
                        <td>
                            2o Ano/<br />1a Série
                        </td>
                        <td>
                            3o Ano/<br />2a Série
                        </td>
                        <td>
                            4o Ano/<br />3a Série
                        </td>
                        <td style="border-left: solid black 2px">
                            5o Ano/<br />4a Série
                        </td>
                        <td>
                            6o Ano/<br />5a Série
                        </td>
                        <td>
                            7o Ano/<br />6a Série
                        </td>
                        <td>
                            8o Ano/<br />7a Série
                        </td>
                        <td>
                            9o Ano/<br />8a Série
                        </td>
                        <td style="border-left: solid black 2px">
                            1B
                        </td>
                        <td>
                            2B
                        </td>
                        <td>
                            3B
                        </td>
                        <td>
                            4B
                        </td>
                    </tr>
                    <?php
                    $bc = [9 => 'Língua Portuguesa', 10 => 'Arte', 11 => 'Educação Física', 6 => 'Matemática', 12 => 'Ciências Naturais', 13 => 'História', 14 => 'Geografia'];
                    foreach ($bc as $k => $v) {
                        ?>
                        <tr>
                            <?php
                            if (@$primeiro != 1) {
                                ?>
                                <td rowspan="7">
                                    BC
                                </td>
                                <?php
                                $primeiro = 1;
                            }
                            ?>
                            <td>
                                <input type="hidden" name="dados[disciplinas][2][<?php echo $v ?>]" value="<?php echo $k ?>" />
                                <?php echo $v ?>
                            </td>
                            <?php
                            for ($c = 1; $c <= 9; $c++) {
                                ?>
                                <td style="width: 4%;<?php echo $c == 5 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if ($c <= $ano) {
                                        if (!empty($hist['discNota'][$c][$k])) {
                                            $anoAtivo[$c] = 1;
                                        }
                                        ?>
                                        <input <?php echo $readonly ?> style="text-align: center; border: none" type="text" name="dados[discNota][<?php echo $c ?>][<?php echo $k ?>]" value="<?php echo str_replace('.', ',', @$hist['discNota'][$c][$k]) ?>" size="5" />
                                        <?php
                                    } else {
                                        ?>
                                        <input type = "hidden" name = "dados[discNota][<?php echo $c ?>][<?php echo $k ?>]" value = "<?php echo str_replace('.', ',', @$hist['discNota'][$c][$k]) ?>"  />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            //notas bimestrais
                            for ($c = 1; $c <= $qtUnidLetiva; $c++) {
                                ?>
                                <td style="width: 4%;<?php echo $c == 1 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if ($ano == date("Y")) {
                                        if (empty($parcial[$c][$k])) {
                                            $nparc = str_replace('.', ',', @$hist['parcial'][$c][$k]);
                                        } else {
                                            $nparc = $parcial[$c][$k];
                                        }
                                    }
                                    ?>
                                    <input  <?php echo $readonly ?> style="text-align: center; border: none" type="text" name="dados[parcial][<?php echo $c ?>][<?php echo $k ?>]" value="<?php echo @$nparc ?>" size="5" />
                                </td>
                                <?php
                            }
                            @$falta += @$hist['falta'][$k];
                            ?>
                            <td style="width: 4%; text-align: center; border-left: solid 2px #000000">
                                <?php
                                if ($ano == date("Y")) {
                                    ?>
                                    <input  <?php echo $readonly ?> onkeyup="ctfalta()" id="falta<?php echo $contaFalta++ ?>" style="text-align: center; border: none" type="text" name="dados[falta][<?php echo $k ?>]" value="<?php echo @$faltaParc[$k] ?>" size="5" />
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    @$primeiro = 0;
                    $bd = [15 => 'L.E.Inglês', 16 => 'I.Filosofia', 17 => 'Música'];
                    foreach ($bd as $k => $v) {
                        ?>
                        <tr>
                            <?php
                            if (@$primeiro != 1) {
                                ?>
                                <td rowspan="3">
                                    BD 
                                </td>
                                <?php
                                $primeiro = 1;
                            }
                            ?>
                            <td>
                                <input  <?php echo $readonly ?> type="hidden" name="dados[disciplinas][1][<?php echo $v ?>]" value="<?php echo $k ?>" />
                                <?php echo $v ?>
                            </td>
                            <?php
                            for ($c = 1; $c <= 9; $c++) {
                                if (@$hist['discNota'][$c][$k] > 0) {
                                    if ($k == 15) {
                                        @$carga[1][$c] += 80;
                                    } else {
                                        @$carga[1][$c] += 20;
                                    }
                                }
                                ?>
                                <td style="width: 5%;<?php echo $c == 5 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if ($c <= $ano) {
                                        if (!empty($hist['discNota'][$c][$k])) {
                                            $anoAtivo[$c] = 1;
                                        }
                                        ?>
                                        <input  <?php echo $readonly ?> style="text-align: center; border: none" type="text" name="dados[discNota][<?php echo $c ?>][<?php echo $k ?>]" value="<?php echo str_replace('.', ',', @$hist['discNota'][$c][$k]) ?>" size="5" />
                                        <?php
                                    } else {
                                        ?>
                                        <input type = "hidden" name = "dados[discNota][<?php echo $c ?>][<?php echo $k ?>]" value = "<?php echo str_replace('.', ',', @$hist['discNota'][$c][$k]) ?>"  />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            //notas bimestrais

                            for ($c = 1; $c <= 4; $c++) {
                                ?>
                                <td style="width: 3%;<?php echo $c == 1 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if ($ano == date("Y")) {
                                        if (empty($parcial[$c][$k])) {
                                            $nparc = str_replace('.', ',', @$hist['parcial'][$c][$k]);
                                        } else {
                                            $nparc = $parcial[$c][$k];
                                        }
                                        ?>
                                        <input <?php echo $readonly ?> style="text-align: center; border: none" type="text" name="dados[parcial][<?php echo $c ?>][<?php echo $k ?>]" value="<?php echo $nparc ?>" size="5" />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            @$falta += @$hist['falta'][$k];
                            ?>
                            <td style="width: 4%; text-align: center; border-left: solid 2px #000000">
                                <?php
                                if ($ano == date("Y")) {
                                    ?>
                                    <input  <?php echo $readonly ?> onkeyup="ctfalta()" id="falta<?php echo $contaFalta++ ?>"  style="text-align: center; border: none" type="text" name="dados[falta][<?php echo $k ?>]" value="<?php echo @$faltaParc[$k] ?>" size="5" />
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    $primeiro = 0;
                    $bce = [18, 19, 20, 21, 22, 23, 24, 25];
                    $adicional = ['nd1' => 'nd1', 'nd2' => 'nd2', 'nd3' => 'nd3', 'nd4' => 'nd4', 'nd5' => 'nd5'];
                    if (!empty($hist['disciplinas'][3])) {
                        asort($hist['disciplinas'][3]);
                        $disc3 = array_merge(@$hist['disciplinas'][3], $adicional);
                    } else {
                        $disc3 = $adicional;
                    }

                    foreach ($disc3 as $k => $v) {
                        ?>
                        <tr>
                            <?php
                            if ($primeiro != 1) {
                                ?>
                                <td rowspan="<?php echo $bas[3] + 5 ?>">
                                    BEC
                                </td>
                                <?php
                                $primeiro = 1;
                            }
                            ?>
                            <td>
                                <?php
                                if (is_numeric($v)) {
                                    echo $disciplinas[$v];
                                    ?>
                                    <input type="hidden" name="dados[disciplinas][3][<?php echo $v ?>]" value="<?php echo $v ?>" />
                                    <?php
                                } else {
                                    if (in_array($v, $adicional)) {
                                        $valor = '';
                                    } else {
                                        $valor = $v;
                                    }
                                    ?>
                                    <input  <?php echo $readonly ?> type = "text" name = "dados[disciplinas][3][<?php echo $v ?>]" value = "<?php echo $valor ?>" />
                                    <?php
                                }
                                ?>
                            </td>
                            <?php
                            for ($c = 1; $c <= 9; $c++) {
                                if (@$hist['discNota'][$c][$v] > 0) {
                                    if (in_array($v, $bce)) {
                                        @$carga[3][$c] += 100;
                                    }
                                }
                                ?>
                                <td style="width: 5%;<?php echo $c == 5 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if ($c <= $ano) {
                                        if (!empty($hist['discNota'][$c][$v])) {
                                            $anoAtivo[$c] = 1;
                                        }
                                        ?>
                                        <input  <?php echo $readonly ?> style="text-align: center; border: none" type="text" name="dados[discNota][<?php echo $c ?>][<?php echo $v ?>]" value="<?php echo str_replace('.', ',', @$hist['discNota'][$c][$v]) ?>" size="5"  />
                                        <?php
                                    } else {
                                        ?>
                                        <input type = "hidden" name = "dados[discNota][<?php echo $c ?>][<?php echo $v ?>]" value = "<?php echo str_replace('.', ',', @$hist['discNota'][$c][$v]) ?>"  />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            for ($c = 1; $c <= 4; $c++) {
                                ?>
                                <td style="width: 3%;<?php echo $c == 1 ? 'border-left: solid black 2px' : '' ?>">
                                    <input  <?php echo $readonly ?> style="text-align: center; border: none" type="text" name="dados[parcial][<?php echo $c ?>][<?php echo $v ?>]" value="<?php echo str_replace('.', ',', @$hist['parcial'][$c][$v]) ?>" size="5" />
                                </td>
                                <?php
                            }
                            @$falta += @$hist['falta'][$k];
                            ?>
                            <td style="width: 4%; text-align: center; border-left: solid 2px #000000">
                                <input  <?php echo $readonly ?> onkeyup="ctfalta()" id="falta<?php echo $contaFalta++ ?>" style="text-align: center; border: none" type="text" name="dados[falta][<?php echo $k ?>]" value="<?php echo @$hist['falta'][$k] ?>" size="5" />
                            </td>
                        </tr>
                        <?php
                    }
                    $base = [2 => 'Carga Horária Base Comum', 1 => 'Carga Horária Base Diversificada', 3 => 'Carga Horária Base Extra Curricular', 0 => 'Carga Horária Total'];
                    for ($c = 1; $c <= 9; $c++) {
                        if (@$anoAtivo[$c] == 1) {
                            @$carga[2][$c] = 1000 - @$carga[1][$c];
                            @$carga[0][$c] = 1000 + @$carga[3][$c];
                        } else {
                            @$carga[2][$c] = null;
                            @$carga[0][$c] = NULL;
                        }
                    }
                    foreach ($base as $k => $b) {
                        ?>
                        <tr>
                            <td colspan="3">
                                <?php echo $b ?>
                            </td>
                            <?php
                            for ($c = 1; $c <= 9; $c++) {
                                ?>
                                <td style="width: 3%;<?php echo $c == 5 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if (empty($hist['carga'][$k][$c])) {
                                        $ch[$k][$c] = @$carga[$k][$c];
                                    } else {
                                        $ch[$k][$c] = $hist['carga'][$k][$c];
                                    }
                                    if ($k != 0) {
                                        if ($c <= $ano) {
                                            ?>
                                            <input id="<?php echo $k . '-' . $c ?>" onkeyup="soma(<?php echo $c ?>)" style="text-align: center; border: none" type="text" name="dados[carga][<?php echo $k ?>][<?php echo $c ?>]" value="<?php echo $ch[$k][$c] ?>" size="5" />
                                            <?php
                                        } else {
                                            ?>
                                            <input type = "hidden" name = "dados[carga][<?php echo $k ?>][<?php echo $c ?>]"  value="<?php echo $ch[$k][$c] ?>" />
                                            <?php
                                        }
                                    } else {
                                        if ($c <= $ano) {
                                            ?>
                                            <input readonly id="<?php echo $c ?>"  style="text-align: center; border: none" type="text"  value="<?php echo empty($anoAtivo[$c]) ? '' : ($ch[1][$c] + $ch[2][$c] + $ch[3][$c]) ?>" size="5" />
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            if ($k == 2) {
                                ?>
                                <td colspan="5" rowspan="4" style="border-left: solid black 2px; text-align: center; font-weight: bold">
                                    <?php
                                    if (!empty($aluno->_codigo_classe)) {
                                        if (substr($aluno->_codigo_classe, 3, 1) <= 5) {
                                            ?>
                                            Falta dia
                                            <input  <?php echo $readonly ?> style="text-align: center" type = "text" name="dados[faltaDia]" value = "<?php echo @$hist['faltaDia'] ?>" size = "10"  />
                                            <?php
                                        }
                                        ?>
                                        Faltas Aula
                                        <input id="ttfalta" style="text-align: center" type = "text" value = "<?php echo @$falta ?>" size = "10" readonly = "readonly" />
                                        <?php
                                    }
                                    ?> 
                                    <br />
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="17">
                            BC - Base Comum, BD - Base Diversificada, BEC - Base Extra Curricular
                            <br />
                            ENSINO RELIGIOSO E ORIENTAÇÃO DE ESTUDOS(DELIBERAÇÃO CME 05/2001) - 1o ANO - LEI 11274/2006
                        </td>
                    </tr>
                </table>
                <br /><br />
                <div style="border: solid #000000 2px; width: 100%; text-align: center">
                    ESTUDOS REALIZADOS NO ENSINO FUNDAMENTAL
                </div>
                <br />
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr style="font-weight: bolder;font-size: 16px">
                            <td>
                                Série/Ano
                            </td>
                            <td>
                                Regime
                            </td>
                            <td>
                                Ano
                            </td>
                            <td>
                                Estabelecimento
                            </td>
                            <td>
                                Municipio
                            </td>
                            <td>
                                Estado
                            </td>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
                        $anoSerie = [
                            1 => '1o Ano',
                            2 => '2o Ano/1a Série',
                            3 => '3o Ano/2a Série',
                            4 => '4o Ano/3a Série',
                            5 => '5o Ano/4a Série',
                            6 => '6o Ano/5a Série',
                            7 => '7o Ano/6a Série',
                            8 => '8o Ano/7a Série',
                            9 => '9o Ano/8a Série'
                        ];
                        for ($c = 1; $c <= 9; $c++) {
                            if (is_numeric(@$hist['fk_id_inst'][$c]) && @$hist['fk_id_inst'][$c] != '') {
                                $ultimaInstancia = $hist['fk_id_inst'][$c];
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php echo $anoSerie[$c] ?>
                                </td>
                                <td>
                                    <?php
                                    if ($c <= $ano) {
                                        ?>
                                        <select name="dados[regime][<?php echo $c ?>]">
                                            <option></option>
                                            <option <?php echo @$hist['regime'][$c] == 'EF9' ? 'selected' : '' ?>>EF9</option>
                                            <option <?php echo @$hist['regime'][$c] == 'EF8' ? 'selected' : '' ?>>EF8</option>
                                        </select>
                                        <?php
                                    } else {
                                        ?>
                                        <input type="hidden" name="dados[regime][<?php echo $c ?>]" value="<?php echo @$hist['regime'][$c] ?>" />
                                        <?php
                                    }
                                    ?>

                                </td>
                                <td>
                                    <?php
                                    if ($c <= $ano) {
                                        ?>
                                        <select name="dados[serieAno][<?php echo $c ?>]">
                                            <option></option>
                                            <?php
                                            for ($i = date("Y"); $i >= 1950; $i--) {
                                                ?>
                                                <option <?php echo @$hist['serieAno'][$c] == $i ? 'selected' : '' ?>><?php echo $i ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                        <?php
                                    } else {
                                        ?>
                                        <input type="hidden" name="dados[serieAno][<?php echo $c ?>]" value="<?php echo @$hist['serieAno'][$c] ?>" />
                                        <?php
                                    }
                                    ?>

                                </td>
                                <td>
                                    <?php
                                    if ($c <= $ano) {
                                        ?>
                                        <select name="dados[fk_id_inst][<?php echo $c ?>]" >
                                            <option onclick="document.getElementById('escol<?php echo $c ?>').style.display = 'none';document.getElementById('cid<?php echo $c ?>').value = '';document.getElementById('uf<?php echo $c ?>').value = ''" ></option>
                                            <option onclick="document.getElementById('escol<?php echo $c ?>').style.display = '';document.getElementById('cid<?php echo $c ?>').value = '';document.getElementById('uf<?php echo $c ?>').value = ''" <?php echo is_string(@$hist['fk_id_inst'][$c]) && @$hist['fk_id_inst'][$c] != '' ? 'selected' : '' ?> value="" >Fora da rede</option>
                                            <?php
                                            $escolas = escolas::idInst('%|4|%', 'fk_id_tp_ens');
                                            asort($escolas);
                                            foreach ($escolas as $key => $value) {
                                                ?>
                                                <option onclick="document.getElementById('escol<?php echo $c ?>').style.display = 'none';document.getElementById('cid<?php echo $c ?>').value = 'Barueri';document.getElementById('uf<?php echo $c ?>').value = 'SP'" <?php echo @$hist['fk_id_inst'][$c] == $key ? 'selected' : '' ?> value="<?php echo $key ?>" ><?php echo $value ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                        <input  <?php echo $readonly ?> name="dados[escola][<?php echo $c ?>]" id="escol<?php echo $c ?>"  style=" display: <?php echo is_numeric(@$hist['fk_id_inst'][$c]) || empty(@$hist['fk_id_inst'][$c]) ? 'none' : '' ?>; " type="text" value="<?php echo @$hist['fk_id_inst'][$c] ?>"  />
                                        <?php
                                    } else {
                                        ?>
                                        <input type="hidden" name="dados[fk_id_inst][<?php echo $c ?>]" value="<?php echo @$hist['fk_id_inst'][$c] ?>" />
                                        <?php
                                    }
                                    ?>

                                </td>
                                <td>
                                    <?php
                                    if ($c <= $ano) {
                                        ?>
                                        <input <?php echo $readonly ?> id="cid<?php echo $c ?>" style="text-align: center;" type="text" name="dados[cidade][<?php echo $c ?>]" value="<?php echo @$hist['cidade'][$c] ?>" size="5" />
                                        <?php
                                    } else {
                                        ?>
                                        <input type="hidden" name="dados[cidade][<?php echo $c ?>]" value="<?php echo @$hist['cidade'][$c] ?>" />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($c <= $ano) {
                                        ?>
                                        <input <?php echo $readonly ?> id="uf<?php echo $c ?>"  style="text-align: center;" type="text" name="dados[uf][<?php echo $c ?>]" value="<?php echo @$hist['uf'][$c] ?>" size="5" />
                                        <?php
                                    } else {
                                        ?>
                                        <input type="hidden" name="dados[uf][<?php echo $c ?>]" value="<?php echo @$hist['uf'][$c] ?>" />
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                OBSERVAÇÕES
                            </div>
                            <div class="panel panel-body">
                                <textarea name="dados[obs]" style="width: 100%; height: 100px"><?php echo empty($hist['obs']) ? 'Nada consta em seu prontuário que o(a) desabone.' : $hist['obs'] ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                CERTIFICADO
                            </div>
                            <div class="panel panel-body">
                                <textarea name="dados[certificado]" style="width: 100%; height: 100px"><?php echo empty($hist['certificado']) ? 'O(A) Diretor(a) da ' . $escola->_nome . ', de acordo com o artigo 24 - Lei Federal 9394/96 certifica que ' . $aluno->_nome . ' , R.G.: ' . $aluno->_rgCompl . ', concluio o __ Ano do Ensino Fundamental no Ano Letivo de ____, estando apt' . tool::sexoArt($aluno->_sexo) . ' ao prosseguimento dos estudos no __ Ano do Ensino Fundamental.' : $hist['certificado'] ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <input type="hidden" name="historico" value="1" />
                        <input type="hidden" name="id_pessoa" value="<?php echo $_POST['id_pessoa'] ?>" />
                        <input type="hidden" name="ano" value="<?php echo $ano ?>" />
                        <input type="hidden" name="set" value="1" />
                        <input type="hidden" name="fechado" value="<?php echo $histOld->_situacao ?>" />

                        <?php
                        if ($histOld->_situacao == 0) {
                            ?>
                            <button class="btn btn-info" type="submit" >
                                Salvar
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="col-md-4 text-center">
                        <button  onclick="document.getElementById('pdf').submit()" class="btn btn-info" type="button" >
                            <?php
                            if ($histOld->_situacao == 0) {
                                ?>
                                Vizualizar (Salve Antes)
                                <?php
                            } else {
                                ?>
                                Imprimir
                                <?php
                            }
                            ?>

                        </button>
                    </div>
                    <div class="col-md-4 text-center">
                        <button  onclick="document.getElementById('fechado').submit()" class="btn btn-<?php echo $histOld->_situacao == 0 ? 'danger' : 'success' ?>" type="button" >
                            <?php
                            if ($histOld->_situacao == 0) {
                                ?>
                                Clique aqui para finalizar (Salve Antes)
                                <?php
                            } else {
                                ?>
                                Finalizado (Clique aqui para editar o hitórico)
                                <?php
                            }
                            ?>
                        </button>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </form>
</div>

<form target="_blank" id="pdf" action="<?php echo HOME_URI ?>/hist/pdf" method="POST">
    <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
    <input type="hidden" name="ano" value="<?php echo $ano ?>" />
</form>
<form  method="POST" id="fechado" >
    <input type="hidden" name="fk_id_inst" value="<?php echo @$ultimaInstancia ?>" />
    <input type="hidden" name="fechaAbre" value="fechaAbre" />
    <input type="hidden" name="fechado" value="<?php echo $histOld->_situacao == 0 ? 1 : 0 ?>" />
    <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
    <input type="hidden" name="ano" value="<?php echo $ano ?>" />
</form>

<script>
    function soma(c) {
        c1 = document.getElementById('1-' + c).value;
        c2 = document.getElementById('2-' + c).value;
        c3 = document.getElementById('3-' + c).value;


        document.getElementById(c).value = Number(c1) + Number(c2) + Number(c3);
    }


    function ctfalta() {
        var ctf = 0;
<?php
for ($h = 1; $h < $contaFalta; $h++) {
    ?>
            if (document.getElementById('falta<?php echo $h ?>').value != '') {
                ctf = ctf + parseInt(document.getElementById('falta<?php echo $h ?>').value);
            }
    <?php
}
?>
        document.getElementById("ttfalta").value = ctf;
    }
</script>

