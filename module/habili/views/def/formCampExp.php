<?php
$id_ce = filter_input(INPUT_GET, 'id_ce', FILTER_SANITIZE_URL);
$n_ce = @$_REQUEST['n_ce'];
$at_ce = filter_input(INPUT_GET, 'at_ce', FILTER_SANITIZE_URL);
$id_gh = filter_input(INPUT_GET, 'fk_id_gh', FILTER_SANITIZE_URL);
?>
<form target="_parent" action="<?php echo HOME_URI ?>/habili/campExp" method="POST">
    <div class="row">
        <div class="col-sm-12">
            <?php echo formErp::input('1[n_ce]', 'Camp. Experiêcia', $n_ce) ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col">
            <?php echo formErp::selectDB('coord_grup_hab', '1[fk_id_gh]', 'Grupo de Hab.', $id_gh, NULL, NULL, NULL, ['at_gh' => 1]); ?>
        </div>
        <div class="col">
            <?php echo formErp::select('1[at_ce]', [1 => 'Sim', 0 => 'Não'], 'Ativo', intval($at_ce)) ?>
        </div>
    </div>
    <br />
        <div class="row">
        <div class="col text-center">
            <input type="hidden" name="1[id_ce]" value="<?php echo $id_ce ?>" />
            <?php
            echo formErp::hiddenToken('coord_campo_experiencia', 'ireplace');
            echo formErp::button('Salvar');
            ?>
        </div>
    </div>
</form>
