<?php
	$sign = new assinaturaDigital();
	$sign->bntDisable(true, "Desabilitar", null, 'desab');
	$sign->bntClear(true, "Limpar", null, 'limpa');
	$sign->actChange(true, '__change');
	// $sign->setCSSBox([
	// 	'width' => '50px'
	// ]);
	$sign->gerar();
?>

<button onclick="validar()" id="enviar">Capturar Assinatura</button>
<script type="text/javascript">
	function desab(){
		alert('desabilitei');
	}

	function limpa(){
		alert('limpei');
		$('#enviar').attr('disabled', true);
	}

	function __change(){
		$('#enviar').attr('disabled', false);
	}

	function validar(){
		console.log(getAssinatura());
		alert('OK');
	}
</script>
