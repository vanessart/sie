<?php
if (!defined('ABSPATH'))
    exit;
?>
<form method="POST">
    <?=
    formErp::hidden([
        'activeNav' => 2
    ])
    ?>
    <button class="btn btn-primary" onclick="edit()">
        Nova Mala Direta
    </button>
</form>


