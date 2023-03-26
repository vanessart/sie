<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$periodosLet = $model->getPLaluno($id_pessoa);
if (empty($id_pl)) {
   $id_pl = curso::id_pl_atual()['id_pl'];
}
$docs = $model->getDocs($id_pessoa,$id_turma,$id_pl);
if ($docs) {
    $form['array'] = $docs;
    $form['fields'] = [
        'Documento' => 'titulo',
        '||1' => 'btn1',
        '||2' => 'btn2',
        '||3' => 'btn3',
        '||4' => 'btn4',
    ];
}?>
<div class="fieldTop">
    Formulários e Anexos de <?= $n_pessoa ?>
</div>
<div class="row">
    <?php
    foreach ($periodosLet as $v){?>
        <div class="col-2 text-center">
            <form id='ano' method="POST">
                <?= formErp::hidden($hidden);?>
                <input type="hidden" id='id_pl' name="id_pl" value="<?= $v['id_pl'] ?>">
                <input type="hidden" id='activeNav' name="activeNav" value="2">
                <input data-just="<?= $v['id_pl'] ?>" class="justifica btn <?= ($id_pl==$v['id_pl'])?"btn-info":"btn-outline-info" ?>" type="submit" value="<?= $v['n_pl'] ?>" />
            </form>
        </div>
        <?php
    }?>
</div>
<br><br>
<?php 

if (!empty($form)) {
    report::simple($form);
}

toolErp::modalInicio();
?>

<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
<?php
toolErp::modalFim();
?>
<form id="form" target="_blank" action="" method="POST">
    <?=
    formErp::hidden([
        'id_pessoa' => $id_pessoa,
        'n_pessoa' => $n_pessoa,
        'n_turma' => $n_turma,
        'id_turma' => $id_turma,
        'activeNav' => 2,
    ]);
    ?>  
    <input type="hidden" name="id" id="id" value="" />
    <input type="hidden" name="id_pdi" id="id_pdi" value="" />
    <input type="hidden" name="id_pl" id="id_pl" value="<?= $id_pl ?>" />
    <input type="hidden" name="id_adapt" id="id_adapt" value="" />
    <input type="hidden" name="bimestre" id="bimestre" value="" />
    <input type="hidden" name="id_turma" id="id_turma" value="" />
    <input type="hidden" name="id_protocolo" id="id_protocolo" value="" />
</form>

<script>
    function doc(action,id,bimestre){
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = "<?= $n_pessoa ?>";
        document.getElementById("id").value = id;
        document.getElementById("bimestre").value = bimestre;
        document.getElementById("id_pdi").value = id;
        document.getElementById("id_adapt").value = id;
        document.getElementById("id_turma").value = id;
        document.getElementById("id_protocolo").value = id;
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/'+action;
        document.getElementById("form").submit();
    }
</script>
