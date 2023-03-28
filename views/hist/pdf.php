<?php
ob_start();
?>
<head>
    <style>
        .textovertical{
            width:1px;
            word-wrap: break-word;
            white-space: pre-wrap;
            padding: 5px;
        }
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: left;

            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            border: 1px solid;
        }
        .topo2{
            font-size: 7pt;
            font-weight: bolder;
            text-align: center;
            padding: 1px;
            border: 1px solid;
        }
        .topo3{
            font-size: 12pt;
            font-weight: bolder;
            text-align: center;
            padding: 5px;
            border: 1px solid;

        }
        .nota{
            width: 30px;
            text-align: center;
            padding: 2px;
        }
        .ano{
            width: 30px;
            text-align: center;
            padding: 2px;
        }       
    </style>
</head>

<?php
$wciclo = [6, 7, 8, 9];
$pandemiaC = [2020 => 820, 2021 => 862];
$ciclo_ano = filter_input(INPUT_POST, 'cicloano', FILTER_SANITIZE_STRING);

if (is_numeric($_POST['id_pessoa'])) {
    $d = disciplina::disc();

    foreach ($d as $dd) {
        /*
          if ($dd['sg_area'] == 'CI') {

          } else {
          $disciplinas[$dd['id_disc']] = $dd['n_disc'];
          }

         * 
         */
        $disciplinas[$dd['id_disc']] = $dd['n_disc'];
    }
    $ano = @$_POST['ano'];
    $id_pessoa = $_POST['id_pessoa'];

    $escola = new escola();
    $aluno = new aluno($id_pessoa);

    $aluno->vidaEscolar(NULL);
    if (!empty($aluno->_id_curso) && !empty($aluno->_id_pl)) {
        $_id_curso = $aluno->_id_curso;
        $_id_pl = $aluno->_id_pl;
    } elseif (!empty($aluno->_outrosRegistros)) {
        $_id_curso = $aluno->_outrosRegistros[0]['id_curso'];
        //$_id_pl = $aluno->_outrosRegistros[0]['fk_id_pl'];
    }
    $idPlSit = sql::get('ge_periodo_letivo', 'id_pl', ['at_pl' => 1]);
    $idPlSit = array_column($idPlSit, 'id_pl');

    if (!empty($_id_curso) && !empty($_id_pl)) {
        if (in_array(@$_id_pl, $idPlSit)) {
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
    $histOld = new historico($id_pessoa);
    $hist = (array) $histOld->_dadosAntigos;

    // $bk_ano = $hist['serieAno'][$_POST['ano']];
    //$u = $model->pegaultimoano($id_pessoa . $bk_ano);
    if ($ano_ciclo) {
        $u = $model->pegaultimoano($id_pessoa, $ano_ciclo);
    } else {
        $u = $model->pegaultimoano($id_pessoa);
    }

    $wlayout_ciclo = $u['Ciclo'];

    if (empty($hist['segmento'])) {
        $seguimento = 'ENSINO FUNDAMENTAL';
    } else {
        $seguimento = $hist['segmento'];
    }
    $curso = sql::get('ge_cursos', 'qt_letiva, sg_letiva', ['id_curso' => $aluno->_id_curso], 'fetch');
    if (!empty($curso['qt_letiva']) && !empty($curso['sg_letiva'])) {
        $qtUnidLetiva = $curso['qt_letiva'];
        $sigla = $curso['sg_letiva'];
    } else {
        $qtUnidLetiva = 4;
        $sigla = 'B';
    }
    ?>
    <div>
        <div class="fieldBody">
            <div style="border: 1px solid" >
                <table>
                    <tr>
                        <td  style="width: 50px; padding-left: 5px">
                            <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.jpg"/>
                        </td>
                        <td style="padding-top: 5px; width: 579px; text-align: center">
                            <div style="font-size: 22px; font-weight: bold">
                                <?= CLI_NOME ?>
                                <br />
                                SE - Secretaria de Educação
                            </div>
                            <div style="font-size: 18px">
                                <?php echo $escola->_nome ?>
                            </div>
                            <div style="font-size: 12px">
                                <?php echo $escola->enderecoEstruturado(1) ?>
                            </div>
                            <div style="font-size: 12px">
                                Ato de Criação:
                                <?php echo $escola->_ato_cria ?>
                            </div>
                            <div style="font-size: 12px; padding-bottom: 5px">
                                Ato de Municipalização:
                                <?php echo $escola->_ato_municipa ?>
                            </div>
                        </td>
                        <td style="width: 50px; padding-right: 5px">
                            <?php
                            if (file_exists(ABSPATH . '/pub/fotos/' . $id_pessoa . '.jpg')) {
                                ?>
                                <!--
                                    <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $id_pessoa; ?>.jpg" width="100" height="110" />
                                -->
                                <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="100" height="110" alt="foto"/>

                                <?php
                            } else {
                                ?>
                                <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="100" height="110" alt="foto"/>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <?php
            if ($histOld->_situacao == 0) {
                ?>
                <div style="color: red">
                    HISTÓRICO MODELO
                </div>
                <?php
            } else {
                ?>
                <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center; width: 679px">
                    HISTÓRICO ESCOLAR
                </div>
                <?php
            }
            ?>

            <table>
                <tr>
                    <td style="width: 679px" class="topo" colspan="4">
                        Nome d<?php echo tool::sexoArt($aluno->_sexo) ?> Alun<?php echo tool::sexoArt($aluno->_sexo) ?>:
                        <b><?php echo $aluno->_nome ?></b>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px" class="topo">
                        R.S.E. nº. 
                        <?php echo $_POST['id_pessoa'] ?>
                    </td>
                    <td style="width: 100px" class="topo">
                        R.A. nº
                        <?php echo $aluno->_ra ?>-<?php echo $aluno->_ra_dig ?>
                    </td>
                    <td style="width: 100px" class="topo">

                        <?php
                        if (@$aluno->_nascUf == "EX") {
                            if (!empty($aluno->_rgDig)) {
                                echo @$aluno->_rg . ' - ' . @$aluno->_rgDig;
                            } else {
                                echo @$aluno->_rg;
                            }
                        } else {
                            ?>
                            R.G. nº.
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
                        }
                        ?>
                    </td>
                    <td style="width: 100px" class="topo">
                        Dt. Nascimento:
                        <?php echo data::converteBr($aluno->_nasc) ?>
                    </td>                
                </tr>
                <tr>
                    <td style="width: 100px" class="topo">                     
                        Sexo:
                        <?php echo tool::sexo($aluno->_sexo) ?>
                    </td>
                    <td style="width: 67px" class="topo" colspan="2">                     
                        Cidade/U.F. Nasc.:
                        <?php echo $aluno->_nascCidade . " - " . $aluno->_nascUf ?>
                    </td>  
                    <td style="width: 33px" class="topo">                     
                        Nacionalidade:
                        <?php echo $aluno->_nascionalidade ?>                                         
                    </td>
                </tr>
                <tr>
                    <td class="topo" colspan="4">
                        Cert. Nascimento (antiga:Termo-Livro-Folha):
                        <?php echo $aluno->_certidao ?>

                    </td>
                </tr>
            </table>

            <?php
            @$bas[3] = count(@$hist['disciplinas'][3]);

            if ($wlayout_ciclo < 6) {
               $linhas = 13 + @$bas[3];
               //  $linhas = 13;
            } else {
             $linhas = 14 + @$bas[3];
              // $linhas = 14;
            }

            /*
             * 
             * Desativei escolas integrais correção na linha 60 ...
              //$linhas = $linhas + $bas[3];
              if (in_array(tool::id_inst(), [47, 21, 67])) {
              $integral = [18, 19, 20, 21, 22, 23, 24, 25];
              $li = 1;
              foreach ($integral as $v) {
              for ($x = 6; $x <= 9; $x++) {
              $l[$v] = 0;
              if (empty($hist['disciplinas']['discNota'][$x][$v])) {
              $l[$v] = 1;
              }
              }
              if ($l[$v] == 1) {
              //  $linhas = $linhas - 1;
              }
              }
              if ($wlayout_ciclo < 6) {
              //   $linhas = 13 + @$bas[3];
              $linhas = 13;
              } else {
              //  $linhas = 14 + @$bas[3];
              $linhas = 14;
              }
              }

             * 
             */
            ?>

            <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center; width: 679px">
                RESULTADO DOS ESTUDOS REALIZADOS NO <?php echo $seguimento ?>
            </div>   
            <table>
                <tr>             
                    <td style="border: 1px solid; width: 5px" rowspan="<?php echo $linhas ?>">
                        <img src="<?php echo HOME_URI ?>/views/_images/hiscorico1.jpg"/>
                    </td>
                    <td style="border: 1px solid; text-align: center; width: 80px" rowspan="3" colspan="2">
                        <b>COMPONENTES CURRICULARES</b>
                    </td>
                    <td style="border: 1px solid; text-align: center; width: 594px" colspan="14">
                        <b>Programa Padrão <?php echo $seguimento ?></b>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid; text-align: center; width: 230px" colspan="5">
                        <b>Anos Iniciais</b>
                    </td>
                    <td style="border: 1px solid; text-align: center; width: 139px" colspan="4">
                        <b>Anos Finais</b>
                    </td>
                    <td style="border: 1px solid; text-align: center; font-weight: 5px bolder; width: 230px" colspan="<?php echo $qtUnidLetiva + 1 ?>">
                        <?php
                        if (!empty($aluno->_codigo_classe) && substr($aluno->_periodo_letivo, -4) == date("Y")) {
                            ?>
                            <span>
                                <b>                 
                                    Ano Letivo Atual<br />
                                    <?php
                                    echo $aluno->_nome_classe . ' - ' . $aluno->_periodo_letivo;
                                    ?>
                                </b>
                            </span>
                            <?php
                        }
                        ?>
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
                        <td class="topo2">
                            <?php echo @$n_ciclo ?>
                        </td>
                        <?php
                    }
                    for ($ctd = 1; $ctd <= $qtUnidLetiva; $ctd++) {
                        ?>
                        <td  class="topo2" style="text-align: center">
                            <?php echo $ctd . ' ' . $sigla ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td class="topo2">
                        Faltas Aula
                    </td>
                </tr>
                <?php
                //$bc = [9 => 'Língua Portuguesa', 10 => 'Arte', 11 => 'Educação Física', 6 => 'Matemática', 12 => 'Ciências Naturais', 13 => 'História', 14 => 'Geografia'];
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
                            <td class="topo3" rowspan= "<?php echo $wcol ?>">
                                BNCC
                            </td>
                            <?php
                            $primeiro = 1;
                        }
                        ?>
                        <td class="topo3" style="text-align: left; width: 300px">
                            <?php echo $v ?>
                        </td>
                        <?php
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                            <td class="topo3">
                                <?php
                                if ($c <= $ano) {
                                    if (!empty($hist['discNota'][$c][$k])) {
                                        $anoAtivo[$c] = 1;
                                    }
                                    echo empty($hist['discNota'][$c][$k]) ? '---' : str_replace('.', ',', $hist['discNota'][$c][$k]);
                                } else {
                                    echo '---';
                                }
                                ?>
                            </td>
                            <?php
                        }
                        //notas bimestrais

                        for ($c = 1; $c <= $qtUnidLetiva; $c++) {
                            ?>
                            <td class="topo3">
                                <?php
                                if (empty($aluno->_situacaoFinal)) {
                                    if ($ano == date("Y")) {
                                        ?>
                                        <?php echo!empty($hist['parcial'][$c][$k]) ? $hist['parcial'][$c][$k] : (!empty($parcial[$c][$k]) ? $parcial[$c][$k] : '---' ) ?>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td class="topo3">
                            <?php
                            if (empty($aluno->_situacaoFinal)) {
                                if ($ano == date("Y")) {
                                    echo $ft = !empty($hist['falta'][$v]) ? $hist['falta'][$v] : (!empty(@$faltaParc[$k]) ? @$faltaParc[$k] : '---');
                                    @$falta += is_numeric($ft) ? $ft : 0;
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                @$primeiro = 0;
                $bd = [15 => 'L.E.Inglês', 16 => 'Introdução à Filosofia', 17 => 'Música'];
                foreach ($bd as $k => $v) {
                    ?>
                    <tr>
                        <?php
                        if (@$primeiro != 1) {
                            ?>
                            <td class="topo3" rowspan="3">
                                BD
                            </td>
                            <?php
                            $primeiro = 1;
                        }
                        ?>
                        <td class="topo3" style="text-align: left">
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
                            <td class="topo3">
                                <?php
                                if ($c <= $ano) {
                                    if (!empty($hist['discNota'][$c][$k])) {
                                        $anoAtivo[$c] = 1;
                                    }
                                    echo empty($hist['discNota'][$c][$k]) ? '---' : str_replace('.', ',', $hist['discNota'][$c][$k]);
                                } else {
                                    echo '---';
                                }
                                ?>
                            </td>
                            <?php
                        }
                        //notas bimestrais
                        for ($c = 1; $c <= $qtUnidLetiva; $c++) {
                            ?>
                            <td class="topo3">
                                <?php
                                if (empty($aluno->_situacaoFinal)) {
                                    if ($ano == date("Y")) {
                                        ?>
                                        <?php echo!empty($hist['parcial'][$c][$k]) ? $hist['parcial'][$c][$k] : (!empty($parcial[$c][$k]) ? $parcial[$c][$k] : '---' ) ?>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td class="topo3">
                            <?php
                            if (empty($aluno->_situacaoFinal)) {
                                if ($ano == date("Y")) {
                                    echo $ft = !empty($hist['falta'][$v]) ? $hist['falta'][$v] : (!empty(@$faltaParc[$k]) ? @$faltaParc[$k] : '---');

                                    @$falta += is_numeric($ft) ? $ft : 0;
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                $primeiro = 0;
                $bce = [18, 19, 20, 21, 22, 23, 24, 25];
                // $ad = ['nd1' => 'nd1', 'nd2' => 'nd2', 'nd3' => 'nd3', 'nd4' => 'nd4', 'nd5' => 'nd5'];
                $ad = ['nd1' => 'nd1', 'nd2' => 'nd2', 'nd3' => 'nd3', 'nd4' => 'nd4', 'nd5' => 'nd5', 'nd6' => 'nd6', 'nd7' => 'nd7', 'nd8' => 'nd8', 'nd9' => 'nd9', 'nd10' => 'nd10', 'nd11' => 'nd11', 'nd12' => 'nd12'];
                if (!empty($hist['disciplinas'][3])) {
                    ###########################################################gambiarra#######################################
                    foreach ($hist['disciplinas'][3] as $k => $v) {
                        for ($c = 1; $c <= 9; $c++) {
                            if (!empty($hist['discNota'][$c][$v])) {
                                $temBEC = 1;
                            }
                        }
                    }
                    ####################################################################################################################   


                    if (!empty($temBEC)) {
                        foreach ($hist['disciplinas'][3] as $k => $v) {
                            ?>
                            <tr>
                                <?php
                                if ($primeiro != 1) {
                                    ?>
                                    <td class="topo3" rowspan="<?php echo $bas[3] ?>">
                                        BEC
                                    </td>
                                    <?php
                                    $primeiro = 1;
                                }
                                ?>
                                <td class="topo3" style="text-align: left">
                                    <?php
                                    if (is_numeric($k)) {
                                        echo $disciplinas[$k];
                                    } else {
                                        echo (in_array($k, $ad)) ? $v : $k;
                                        //  echo $hist['disciplinas'][3]['nd1'];
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
                                    <td class="topo3">
                                        <?php
                                        if ($c <= $ano) {
                                            if (!empty($hist['discNota'][$c][$v])) {
                                                $anoAtivo[$c] = 1;
                                            }
                                            echo empty($hist['discNota'][$c][$v]) ? '---' : str_replace('.', ',', @$hist['discNota'][$c][$v]);
                                        } else {
                                            echo '---';
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                                //notas bimestrais

                                for ($c = 1; $c <= $qtUnidLetiva; $c++) {
                                    ?>
                                    <td class="topo3"> 
                                        <?php
                                        if (empty($aluno->_situacaoFinal)) {
                                            if ($ano == date("Y")) {
                                                ?>
                                                <?php echo!empty($hist['parcial'][$c][$k]) ? $hist['parcial'][$c][$k] : (!empty($parcial[$c][$k]) ? $parcial[$c][$k] : '---' ) ?>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                                ?>
                                <td class="topo3">
                                    <?php
                                    if (empty($aluno->_situacaoFinal)) {
                                        if ($ano == date("Y")) {
                                            echo $ft = !empty($hist['falta'][$v]) ? $hist['falta'][$v] : (!empty(@$faltaParc[$k]) ? @$faltaParc[$k] : '---');
                                            @$falta += is_numeric($ft) ? $ft : 0;
                                        }
                                    }
                                    ?>
                                </td>   
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                <?php
                $base = [2 => 'Base Nacional Comum Curricular', 1 => 'Base Diversificada', 3 => 'Base Extra Curricular', 0 => 'Carga Horária Total'];
                for ($c = 1; $c <= 9; $c++) {
                    @$carga[2][$c] = 1000 - @$carga[1][$c];
                    @$carga[0][$c] = 1000 + @$carga[3][$c];
                }
                ?>
                <tr>
                    <td style="font-weight:bold; font-size:14pt; background-color: #000000; color:#ffffff; text-align: center" colspan="17">
                        CARGA HORÁRIA
                    </td>     
                </tr>    
                <?php
                foreach ($base as $k => $b) {
                    ?>
                    <tr>
                        <td class="topo3" colspan="3" style="text-align: left">
                            <?php echo $b ?>
                        </td>
                        <?php
                        for ($c = 1; $c <= 9; $c++) {
                            ?>
                            <td class="topo3">
                                <?php
                                if ($u['Texto'] == '' AND ( $u[$c] != 0)) {
                                    if ($c <= $ano) {
                                        if ($k == 0) {
                                            echo $u[$c];
                                        } else {
                                            echo '-';
                                        }
                                    } else {
                                        echo '-';
                                    }
                                } else {
                                    if ($c <= $ano) {
                                        if (!empty($hist['discNota'][$c][$v])) {
                                            $anoAtivo[$c] = 1;
                                        }
                                        if ($k == 0) {
                                            echo @$cg[$c] <> 0 ? @$cg[$c] : '---';
                                        } else {
                                            echo @$hist['carga'][$k][$c];
                                            @$cg[$c] += @$hist['carga'][$k][$c];
                                        }
                                    } else {
                                        echo '---';
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }
                        if ($k == 2) {
                            ?>
                            <td class="topo3" colspan="5" rowspan="4">
                                <?php
                                if ($k == 2) {
                                    if (!empty($aluno->_codigo_classe)) {
                                        if (substr($aluno->_codigo_classe, 3, 1) <= 5) {
                                            ?>
                                            Falta dia: 
                                            <?php echo @$hist['faltaDia'];
                                            ?>
                                            <br /> <br />
                                            <?php
                                        }
                                        if (empty($aluno->_situacaoFinal)) {
                                            if (!empty($falta)) {
                                                ?>
                                                Faltas Aula: 
                                                <?php
                                                echo @$falta;
                                            }
                                        }
                                    }
                                    ?> 
                                    <br />
                                    <?php
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
                <tr>
                    <td colspan="17" style="text-align: left; font-size: 11pt;padding: 5px; border: 1px solid">
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
                        ENSINO RELIGIOSO E ORIENTAÇÃO DE ESTUDOS (DELIBERAÇÃO CME 05/2001) - 1o ANO - LEI 11274/2006
                    </td>
                </tr>
            </table>
            <div style="text-align: right; color: red; font-size: 10px">
                Vide verso
            </div>
            <div style="page-break-after: always"></div>

            <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                ESTUDOS REALIZADOS NO ENSINO FUNDAMENTAL
            </div>

            <table class="ano" border="1" style="width: 100%">
                <thead>
                    <tr>
                        <td style="width: 12%" class="topo2">
                            Série/Ano
                        </td>
                        <td style="width: 7%" class="topo2">
                            Regime
                        </td>
                        <td style="width: 7%" class="topo2">
                            Ano
                        </td>
                        <td style="width: 40%" class="topo2">
                            Estabelecimento
                        </td>
                        <td style="width: 26%" class="topo2">
                            Município
                        </td>
                        <td style="width: 8%" class="topo2">
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
                        if ($c <= $ano && @$anoAtivo[$c] == 1) {
                            ?>
                            <tr>
                                <td class="topo2">
                                    <?php echo empty($anoSerie[$c]) ? '---' : $anoSerie[$c] ?>
                                </td>
                                <td class="topo2">
                                    <?php echo empty(@$hist['regime'][$c]) ? '---' : @$hist['regime'][$c] ?>
                                </td>
                                <td class="topo2">
                                    <?php echo empty(@$hist['serieAno'][$c]) ? '---' : @$hist['serieAno'][$c] ?>
                                </td>
                                <td class="topo2">
                                    <?php echo empty(@$hist['escola'][$c]) ? '----------------' : @$hist['escola'][$c] ?>
                                </td>
                                <td class="topo2">
                                    <?php echo empty(@$hist['cidade'][$c]) ? '---' : @$hist['cidade'][$c] ?>
                                </td>
                                <td class="topo2">
                                    <?php echo empty(@$hist['uf'][$c]) ? '---' : @$hist['uf'][$c] ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <br />
            <table class="ano" border="1" style="width: 100%">
                <thead>
                    <tr>
                        <td style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                            OBSERVAÇÕES
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="topo2" style=" text-align: justify"> 
                            <?php echo @$hist['obs'] ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
            <table class="ano" border="1" style="width: 100%">
                <thead>
                    <tr>
                        <td style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                            CERTIFICADO
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="topo2" style=" text-align: justify"> 
                            <?php echo @$hist['certificado'] ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
            <div style="text-align: right; font-size: 10px">
                <?= CLI_CIDADE ?>, <?php echo date("d") ?> de <?php echo data::mes(date('m')) ?> de <?php echo date("Y") ?>
            </div>
            <br /><br /><br />
            <div style="float: left; width: 50%; text-align: center; font-size:10px">
                ______________________________________ 
                <br />
                Secretário(a) da Escola RG(Carimbo)
            </div>
            <div style="float: left; width: 50%; text-align: center; font-size:10px">
                __________________________ 
                <br />
                Diretor(a) RG(Carimbo)
            </div>
            <?php
            if ($histOld->_situacao == 1) {
                $token = sql::get('hist_', 'token', ['id_pessoa' => $aluno->_rse], 'fetch')['token'];
                ?>
                <br /><br />
                <table class="ano" style="width: 100%">
                    <tr>
                        <td style="text-align: center">
                            Verificação de autenticidade disponível no endereço eletrônico http://educacao.barueri.sp.gov.br,
                            através do código: 
                            <br /><br />
                            004<?php echo $token . $aluno->_rse ?>
                            <br /><br />
                            Última Atualização: 
                            <?php echo data::converteBr(sql::get('hist_', 'dt_hist', ['id_pessoa' => $aluno->_rse], 'fetch')['dt_hist']) ?>

                        </td>
                        <td style="text-align: right">
                            <?php
                            $code = HOME_URI . '/app/code/php/qr_img.php?d=https://portal.educ.net.br/ge/pub/rastro?rastro=' . '004' . $token . $aluno->_rse . '&.PNG';
                            ?>
                            <img src = "<?php echo $code ?>"/>
                        </td>
                    </tr>
                </table>
                <?php
            }
            ?>
        </div>
        <div style="font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
            Obs. Este documento não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
        </div>
    </div>
    <?php
}
tool::pdfSimpleSemRodape();
