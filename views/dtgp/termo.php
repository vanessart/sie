<?php
ob_start();
$sel = sql::get('dtpg_seletivas', '*', ['id_sel' => @$_POST['fk_id_sel']], 'fetch')['n_sel'];
$dados = sql::get('dtgp_cadampe', '*', ['id_cad' => @$_POST['id_cad']], 'fetch');
?>
<div class="fieldTop">
    TERMO DE COMPROMISSO 
</div>
<div style="text-align: center; font-size: 14px">
    PROFESSOR EVENTUAL – CADAMPE
    <br />
    <?php echo  $sel?>
</div>
<br /><br /><br /><br />
<div style="text-align: justify; font-size: 16px">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Eu, <?php echo strtoupper(@$dados['n_insc']) ?>, 
    devidamente qualificado perante o Cadastro Municipal de Professores Eventuais 
    CADAMPE do Município de <?php echo date("d") ?>, como professor eventual de
    <?php echo @$_POST['disciplina'] ?> , 
    firmo o presente compromisso de atender às convocações que se 
    fizerem necessárias ao interesse público,
    nos termos da Lei 2.323/13 e Decreto 8.546/17,
    observando todas as normas legais e administrativas
    relacionadas ao magistério público da Educação Básica Municipal. 
</div>
<br /><br />
<div style="text-align: justify; font-size: 16px">
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   Declaro estar ciente das penalidades quanto ao não atendimento das convocações e das normas aplicáveis ao exercício do magistério. 
</div>
<br /><br /><br /><br />
<div style="text-align: center; font-size: 16px">
    <?= CLI_CIDADE ?>, <?php echo date("d")  ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
</div>
<br /><br /><br /><br /><br />
<div style="text-align: center; font-size: 16px">
    ____________________________________
    <br />
     <?php echo strtoupper(@$dados['n_insc']) ?>
</div>
<?php
tool::pdfSecretaria();
?>