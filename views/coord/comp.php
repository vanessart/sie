<?php
$segm = gtMain::cursosPorSegmento();
?>

<div class="fieldBody">
    <div class="fieldBorder2" style="min-height: 80vh">
        <?php
        if (empty($_REQUEST['id_curso'])) {
            ?>
            <div class="row">
                <div class="col-md-4">
                    <?php formulario::select('id_curso', $segm, 'Curso', @$_POST['id_curso'], NULL, NULL, '  style="width: 100%;"') ?>
                </div>
                <div id="gr" class="col-md-4">
                    <div class="btni">
                        Grupos
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-default"  style="width: 100%; pointer-events: none">
                        Cadastro de Competências
                    </button>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                 <div id="gr" class="col-md-4">
                     <form method="POST">
                         <input style="width: 100%" class="btn btn-primary" type="submit" value="Trocar de Curso" />
                         </form>
                 </div>
               <div id="gr" class="col-md-4">
                    <?php
                    include ABSPATH . '/views/coord/_comp/gr.php';
                    ?>
                </div>
                <div class="col-md-4">
                    <input style="width: 100%" class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Nova Competência" />
                </div>

            </div>
            <?php
        }
        ?>
        <br /><br />
        <div>
            <?php
            if (!empty($_REQUEST['id_gr'])) {
                include ABSPATH . '/views/coord/_comp/list.php';
            }
            ?>
        </div>
    </div> 
</div> 
<?php
if (!empty($_REQUEST['id_curso'])) {

    if (empty($_REQUEST['modal'])) {
        $modal = 1;
    }
    tool::modalInicio('width: 95%', @$modal);
    include ABSPATH . '/views/coord/_comp/form.php';
    tool::modalFim();
}
javaScript::divDinanmica('id_curso', 'gr', HOME_URI . '/coord/compgr');
?>
