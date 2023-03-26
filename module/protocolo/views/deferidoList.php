<?php
if (!defined('ABSPATH'))
    exit;
$id_status = filter_input(INPUT_POST, 'id_status', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$area = $model->areaGet(1);//AEE
$escolas = escolas::idEscolas();
$deferido = 1;
$hidden = [
	'id_inst'=>$id_inst,
	'id_status'=>$id_status,
];
if (toolErp::id_nilvel()==48) {
	$id_inst = toolErp::id_inst();
}
if (!empty($area)) {
	$protocolos = $model->protocoloDeferidoGet(1,$id_inst,$id_status);//1=AEE
	?>
	<div class="body">
	    <div class="fieldTop">
	       Acompanhar Protocolos Deferidos
	    </div>
		<div class="row">
	        <div class="col-md-4">
	            <?= formErp::selectDB('protocolo_status', 'id_status', ['Situação', 'Todas'], $id_status, 1, $hidden,null," WHERE fk_id_proto_area =1 AND at_proto_status = 1 AND tipo = 2 ") ?>
	        </div>
	        <?php 
	    	if (user::session('id_nivel') == 10) { ?>
		        <div class="col-md-6">
		            <?= formErp::select('id_inst', $escolas, ['Escola', 'Todas'], @$id_inst, 1, $hidden); ?>
		        </div> 
		    <?php  
		    } ?>
		  </div>
		<br /><br />
		<?php
		if (!empty($protocolos)) {
		    report::simple($protocolos);
		}else{
			toolErp::divAlert('warning','Não há Protocolos a serem exibidos');
		}?>
	</div>
	<?php
}else{
	echo toolErp::divAlert('warning', 'Sinto muito, você não tem acesso a protocolos');
}?>
<form id="form" target="frame" action="" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    <input type="hidden" name="id_protocolo" id="id_protocolo" value="" />
    <input type="hidden" name="n_pessoa" id="n_pessoa" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
<?php
toolErp::modalFim();
?>
<script>
    function contactaALuno(id,n_pessoa,id_protocolo){
        if (id){
            document.getElementById("id_pessoa").value = id;
            document.getElementById("id_protocolo").value = id_protocolo;
            document.getElementById("n_pessoa").value = n_pessoa;
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/contato';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
