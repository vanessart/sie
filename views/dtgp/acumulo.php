<?php
ob_start();
$sel = sql::get('dtpg_seletivas', '*', ['id_sel' => @$_POST['fk_id_sel']], 'fetch')['n_sel'];
$dados = sql::get('dtgp_cadampe', '*', ['id_cad' => @$_POST['id_cad']], 'fetch');
?>
<div class="fieldTop">
    DECLARAÇÃO DE ACÚMULO DE CARGO
</div>
<br /><br /><br /><br />
<div style="text-align: justify; font-size: 18px">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Eu, <?php echo strtoupper(@$dados['n_insc']) ?>, 
    CPF <?php echo @$dados['cpf'] ?>   declaro em consonância os incisos XVI e XVII do artigo 37 da Constituição Federal de 1988, sob pena de responsabilidade, que não exerço cargo, emprego ou função atividade no âmbito do Serviço Público Federal, Estadual  ou Municipal, ou ainda em Autarquias, Fundações, Empresas Públicas, Sociedade de Economia Mista, suas subsidiárias e sociedades controladas direta ou indiretamente pelo Poder Público, bem como não percebo proventos decorrentes de aposentadoria em cargo, emprego ou função pública. 
</div>
<br /><br />
<div style="text-align: justify; font-size: 18px">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Por ser expressão de verdade, firmo a presente.	
</div>
<br /><br /><br /><br />
<div style="text-align: justify; font-size: 18px">
    Barueri, <?php echo date("d") ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
</div>
<br /><br /><br /><br /><br />
<div style="text-align: center">
    ____________________________________
    <br />
    <?php echo strtoupper(@$dados['n_insc']) ?>
</div>
<?php
tool::pdfSecretaria();
?>