<?php
if (!defined('ABSPATH'))
    exit;
?>
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::input('1[maps]', 'Endereço Maps', $esc->_maps) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[latitude]', 'Latitude', $esc->_latitude) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[longitude]', 'Longitude', $esc->_longitude) ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 20px">
        <?=
        formErp::hidden([
            'id_inst' => $id_inst,
            '1[id_escola]' => $esc->_id_escola,
            'activeNav' => 4
        ])
        . formErp::hiddenToken('ge_escolas', 'ireplace')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
<?php
if (!empty($esc->_maps)) {
    $endMaps = $esc->_maps;
} elseif (!empty($esc->_latitude) && !empty($esc->_longitude)) {
    $endMaps = $esc->_latitude . ', ' . $esc->_longitude;
}
if (!empty($endMaps)) {
    ?>
    <iframe
        style="height: 60vh; width: 100%"
        frameborder="0" style="border:0"
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBcFBN29_muY_zDoI92Ibdzr0dgaTjt5Ho&q=<?= $endMaps ?>" allowfullscreen>
    </iframe>
    <?php
} else {
    ?>
    <div class="alert alert-danger">
        Para visualizar o mapa é necessário o Enderoço Maps ou Coordenadas
    </div>
    <?php
}
?>
