<?php
if (!defined('ABSPATH')) {
    exit;
}
$id_pessoa = toolErp::id_pessoa();
$tpd = $model->turmaDisciplina($id_pessoa);

$diaSem = filter_input(INPUT_POST, 'diaSem', FILTER_SANITIZE_STRING);
if (empty($diaSem)) {
    $diaSem = date('N');
    if (in_array($diaSem, [6, 7])) {
        $diaSem = 't';
    }
}
$dias = [
    't' => 'T', '1' => '2ª', 2 => '3ª', 3 => '4ª', 4 => '5ª', 5 => '6ª'
];
if ($diaSem == 't') {
    $file = 1;
} else {
    $file = 2;
}
?>
<style>
    .bola{
        height: 40px;
        width: 40px;
        border-radius: 50%;
        border: 1px solid black;
        background:white;
        cursor: pointer;
    }

    #border-main{
        margin:30px
    }

    #chamada-buttom{
        height:120em;
    }
    .geral{
        margin: 0 auto;
        max-width:800px;
    }

</style>
<div class="body">
    <table style="width: 100%">
        <tr>
            <?php
            foreach ($dias as $k => $v) {
                if ($diaSem == $k) {
                    $background_color = 'blue';
                } else {
                    $background_color = 'whrite';
                }
                ?>
                <td style="text-align: center">
                    <form method="POST">
                        <input type="hidden" name="diaSem" value="<?= $k ?>" />
                        <input type="submit" style="background-color: <?= $background_color ?>" value="<?= $v ?>" class="bola">
                    </form>
                </td>
                <?php
            }
            ?>
        </tr>
    </table>
    <br />
    <?php
    include ABSPATH . "/module/profe/views/_main/$file.php";
    ?>
</div>