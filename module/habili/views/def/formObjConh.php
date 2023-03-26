<?php
$id_oc = filter_input(INPUT_GET, 'id_oc', FILTER_SANITIZE_URL);
$n_oc = @$_REQUEST['n_oc'];
$at_oc = filter_input(INPUT_GET, 'at_oc', FILTER_SANITIZE_URL);
$id_gh = filter_input(INPUT_GET, 'id_gh', FILTER_SANITIZE_URL);
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_URL);
?>
<form target="_parent" action="<?php echo HOME_URI ?>/habili/objtCon" method="POST">
    <div class="row">
        <div class="col-sm-12">
            <?php echo formErp::input('1[n_oc]', 'Obj. Conhecimento', $n_oc) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?php echo formErp::selectDB('coord_grup_hab', '1[fk_id_gh]', 'Grupo de Habilidades', $id_gh, NULL, NULL, NULL, ['at_gh' => 1]); ?>
        </div>
        <div class="col">
            <?php echo formErp::select('1[at_oc]', [1 => 'Sim', 0 => 'Não'], 'Ativo', intval($at_oc)) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col text-center">
            <input type="hidden" name="1[id_oc]" value="<?php echo $id_oc ?>" />
            <input type="hidden" name="id_gh" value="<?php echo $id_gh ?>" />
            <input type="hidden" name="pagina" value="<?php echo $pagina ?>" />
            <?php
            echo formErp::hiddenToken('coord_objeto_conhecimento', 'ireplace');
            echo formErp::button('Salvar');
            ?>
        </div>
    </div>
</form>
