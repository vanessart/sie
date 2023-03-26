
<form method="POST" action="<?= HOME_URI ?>/protocolo/termoRecusa" id='post'>
	<?php
	$dados1 = $_POST[1];
	unset($_POST[1]);
	unset($_POST['formToken']);
	echo formErp::hidden($_POST);
	echo formErp::hidden($dados1);
?>
</form>
<script type="text/javascript">
	document.getElementById('post').submit();
</script>