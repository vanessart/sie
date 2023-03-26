<?php
if (!defined('ABSPATH'))
    exit;
?>
<style>
    .valor{
        font-weigh: bolder;
    }
    #fixo{
        position: fixed;
        top: 0;
        z-index: 5;
        background-color: white;
        width: 98%;
        overflow: hidden;
        box-shadow: #e5e6e7 0px 4px 5px;
        border-bottom: 1px solid #dee2e6;
    }

    #movel{
        margin-top: 240px;
    }
</style>

<div class="body">

    <div>
        <div id='fixo'>
            <form id="call" method="POST">
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-6">
                        <?= formErp::select('1[fk_id_pessoa_call]', $callCenter, 'Responsável', $pedido[0]['fk_id_pessoa_call'], null, $hidden)
                        . formErp::hidden([
                            '1[id_cadampe_pedido]'=>$id_pedido,
                        ]) ?>
                        
                        <?php 
                        if (empty($block))
                            echo formErp::hiddenToken('cadampe_pedido', 'ireplace', null, null, 1);
                        ?>
                    </div>
                    <div class="col">
                        <?php 
                        if (empty($block))
                            echo formErp::button('Trocar Responsavel');
                        ?>
                    </div>
                </div>
            </form>
            <div class="row" style="margin-top:10px;">
                <div class="col">
                    <label style="font-weight:bolder"><?= $pedido[0]['n_inst'] ?> </label>
                </div>
                <div class="col">
                    <label style="font-weight:bolder">Solicitante:</label>
                    <label> <?= $pedido[0]['solicitante'] ?></label>
                </div>
                <div class="col">
                    <label style="font-weight:bolder">Telefone:</label>
                    <label class="valor"><?= $pedido[0]['tel1'] ?></label>
                </div>
            </div>
            
            <br>
            <?php foreach ($fim_linha as $k => $v) { ?>
                <div class="row">
                    <div class="col-md-8">
                        <label style="font-weight:bolder"><?= $v['n_categoria'] ?></label> 
                    </div>
                    <div class="col-md-4">
                        <label style="font-weight:bolder">Data:</label>
                        <label class="valor">De <?= data::converteBr($pedido[0]['dt_inicio']) ?> a <?= data::converteBr($pedido[0]['dt_fim']) ?></label>
                    </div>  
                </div>
                <br>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover table-condensed table-responsive table-striped" cellpadding="2" style="width:100%;font-weight: normal;">
                            <tr>
                                <th width="40%"></th> 
                                <?php foreach ($dias as $b => $bb) { ?>
                                    <td align="center" classs="col-md-2" style="font-weight: bold;<?php if (!empty($bb['cor'])) {
                                        echo 'color:' . $bb['cor'] . ';';
                                    } ?>" ><?= $bb['dia'] ?></td>
                                <?php } ?>
                            </tr>
                            <?php foreach ($v['periodos'] as $d) { ?>  
                                <tr>
                                    <td style="vertical-align:middle"><?= $d['n_periodo'] ?></td>
                                    <?php foreach ($dias as $b => $bb) { ?>
                                        <td align="center" <?php if (!empty($bb['cor'])) {
                                            echo 'style="color:' . $bb['cor'] . ';"';
                                        } ?>>
                                            <?php
                                            if (empty($d['aulas'])) {
                                                echo "<td colspan='5'>Turma com Horário vazio</td>";
                                                break;
                                            } else {
                                                echo (empty($d['aulas'][$b])) ? "" : implode("<br>", $d['aulas'][$b]);
                                            }
                                            ?>
                                        </td>
                                    <?php }
                                ?>
                                </tr>
        <?php }
    ?>
                        </table>
                    </div>
                </div>
                <?php
            }
            echo '</div> <div id="movel">';

            require_once ABSPATH . "/module/cadampe/views/_def/mensagens.php";

            if (empty($block)) {
            ?>
            <div class="row" style="margin-top: 5%;">
                <?php 
                foreach ($situacao as $k => $v) { ?>
                    <div class="col text-center">
                        <form id='form<?= $v['id_status'] ?>' method="POST">
                            <?=
                            formErp::hidden([
                                '1[id_cadampe_pedido]' => $id_pedido,
                                '1[fk_id_status]' => $v['id_status'],
                                'id_cadampe_pedido' => $id_pedido,
                                'closeModal' => isset($_POST['last_id']), //se for um insert na tabela pedido, atualiza a pagina solicitarList
                                'alterStatus' => 1,
                            ])
                            . formErp::hiddenToken('cadampe_pedido', 'ireplace', null, null, 1);
                            ?>

                            <input onclick="document.getElementById('form<?= $v['id_status'] ?>').submit()" class="btn <?= ($pedido[0]['fk_id_status']==$v['id_status'])?"btn-info":"btn-outline-info" ?>" type="button" value="<?= $v['n_status'] ?>" />
                        </form>
                    </div>
                    <?php
                } ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <br>
    <br>
    <?php if (empty($block) && $status == 4) { ?>
    <div class="row" >
        <div class="col-md-4">
            <?= formErp::select('iddisc', $disc_, ['CADAMPE para', 'Todas'], @$id_categoria, 1, $hidden); ?>
        </div>
    </div>
    <?php
        if (!empty($formCadampe)) {
            report::simple($formCadampe);
        }
    }
    ?>
</div>

<form id="NaoAtr" method="POST" target="frameNaoAtr" action="<?= HOME_URI ?>/cadampe/naoAtribuirCadampe">
    <input type="hidden" name="id_ec" id="id_ec__" value="" />
    <input type="hidden" name="fk_id_pessoa_cadampe" id="fk_id_pessoa_cadampe__" value="" />
    <input type="hidden" name="n_pessoa" id="n_pessoa" value="" />
    <?=
        formErp::hidden([
            'id_cadampe_pedido' => $id_pedido,
            'fk_id_pessoa_call' => $pedido[0]['fk_id_pessoa_call']
        ]);
    ?>
</form>
<?php
toolErp::modalInicio(0, 'modal-md', 'NaoAtrModal', 'Não Atribuir')
?>
<iframe name="frameNaoAtr" id="frameNaoAtr" style="width: 100%; height: 50vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>

<form id="editC" method="POST" target="frameeditC" action="<?= HOME_URI ?>/cadampe/editCadampe">
    <input type="hidden" name="id_pessoa_cadampe_edit" id="id_pessoa_cadampe_edit" value="" />
    <input type="hidden" name="id_ec_tel" id="id_ec_tel" value="" />
    <?=
        formErp::hidden([
            'id_cadampe_pedido' => $id_pedido,
            'fk_id_pessoa_call' => $pedido[0]['fk_id_pessoa_call']
        ]);
    ?>
</form>
<?php
toolErp::modalInicio(0, 'modal-sm', 'editCModal', 'Editar Telefone')
?>
<iframe name="frameeditC" id="frameeditC" style="width: 100%; height: 50vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>

<form id="hist" method="POST" target="framehist" action="<?= HOME_URI ?>/cadampe/historicoCadampe">
    <input type="hidden" name="id_pessoa_cadampe_hist" id="id_pessoa_cadampe_hist" value="" />
    <input type="hidden" name="n_pessoa_cadampe" id="n_pessoa_cadampe" value="" />
</form>
<?php
toolErp::modalInicio(0, 'modal-md', 'histModal', '')
?>
<iframe name="framehist" id="framehist" style="width: 100%; height: 50vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>
<input type="hidden" name="id_pedido" id="id_pedido" value="<?= $id_pedido ?>">
<script>
    conta = 1;
    setInterval(function () {
        fetch('<?= HOME_URI ?>/cadampe/solicitaSet?id=<?= $id_pedido ?>&sit=1&conta='+conta+'&user=1');
        conta++;
    }, 1000);

    function nao_atr(id_ec, id_pessoa,id_pedido,n_pessoa) {
        document.getElementById("fk_id_pessoa_cadampe__").value = id_pessoa;
        document.getElementById("id_ec__").value = id_ec;
        document.getElementById("n_pessoa").value = n_pessoa;
        var titulo= document.getElementById('NaoAtrModalLabel');
            titulo.innerHTML  = n_pessoa;
        $('#NaoAtrModal').modal('show');
        document.getElementById('NaoAtr').submit();
    }

    function hist(id_pessoa_cadampe, n_pessoa) {
        document.getElementById("id_pessoa_cadampe_hist").value = id_pessoa_cadampe;
        document.getElementById("n_pessoa_cadampe").value = n_pessoa;
        var titulo= document.getElementById('histModalLabel');
            titulo.innerHTML  = "Histórico de "+n_pessoa;
        $('#histModal').modal('show');
        document.getElementById('hist').submit();
    }

    function editC(id_ec, id_pessoa,id_pedido,n_pessoa) {
        document.getElementById("id_pessoa_cadampe_edit").value = id_pessoa;
        document.getElementById("id_ec_tel").value = id_ec;
        var titulo= document.getElementById('editCModalLabel');
            titulo.innerHTML  = "Editar Telefone de "+n_pessoa;
        $('#editCModal').modal('show');
        document.getElementById('editC').submit();
    }

    function sobe() {
        window.scrollTo(0, 0);
    }

    escuta = {};
    id_pedido = document.getElementById("id_pedido").value;

    setInterval(function () {
        fetch('<?= HOME_URI ?>/cadampe/cadampeGet?id_cadampe_pedido='+id_pedido)
                .then(resp => resp.json())
                .then(resp => {

                    for (id in resp) {
                        if(document.getElementById(id)){
                            if (escuta[id]) {
                                if (parseInt(escuta[id]['conta']) === parseInt(resp[id]['conta'])) {
                                    document.getElementById(id).classList.remove('btn-danger');
                                    document.getElementById(id).classList.add('btn-link');
                                } else {
                                    document.getElementById(id).classList.remove('btn-link');
                                    document.getElementById(id).classList.add('btn-danger');
                                }
                            }
                        }
                    }
                    escuta = resp;
                })
    }, 2000);

</script>