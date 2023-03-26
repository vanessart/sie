<?php
$segm = gtMain::cursosPorSegmento();
?>
<div class="fieldBody">
    <div class="fieldBorder2" style="min-height: 80vh">

        <div class="row">
            <div class="col-md-4">
                <?php formulario::select('id_curso', $segm, 'Segmento', @$_POST['id_curso'], 1, NULL, '  style="width: 100%;"') ?>
            </div>
            <?php
            if (empty($_REQUEST['id_curso'])) {
                ?>
                <div class="col-md-8">
                    <button class="btn btn-default"  style="width: 100%; pointer-events: none">
                        Cadastro de Eixos Cognitivos
                    </button>
                </div>
                <?php
            } else {
                ?>
                <div class="col-md-2">
                    <input style="width: 100%" class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Novo Eixo" />
                </div>
                <div class="col-md-6">
                    <button class="btn btn-default"  style="width: 100%; pointer-events: none">
                        Eixos: <?php echo $curso = sql::get('ge_cursos', 'n_curso', ['id_curso' => $_REQUEST['id_curso']], 'fetch')['n_curso']; ?>
                    </button>  
                </div>
                <?php
            }
            ?>
        </div>
<br /><br />
        <?php
        if (!empty($_REQUEST['id_curso'])) {
            $sql = "SELECT * FROM `coord_eixo` "
                    . " where fk_id_curso = " . $_REQUEST['id_curso']
                    . " order by n_ei ";
            $query = $model->db->query($sql);
            $array = $query->fetchAll();
            $sqlkey = DB::sqlKey('coord_eixo', 'delete');
            foreach ($array as $k => $v) {
                $v['id_curso'] = $_REQUEST['id_curso'];
                $array[$k]['n_curso'] = $curso;
                $v['modal'] = 1;
                $array[$k]['ac'] = formulario::submit('Editar', NULL, $v);
                $array[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_ei]' => $v['id_ei'], 'id_curso'=>$_REQUEST['id_curso']]);
            }
            $form['array'] = $array;
            $form['fields'] = [
                'Eixo' => 'n_ei',
                'Código' => 'cod_ei',
                'Curso' => 'n_curso',
                '||1' => 'del',
                '||2' => 'ac'
            ];
            tool::relatSimples($form);
        }
        ?>
    </div> 
</div>
<?php
if (empty($_REQUEST['modal'])) {
    $modal = 1;
}
tool::modalInicio('width: 95%', @$modal);
include ABSPATH . '/views/coord/_eixo/form.php';
tool::modalFim();
