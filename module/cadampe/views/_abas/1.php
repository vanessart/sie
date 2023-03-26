<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">

    <div class="row">
        <div class="col-md-3">
            <?= formErp::selectDB('cadampe_status', 'fk_id_status', 'Situação', $id_status1, 1, $hidden1) ?>
        </div>
        <div class="col-md-3">
            <?= formErp::select('periodo', ['M' => 'M - Manhã', 'T' => 'T - Tarde', 'I' => 'I - Integral'], ['Período', 'Todos'], @$periodo, 1, $hidden1); ?>
        </div>
        <div class="col-md-6">
            <?= formErp::select('iddisc', $disc_, ['Disciplina', 'Todas'], @$iddisc, 1, $hidden1); ?>
        </div>
        
    </div>
    <br>
        <div class="row">
            <div class="col-md-3">
                <?= formErp::select('mes', data::meses(), 'Mês', @$mes, 1, @$hidden1, ' required ') ?>
            </div>
            
            <div class="col-md-4">
                <form method="POST">
                    <?= 
                    formErp::hidden($hidden1)
                    .formErp::button('Atualizar Lista',null,null,'warning'); ?>
                    
                </form>
            </div>
        </div>
    <br>

    <?php
    if (!empty($formPedido)) {
        report::simple($formPedido);
    }
    ?>

    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" name="id_cadampe_pedido" id="id_cadampe_pedido" value="" />
        <input type="hidden" name="atribuir" id="atribuir" value="1" />
    </form>

    <?php
    toolErp::modalInicio(0, 'modal-fullscreen', null, '')
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>
<input type="hidden" name="activeNav" id="activeNav" value="<?= $activeNav ?>">
<script>
    //faz reload da pagina após inserir um pedido no banco
    $('#myModal').on('hidden.bs.modal', function () {
        el = document.getElementById('fframe');
        if (typeof el == null)
            return;
        item = el.contentWindow.document.getElementsByName('closeModal')[0].value;
        if (item == 1)
            window.location.reload();
    });

    function edit(id_cadampe_pedido,id_status,periodoProto){
        document.getElementById("id_cadampe_pedido").value = id_cadampe_pedido;
        var titulo= document.getElementById('myModalLabel');
        if (id_status==3){
            titulo.innerHTML  = "Protocolo "+id_cadampe_pedido+" <label class='modal_green'>Finalizado</label";
        } else if (id_status==2){
            titulo.innerHTML  = "Protocolo "+id_cadampe_pedido+" <label class='modal_red'>Cancelado</label";
        }else{
            titulo.innerHTML  = "<b>Protocolo "+id_cadampe_pedido+":</b> "+periodoProto;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/cadampe/atribuirCadampe';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    escuta = {};
    frame = document.getElementById('fframe');

    setInterval(function () {
        fetch('<?= HOME_URI ?>/cadampe/solicitaGet?activeNav='+frame)
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