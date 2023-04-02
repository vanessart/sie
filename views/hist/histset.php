<head>
    <style>
        .textovertical{
            width:1px;
            word-wrap: break-word;
            white-space: pre-wrap;
            padding: 5px;
        }
    </style>
</head>

<div class="fieldBody" >

    <?php
    $corEdit = 'khaki';
    $contaFalta = 1;
    $d = disciplina::disc();
    $wciclo = [6, 7, 8, 9];
    $pandemiaC = [2020 => 820, 2021 => 862];
    $anociclo = filter_input(INPUT_POST, 'ano', FILTER_UNSAFE_RAW);

    foreach ($d as $dd) {
        $disciplinas[$dd['id_disc']] = $dd['n_disc'];
    }

    inst::autocomplete();
    if (is_numeric(@$_POST['id_pessoa'])) {

        $backup = @$_POST['backup'];
        $id_pessoa = $_POST['id_pessoa'];

        if (empty($_POST['ano'])) {
            $ano = date("Y");
            $u = $model->pegaultimoano($id_pessoa);
        } else {
            $ano = $_POST['ano'];
            $u = $model->pegaultimoano($id_pessoa, $ano);
        }

        $wlayout_ciclo = $u['Ciclo'];

        $escola = new escola();
        $aluno = new aluno($id_pessoa);
        $aluno->vidaEscolar(NULL, tool::id_inst());
        $periodo = gtMain::periodos(1);

        if (array_key_exists($aluno->_id_pl, $periodo)) {
            $periodoAtivo = 1;
        }

        $curso = sql::get('ge_cursos', 'qt_letiva, sg_letiva', ['id_curso' => $aluno->_id_curso], 'fetch');

        if (!empty($curso['qt_letiva']) && !empty($curso['sg_letiva'])) {
            $qtUnidLetiva = $curso['qt_letiva'];
            $sigla = $curso['sg_letiva'];
        } else {
            $qtUnidLetiva = 4;
            $sigla = 'B';
        }

        if (!empty($aluno->_id_curso) && !empty($aluno->_id_pl)) {
            $_id_curso = $aluno->_id_curso;
            $_id_pl = $aluno->_id_pl;
        } elseif (!empty($aluno->_outrosRegistros)) {
            $_id_curso = $aluno->_outrosRegistros[0]['id_curso'];
            //$_id_pl = $aluno->_outrosRegistros[0]['fk_id_pl'];
        }

        $histOld = new historico($id_pessoa, @$backup);
        $id_pl_ativo = gtMain::periodos(1);

        if (array_key_exists(@$_id_pl, $id_pl_ativo)) {
            if (!empty($_id_curso) && !empty($_id_pl) && empty($aluno->_situacaoFinal)) {
                $sql = "select * from aval_mf_" . $_id_curso . "_" . $_id_pl . " "
                        . " where fk_id_pessoa = $id_pessoa ";
                $query = pdoHab::getInstance()->query($sql);
                $parcx = $query->fetchAll(PDO::FETCH_ASSOC);

                foreach ($parcx as $v) {
                    $wlayout_ciclo = $v['fk_id_ciclo'];
                    foreach ($v as $kk => $vv) {
                        if (substr($kk, 0, 6) == 'media_') {
                            $parcial[$v['atual_letiva']][substr($kk, 6)] = $vv;
                        } elseif (substr($kk, 0, 6) == 'falta_') {
                            @$faltaParc[substr($kk, 6)] += $vv;
                        }
                    }
                }
            }
        }

        if ($histOld->_situacao == 1) {
            $readonly = "readonly";
        } else {
            $readonly = NULL;
        }

        $hist = (array) $histOld->_dadosAntigos;

        if (empty($hist['segmento'])) {
            $seguimento = 'ENSINO FUNDAMENTAL';
        } else {
            $seguimento = $hist['segmento'];
        }
        ?>

        <div class="fieldWhite">
            <div class="row">
                <div class="col-md-3">
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
                            <input type="hidden" name="ano" value="<?php echo $ano ?>" />
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
                <div class="col-md-2">
                    <form method="POST">
                        <input type="hidden" name="editUltAnos" value="1" />
                        <input type="hidden" name="id_pessoa" value="<?php echo $_POST['id_pessoa'] ?>" />
                        <input class="btn btn-warning" type="submit" value="Importar Anos Anteriores" />
                    </form>
                </div>
            </div>
            <!--- desabilitado -->
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
            <!-- ???!!!!-->
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
            <div style="width: 1300px; margin: 0 auto">
                <!-- desabilitado -->
                <table class="table" style="display:none">
                    <tr>
                        <td  style="width: 50px">
                            <img style="width: 150px" src="<?php echo HOME_URI ?>/views/_images/brasao.png"/>
                        </td>
                        <td>
                            <div style="font-size: 20px">
                                <?= CLI_NOME ?>
                                <br />
                                SE - Secretaria de Educação
                            </div>
                            <div>
                                <?= CLI_URL ?> E-Mail: <?= CLI_MAIL ?>
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
                <br />
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
                        <?php echo $aluno->_ra ?>-<?php echo $aluno->_ra_dig ?>
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
                    <div class="col-md-2">
                        Sexo:
                        <?php echo tool::sexo($aluno->_sexo) ?>
                    </div>
                    <div class="col-md-4">
                        R.G.:
                        <?php
                        echo intval(@$aluno->_rg) . ' - ' . @$aluno->_rgDig;
                        //$rg = intval(@$aluno->_rg);
                        /*
                          if (is_int(@$aluno->_rg)) {
                          echo intval(@$aluno->_rg) . ' - ' . @$aluno->_rgDig;
                          } else {
                          echo @$aluno->_rg . ' - ' . @$aluno->_rgDig;
                          }
                         * 
                         */
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        Cert. Nascimento ( antiga:Termo-Livro-Folha):
                        <?php echo $aluno->_certidao ?>
                    </div>
                </div>
                <br />
                <div style="border: solid #000000 2px; width: 100%; text-align: center">
                    <table style="margin: 0 auto">
                        <tr>
                            <td style="font-size: 20; font-weight: bold">
                                RESULTADO DOS ESTUDOS REALIZADOS NO&nbsp; 
                            </td>
                            <td>
                                <input style="font-size: 20; font-weight: bold; border: none; background: <?php echo $corEdit ?>; border-radius: 0"  type="text" name="dados[segmento]" value="<?php echo $seguimento ?>" />
                            </td>
                        </tr>
                    </table>
                </div>
                <br />
                <?php
                if (in_array(tool::id_inst(), [47, 21, 67])) {
                    if (in_array($wlayout_ciclo, $wciclo)) {
                        $dis1 = @$hist['disciplinas'][3];
                        $dis1[0] = 18;
                        $dis1[1] = 19;
                        $dis1[2] = 20;
                        $dis1[3] = 21;
                        $dis1[4] = 22;
                        $dis1[5] = 23;
                        $dis1[6] = 24;
                        $dis1[7] = 25;

                        @$bas[3] = count($dis1);
                        //   $linhas = 19 + $bas[3]; aki
                        $linhas = 18 + $bas[3];
                    } else {
                        // @$bas[3] = count(@$hist['disciplinas'][3]);
                        $bas[3] = 0;
                        if ($wlayout_ciclo > 6) {
                            //$linhas = 18;
                            $linhas = 25;
                        } else {
                            //$linhas = 19;
                            $linhas = 26;
                        }
                    }
                } else {
                    @$bas[3] = count(@$hist['disciplinas'][3]);
                    if ($wlayout_ciclo < 6) {
                        //$linhas = 18;
                        $linhas = 25;
                    } else {
                        $linhas = 19;
                    }
                    //$linhas = 19;
                    $linhas = 26;
                }
                ?>
                <table border=1 cellspacing=0 cellpadding=1>
                    <tr>
                        <td rowspan="<?php echo $linhas ?>" style="width: 5px; background-color: white">
                            <img style="border: none" src="<?php echo HOME_URI ?>/views/_images/hiscorico1.png"/>
                        </td>
                        <td rowspan="3" colspan="2" style="font-weight: bolder;text-align: center">
                            COMPONENTES CURRICULARES
                        </td>
                        <td colspan="5" style = "text-align: center">
                            <?php
                            if ($ano == date("Y")) {
                                ?>
                                Ano Letivo Atual (<?php echo $aluno->_codigo_classe ?>)
                                <?php
                            }
                            ?>
                        </td>
                        <td colspan="11" style = "text-align: center">
                            Programa Padrão <?php echo $seguimento ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style = "text-align: center">
                            Anos Iniciais
                        </td>
                        <td colspan="4" style="border-left: solid black 2px; text-align: center">
                            Anos Finais
                        </td>
                        <td colspan="<?php echo $qtUnidLetiva ?>" style="border-left: solid black 2px; text-align: center">
                            Notas Bimestrais
                        </td>
                        <td colspan="2" rowspan="2" style="text-align: center; border-left:solid black 2px; text-align: center">
                            Faltas Aula
                        </td>
                    </tr>
                    <tr>
                        <?php
                        for ($an = 1; $an <= 9; $an++) {
                            if (empty(@$hist['n_ciclo'][$an])) {
                                $n_ciclo = $an . 'o Ano';
                            } else {
                                @$n_ciclo = @$hist['n_ciclo'][$an];
                            }
                            ?>
                            <td  <?php echo $an == 6 ? ' style="border-left: solid black 2px" ' : '' ?>>
                                <input style="border: none; text-align: center; border-radius: 0; background-color: <?php echo $corEdit ?>" type="text" name="dados[n_ciclo][<?php echo $an ?>]" value="<?php echo @$n_ciclo ?>" />
                            </td>
                            <?php
                        }
                        for ($ctd = 1; $ctd <= $qtUnidLetiva; $ctd++) {
                            ?>
                            <td style="<?php echo $ctd == 1 ? 'border-left: solid black 2px' : '' ?>; text-align: center">
                                <?php echo $ctd . ' ' . $sigla ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    if (in_array($wlayout_ciclo, $wciclo)) {
                        $bc = [9 => 'Língua Portuguesa', 10 => 'Arte', 11 => 'Educação Física', 30 => 'Língua Inglesa', 6 => 'Matemática', 12 => 'Ciências Naturais', 13 => 'História', 14 => 'Geografia'];
                        $wcol = 8;
                    } else {
                        $bc = [9 => 'Língua Portuguesa', 10 => 'Arte', 11 => 'Educação Física', 6 => 'Matemática', 12 => 'Ciências Naturais', 13 => 'História', 14 => 'Geografia'];
                        $wcol = 7;
                    }
                    foreach ($bc as $k => $v) {
                        ?>
                        <tr>
                            <?php
                            if (@$primeiro != 1) {
                                ?>
                                <td rowspan="<?php echo $wcol ?>">
                                    <p class="textovertical">
                                        BNCC
                                    </p>
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
                                <td style="width: 4%;<?php echo $c == 6 ? 'border-left: solid black 2px' : '' ?>">
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
                                    if (empty($aluno->_situacaoFinal)) {
                                        if ($ano == date("Y") && !empty($periodoAtivo)) {
                                            /**
                                              if (empty($parcial[$c][$k])) {
                                              $nparc = str_replace('.', ',', @$hist['parcial'][$c][$k]);
                                              } else {
                                             * 
                                             */
                                            @$nparc = $parcial[$c][$k];
                                            /**
                                              }
                                             * 
                                             */
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
                                if (empty($aluno->_situacaoFinal)) {
                                    if ($ano == date("Y") && !empty($periodoAtivo)) {
                                        ?>
                                        <input  <?php echo $readonly ?> onkeyup="ctfalta()" id="falta<?php echo $contaFalta++ ?>" style="text-align: center; border: none" type="text" name="dados[falta][<?php echo $k ?>]" value="<?php echo @$faltaParc[$k] ?>" size="5" />
                                        <?php
                                    }
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
                                    <p class="textovertical">
                                        BD
                                    </p>
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
                                <td style="width: 5%;<?php echo $c == 6 ? 'border-left: solid black 2px' : '' ?>">
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
                            for ($c = 1; $c <= $qtUnidLetiva; $c++) {
                                ?>
                                <td style="width: 4%;<?php echo $c == 1 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if (empty($aluno->_situacaoFinal)) {
                                        if ($ano == date("Y") && !empty($periodoAtivo)) {
                                            @$nparc = $parcial[$c][$k];
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
                                if (empty($aluno->_situacaoFinal)) {
                                    if ($ano == date("Y") && !empty($periodoAtivo)) {
                                        ?>
                                        <input  <?php echo $readonly ?> onkeyup="ctfalta()" id="falta<?php echo $contaFalta++ ?>" style="text-align: center; border: none" type="text" name="dados[falta][<?php echo $k ?>]" value="<?php echo @$faltaParc[$k] ?>" size="5" />
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }

                    $primeiro = 0;
                    $bce = [18, 19, 20, 21, 22, 23, 24, 25];
                    //$adicional = ['nd1' => 'nd1', 'nd2' => 'nd2', 'nd3' => 'nd3', 'nd4' => 'nd4', 'nd5' => 'nd5'];
                    $adicional = ['nd1' => 'nd1', 'nd2' => 'nd2', 'nd3' => 'nd3', 'nd4' => 'nd4', 'nd5' => 'nd5', 'nd6' => 'nd6', 'nd7' => 'nd7', 'nd8' => 'nd8', 'nd9' => 'nd9', 'nd10' => 'nd10', 'nd11' => 'nd11', 'nd12' => 'nd12'];
                    if (!empty($hist['disciplinas'][3])) {
                        asort($hist['disciplinas'][3]);
                        $disc3 = array_merge($hist['disciplinas'][3], $adicional);
                    } else {
                        $disc3 = $adicional;
                    }
                    // $disc3 = $adicional;
                    if (in_array(tool::id_inst(), [47, 21, 67])) {
                        if (in_array($wlayout_ciclo, $wciclo)) {
                            $disc3[0] = 18;
                            $disc3[1] = 19;
                            $disc3[2] = 20;
                            $disc3[3] = 21;
                            $disc3[4] = 22;
                            $disc3[5] = 23;
                            $disc3[6] = 24;
                            $disc3[7] = 25;
                        }
                    }
                    foreach ($disc3 as $k => $v) {
                        ?>
                        <tr>
                            <?php
                            if ($primeiro != 1) {
                                //aki
                                ?>
                                <td rowspan="<?php echo 12 ?>"> <!-- mudei de 5 para 12  e tirei $bas[3] -->
                                    <p class="textovertical">
                                        BEC
                                    </p>
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
                                <td style="width: 5%;<?php echo $c == 6 ? 'border-left: solid black 2px' : '' ?>">
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
                            //notas bimestrais
                            for ($c = 1; $c <= $qtUnidLetiva; $c++) {
                                ?>
                                <td style="width: 4%;<?php echo $c == 1 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if (empty($aluno->_situacaoFinal)) {
                                        if ($ano == date("Y") && !empty($periodoAtivo)) {
                                            if (empty($parcial[$c][$v])) {
                                                $nparc = str_replace('.', ',', @$hist['parcial'][$c][$v]);
                                            } else {
                                                $nparc = $parcial[$c][$v];
                                            }
                                        }
                                    }
                                    ?>
                                    <input  <?php echo $readonly ?> style="text-align: center; border: none" type="text" name="dados[parcial][<?php echo $c ?>][<?php echo $v ?>]" value="<?php echo @$nparc ?>" size="5" />
                                </td>
                                <?php
                            }
                            @$falta += @$hist['falta'][$v];
                            ?>
                            <td style="width: 4%; text-align: center; border-left: solid 2px #000000">
                                <?php
                                if (empty($aluno->_situacaoFinal)) {
                                    if ($ano == date("Y") && !empty($periodoAtivo)) {
                                        ?>
                                        <input  <?php echo $readonly ?> onkeyup="ctfalta()" id="falta<?php echo $contaFalta++ ?>" style="text-align: center; border: none" type="text" name="dados[falta][<?php echo $v ?>]" value="<?php echo @$faltaParc[$v] ?>" size="5" />
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    $base = [2 => 'Carga Horária Base Nacional Comum Curricular', 1 => 'Carga Horária Base Diversificada', 3 => 'Carga Horária Base Extra Curricular', 0 => 'Carga Horária Total'];
                    for ($c = 1; $c <= 9; $c++) {
                        if (@$anoAtivo[$c] == 1) {
                            @$carga[2][$c] = 1000 - @$carga[1][$c];
                            @$carga[0][$c] = 1000 + @$carga[3][$c];
                        } else {
                            @$carga[2][$c] = NULL;
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
                                <td style="width: 3%;<?php echo $c == 6 ? 'border-left: solid black 2px' : '' ?>">
                                    <?php
                                    if (empty($hist['carga'][$k][$c])) {
                                        $ch[$k][$c] = @$carga[$k][$c];
                                    } else {
                                        $ch[$k][$c] = $hist['carga'][$k][$c];
                                    }
                                    if ($k != 0) {
                                        if ($c <= $ano) {
                                            if ($u['Texto'] == '' AND ($u[$c] != 0)) {
                                                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-';
                                            } else {
                                                ?>
                                                <input id="<?php echo $k . '-' . $c ?>" onkeyup="soma(<?php echo $c ?>)" style="text-align: center; border: none" type="text" name="dados[carga][<?php echo $k ?>][<?php echo $c ?>]" value="<?php echo $ch[$k][$c] ?>" size="5" />
                                                <?php
                                            }
                                        } else {
                                            if ($u['Texto'] == '' AND $u['Ciclo'] == $c) {
                                                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-';
                                            } else {
                                                ?>
                                                <input type = "hidden" name = "dados[carga][<?php echo $k ?>][<?php echo $c ?>]"  value="<?php echo $ch[$k][$c] ?>" />

                                                <?php
                                            }
                                        }
                                    } else {
                                        if ($c <= $ano) {
                                            if ($u['Texto'] == '' AND ($u[$c] != 0)) {
                                                ?>
                                                <input readonly id="<?php echo $c ?>"  style="text-align: center; border: none" type="text"  value="<?php echo $u[$c] ?>" size="5" /> 
                                                <?php
                                            } else {
                                                ?>
                                                <input readonly id="<?php echo $c ?>"  style="text-align: center; border: none" type="text"  value="<?php echo empty($anoAtivo[$c]) ? '' : ($ch[1][$c] + $ch[2][$c] + $ch[3][$c]) ?>" size="5" />
                                                <?php
                                            }
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
                                        if (empty($aluno->_situacaoFinal)) {
                                            ?>
                                            Total Faltas Aula
                                            <input id="ttfalta" style="text-align: center" type = "text" value = "<?php echo @$falta ?>" size = "10" readonly = "readonly" />
                                            <?php
                                        }
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
                            <div style="display:<?php echo $u['Texto'] ?>">
                                <span style="text-align: left; font-weight: bolder; color: red ">Ano 2020 e 2021:</span>
                                <p style="text-align: justify; font-weight: bolder"> “<?php echo $aluno->_sexo == 'F' ? "A aluna " : "O aluno " ?>participou da integralização da carga horária
                                    mínima do ano letivo afetado pela pandemia da Covid – 19, por
                                    meio de reprogramação curricular (2020 – 2021), conforme o
                                    disposto na LDB, Art. 23, a BNCC, a Lei no 14.040, de
                                    18/08/2020, o Parecer CNE/CP nº 15/2020, de 06/10/2020, 
                                    Deliberação CME nº 05/2020 e Deliberação CME nº 04/2021.”
                                </p>
                            </div>
                            BNCC - Base Nacional Comum Curricular, BD - Base Diversificada, BEC - Base Extra Curricular
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
                        for ($an = 1; $an <= 9; $an++) {
                            if (empty(@$hist['n_ciclo'][$an])) {
                                $anoSerie[$an] = $an . 'o Ano';
                            } else {
                                $anoSerie[$an] = @$hist['n_ciclo'][$an];
                            }
                        }

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
                                        <select id="<?php echo $c ?>" name="dados[fk_id_inst][<?php echo $c ?>]" >
                                            <option ></option>
                                            <option <?php echo is_string(@$hist['fk_id_inst'][$c]) && @$hist['fk_id_inst'][$c] != 0 ? 'selected' : '' ?> value="0" >Fora da rede</option>
                                            <?php
                                            $escolas = escolas::idInst('%|4|%', 'fk_id_tp_ens');
                                            asort($escolas);
                                            foreach ($escolas as $key => $value) {
                                                ?>
                                                <option <?php echo @$hist['fk_id_inst'][$c] == $key ? 'selected' : '' ?> value="<?php echo $key ?>" ><?php echo $value ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                        <input  <?php echo $readonly ?> name="dados[escola][<?php echo $c ?>]" id="escol<?php echo $c ?>"  style=" display: <?php echo is_numeric(@$hist['fk_id_inst'][$c]) || empty(@$hist['fk_id_inst'][$c]) ? 'none ' : '' ?>; " type="text" value="<?php echo @$hist['fk_id_inst'][$c] ?>"  />
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
                                <textarea name="dados[certificado]" style="width: 100%; height: 100px"><?php echo @$hist['id_inst'] != tool::id_inst() ? 'O(A) Diretor(a) da ' . $escola->_nome . ', de acordo com o artigo 24 - Lei Federal 9394/96 certifica que ' . $aluno->_nome . ' , R.G.: ' . $aluno->_rg . ' - ' . $aluno->_rgCompl . ', concluiu o __ Ano do Ensino Fundamental no Ano Letivo de ____, estando apt' . tool::sexoArt($aluno->_sexo) . ' ao prosseguimento dos estudos ' . ($u['Ciclo'] == 9 ? 'na 1ª Série do Ensino Médio.' : 'no __ Ano do Ensino Fundamental.') : $hist['certificado'] ?></textarea>
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
                        <input type="hidden" name="fk_id_inst" value="<?php echo tool::id_inst() ?>" />
                        <input type="hidden" name="fechado" value="<?php echo $histOld->_situacao ?>" />
                        <input type="hidden" name="editUltAnos" value="1" />
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
                                Finalizado (Clique aqui para editar o histórico)
                                <?php
                            }
                            ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <?php
    }
    ?>
</div>

<form target="_blank" id="pdf" action="<?php echo HOME_URI ?>/hist/pdf" method="POST">
    <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
    <input type="hidden" name="anociclo" value="<?php echo $anociclo ?>" />
    <input type="hidden" name="ano" value="<?php echo $ano ?>" />
</form>

<form  method="POST" id="fechado" >
    <input type="hidden" name="fk_id_inst" value="<?php echo tool::id_inst() ?>" />
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
        c4 = document.getElementById('4-' + c).value;
        document.getElementById(c).value = Number(c1) + Number(c2) + Number(c3) + Numer(c4);
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
<!-- Seta para corrigir conflito de versão -->
<script async type="text/javascript"  crossorigin="anonymous">
    $.noConflict();
    $(document).ready(function ($) {

        $("select").change(function () {

            //console.log($(this).val());
            var foraRede = $(this).val();

            var idTexto = $(this).attr('id');
            if (foraRede == 0) {
                $("#escol" + idTexto).show();
                document.getElementById("cid" + idTexto).value = "";
                document.getElementById("uf" + idTexto).value = "";
            } else {
                $("#escol" + idTexto).hide();
                document.getElementById("cid" + idTexto).value = "<?= CLI_CIDADE ?>";
                document.getElementById("uf" + idTexto).value = "<?= CLI_UF ?>";
            }

        });

    });
</script>


