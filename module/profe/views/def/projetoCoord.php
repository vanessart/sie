<?php
if (!defined('ABSPATH'))
    exit;

$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$autores = filter_input(INPUT_POST, 'autores', FILTER_SANITIZE_STRING);
$id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_SANITIZE_STRING);
$hidden = $_POST['hidden'];
if ($id_projeto) {
    $projeto = sql::get('profe_projeto', 'id_projeto, n_projeto, dt_inicio, dt_fim, habilidade, justifica, situacao, recurso, resultado, fonte, autores, avaliacao, devolutiva, fk_id_projeto_status, coord_vizualizar , msg_coord', 'WHERE id_projeto =' . $id_projeto, 'fetch', 'left');
    $n_projeto = $projeto['n_projeto'];
    $titulo = 'Título: ' . $n_projeto;
    $id_status = $projeto['fk_id_projeto_status'];
    $coord_vizualizar = $projeto['coord_vizualizar'];
}

if ($coord_vizualizar == 0 && $id_status == 2) {
    $visualizou['coord_vizualizar'] = 1;
    $visualizou['id_projeto'] = $id_projeto;
    $model->db->ireplace('profe_projeto', $visualizou, 1);
}
?>
<style>
    .mensagens {
    }
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        box-shadow: #e1e1d1 5px 5px 14px;
        margin: 10px auto;
        padding: 4px;
        /*min-height: 80px;*/
    }

    .mensagens .corpoMensagem {
        display: block;
        /*margin-top: 10px;*/
        font-weight: normal;
        white-space: pre-wrap;
        padding: 5px;
    }
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
    }
    .mensagens .mensagemLinha-2{
        border-left: 5px solid #f9ca6e;
    }
    .tituloBox.box-0{
        color: #7ed8f5;
    }
    .tituloBox.box-2{
        color: #f9ca6e;
    }
</style>
<div class="body">

    <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div class="col" style="font-weight: bold; text-align: center;">
                <p style=" font-size: 24px"><?= str_replace('.', '', $titulo) ?></p>
                <p style=" font-size: 20px"><?= $n_turma ?> - <?= $data ?></p>
                <p style=" font-size: 16px; text-align: left;">Autores: <?= $autores ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem" >
                <div>
                    <p class="tituloBox box-0">JUSTIFICATIVA</p>
                    <?php if (!empty($projeto['justifica'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['justifica'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem" >
                <div>
                    <p class="tituloBox box-0">HABILIDADE</p>
                    <?php if (!empty($projeto['habilidade'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['habilidade'] ?> </span>
                        <?php
                    } else {
                        $sql = "SELECT "
                                . " h.descricao, h.codigo "
                                . " FROM profe_projeto_hab ph "
                                . " JOIN coord_hab h on h.id_hab = ph.fk_id_hab "
                                . " AND ph.fk_id_projeto = " . $projeto['id_projeto'];
                        $query = pdoSis::getInstance()->query($sql);
                        $habList = $query->fetchAll(PDO::FETCH_ASSOC);
                        if ($habList) {
                            ?>
                            <table class="table table-bordered table-hover table-responsive">
                                <?php
                                foreach ($habList as $hh) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $hh['codigo'] . ' - ' . $hh['descricao'] ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <?php
                        } else {
                            echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem" >
                <div>
                    <p class="tituloBox box-0">RECURSO</p>
                    <?php if (!empty($projeto['recurso'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['recurso'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem" >
                <div>
                    <p class="tituloBox box-0">RESULTADO</p>
                    <?php if (!empty($projeto['resultado'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['resultado'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem" >
                <div>
                    <p class="tituloBox box-0">FONTES DE PESQUISA</p>
                    <?php if (!empty($projeto['fonte'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['fonte'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-2" >
                <div>
                    <p class="tituloBox box-2">AVALIAÇÃO FINAL</p>
                    <?php if (!empty($projeto['avaliacao'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['avaliacao'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($id_projeto)) { ?> 
        <br /><br />
        <form name="salvarForm" action="<?= HOME_URI ?>/profe/projetoCoordList" method="POST" target='_parent'>
            <input type="hidden" name="1[fk_id_projeto_status]" id="id_status" value="">
            <input type="hidden" name="1[coord_vizualizar]" id="coord_vizualizar" value=""> 
            <input type="hidden" name="n_status" id="n_status" value=""> 
            <?=
            formErp::hidden([
                'activeNav' => 2,
                '1[id_projeto]' => $id_projeto,
                'fk_id_ciclo' => $id_ciclo,
                'fk_id_disc' => $id_disc,
                'id_projeto' => $id_projeto,
                'n_projeto' => $n_projeto,
                'msg_coord' => @$msg_coord,
                'autores' => $autores,
                'n_turma' => $n_turma,
                'id_turma' => $id_turma,
                'data' => $data
            ])
            ?>

            <div class="input-group">
                <span class="input-group-text" style="display: block; width: 200px">
                    Devolutiva
                </span>
                <textarea class="form-control" name="1[devolutiva]" ><?= @$projeto['devolutiva'] ?></textarea>
            </div>
            <br /><br />
            <?= formErp::hiddenToken('profe_projeto', 'ireplace') ?>

            <?php if ($id_status == 2 && !in_array(toolErp::id_nilvel(), [18, 2, 22])) { ?>
                <div style="text-align: center; padding: 13px">
                    <button class=" btn btn-outline-info" onclick="salvar(2, 1, null)">
                        Apenas Salvar
                    </button>
                    <button class=" btn btn-outline-info" onclick="salvar(3, 0, 'Aprovou')">
                        Salvar e Aprovar
                    </button>
                    <button class=" btn btn-outline-info" onclick="salvar(4, 0, 'Devolveu')">
                        Salvar e Devolver ao Professor
                    </button>
                </div>
            <?php } else {
                ?>
                <div class="alert alert-danger" style="padding-top:  10px; padding-bottom: 0">
                    <div class="row">
                        <div class="col" style="font-weight: bold; text-align: center;">
                            <p style=" font-size: 14px">Projeto Indisponível para alterações.</p>
                        </div>
                    </div>
                </div>
            <?php }
            ?>    

        </form>
    <?php }
    ?> 
</div>

<script type="text/javascript">
    function salvar(id_status, vizualizar, status) {
        document.getElementById("id_status").value = id_status;
        document.getElementById("coord_vizualizar").value = vizualizar;

        if (id_status > 2) {
            document.getElementById("n_status").value = status;
        }

        document.salvarForm.submit();

    }
</script>