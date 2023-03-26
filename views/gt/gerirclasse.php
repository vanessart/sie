<?php
$id_turma = @$_POST['id_turma'];
if (!empty($id_turma)) {
    $turma = $model->turma($id_turma);

    $dataAtualizacao = data::porExtenso(substr($turma['dt_gdae'], 0, 10));

    if (!empty($dataAtualizacao)) {
        $atualizacao = "Última Atualização em " . $dataAtualizacao . " as " . substr($turma['dt_gdae'], 11) . " horas ($id_turma)";
    } else {
        $atualizacao = "Classe Não Sincronizada ($id_turma)";
    }
}

$id_pl = gtMain::periodoSet(@$_POST['periodoLetivo']);

$turmas = gtTurmas::idNome(tool::id_inst(), $id_pl);

$periodos = gtMain::periodosPorSituacao();

$periodosAtivos = gtMain::periodos(1);
$periodosAtivos1 = gtMain::periodos(2);
if (!empty($periodosAtivos1)) {
    $periodosAtivos = $periodosAtivos + $periodosAtivos1;
}
?>

<br /><br />
<div class="Body">
    <div style="text-align: center; font-size: 16px; font-weight: bold">
        <?php echo @$atualizacao ?>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-3">
            <?php
            echo formulario::select('periodoLetivo', $periodos, 'Período Letivo', @$id_pl, 1);
            ?>
        </div>
        <div class="col-sm-3" style="text-align: center; font-size: 18px">
            <?php
            if (!empty($turmas)) {
                echo formulario::select('id_turma', $turmas, 'Classe', @$_POST['id_turma'], 1, ['periodoLetivo' => $id_pl]);
            }
            ?>

        </div>
        <div class="col-sm-3" style="text-align: center; font-size: 18px">
            <?php
            if (!empty($id_turma && array_key_exists($id_pl, $periodosAtivos))) {
                ?>
                <form method="POST">
                    <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                    <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
                    <input type="hidden" name="atualizarClasseGdae" value="1" />
                    <input class="btn btn-primary"  type="submit" value="Sincronizar Alunos" />
                </form>
                <?php
            }
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($_POST['sincronizar'])) {
        $classeProdesp = gt_gdaeSet::turma($turma['prodesp']);
        if (is_array($classeProdesp)) {
            $siebComRaId = gt_gdaeSet::alunosComIdPessoa($classeProdesp)
            ?>
            <form method="POST">
                <div style="text-align: center">
                    <?php echo DB::hiddenKey('sincronizarTodosAlunos') ?>
                    <input type="hidden" name="sincronizarTodosAlunos" value="1" />
                    <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                    <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
                </div>
                <br /><br />
                <table class="table" >
                    <tr>
                        <td colspan="3" style="background-color: #E2F7CA; font-weight: bold">
                            Prodesp
                        </td>
                        <td rowspan="2" style="background-color: #C4DAED;  font-weight: bold">
                            SIEB
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #E2F7CA; font-weight: bold">
                            Nº
                        </td>
                        <td style="background-color: #E2F7CA; font-weight: bold">
                            nome
                        </td>
                        <td style="background-color: #E2F7CA; font-weight: bold">
                            RA
                        </td>
                    </tr>
                    <?php
                    foreach ($classeProdesp as $v) {
                        $v = (array) $v;
                        ?>
                        <tr>
                            <td>
                                <?php echo $v['numero'] ?>
                            </td>
                            <td>
                                <?php echo $v['nomeAluno'] ?>
                            </td>
                            <td>
                                <?php echo $v['RA'] . '-' . $v['digitoRA'] ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($siebComRaId[gt_gdaeSet::raNormatiza($v['RA'])])) {
                                    $a = $siebComRaId[gt_gdaeSet::raNormatiza($v['RA'])];
                                    if (trim($v['nomeAluno']) != strtoupper(trim($a['n_pessoa']))) {
                                        $classy = "alert alert-warning";
                                    } else {
                                        $classy = NULL;
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-10 <?php echo @$classy ?>" style="text-align: left; height: 100px">
                                            <input type="hidden"   name="ra[<?php echo $v['numero'] ?>]" value="<?php echo @$a['id_pessoa'] ?>"/>
                                            <?php echo $a['n_pessoa'] . '<br />RSE: ' . $a['id_pessoa'] . '<br />Nasc: ' . data::converteBr($a['dt_nasc']) . '<br />Mãe: ' . $a['mae'] ?>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    $erro = 1;
                                    $nomes = alunos::busca($v['nomeAluno']);
                                    if (!empty($nomes)) {
                                        $cc = 1;
                                        foreach ($nomes as $a) {
                                            ?>
                                            <div class="alert alert-danger" style="text-align: left; ">
                                                <a onclick="aceAluno(<?php echo $a['id_pessoa'] ?>)" href="#">                  
                                                    <?php echo $a['n_pessoa'] . '<br />RSE: ' . $a['id_pessoa'] . '<br />Nasc: ' . data::converteBr($a['dt_nasc']) . '<br />Mãe: ' . $a['mae'] ?>
                                                </a>
                                            </div>
                                            <div  style="border-bottom: solid lightslategray 1px; width: 90%; margin: 0 auto"></div>
                                            <br />
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="col-sm-11" style="text-align: left; ">
                                            Sem sugestão
                                        </div>
                                        <?php
                                    }
                                }
                                ?>


                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
                if (empty($erro)) {
                    ?>
                    <div style="text-align: center">
                        <input class="btn btn-primary" style="font-size: 18px" type="submit" value="Está tudo em ordem, conferi todos os nomes. Pode sincronizar agora" />  
                    </div>
                    <?php
                }
                ?>
            </form>
            <br /><br />
            <div class="row">
                <div class="col-sm-6 text-center">
                    <?php
                    if (empty($erro) && $turma['prodesp'] > 1) {
                        ?>
                        <form style="text-align: center" method="POST">
                            <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                            <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
                            <input class="btn btn-success" name="sincronizar" type="submit" value="Atualizar Alunos" />
                        </form> 
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-6 text-center">
                    <form style="text-align: center" method="POST">
                        <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                        <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
                        <input class="btn btn-primary"  type="submit" value="Voltar" />
                    </form>
                </div>
            </div>

            <?php
        }
    } elseif (!empty($id_turma)) {
        $erro = $model->relatAlunos($id_pl);
        ?>
        <br /><br />
        <div class="row">
        </div>
        <div class="col-sm-6 text-center">
            <div>

                <?php
                if ($turma['prodesp'] > 1) {
                    /**
                      ?>
                      <form method="POST">
                      <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                      <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
                      <input class="btn btn-success" name="sincronizar" type="submit" value="Sincronizar Alunos" />
                      </form>
                      <?php
                     * 
                     */
                }
                ?>
            </div>
        </div>
        <div class="col-sm-6 text-center">


            <?php
            if (!empty($_POST['sincronizar'])) {
                /**
                  ?>
                  <form method="POST">
                  <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                  <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
                  <input class="btn btn-primary"  type="submit" value="Voltar" />
                  </form>
                  <?php
                 * 
                 */
            } elseif (!empty($dataAtualizacao) && $turma['prodesp'] > 1) {
                if (empty($erro) && $turma['prodesp'] > 1) {
                    /**
                      ?>
                      <form method="POST">
                      <input type="hidden" name="prodesp" value="<?php echo $turma['prodesp'] ?>" />
                      <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                      <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
                      <input class="btn btn-primary" name="sincronizarAlunos"  type="submit" value="Atualizar Dados dos Alunos" />
                      </form>
                      <?php
                     * 
                     */
                }
            }
            ?>      
        </div>
    </div>
    <?php
}
?>
</div>
<form id="atu" method="POST">
    <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
    <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
    <input id="ra" type="hidden" name="ra" value="" />
    <input id="dig" type="hidden" name="dig" value="" />
    <input id="uf" type="hidden" name="uf" value="" />
    <input id="id_pessoa" type="hidden" name="id_pessoa" value="" />
    <input type="hidden" name="sincronizar" value="1" />
</form>

<form id="dra" method="POST">
    <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
    <input type="hidden" name="periodoLetivo" value="<?php echo @$id_pl ?>" />
    <input id="idp" type="hidden" name="delra" value="" />
</form>
<form id="acessoa" target="_blank" action="<?php echo HOME_URI ?>/gt/aluno" id="pessoa<?php echo $v['id_pessoa'] ?>" method="POST">
    <input type="hidden" name="activeNav" value="1" />
    <input id="idacesso" type="hidden" name="id_pessoa" value="" />
</form>
<script>
    function delRa(id) {
        if (confirm("Tem certeza? Esta ação removerá o RA deste aluno da nossa base de dados")) {
            document.getElementById('idp').value = id;
            document.getElementById('dra').submit();
        }
    }

    function atualiza(ra, dig, uf, id_pessoa) {
        document.getElementById('ra').value = ra;
        document.getElementById('dig').value = dig;
        document.getElementById('uf').value = uf;
        document.getElementById('id_pessoa').value = id_pessoa;
        document.getElementById('atu').submit();
    }
    function aceAluno(id) {
        document.getElementById("idacesso").value = id;
        document.getElementById("acessoa").submit();
    }
</script>


