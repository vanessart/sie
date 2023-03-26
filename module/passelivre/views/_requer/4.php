<?php
if (!defined('ABSPATH'))
    exit;
if (!empty($req['latitude']) && !empty($req['latitude']) && !empty($esc)) {
    ?>
    <iframe
        frameborder="0" style="border:0; width: 100%; height: 500px"
        src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyBcFBN29_muY_zDoI92Ibdzr0dgaTjt5Ho&origin=<?php echo trim($req['latitude']) ?>, <?php echo trim($req['longitude']) ?>&destination=<?php echo $esc['maps'] ?>&mode=walking" allowfullscreen>
    </iframe> 
    <?php
} elseif (!empty($req['latitude']) && !empty($req['latitude'])) {
    ?>
    <iframe
        frameborder="0" style="border:0; width: 100%; height: 60vh"
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBcFBN29_muY_zDoI92Ibdzr0dgaTjt5Ho&q=<?php echo trim($req['latitude']) ?>, <?php echo trim($req['longitude']) ?>" allowfullscreen>
    </iframe>
    <?php
}
//$maps = toolErp::distancia((trim($req['latitude']) . ',' . trim($req['longitude'])), $esc['maps']);
?>

