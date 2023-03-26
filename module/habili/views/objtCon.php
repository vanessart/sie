<?php
if (!defined('ABSPATH'))
    exit;
$hidden['n_oc'] = $n_oc = filter_input(INPUT_POST, 'n_oc', FILTER_SANITIZE_STRING);
$hidden['id_gh'] = $id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
$hidden['buscar'] = $buscar = filter_input(INPUT_POST, 'buscar', FILTER_SANITIZE_NUMBER_INT);
$where['fk_id_gh'] = $id_gh;
if (!empty($buscar)) {
    $where['n_oc'] = '%' . $n_oc . '%';
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Objeto de Conhecimento
    </div>
    <br /><br />
    <div class="row">
        <div class="col">
            <div class="col">
                <?php echo formErp::selectDB('coord_grup_hab', 'id_gh', 'Grupo de Habilidades', $id_gh, 1, NULL, NULL, ['at_gh' => 1]); ?>
            </div>
        </div>
    </div>
    <br />
    <?php
    if (!empty($id_gh)) {
        ?>
        <div class="border">
            <div class="row">
                <div class="col-sm-8">
                    <form method="POST">
                        <div class="row">
                            <div class="col-sm-10">
                                <?php
                                echo formErp::hidden($hidden);
                                echo formErp::input('n_oc', 'Obj. Conhecimento', $n_oc);
                                ?> 
                            </div>
                            <div class="col-sm-2">
                                <input type = "hidden" name = "buscar" value = "1" />
                                <?php
                                echo formErp::button('Buscar')
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-1 text-right">
                    <?php echo formErp::submit('Limpar', NULL, ['id_gh' => $id_gh], NULL, NULL, NULL, 'btn btn-warning ') ?>
                </div>
                <div class="col-sm-3 text-right">
                    <?php
                    echo formErp::button('Novo Cadastro', null, "cad('" . toolErp::varGet($hidden) . "')");
                    ?>
                </div>
            </div>
        </div>
        <br /><br />
        <?php
    }
    ?>
    <div style="text-align: center">
        <?php
        if (!empty($id_gh)) {
            $conta = coordena::objetoConhecimento($where, 'COUNT(`id_oc`) as ct')[0]['ct'];
            $pag = report::pagination(100, $conta, $hidden, 5);
        }
        ?> 
    </div>
    <br /><br />
    <?php
    if (!empty($id_gh)) {

        $array = coordena::objetoConhecimento($where, '*', [$pag, 100]);
        $array = formErp::submitAcessarApagar($array, 'coord_objeto_conhecimento', ['id_gh' => $id_gh, 'pagina' => @$_POST['pagina']], NULL, NULL, NULL, 'cad(%)');
        if ($array) {
            $form['array'] = $array;
            $form['fields'] = [
                'ID' => 'id_oc',
                'Objeto de Conhecimento' => 'n_oc',
                'Ativo?' => 'at_oc',
                '||1' => 'apagar',
                '||2' => 'acessar'
            ];
        }
        if (!empty($form)) {
            report::simple($form);
        }
        if (!empty($_POST['id_oc'])) {
            $atv = 1;
        }
        javaScript::iframeModal('cad', HOME_URI . '/habili/def/formObjConh.php', @$atv, NULL, NULL, 'width: 100%; height: 60vh');
    }
    ?>
</div>
