<?php
$id_sa = filter_input(INPUT_POST, 'id_sa', FILTER_SANITIZE_NUMBER_INT);
$cl = box::dirList(ABSPATH . '/class/');
$descr_sa = @$_POST['descr_sa'];
$pesq = @$_POST['pesq'];
$modal = filter_input(INPUT_POST, 'modal', FILTER_SANITIZE_NUMBER_INT);
foreach ($cl as $v) {
    $class[$v] = explode('.php', $v)[0];
}
?>
<div class="fieldBody">
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-2">
                <input class="btn btn-info" type="button" onclick=" $('#myModal').modal('show');$('.form-class').val('')" value="Nova Ajuda" />
            </div>
            <div class="col-sm-10">
                <?php echo form::input('pesq[criterio]', 'Palavra', @$pesq['criterio']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-5">
                <?php echo form::selectDB('sys_ajuda_cat', 'pesq[id_ac]', 'Categoria', @$pesq['id_ac']) ?>
            </div>
            <div class="col-sm-5">
                <?php echo form::select('pesq[class]', $class, 'class', @$pesq['class']) ?>
            </div>
            <div class="col-sm-2">
                <?php echo form::button('Buscar') ?>
            </div>
        </div>
    </form>
    <?php
    box::modalInicio(NULL, $modal);
    ?>
    <form method="POST">
        <div class="row">
            <div class="col-sm-6">
                <?php echo form::input('1[n_sa]', 'Título') ?>
                <br /><br />
                <?php echo form::selectDB('sys_ajuda_cat', '1[fk_id_ac]', 'Categoria') ?>
                <br /><br />
                <?php echo form::select('1[class]', $class, 'Classe') ?>
                <br /><br />
                <div style="text-align: center">
                    <?php echo form::hiddenToken('sys_ajuda', 'ireplace', ['id_sa' => $id_sa]) ?>
                    <?php echo form::button('Salvar') ?>
                </div>
            </div>
            <div class="col-sm-6">
                <?php echo form::textarea('1[descr_sa]', $descr_sa, 'Descrição', 100, 1) ?>
            </div>
        </div>
    </form>
    <?php
    box::modalFim();
    ?>
    <br /><br />
    <?php
    
    $form['array'] = $model->ajuda($pesq);
    $form['fields'] = [
        'ID' => 'id_sa',
        'Título' => 'n_sa',
        'Categoria' => 'n_ac',
    ];
    report::forms($form, 'sys_ajuda', ['modal'=>1]);
    ?>  
</div>





