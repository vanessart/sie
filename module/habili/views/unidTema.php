<?php
if (!defined('ABSPATH'))
    exit;
$hidden['n_ut'] = $n_ut = filter_input(INPUT_POST, 'n_ut', FILTER_SANITIZE_STRING);
$hidden['id_gh'] = $id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
$hidden['buscar'] = $buscar = filter_input(INPUT_POST, 'buscar', FILTER_SANITIZE_NUMBER_INT);
$where['fk_id_gh'] = $id_gh;
if (!empty($buscar)) {
    $where['n_ut'] = '%' . $n_ut . '%';
}
?>
<div class="Body">
    <div class="fieldTop">
        Cadastro de Unidade Temática
    </div>
    <br />
    <div class="row">
        <div class="col-sm-3">
            <?php echo formErp::selectDB('coord_grup_hab', 'id_gh', 'Grupo de Habilidades', $id_gh, 1, NULL, NULL, ['at_gh' => 1]); ?>
        </div>
        <?php
        if (!empty($id_gh)) {
            ?>
            <div class="col-sm-5">
                <form method="POST">
                    <div class="row">
                        <div class="col-sm-8">
                            <?php
                            echo formErp::hidden($hidden);
                            echo formErp::input('n_ut', 'coord_uni_tematica', $n_ut);
                            ?> 
                        </div>
                        <div class="col-sm-4">
                            <input type = "hidden" name = "buscar" value = "1" />
                            <?php
                            echo formErp::button('Buscar')
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-2">
                <?php echo formErp::submit('Limpar', NULL, ['id_gh' => $id_gh], NULL, NULL, NULL, 'btn btn-warning ') ?>
            </div>
            <div class="col-sm-2">
                <?php echo formErp::button('Novo Cadastro', 'glyphicon glyphicon-user', "cad('" . toolErp::varGet($hidden) . "')"); ?>
            </div>
            <?php
        }
        ?>
    </div>
    <br /><br />
    <div style="text-align: center">
        <?php
        if (!empty($id_gh)) {
            $conta = coordena::unTematica($where, 'COUNT(`id_ut`) as ct')[0]['ct'];
            $pag = report::pagination(100, $conta, $hidden, 5);
        }
        ?> 
    </div>
    <br /><br />
    <?php
    if (!empty($id_gh)) {

        $array = coordena::unTematica($where, NULL, [$pag, 100]);
        $array = formErp::submitAcessarApagar($array, 'coord_uni_tematica', ['id_gh' => $id_gh, 'pagina' => @$_POST['pagina']], NULL, NULL, NULL, 'cad(%)');
        $form['array'] = $array;
        $form['fields'] = [
            'ID' => 'id_ut',
            'Disciplina' => 'n_disc',
            'Unidade Temática' => 'n_ut',
            'Ativo?' => 'at_ut',
            '||1' => 'apagar',
            '||2' => 'acessar'
        ];
        report::simple($form);
        if (!empty($_POST['id_ut'])) {
            $atv = 1;
            $hidden = $_POST;
        }
        javaScript::iframeModal('cad', HOME_URI . '/habili/def/formUnidTema.php', @$atv, NULL, NULL, 'width: 100%; height: 50vh');
    }
    ?>
</div>
