<style>

    #tb td{
        padding: 6px
    }
    .fichas button{
        width: 100%;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;

$declaracaoEscolar = $model->declaracaoEscolar($aluno->dadosPessoais['id_pessoa'], $aluno->dadosPessoais['dt_nasc']);
$periodosAtivos = ng_main::periodosAtivos();
$vidaE = $aluno->vidaEscolar();
if ($vidaE) {
    foreach ($vidaE as $v) {
        if (in_array($v['id_pl'], $periodosAtivos)) {
            if ($v['id_ciclo'] != 32 && $v['id_tas'] == 0) {
                $id_turma_atual = $v['id_turma'];
            }
            if ($v['id_tas'] == 0) {
                $alert = "primary";
            } else {
                $alert = "warning";
            }
            ?>
            <div class="alert alert-<?= $alert ?> border" style="margin-top: 4px">
                <div class="row">
                    <div class="col">
                        <table id="tb">
                            <tr>
                                <td>
                                    Escola:
                                </td>
                                <td>
                                    <?= $v['n_inst'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Turma:
                                </td>
                                <td>
                                    <?= $v['n_turma'] ?> (<?= $v['codigo'] ?>)
                                </td>
                            </tr>
                            <?php
                            if (in_array($v['id_ciclo'], [31, 35, 36, 37])) {
                                $sql = "SELECT "
                                        . " ci.id_ciclo, concat(ci.n_ciclo, '(', c.n_curso, ')') n_ciclo "
                                        . " FROM ge_ciclos ci "
                                        . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso and c.id_curso = " . $v['id_curso']
                                        . " WHERE ci.id_ciclo in (1,2,3,4,5,6,7,8,9,25,26,27,28,29,30) "
                                        . " order by ci.n_ciclo";
                                $query = pdoSis::getInstance()->query($sql);
                                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                                $ciclos = toolErp::idName($array);
                                ?>
                                <tr>
                                    <td colspan="2">
                                        <form method="POST">
                                            <table style="width: 100%">
                                                <tr>
                                                    <td>
                                                        <?= formErp::select('1[fk_id_ciclo_aluno]', $ciclos, 'Ciclo', $v['fk_id_ciclo_aluno']) ?>
                                                    </td>
                                                    <td style="width: 20px">
                                                        <?=
                                                        formErp::hidden([
                                                            '1[id_turma_aluno]' => $v['id_turma_aluno'],
                                                            'id_pessoa' => $id_pessoa,
                                                            'activeNav' => 4
                                                        ])
                                                        . formErp::hiddenToken('ge_turma_aluno', 'ireplace')
                                                        ?>
                                                        <button class="btn btn-success" style="width: 50px">
                                                            Ok
                                                        </button> 
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td>
                                    Chamada:
                                </td>
                                <td>
                                    <?= $v['chamada'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Situação:
                                </td>
                                <td>
                                    <?= $v['n_tas'] ?>
                                </td>
                            </tr>
                            <?php
                            if (!empty($v['id_tas'])) {
                                ?>
                                <tr>
                                    <td>
                                        Data de Transferência:
                                    </td>
                                    <td>
                                        <?= dataErp::converteBr($v['dt_transferencia']) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td>
                                    Data de Matrícula:
                                </td>
                                <td>
                                    <form method="POST">
                                        <table style="width: 100%">
                                            <tr>
                                                <td>
                                                    <?= formErp::input('1[dt_matricula]', null, $v['dt_matricula'], null, null, 'date') ?>
                                                </td>
                                                <td style="width: 20px">
                                                    <?=
                                                    formErp::hidden([
                                                        '1[id_turma_aluno]' => $v['id_turma_aluno'],
                                                        'id_pessoa' => $id_pessoa,
                                                        'activeNav' => 4
                                                    ])
                                                    . formErp::hiddenToken('ge_turma_aluno', 'ireplace')
                                                    ?>
                                                    <button class="btn btn-light">
                                                        <img style="width: 20px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/upload.png" alt="S"/>
                                                    </button> 
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                            <?php
if(!empty($v['dt_transferencia'])){
    ?>
 <tr>
                                <td>
                                    Data de Transferência:
                                </td>
                                <td>
                                    <form method="POST">
                                        <table style="width: 100%">
                                            <tr>
                                                <td>
                                                    <?= formErp::input('1[dt_transferencia]', null, $v['dt_transferencia'], null, null, 'date') ?>
                                                </td>
                                                <td style="width: 20px">
                                                    <?=
                                                    formErp::hidden([
                                                        '1[id_turma_aluno]' => $v['id_turma_aluno'],
                                                        'id_pessoa' => $id_pessoa,
                                                        'activeNav' => 4
                                                    ])
                                                    . formErp::hiddenToken('ge_turma_aluno', 'ireplace')
                                                    ?>
                                                    <button class="btn btn-light">
                                                        <img style="width: 20px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/upload.png" alt="S"/>
                                                    </button> 
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                                    <?php
}
?>
                        </table>
                    </div>
                    <div class="col fichas">
                        <div class="row">
                            <div class="col">
                                <form  id="dTrans" target="_blank" action="<?php echo HOME_URI ?>/gestao/decl_transf" name="transfd" method="POST">
                                    <input id="dt" type="hidden" name="sel[]" value="<?= $id_pessoa ?>" />
                                    <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />  
                                    <button class="btn btn-info">
                                        Declaração de Transferência
                                    </button>
                                </form>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col">
                                <a style="width: 100%" href="<?= $declaracaoEscolar ?>&id_turma=<?= $v['id_turma'] ?>" target="_blank" class="btn btn-info">
                                    Declaração de Escolaridade
                                </a>
                            </div>
                        </div>
                        <br />
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <br />
    <div onclick="anteriores()" class="alert alert-dark border" style="margin-top: 4px; text-align: center; cursor: pointer">
        Ver Períodos Anteriores
        <br /><br />
        <div id="ant" style="display: none">
            <?php
            foreach ($vidaE as $v) {
                if (!in_array($v['id_pl'], $periodosAtivos)) {
                    ################## gambiarra - corrigir ficha cadastral - inicio ###
                    if ($v['id_ciclo'] != 32 && $v['id_tas'] == 0 && empty($id_turma_atual)) {
                        $id_turma_atual = $v['id_turma'];
                    }
                    ####### fim ####
                    ?>
                    <div class="alert alert-warning border" style="margin-top: 4px">
                        <table id="tb">
                            <tr>
                                <td>
                                    Escola:
                                </td>
                                <td>
                                    <?= $v['n_inst'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Turma:
                                </td>
                                <td>
                                    <?= $v['n_turma'] ?> (<?= $v['codigo'] ?>)
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Chamada:
                                </td>
                                <td>
                                    <?= $v['chamada'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Situação:
                                </td>
                                <td>
                                    <?= $v['n_tas'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Período:
                                </td>
                                <td>
                                    <?= $v['n_pl'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Data de Matrícula:
                                </td>
                                <td>
                                    <?= data::converteBr($v['dt_matricula']) ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
}
?>
<script>
    window.idant = 0;
    function anteriores() {
        if (window.idant == 0) {
            document.getElementById("ant").style.display = '';
            window.idant = 1;
        } else {
            document.getElementById("ant").style.display = 'none';
            window.idant = 0;
        }

    }
</script>