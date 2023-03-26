<?php
if (!defined('ABSPATH'))
    exit;

if (empty($pedido)) { 
    echo '<font color="red">Em atualização. Aguarde alguns minutos. </font>';
    exit;
}
?>
<style>
    .barra{
        overflow: hidden;
    }
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
        max-height: 235px;
        overflow-y: auto;
    }

    #movel{
        margin-top: 240px;
    }
    
</style>

<div class="body">

    <div>
        <div id='fixo'>
            <form name="fk_id_pessoa_callForm" method="POST">
                <div class="row" style="margin-top:10px;">
                     <div class="col-md-7">
                     </div>
                    <div class="col-md-5">
                        <?php 
                        if (empty($block)){
                           echo  formErp::select('1[fk_id_pessoa_call]', $callCenter, 'Responsável', $pedido[0]['fk_id_pessoa_call'], 1, $hidden)
                            
                            . formErp::hidden([
                                '1[id_cadampe_pedido]'=>$id_pedido,
                                'id_pedido_respon'=>$id_pedido,
                                'alterRespons'=>1
                            ]);
                            echo formErp::hiddenToken('cadampe_pedido', 'ireplace', null, null, 1);
                        }else if(!empty($pedido[0]['fk_id_pessoa_call'])){

                            Echo "<label style='color: green; font-weight:bolder'>Atendente responsável: ".toolErp::n_pessoa($pedido[0]['fk_id_pessoa_call'])."</label>";

                        }else{
                            Echo "<label style='color: red; font-weight:bolder'>Protocolo sem Atendente responsável</label>";
                        }
                        ?>
                    </div>
                </div>
            </form>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-condensed table-responsive" cellpadding="2" style="width:100%;font-weight: normal;">
                        <tr>
                            <th width="40%">
                                <label style="font-weight:bolder"><?= $pedido[0]['n_inst'] ?>
                            </th>
                            <th>
                                <label style="font-weight:bolder">Solicitante:</label>
                                <label> <?= $pedido[0]['solicitante'] ?></label>
                            </th>
                            <th>
                                <label style="font-weight:bolder">Data:</label>
                                <label> <?= dataErp::diaHora($pedido[0]['dt_update']) ?></label>
                            </th>
                        </tr>
                        <tr>
                            <th width="40%">
                                <label class="dado">Endereço:</label>
                                <label class="valor"><?= $pedido[0]['logradouro'] ?>, <?= $pedido[0]['num'] ?> - <?= $pedido[0]['bairro'] ?></label>
                            </th>
                            <th>
                                <label style="font-weight:bolder">Telefone:</label>
                                <label class="valor"><?= $pedido[0]['tel1'] ?></label>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
            <?php foreach ($fim_linha as $k => $v) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover table-condensed table-responsive table-striped" cellpadding="2" style="width:100%;font-weight: normal;">
                            <tr>
                                <th width="40%"><label style="font-weight:bolder"><?= $v['n_categoria'] ?> <br> <?= substr(data::converteBr($pedido[0]['dt_inicio']), 0, -5) ?> a <?= substr(data::converteBr($pedido[0]['dt_fim']), 0, -5) ?></label> </th> 
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
                if (!empty($apenasCancela)) { ?>
                    <div class="alert alert-warning" style="padding: 10px;">
                        Pode haver turmas ou professor não alocados. Confirme as informações com a escola.
                    </div>
                <?php }

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

                            <input onclick="status(<?= $v['id_status'] ?>)" class="btn <?= ($pedido[0]['fk_id_status']==$v['id_status'])?"btn-info":"btn-outline-info" ?>" type="button" value="<?= $v['n_status'] ?>" />
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
    <br>
    <?php 
    if (empty($block) && $status == 4) { ?>
    <div class="row" >
        <div class="col-md-8" style="margin-bottom:10px">
            <?= formErp::select('iddisc', $disc_, ['CADAMPE para', 'Selecione'], @$id_categoria, 1, $hidden); ?>
        </div>
        <div class="col-md-4">
            <form method="POST">
                <input type="hidden" name="id_cadampe_pedido" id="id_cadampe_pedido" value="<?= $id_pedido ?>" />
                <?= formErp::button('Atualizar Lista',null,null,'warning'); ?>
                
            </form>
        </div>
        <?php
        if (!empty($formCadampe)) {
            report::simple($formCadampe);
        }
    }?>
    </div>

<form id="NaoAtr" method="POST" target="frameNaoAtr" action="<?= HOME_URI ?>/cadampe/naoAtribuirCadampe">
    <input type="hidden" name="id_ec" id="id_ec__" value="" />
    <input type="hidden" name="fk_id_pessoa_cadampe" id="fk_id_pessoa_cadampe__" value="" />
    <input type="hidden" name="n_pessoa" id="n_pessoa" value="" />
    <input type="hidden" name="periodo" id="periodo" value="" />
    <input type="hidden" name="dt_inicio" id="dt_inicio" value="" />
    <input type="hidden" name="dt_fim" id="dt_fim" value="" />
    <?=
        formErp::hidden([
            'id_cadampe_pedido' => $id_pedido,
            'fk_id_pessoa_call' => $pedido[0]['fk_id_pessoa_call'],
            'id_categoria' => $id_categoria
        ]);
    ?>
</form>
<?php
toolErp::modalInicio(0, 'modal-md barra', 'NaoAtrModal', 'Não Atribuir')
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
toolErp::modalInicio(0, 'modal-md', 'editCModal', 'Editar Telefone')
?>
<iframe name="frameeditC" id="frameeditC" style="width: 100%; height: 50vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>

<form id="hist" method="POST" target="framehist" action="<?= HOME_URI ?>/cadampe/historicoCadampe">
    <input type="hidden" name="id_pessoa_cadampe_hist" id="id_pessoa_cadampe_hist" value="" />
    <input type="hidden" name="n_pessoa_cadampe" id="n_pessoa_cadampe" value="" />
    <input type="hidden" name="id_categoria" id="id_categoria" value="" />
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
        fetch('<?= HOME_URI ?>/cadampe/solicitaSet?id=<?= $id_pedido ?>&sit=1&conta='+conta);
        conta++;
    }, 1000);

    function status(id_status){

        if (id_status == 3) {
            if (confirm("Esse protocolo ainda não possui CADAMPE atribuído. Deseja finalizar?")) {
                document.getElementById('form'+id_status).submit();
            }
        }else if (id_status == 2) {
            if (confirm("Deseja Cancelar esse protocolo?")) {
                document.getElementById('form'+id_status).submit();
            }
        }else if (id_status == 1) {
            if (confirm("Deseja voltar a situação para ABERTO?")) {
                document.getElementById('form'+id_status).submit();
            }
        }else{
            document.getElementById('form'+id_status).submit();
        }
    }

    function nao_atr(id_ec, id_pessoa,id_pedido,n_pessoa,periodo,dt_inicio,dt_fim) {
        document.getElementById("fk_id_pessoa_cadampe__").value = id_pessoa;
        document.getElementById("id_ec__").value = id_ec;
        document.getElementById("n_pessoa").value = n_pessoa;
        document.getElementById("periodo").value = periodo;
        document.getElementById("dt_inicio").value = dt_inicio;
        document.getElementById("dt_fim").value = dt_fim;
        var titulo= document.getElementById('NaoAtrModalLabel');
            titulo.innerHTML  = n_pessoa;
        $('#NaoAtrModal').modal('show');
        document.getElementById('NaoAtr').submit();
    }

    function hist(id_categoria, id_pessoa_cadampe, n_pessoa) {
        document.getElementById("id_pessoa_cadampe_hist").value = id_pessoa_cadampe;
        document.getElementById("n_pessoa_cadampe").value = n_pessoa;
        document.getElementById("id_categoria").value = id_categoria;
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