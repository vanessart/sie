<?php
$destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$destino_ = unserialize(base64_decode($model->_setup['destino']));
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
$statusMostrar = filter_input(INPUT_POST, 'statusMostrar', FILTER_SANITIZE_STRING);
if (!empty($id_li)) {
    $linhaDados = transporteErp::linhaGet($id_li);
}
foreach ($destino_ as $k => $v) {
    if ($v['ativo'] == 1) {
        $destinoList[$k] = $v['nome'];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Alocar Adaptado
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-6">
            <?php echo formErp::select('destino', $destinoList, 'Destino', $destino, 1, ['statusMostrar' => $statusMostrar, 'id_inst' => $id_inst, 'id_li' => $id_li]) ?>
        </div>
        <div class="col-sm-6">
            <?php
            if ($destino) {
                echo formErp::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1, ['destino' => $destino, 'statusMostrar' => $statusMostrar, 'id_li' => $id_li]);
            }
            ?>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-5">
                <br /><br />
                <?php
                if (empty($statusMostrar)) {
                    $statusMostrar = '0, 1';
                }
                if ($destino) {
                    echo formErp::select('statusMostrar', ['0, 1' => 'Deferidos e aguardando deferimento', '0, 1, 2, 6' => 'Todos os não alocados','T' => 'Todos'], 'Status', $statusMostrar, 1, ['destino' => $destino, 'id_inst' => $id_inst, 'id_li' => $id_li]);
                }
                ?>
                <br /><br />
                <?php
                if ($destino) {

                    $alunos = $model->alunoDestino($destino, $id_inst, $statusMostrar);
                    ?>
                    <form id="alunos" method="POST">

                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <tr>
                                <td>
                                    Escola
                                </td>
                                <td>
                                    RA
                                </td>
                                <td>
                                    Nome
                                </td>
                                <td>
                                    Status
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                            </tr>
                            <?php
                            foreach ($alunos as $v) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $v['n_inst'] ?>
                                    </td>
                                    <td>
                                        <?php echo $v['ra'] . '-' . $v['ra_dig'] ?>
                                    </td>
                                    <td>
                                        <?php echo $v['n_pessoa'] ?>
                                    </td>
                                    <td>
                                        <?php echo $v['n_sa'] ?>
                                    </td>
                                    <td>
                                        <input class="btn btn-success" type="button" onclick="frameSet('<?= $v['id_pessoa'] ?>', '<?php echo $v['n_inst'] ?>')" value="Informações" />
                                    </td>
                                    <td>
                                        <input onclick="document.getElementById('tur').style.display = '';document.getElementById('lin').style.display = 'none';" type="checkbox" name="aluno[<?php echo $v['id_pessoa'] ?>]" value="<?php echo $v['id_pessoa'] ?>" />
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php
                        echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'destino' => $destino]);
                        echo formErp::hiddenToken('cadAlunoAdaptado');
                        ?>
                    </form>
                    <?php
                }
                ?>

            </div>
            <div class="col-sm-1">
                <?php
                if (!empty($id_li)) {
                    ?>
                    <button id="tur" onclick="document.getElementById('alunos').submit()" style="font-weight: bold; display: none" class="btn btn-warning">
                        &#8674;
                    </button>
                    <br /><br />
                    <button id="lin" onclick="document.getElementById('alunosTransp').submit()" style="font-weight: bold; display: none" class="btn btn-warning">
                        &#8592;
                    </button>
                    <?php
                }
                ?>
            </div>
            <div class="col-sm-6">
                <?php
                if ($destino) {

                    $transLinha = transporteErp::nomeLinha(NULL, 1);

                    echo formErp::select('id_li', $transLinha, 'Linha', $id_li, 1, ['id_inst' => $id_inst, 'destino' => $destino, 'statusMostrar' => $statusMostrar]);
                }
                if (!empty($id_li)) {
                    $alunos = transporteErp::LinhaAlunosAdaptado($id_li);

                    foreach ($alunos as $v) {
                        if ($v['fk_id_sa'] == 1) {
                            @$utilizados++;
                        } elseif ($v['fk_id_sa'] == 0) {
                            @$espera++;
                        }
                    }
                    ?>
                    <br /><br />
                    <form id="alunosTransp" method="POST" >
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <tr>
                                <td colspan="7">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            Linha: <?php echo $linhaDados['n_li'] ?> (Viagem: <?php echo $linhaDados['viagem'] ?>)
                                            <br /><br />
                                            Motorista: <?php echo $linhaDados['motorista'] ?>
                                            <br /><br />
                                            Período: <?php echo $linhaDados['periodo'] ?>
                                            <br /><br />
                                            Acessibilidade: <?php echo toolErp::simnao($linhaDados['acessibilidade']) ?>
                                            <br /><br />
                                            Monitor: <?php echo $linhaDados['monitor'] ?>
                                            <br /><br />
                                            Abrangência: <?php echo $linhaDados['abrangencia'] ?>
                                        </div>
                                       
                                    </div>

                                    <br /><br />
                                    <table class="table table-bordered table-hover table-responsive table-striped">
                                        <tr>
                                            <td>
                                                Vagas
                                            </td>
                                            <td>
                                                Utilizados
                                            </td>
                                            <td>
                                                Espera
                                            </td>
                                            <td>
                                                Capacidade
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $vagas = intval(@$linhaDados['capacidade']) - intval(@$utilizados);
                                            ?>
                                            <td <?php echo $vagas < 1 ? 'style="background-color: red; color: white"' : '' ?>>
                                                <?php echo intval($vagas) ?>
                                            </td>
                                            <td>
                                                <?php echo intval(@$utilizados) ?>
                                            </td>
                                            <td>
                                                <?php echo intval(@$espera) ?>
                                            </td>
                                            <td>
                                                <?php echo intval(@$linhaDados['capacidade']) ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>
                                    Nº
                                </td>
                                <td>
                                    RA
                                </td>
                                <td>
                                    Nome
                                </td>
                                <td>
                                    Turma
                                </td>
                            </tr>
                            <?php
                            foreach ($alunos as $a) {
                                ?>
                                <tr>
                                    <td>
                                        <input onclick="document.getElementById('tur').style.display = 'none';document.getElementById('lin').style.display = '';" type="checkbox" name="aluno[<?php echo $a['id_pessoa'] ?>]" value="<?php echo $a['destino'] ?>" />
                                    </td>
                                    <td>
                                        <?php echo $a['chamada'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['ra'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['n_pessoa'] ?>
                                    </td>
                                    <td>
                                        <?php echo $a['n_turma'] ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                        </table>
                        <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
                        <?php
                        echo formErp::hidden(['id_inst' => $id_inst, 'id_li' => $id_li, 'destino' => $destino]);
                        echo formErp::hiddenToken('exclAlunoAdaptado');
                        ?>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <br /><br />
    </div>
    <br /><br />
</div>
<form id="frameSubmit" target="frame" method="POST">
    <?= formErp::hidden(['statusMostrar' => $statusMostrar, 'id_li' => $id_li]) ?>
    <input id="n_inst" type="hidden" name="n_inst" value="" />
    <input id="id_pessoa" type="hidden" name="id_pessoa" value="" />
    <input type="hidden" name="destino" value="<?= $destino ?>" />
    <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />

    <input type="hidden" name="info" value="1" />
</form>
<script>
    function frameSet(idPessoa, nInst) {
        document.querySelector('#id_pessoa').value = idPessoa;
        document.querySelector('#n_inst').value = nInst;
        document.querySelector('#frameSubmit').submit();
        $('#myModal').modal('show');
    }
</script>
<?php
toolErp::modalInicio();
?>
<iframe id="frame" name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>