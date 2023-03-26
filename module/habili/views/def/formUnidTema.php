<?php
$id_ut = filter_input(INPUT_GET, 'id_ut', FILTER_SANITIZE_URL);
$n_ut = @$_REQUEST['n_ut'];
$at_ut = filter_input(INPUT_GET, 'at_ut', FILTER_SANITIZE_URL);
$id_gh = filter_input(INPUT_GET, 'id_gh', FILTER_SANITIZE_URL);
$id_disc = filter_input(INPUT_GET, 'fk_id_disc', FILTER_SANITIZE_URL);
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_URL);
?>
<form target="_parent" action="<?php echo HOME_URI ?>/habili/unidTema" method="POST">
    <div class="row">
        <div class="col-sm-12">
            <?php echo formErp::input('1[n_ut]', 'Un. Temática', $n_ut) ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col">
            <?php echo formErp::selectDB('coord_grup_hab', '1[fk_id_gh]', 'Grupo de Habilidades', $id_gh, NULL, NULL, NULL, ['at_gh' => 1]); ?>
        </div>
        <div class="col">
            <?php echo formErp::selectDB('ge_disciplinas', '1[fk_id_disc]', 'Disciplina', $id_disc); ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?php echo formErp::select('1[at_ut]', [1 => 'Sim', 0 => 'Não'], 'Ativo', intval($at_ut)) ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-12 text-center">
            <input type="hidden" name="1[id_ut]" value="<?php echo $id_ut ?>" />
            <input type="hidden" name="id_gh" value="<?php echo $id_gh ?>" />
            <input type="hidden" name="pagina" value="<?php echo $pagina ?>" />
            <?php
            echo formErp::hiddenToken('coord_uni_tematica', 'ireplace');
            echo formErp::button('Salvar');
            ?>
        </div>
    </div>
</form>
