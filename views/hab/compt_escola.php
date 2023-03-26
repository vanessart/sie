<div class="fieldBody">

    <?php
    if (!empty($_POST['id_filtro'])) {
        $id_inst = $_POST['id_inst'];
        $id_filtro = $_POST['id_filtro'];
        tool::modalInicio('width: 90%');
        include ABSPATH . '/views/hab/rel_compt.php';
        tool::modalFim();
    }
    ?>
    <br />
    <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
        <?php // echo $turma['escola']  ?>
        <br /><br />
        <div class="row">
            <div class="col-md-5">
                <?php
                $escolas = hab::listescolas();
                foreach ($escolas as $v) {
                    $options[$v['id_inst']] = $v['n_inst'];
                }
                formulario::select('id_inst', $options, 'Escolas', @$_POST['id_inst'], 1, ['id_inst' => $v['id_inst']]);
                ?> 

            </div>
            <div class="col-md-3">
                <?php
                formulario::select('id_filtro', ['1' => 'Turma', '2' => 'Ciclo', '3' => 'Disciplina', '4' => 'Professor'], 'Filtrar:', @$_POST['id_filtro'], 1, ['id_inst' => @$_POST['id_inst']]);
                ?>
            </div>
        </div>
    </div>
</div>