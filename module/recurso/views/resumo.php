<?php
if (!defined('ABSPATH'))
    exit;
$escola = $model->escolasOpt();
$id_serial = filter_input(INPUT_POST, 'id_serial', FILTER_SANITIZE_NUMBER_INT);
$n_serial = filter_input(INPUT_POST, 'n_serial', FILTER_SANITIZE_STRING);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
$id_equipamento  = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
$rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$sitList = sql::idNome('recurso_situacao');
$id_situacao = filter_input(INPUT_POST, 'id_situacao', FILTER_SANITIZE_NUMBER_INT);
$id_situacao_form = filter_input(INPUT_POST, 'id_situacao_form', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_situacao_form)) {
   $id_situacao = $id_situacao_form;
}
$equipamentoGet = $model->equipamentoSelect();
// if (!empty($id_inst)) {
//    $id_inst = $model->gerente(1,1);
// }
$gerente = $model->gerente();
//$objetoList = $model->objetoGet($id_inst);
$hidden = [
    'id_serial' => $id_serial,
    'n_serial' => $n_serial,
    'id_equipamento' => $id_equipamento,
    'id_situacao' => $id_situacao,
    'n_pessoa' => $n_pessoa,
    'cpf' => $cpf,
    'rm' => $rm,
    'email' => $email,
    'id_inst' => $id_inst,
];
?>
<div class="body">
    <div class="fieldTop">
        Consulta Geral de Equipamentos
    </div>
    <?php
    $abas[1] = ['nome' => "Escolas", 'ativo' => 1];
    $abas[2] = ['nome' => "Secretaria de Educação", 'ativo' => 1, 'hidden' => ['id_inst' => 13]];
    $aba = report::abas($abas, ["secondary", "primary", "outline-secondary"]);
    ?>
    <br /><br />
    <form method="POST">
        <div class="border5">
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                Nº de Série
                            </div>
                            <input class="form-control buscaSerial" type="text" name="n_serial" list="opt" value="<?= $n_serial ?>" onclick="$(this).select()">
                            <datalist id="opt">
                                <option value="Carregando..."></option>
                            </datalist> 
                        </div>
                    </div>
                </div>
                <div class="col">
                     <?= formErp::select('id_equipamento', $equipamentoGet, 'Modelo/Lote', $id_equipamento) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::input('n_pessoa', 'Responsável', $n_pessoa) ?>
                </div>
                <div class="col">
                    <?= formErp::input('cpf', 'CPF (só número)', $cpf) ?>
                </div>

            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::input('rm', 'Matrícula', $rm) ?>
                </div>
                <div class="col">
                    <?= formErp::input('email', 'E-mail', $email) ?>
                </div>
            </div>
        </div>
        <br />
        <div class="border5">
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_inst', $escola, 'Escola', $id_inst); ?>
                </div>
            </div>
        </div>
        <br />   
        <div class="row">
            <div class="col text-center">
                <button onclick="document.getElementById('limpar').submit();" class="btn btn-warning" type="button">
                    Limpar
                </button>
            </div>
            <?= formErp::hidden(['activeNav' => @$aba]) ?>
            <?= formErp::hidden(['search' => 1]) ?>
            <div class="col text-center">
                <button class="btn btn-info" type="submit">
                    Filtrar
                </button>
            </div>
            <div class="col text-center">
                <button type="button" onclick="document.getElementById('export').submit();" class="btn btn-primary" >
                    Exportar (Excel)
                </button>
            </div>
        </div>
        <br />
    </form>
    <form id="limpar">
    </form>
    <form id="export" target="_blank" action="<?= HOME_URI ?>/recurso/pdf/plan.php" method="POST">
        <?= formErp::hidden($hidden) ?>
    </form>
    <?php require ABSPATH . '/module/recurso/views/def/resumo.php';?>
</div>
<form action="<?= HOME_URI ?>/recurso/def/histSerial.php" target="frame" id="formFrame" method="POST">
    <input id="id_serial" type="hidden" name="id_serial" value="" />
    <input id="id_inst" type="hidden" name="id_inst" value="" />
    <input id="id_local" type="hidden" name="id_local" value="" />
    <?= formErp::hidden(['action' => 'resumo']) ?>
</form>
<?php
toolErp::modalInicio(null, null, null, 'Equipamentos');
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
jQuery(function($){
    var xhr = null;
    $('.buscaSerial').keyup(function(e){
        if(typeof e.keyCode == 'undefined'){
            return false;
        }

        if ($(this).val().length >= 3) {
            if (xhr != null){ 
                xhr.abort();
                xhr = null;
            }
            xhr = $.ajax({
                type: "GET",
                url: '<?= HOME_URI ?>/api/serial/objetoGet/<?= $id_inst ?>',
                data: {search: $(this).val()},
                success: function(resp){
                    document.getElementById('opt').innerHTML = resp;
                },error: function(arg) {
                    console.log(arg);
                }
            });
        } else {
            //kill the request
            if (xhr != null){ 
                xhr.abort();
                xhr = null;
            }
        }
    });
});

    function hist(id,id_local,id_inst,n_equipamento,n_serial) {
        document.getElementById('id_serial').value = id;
        document.getElementById('id_local').value = id_local;
        document.getElementById('id_inst').value = id_inst;
        texto = '<div style="text-align: center; color: #7ed8f5;">'+n_equipamento+' - N/S: '+n_serial+'</div>'
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('')
    }
    function situacao(id_situacao) {
        document.getElementById('id_situacao_form').value = id_situacao;
        document.getElementById('situ').submit();
    }
</script>
