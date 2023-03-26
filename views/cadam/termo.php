<?php
ob_start();
$dados = $model->getCadampe($_POST['id_cad']);
?>
<div class="fieldTop">
    TERMO DE COMPROMISSO 
</div>
<div style="text-align: center; font-size: 14px">
    PROFESSOR EVENTUAL – CADAMPE
    <br />
    <?php echo $dados['n_sel'] ?>
</div>
<br /><br /><br /><br />
<div style="text-align: justify; font-size: 16px">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Eu, <?php echo strtoupper(@$dados['n_pessoa']) ?>, 
    devidamente qualificado perante o Cadastro Municipal de Professores
    Eventuais – CADAMPE – do Município de Barueri, como professor eventual de <?php echo @$_POST['disciplina'] ?> , declaro estar
    ciente do disposto nos termos da Lei nº 2.323/2013 e do Decreto nº 8.546/2017, e firmo o presente
    compromisso de atender às convocações que se fizerem necessárias ao interesse público,
    observando todas as normas legais e administrativas relacionadas ao magistério público da Educação
    Básica Municipal.
</div>
<br /><br />
<div style="text-align: justify; font-size: 16px">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Declaro ainda estar ciente das penalidades quanto ao não atendimento das convocações e
    das normas aplicáveis ao exercício do magistério.
</div>
<br /><br /><br /><br />
<div style="text-align: center; font-size: 16px">
    Barueri, <?php echo date("d") ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
</div>
<br /><br /><br /><br /><br />
<div style="text-align: center; font-size: 16px">
    ____________________________________
    <br />
    <?php echo strtoupper(@$dados['n_pessoa']) ?>
</div>
<?php
tool::pdfSecretaria();
?>