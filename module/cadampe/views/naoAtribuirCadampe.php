<?php
if (!defined('ABSPATH'))
    exit;
$oa = concord::oa($fk_id_pessoa_cadampe);
$protocolosCadampe = $model->getCadampeAlocado($fk_id_pessoa_cadampe,$dt_inicio,$dt_fim,$periodo);

if (!empty($protocolosCadampe)) {
    foreach ($protocolosCadampe as $v) {
        $periodoNome = $model->getPeriodo($v['periodo']);
        $id_cadampe_pedido1 = $v['id_cadampe_pedido'];
        $dt_inicio1 = dataErp::converteBr($v['dt_inicio']);
        $dt_fim1 = dataErp::converteBr($v['dt_fim']);
        $protocolo_conflito = "Protocolo $id_cadampe_pedido1: $dt_inicio1 a $dt_fim1 ($periodoNome);";
        $protocolo_conflitoSS = !empty($protocolo_conflitoSS)?$protocolo_conflitoSS.' '.$protocolo_conflito : $protocolo_conflito;
    }
    echo toolErp::divAlert('danger','Atenção! Verifique o conflito de horário com esse protocolo no campo OBS'); 
    $obs = "$n_pessoa encontra-se alocad$oa em período conflitante com este protocolo, sendo: $protocolo_conflitoSS";
}else{
    $obs = '';
}?>

<div class="body" style="overflow-x: hidden;">
    <div id='email' style="display:none;">
       <?= toolErp::linha(['width'=>'100%','height'=>'3px']) ?> 
       <div>
           Enviando Email. Por favor, aguarde...
       </div>
    </div>
    <div id='cadampe' style="display:'';">
        <form id="atr" method="POST">
            <?php if (isset($fone1)) { ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[tel1Obs]', $fone1, null, null, 'Obs:') ?>
                    </div>
                </div>
                <br>
            <?php } ?>
            <?php if (isset($fone2)) { ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[tel2Obs]', $fone2, null, null, 'Obs:') ?>
                    </div>
                </div>
                <br>
            <?php } ?>
            <?php if (isset($fone3)) { ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[tel3Obs]', $fone3, null, null, 'Obs:') ?>
                    </div>
                </div>
                <br>
            <?php } ?>
            <div class="row">
                <div class="col">
                    <?php formErp::textarea('1[obs]', $obs, 'Obs:') ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <textarea style="height:100px" name="mensagemEmail" class="form-control" aria-label="With textarea" placeholder="Digite uma mensagem a ser incorporada ao Email que será enviado para <?= $n_pessoa ?>. (apenas para a opçao CONTACTADO E ACEITOU AS AULAS)"></textarea>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <input type="hidden" name="1[fk_id_cadampe_resposta]" id="id_cadampe_resposta" value="" />
                <?= formErp::hiddenToken('cadampe_contactar', null, null, null, 1) ?>
                <?php
                echo formErp::hidden([
                    '1[fk_id_cadampe_pedido]' => $id_pedido,
                    '1[fk_id_pessoa_cadampe]' => $fk_id_pessoa_cadampe,
                    '1[tel1]' => $fone1,
                    '1[tel2]' => $fone2,
                    '1[tel3]' => $fone3,
                    'fk_id_pessoa_cadampe' => $fk_id_pessoa_cadampe,
                    'id_cadampe_pedido' => $id_pedido,
                    '1[fk_id_pessoa_call]' => $fk_id_pessoa_call,
                    'fk_id_pessoa_call' => $fk_id_pessoa_call,
                    'id_ec' => $id_ec,
                    'id_categoria' => $id_categoria,
                    'n_pessoa_cadampe_fim' => $n_pessoa,
                    'closeModal' => isset($_POST['last_id']), //se for um insert na tabela pedido, atualiza a pagina solicitarList
                    'alterCadampe' => 1, //gerar controle de prioridade cadampe
                ]);

                foreach ($resps as $key => $v) {
                    //echo formErp::radio('1[fk_id_cadampe_resposta]', $v['id_cadampe_resposta'], $v['n_resposta']);
                ?>
                    <div class="col text-center">
                        <input onclick="salvar(<?= $v['id_cadampe_resposta'] ?>,'<?= $v['n_resposta'] ?>')" class="btn btn-outline-info" type="button" value="<?= $v['n_resposta'] ?>" />
                    </div>
                <?php
                }
                ?>
            </div>
        </form>
    </div>
</div>

<script>
    conta = 1;
    setInterval(function() {
        fetch('<?= HOME_URI ?>/cadampe/cadampeSet?id=<?= $fk_id_pessoa_cadampe ?>&sit=1&conta=' + conta);
        conta++;
    }, 1000);

    function closeNaoAtr() {
        $('#NaoAtrModal').modal('hide');
        parent.document.iddiscForm.submit();
    }

    function salvar(id_cadampe_resposta, n_resposta) {

        if (id_cadampe_resposta == 1) {
            mensagem = "Deseja atribuir o protocolo <?= $id_pedido ?> para o professor <?= $n_pessoa ?> ?"

        } else {
            mensagem = n_resposta + "?"
        }

        document.getElementById("id_cadampe_resposta").value = id_cadampe_resposta;

        if (confirm(mensagem)) {
            if (id_cadampe_resposta == 1) {
                document.getElementById('cadampe').style.display = 'none';
                document.getElementById('email').style.display = '';
            }
            document.getElementById('atr').submit();
        }

    }

    function atr() {
        radios = document.getElementsByName('1[fk_id_cadampe_resposta]');
        resposta = 0;
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                if (radios[i].value == 1) {
                    if (confirm("Deseja atribuir o protocolo <?= $id_pedido ?> para o professor <?= $n_pessoa ?> ?")) {
                        document.getElementById('atr').submit();
                    }
                    resposta = 1;
                }
                break;
            }
        }
        if (resposta == 0) {
            document.getElementById('atr').submit();
            closeNaoAtr();
        }
    }

    <?php if (!empty($fk_id_cadampe_resposta)) { ?>
        closeNaoAtr();
    <?php } ?>
</script>