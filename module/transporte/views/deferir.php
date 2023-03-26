<?php
$sisAberto = $model->sistemaAberto();
if (!empty($sisAberto)) {
?>
    <div class="alert alert-danger" style="text-align: center; font-weight: bold;font-size: 18px">
        <?php echo $sisAberto ?>
    </div>
    <?php
    return;
}

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$status = @$_POST['status'];

$option = [
    'A' => 'Aguardando Deferimento',
    'ND' => 'Aguardando Novo Deferimento',
    'T' => 'Todos',
    'SE' => 'Solicitações de Encerramento',
    'E' => 'Encerrados'
];
?>
<div class="body">
    <div class="fieldTop">
        Deferimento
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <?php if (user::session('id_nivel') == 10) { ?>
            <div class="col-sm-6">
                <?php echo formErp::select('id_inst', escolas::idInst(), 'Escola', $id_inst) ?>
            </div>
            <?php } else {
                echo formErp::hidden(['id_inst' => toolErp::id_inst()]);
            } ?>
            <div class="col-sm-3">
                <?php echo formErp::select('status', $option, 'Status', $status) ?>
            </div>
            <div class="col-sm-3">
                <input type="hidden" name="pesq" value="1" />
                <?php echo formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br /><br />
    </form>
    <?php
    if (!empty($status)) {
        $model->alunoRelat($id_inst, $status);
    }
    toolErp::modalInicio(0);
    ?>
    <iframe name="verpage" id="vverpage" style="border: none; width: 100%; height: 80vh"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>
<form id="ver" target="verpage" action="<?php echo HOME_URI ?>/transporte/ver" method="POST">
    <input id="idpes" type="hidden" name="id_pessoa" value="" />
    <input id="idins" type="hidden" name="id_inst" value="" />
</form>
<script>
    function acesso(idPessoa, idInst) {
        document.getElementById('idpes').value = idPessoa;
        document.getElementById('idins').value = idInst;
        var my_content = document.getElementById('vverpage').contentWindow.document;
        my_content.body.innerHTML="";
        $('#myModal').modal('show');
        document.getElementById('ver').submit();
    }
    function update(id_alu, ignore_btn_save) {
        if (!ignore_btn_save) {
            $('.btn-save-'+id_alu).addClass('btn-warning').removeClass('btn-success');
        }

        let id_sa   = $('#ms select.alu-sa-'+id_alu).val();
        let id_li   = $('#ms select.alu-li-'+id_alu).val();
        let dt_ini  = $('#ms input[name="dt_inicio['+id_alu+']"]').val();
        let dt_fim  = $('#ms input[name="dt_fim['+id_alu+']"]').val();
        let tsec    = ($('#ms input[name="transpSec['+id_alu+']"]').is(':checked')) ? '1' : '0';
        let id_inst = $('#ms input[name="id_inst"]').val();
        let status  = $('#ms input[name="status"]').val();
        let pesq    = $('#ms input[name="pesq"]').val();

        const data = [];
        data[0] = { "id_sa": "1", "field": "dt_inicio" };
        data[1] = { "id_sa": "6", "field": "dt_fim" };

        for ( let i=0; i < data.length; i++ )
        {
            f = $('#ms input[name="'+ data[i].field +'['+id_alu+']"]');
            if($('#ms select.alu-sa-'+id_alu+' option:selected').data('id') == data[i].id_sa) {
                f.removeClass('visually-hidden');

                if(!f.val()) {
                    f.addClass('campoError');
                    return false;
                } else {
                    f.removeClass('campoError');
                }

            } else {
                f.addClass('visually-hidden')
            }
        }

        let dados = '';
        dados += (id_sa)    ? 'id_sa['+id_alu+']='+ id_sa : '';
        dados += (id_li)    ? '&id_li['+id_alu+']='+ id_li : '';
        dados += (dt_ini)   ? '&dt_inicio['+id_alu+']='+ dt_ini : '';
        dados += (dt_fim)   ? '&dt_fim['+id_alu+']='+ dt_fim : '';
        dados += (tsec)     ? '&transpSec['+id_alu+']='+ tsec : '';
        dados += (id_inst)  ? '&id_inst='+ id_inst : '';
        dados += (status)   ? '&status='+ status : '';
        dados += (pesq)     ? '&pesq='+ pesq : '';
        
        return dados;
    }
    function salvar(id_alu) {
        // debugger;
        let dados = update(id_alu, true);

        fetch('<?= HOME_URI ?>/transporte/mudaStatusMult', {
            method: "POST",
            body: dados,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(resp => resp.json())
        .then(resp => {
            if (resp.status) {
                $('.btn-save-'+id_alu).addClass('btn-success').removeClass('btn-info btn-warning');
            } else {
                $('.btn-save-'+id_alu).addClass('btn-info').removeClass('btn-success btn-warning');
            }
        });
    }
    function setDate(){
        let d = $('#ms .date-generic').val();
        if (d){
            $('#ms input[type="date"]:not(.date-generic,.visually-hidden)').each(function(e, i) {
                $(this).val(d);
            });
        }
    }
</script>
