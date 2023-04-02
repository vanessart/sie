<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$up = filter_input(INPUT_POST, 'up', FILTER_UNSAFE_RAW);
$tipoDoc = sql::idNome('sed_prontuario', ['at_pront'=>1]);

?>
<div class="body">
    <?php
    if ($up == 'doc') {
        include ABSPATH . '/module/sed/views/_aluno/_5/form.php';
    }elseif ($up == 'webcam') {
        include ABSPATH . '/module/sed/views/_aluno/_5/web.php';
    }
    ?>
</div>


<input type="hidden" name="activeNav" value="5" />
