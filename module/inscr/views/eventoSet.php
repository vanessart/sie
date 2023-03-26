<?php
if (!defined('ABSPATH'))
    exit;
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_SANITIZE_NUMBER_INT);
if ($id_cate) {
    $cate = sql::get('inscr_categoria', '*', ['id_cate' => $id_cate], 'fetch');
    $abaUp = 1;
    $e = explode(' ', $cate['n_cate']);
    $n_cate = $e[0] . ' ' . @$e[1] . ' ' . @$e[2];
    if (count($e) > 3) {
        $n_cate .= '...';
    }
} else {
    $abaUp = null;
}

if ($id_evento) {
    $eve = sql::get('inscr_evento', '*', ['id_evento' => $id_evento], 'fetch');
    $abaCate = 1;
    $e = explode(' ', $eve['n_evento']);
    $n_evento = $e[0] . ' ' . @$e[1] . ' ' . @$e[2];
    if (count($e) > 3) {
        $n_evento .= '...';
    }
} else {
    $abaCate = null;
}
$cates = sql::get('inscr_categoria', '*', ['fk_id_evento' => $id_evento, '>' => 'ordem']);
$evento = sql::get('inscr_evento', '*', ['<' => 'dt_inicio']);
$abas[1] = ['nome' => "Eventos", 'ativo' => 1, 'hidden' => []];
$abas[3] = ['nome' => "Categorias" . (empty($n_evento) ? '' : ' - ' . $n_evento), 'ativo' => $abaCate, 'hidden' => ['id_evento' => $id_evento]];
$abas[4] = ['nome' => "Uploads" . (empty($n_cate) ? '' : ' - ' . $n_cate), 'ativo' => $abaUp, 'hidden' => ['id_evento' => $id_evento, 'id_cate' => $id_cate]];
$abas[2] = ['nome' => "Uploads Genéricos", 'ativo' => $abaCate, 'hidden' => ['id_evento' => $id_evento]];
?>
<div class="body">
    <div class="row" style="margin-top: 15px">
        <div class="col" style="text-align: center; font-weight: bold; font-size: 1.5em">
            Cadastro de Eventos
        </div>
        <div class="col text-center">
            <?php
            if (!empty($id_evento)) {
                ?>
                <form target="_blank" action="<?= HOME_URI ?>/inscr/inscr/<?= $id_evento ?>">
                    <button class="btn btn-primary">
                        https://portal.educ.net.br/ge/inscr/inscr/<?= $id_evento ?>
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    $aba = report::abas($abas);
    include ABSPATH . "/module/inscr/views/_eventoSet/$aba.php";
    ?>
</div>
