<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
?> 
<div class="body" style="text-align: center">
    <?php
    if (!empty($id_pessoa)) {
        ?>
        <img style="width: 100%" src="<?= HOME_URI ?>/pub/fotos/<?= $id_pessoa ?>.jpg" alt="alt"/>
        <?php
    } else {
        ?>
        <img style="width:100%;"src="<?= HOME_URI . '/'. INCLUDE_FOLDER .'/images/anonimo.jpg' ?>" alt="..." ><br><br>
        <?php
    }
    ?>
</div>
