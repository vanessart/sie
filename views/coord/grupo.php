<?php
$segm = gtMain::cursosPorSegmento();
?>

<div class="fieldBody">
    <div class="fieldBorder2" style="min-height: 80vh" >
        <div class="row">
            <div class="col-md-4">
                <?php formulario::select('id_curso', $segm, 'Segmento', @$_POST['id_curso'], 1, NULL, '  style="width: 100%;"') ?>
            </div>
            <?php
            if (empty($_REQUEST['id_curso'])) {
                ?>
                <div class="col-md-8">
                    <button class="btn btn-default"  style="width: 100%; pointer-events: none">
                        Cadastro de Grupo
                    </button>
                </div>
                <?php
            } else {
                ?>
                <div class="col-md-2">
                    <input style="width: 100%" class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Novo Grupo" />
                </div>
                <div class="col-md-6">
                    <button class="btn btn-default"  style="width: 100%; pointer-events: none">
                        Grupos: <?php echo sql::get('ge_cursos', 'n_curso', ['id_curso' => $_REQUEST['id_curso']], 'fetch')['n_curso']; ?>
                    </button>  
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        if (!empty($_REQUEST['id_curso'])) {
            include ABSPATH . '/views/coord/_grupo/list.php';
        }
        ?>
    </div>
</div>
<?php
if (!empty($_REQUEST['id_curso'])) {

    if (empty($_REQUEST['modal'])) {
        $modal = 1;
    }
    tool::modalInicio('width: 95%', @$modal);
    include ABSPATH . '/views/coord/_grupo/form.php';
    tool::modalFim();
}
?>
