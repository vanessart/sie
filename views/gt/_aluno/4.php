<?php
$aluno->vidaEscolar(NULL, tool::id_inst());
$hiddenKey = DB::hiddenKey('ge_turma_aluno', 'replace');

$cicloAno = gt_gdaeSet::cicloAno();
foreach (sql::get(['ge_ciclos', 'ge_cursos']) as $v) {
    $ciclo[$v['id_ciclo']] = $v['n_ciclo'] . ' (' . $v['n_curso'] . ')';
}
?>
<br /><br /><br />
<div class="panel panel-default">
    <div class="panel panel-heading">
        Vida escolar do(a) aluno(a) <?php echo $aluno->_nome . ', RSE:' . @$id_pessoa ?>
    </div>
    <div class="panel panel-body">
        <div class="row">
            <div class="col-md-5">
                <?php
                if (!empty($aluno->_escola)) {
                    ?>
                    <div class="alert alert-info">
                        <table style="width: 100%">
                            <tr>
                                <td>
                                    <div style="text-align: center; font-size: 18px">
                                        <?php echo $aluno->_periodo_letivo; ?>
                                    </div>
                                    <br /><br />
                                    Escola:
                                    <?php
                                    echo $aluno->_escola;
                                    ?>
                                    <br /><br />
                                    <?php
                                    if (empty($aluno->_situacaoFinal)) {
                                        ?>
                                        Situação:
                                        <?php
                                        echo @$aluno->_situacao;
                                    } else {
                                        ?>
                                        Situação Final:
                                        <?php
                                        echo @$aluno->_situacaoFinal;
                                    }
                                    ?>
                                    <br /><br />
                                    D. Nasc.:
                                    <?php echo @data::converteBr($aluno->_nasc) ?>
                                    <br /><br />
                                    Classe: 
                                    <?php echo @$aluno->_nome_classe ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php
                                    if ($aluno->_id_inst == tool::id_inst()) {
                                        ?>
                                        <button onclick="document.getElementById('turma').submit()" class="art-button">
                                            <?php echo $aluno->_codigo_classe ?>
                                        </button>
                                        <?php
                                    } else {
                                        echo $aluno->_codigo_classe;
                                    }
                                    ?>
                                    <br /><br />
                                    N. Chamada: 
                                    <?php echo @$aluno->_chamada ?>

                                    <br /><br />
                                    <form method="POST">
                                        <table style="width: 100%">
                                            <tr>
                                                <td style="white-space: nowrap">
                                                    Matrícula Início:
                                                </td>
                                                <td>
                                                    <input type="text" name="1[dt_matricula]" value="<?php echo @$aluno->_dt_matricula ?>" />
                                                </td>
                                            </tr>
                                            <?php
                                            if (@$aluno->_situacao != 'Frequente') {
                                                ?>
                                                <tr>
                                                    <td style="white-space: nowrap">
                                                        Matrícula Fim:
                                                    </td>
                                                    <td>
                                                        <input type="text" name="1[dt_transferencia]" value="<?php echo @$aluno->_dt_transferencia ?>" />
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                        <br /><br />
                                        <div style="text-align: center">
                                            <?php echo formulario::hidden(['id_pessoa' => $id_pessoa, '1[id_turma_aluno]' => @$aluno->_id_turma_aluno]) ?>
                                            <input type="hidden" name="activeNav" value="4" />
                                            <?php echo DB::hiddenKey('ge_turma_aluno', 'replace') ?>
                                            <input type="submit" value="Atualizar" />
                                        </div>
                                    </form>
                                    <br />
                                    <?php
                                    if ($aluno->_situacao <> "Frequente") {
                                        ?>
                                        Data de Transferência: 

                                        <?php echo @$aluno->_dt_transferencia ?>
                                        <br />
                                        <?php
                                    }
                                    if (!empty($aluno->_origem_escola)) {
                                        ?>
                                        Escola de Origem: 
                                        <?php echo @$aluno->_origem_escola ?>
                                        <br /><br />
                                        <?php
                                    }
                                    if (!empty($aluno->_destino_escola)) {
                                        ?>
                                        Escola de Destino: 
                                        <?php echo @$aluno->_destino_escola ?>
                                        <br /><br />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="width: 200px">

                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                if (NULL) {
//               if (!empty($aluno->_outrosRegistros)) {

                    foreach ($aluno->_outrosRegistros as $classeAluno) {
                        ?>
                        <div class="alert alert-warning">
                            <div style="text-align: center; font-size: 18px">
                                <?php echo $classeAluno['periodo_letivo']; ?>
                            </div>
                            <br /><br />
                            Escola:
                            <?php
                            echo $classeAluno['n_inst'];
                            ?>
                            <br /><br />
                            <?php
                            if (empty($classeAluno['situacao_final'])) {
                                ?>
                                Situação:
                                <?php
                                echo @$classeAluno['situacao'];
                            } else {
                                ?>
                                Situação Final:
                                <?php
                                echo @$classeAluno['n_sf'];
                            }
                            ?>
                            <br /><br />
                            D. Nasc.:
                            <?php echo @data::converteBr($aluno->_nasc) ?>
                            <br /><br />
                            Classe: 
                            <?php echo @$classeAluno['n_turma'] ?>
                            <br /><br />
                            N. Chamada: 
                            <?php echo @$classeAluno['chamada'] ?>
                            <br /><br />
                            Data de Matrícula: 
                            <?php echo data::converteBr($classeAluno['dt_matricula']) ?>
                            <br />
                            <?php
                            if (!empty($aluno->_dt_transferencia)) {
                                ?>
                                Data de Transferência: 
                                <?php echo $classeAluno['dt_transferencia'] <> '0000-00-00' ? data::converteBr($classeAluno['dt_transferencia']) : NULL; ?>
                                <br />
                                <?php
                            }
                            if (!empty($classeAluno['origem_escola'])) {
                                ?>
                                Escola de Origem: 
                                <?php echo @$classeAluno['origem_escola'] ?>
                                <br /><br />
                                <?php
                            }
                            if (!empty($classeAluno['destino_escola'])) {
                                ?>
                                Escola de Destino: 
                                <?php echo @$classeAluno['destino_escola'] ?>
                                <br /><br />
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
                <form id="turma" action="<?php echo HOME_URI . '/gt/gerirclasse' ?>" method="POST">
                    <input type="hidden" name="id_turma" value="<?php echo $aluno->_id_turma ?>" />
                </form>
                <div style="text-align: center;">
                    <?php
                    if (false) {
                        ?>
                        <form method="POST">
                            <input type="hidden" name="activeNav" value="4" />
                            <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
                            <input class="btn btn-info" type="submit" name="maisAnos" value="Atualizar (Anos Anteriores)" />
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <br /><br />
                <?php
                if (!empty($_POST['maisAnos'])) {
                    $gdae = new gdae;
                    $anos = $gdae->ConsultarMatriculasRA(@$aluno->_ra, @$aluno->_ra_dig, @$aluno->_ra_uf);
                    if (!empty($anos['Mensagens']->ConsultarMatricRA)) {
                        $sql = "DELETE FROM `gt_anos_anteriores` WHERE `fk_id_pessoa` = $id_pessoa";
                        $query = $model->db->query($sql);
                        foreach ($anos['Mensagens']->ConsultarMatricRA as $k => $v) {
                            @$v = (array) $v;
                            @$ins['fk_id_pessoa'] = $id_pessoa;
                            @$ins['ano'] = $v['anoLetivo'];
                            @$ins['cie'] = @$v['codigoEscola'];
                            @$ins['matricula'] = @$v['dataMatricula'];
                            @$ins['chamada'] = @$v['numero'];
                            @$ins['anoSerie'] = $ciclo[$cicloAno[$v['tipoEnsino']][$v['serie']]] . ' ' . @$v['turma'];
                            @$ins['situacao'] = @$v['situacaoMatricula'];

                            $model->db->ireplace('ge_anos_anteriores', $ins, 1);
                        }
                    }
                }
                $sql = "SELECT "
                        . " i.n_inst, aa.`ano`,aa.`cie`, aa.`matricula`, "
                        . " aa. `chamada`,aa.`anoSerie`, aa.`situacao` "
                        . " FROM ge_anos_anteriores aa "
                        . " LEFT JOIN ge_escolas e on e.cie_escola = aa.cie "
                        . " LEFT JOIN instancia i on i.id_inst = e.fk_id_inst "
                        . " WHERE `fk_id_pessoa` = $id_pessoa "
                        . " ORDER BY aa.ano DESC ";
                $query = $model->db->query($sql);
                $at = $query->fetchAll();
                if (!empty($at)) {
                    foreach ($at as $v) {
                        $v = (array) $v;
                        ?>
                        <div class="alert alert-warning">
                            <div style="text-align: center; font-size: 20px">
                                <?php echo $v['ano'] ?>
                            </div>
                            <style>
                                td{
                                    padding: 5px;
                                }
                            </style>
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        Escola (CIE)
                                    </td>
                                    <td>
                                        <?php echo (!empty($v['n_inst']) ? $v['n_inst'] . ' - ' : NULL) . @$v['cie'] ?>
                                    </td>
                                </tr> 
                                <!--
                                <tr>
                                    <td>
                                        Dt. Matrícula
                                    </td>
                                    <td>
                                <?php echo @$v['matricula'] ?>
                                    </td>
                                </tr> 
                                -->
                                <tr>
                                    <td>
                                        Nº de Chamada
                                    </td>
                                    <td>
                                        <?php echo @$v['chamada'] ?>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        Ano/Série
                                    </td>
                                    <td>
                                        <?php echo @$v['anoSerie'] ?>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        Situação (código SED)
                                    </td>
                                    <td>
                                        <?php echo @$v['situacao'] ?>
                                    </td>
                                </tr> 
                            </table>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="col-md-2 text-center" style="font-size: 20px; padding-left: 30px; padding-right: 30px">
                <br />
                <div style="display: none">   
                    <form action="<?php echo HOME_URI ?>/hist/histpessoa" method="POST">
                        <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
                        <input style="width: 100%; font-weight: 900" class="btn btn-success" type="submit" value="Historico" />
                    </form>
                </div>
                <br />
                <div>
                    <?php
                    if (@$aluno->_id_curso == 1) {
                        if (substr(@$aluno->_nome_classe, 0, 1) <= 5) {
                            $tano = 15;
                        } else {
                            $tano = 69;
                        }
                        $apd = $model->pegaalunoapdid($id_pessoa);
                        if ($apd == 'Sim') {
                            $tano = 'apd';
                        }
                        ?>
                        <form target="_blank" action="<?php echo BASE_URL_HAB ?>/aval/pdfboletim" method="POST">
                            <input type="hidden" name="t" value="<?php echo $tano ?>" />
                            <input type="hidden" name="id_turma" value="<?php echo @$aluno->_id_turma ?>" />
                            <input type="hidden" name="id_pessoa" value="<?php echo @$aluno->_rse ?>" />
                            <input type="hidden" name="id_inst" value="<?php echo @$aluno->_id_inst ?>" />
                            <input style="width: 100%; font-weight: 900" class="btn btn-success" type="submit" value="Boletim" />
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div> 
            <div class="col-md-2 text-center">

            </div>
            <div class="col-md-3 text-center" style="font-size: 20px">

                <?php
                if (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".jpg")) {
                    ?>
                    <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $id_pessoa ?>.jpg?ass=<?php echo uniqid() ?>" width="199.2" alt="foto"/>
                    <?php
                }
                ?>
            </div>
        </div>

    </div>
    <br />

</div>
