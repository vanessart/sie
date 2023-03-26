<?php
if (!defined('ABSPATH'))
    exit;
$esc = ng_escola::longLat(toolErp::id_inst());
if (!empty($end['latitude']) && !empty($end['longitude']) && !empty($esc))
{
   // echo toolErp::geraMapa( [ ['lat' => $end['latitude'], 'long' => $end['longitude']], ['lat' => $esc['latitude'], 'long' => $esc['longitude']] ]);
    ?>
    <iframe
        frameborder="0" style="border:0; width: 100%; height: 500px"
        src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyBcFBN29_muY_zDoI92Ibdzr0dgaTjt5Ho&origin=<?php echo trim($end['latitude']) ?>, <?php echo trim($end['longitude']) ?>&destination=<?php echo $esc['maps'] ?>&mode=walking" allowfullscreen>
    </iframe> 
    <?php
} elseif (!empty($end['latitude']) && !empty($end['latitude'])) {
    ?>
    <iframe
        frameborder="0" style="border:0; width: 100%; height: 60vh"
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBcFBN29_muY_zDoI92Ibdzr0dgaTjt5Ho&q=<?php echo trim($end['latitude']) ?>, <?php echo trim($end['longitude']) ?>" allowfullscreen>
    </iframe>
    <?php
}
//$maps = toolErp::distancia((trim($end['latitude']) . ',' . trim($end['longitude'])), $esc['maps']);
    ?>

    