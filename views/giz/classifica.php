<?php
$id_cate = @$_REQUEST['id_cate'];
if (!empty($_POST['atualiza']) && !empty($id_cate)) {
    $model->classifica($id_cate);
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Classificação - Giz de Ouro <?php echo date("Y") ?>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-6">
            <?php echo formulario::selectDB('giz_categoria', 'id_cate', 'Categoria', NULL, NULL, 1) ?>
        </div>
        <?php
        if (!empty($id_cate)) {
            ?>
            <div class="col-sm-3">
                <?php
                echo formulario::submit('Atualizar Classificação', NULL, ['id_cate' => $id_cate, 'atualiza' => 1]);
                ?>
            </div>
            <div class="col-sm-3">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <?php
                    $sql = "SELECT "
                            . " r.class as Classificacao, r.total as Potuacao,"
                            . " p.n_pessoa as Nome, c.n_cate as Catogoria, f.rm as Matrícula, "
                            . " pf.titulo as Título, i.n_inst as Escola, m.n_mod as Modalidade, "
                            . " r.projeto as `Nota Projeto`, r.portfolio as `Nota Portfolio`, "
                            . " eixo1 as Eixo1, eixo2 as Eixo2, eixo3 as Eixo3, eixo4 as Eixo4, "
                            . "eixo5 as Eixo5, eixo6 as Eixo6, eixo7 as Eixo7, eixo8 as Eixo8, "
                            . "eixo9 as Eixo9,  r.eixoe as Entrevista "
                            . " FROM giz_result r "
                            . " JOIN giz_prof pf on pf.fk_id_pessoa = r.id_pessoa "
                            . " JOIN pessoa p on p.id_pessoa = r.id_pessoa "
                            . " JOIN instancia i on i.id_inst = r.fk_id_inst "
                            . " JOIN giz_modalidade m on m.id_mod = pf.fk_id_mod "
                            . " JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
                            . " JOIN giz_categoria c on c.id_cate = pf.fk_id_cate"
                            . " JOIN giz_notas n on n.id_prof = pf.id_prof "
                            . " WHERE  c.id_cate = $id_cate "
                            . " order by r.class ASC";
                    ?>
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20) ?>" />
                    <input class="btn btn-success" type="submit" value="Exportar para Excel" />
                </form>
            </div>
            <?php
        }
        ?>
    </div>
    <br /><br />
    <?php
    if (!empty($id_cate)) {
        $form['array'] = $model->classicacao($id_cate);
        $form['fields'] = [
            'Class.' => 'class',
            'RM' => 'rm',
            'Nome' => 'n_pessoa',
            'Proj.' => 'projeto',
            'Portf.' => 'portfolio',
            'E 1' => 'eixo1',
            'E 2' => 'eixo2',
            'E 3' => 'eixo3',
            'E 4' => 'eixo4',
            'E 5' => 'eixo5',
            'E 6' => 'eixo6',
            'E 7' => 'eixo7',
            'E 8' => 'eixo8',
            'E 9' => 'eixo9',
            'Entrev.' => 'eixoe',
            'Total' => 'total',
        ];
        tool::relatSimples($form);
    }        