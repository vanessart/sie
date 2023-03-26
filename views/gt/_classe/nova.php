<div style="min-height: 80vh">
    <div class="row">
        <div class="col-lg-4" style="height: ">
            <?php
            formulario::select('id_curso_', gtMain::cursosPorSegmento(tool::id_inst()), 'Curso: ', @$turma['id_curso'], NULL, NULL, 'disabled');
           // formulario::select('id_curso', gtMain::cursosPorSegmento(tool::id_inst()), 'Curso: ', @$turma['id_curso'], 1, ['modal'=>1]);
            ?>
            <br />
        </div>
        <div id="ciclo" class="col-lg-4">
            <?php
            if (!empty(@$turma['id_curso']) || !empty($_REQUEST['id_curso'])) {
                include ABSPATH . '/views/gt/_classe/ciclo.php';
            }
            ?>
        </div>
    </div>
    <br />
    <div id="formu">
        <?php
        if (!empty(@$turma['id_ciclo']) || !empty($_REQUEST['id_ciclo'])) {
            include ABSPATH . '/views/gt/_classe/form.php';
        }
        ?>
    </div>
</div>
<?php
        javaScript::divDinanmica('id_curso_', 'ciclo', HOME_URI . '/gt/ciclo');
        javaScript::divDinanmica('id_curso_', 'formu', HOME_URI . '/geral/branco');

                                    ?>