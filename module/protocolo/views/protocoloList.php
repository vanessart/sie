<?php
if (!defined('ABSPATH'))
    exit;

$id_status = filter_input(INPUT_POST, 'id_status', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$area = $model->areaGet(1);//AEE
$escolas = escolas::idEscolas();
$hidden = [
	'id_inst'=>$id_inst,
	'id_status'=>$id_status,
];
$alunos = $model->alunosGet($pesquisa,null,1);//1=AEE
if (!empty($area)) {
	$protocolos = $model->protocoloGet(1,$id_inst,$id_status,null,null,@$prof);//1=AEE
	?>
	<div class="body">
	    <div class="fieldTop">
	        <?= $area ?>
	    </div> 
	    <?php 
	    if (user::session('id_nivel') == 8 || (!empty($prof))) { ?>
			<form method="POST">
		        <div class="row">
		            <div class="col-8">
		                <?=
		                formErp::input('pesquisa', 'Nome ou RSE', $pesquisa)
		                ?>
		            </div>
		            <div class="col-2">
		                <?= formErp::button('Pesquisar') ?>
		            </div>
		        </div>
		        <br />
		    </form>
	    <?php
		}?>
		<div class="row">
			<?php
			if ((empty($prof))) { ?>
		        <div class="col-md-4">
		            <?= formErp::selectDB('protocolo_status', 'id_status', ['Situação', 'Todas'], $id_status, 1, $hidden,null," WHERE fk_id_proto_area =1 AND at_proto_status = 1 AND tipo = 1 ") ?>
		        </div>
	        	<?php 
	    	}
	    	if (user::session('id_nivel') == 10) { ?>
		        <div class="col-md-6">
		            <?= formErp::select('id_inst', $escolas, ['Escola', 'Todas'], @$id_inst, 1, $hidden); ?>
		        </div> 
		    	<?php  
		    }?>    
	    <br>
    	<?php
	    if (!empty($alunos)) {
	        report::simple($alunos);
	    } elseif ($pesquisa) {
	        ?>
	        <div class="alert alert-dark text-center">
	            Aluno Não encontrado
	        </div>
	        <?php
	    }
	    ?>
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

