<style>
    .btnAba{
        border-radius: 15px 15px 0 0;
        width: 100%;
    }
</style>
<div style="margin-bottom: 20px;">
    <?php
    if (empty($_POST[$aba])) {
        $activeNav = 1;
    } else {
        $activeNav = filter_input(INPUT_POST, $aba, FILTER_SANITIZE_NUMBER_INT);
    }

    if (!empty($_POST[$aba])) {
        $activeNav = filter_input(INPUT_POST, $aba, FILTER_SANITIZE_NUMBER_INT);
    } else {
        $activeNav = 1;
        $_POST[$aba] = 1;
    }
    ?>
    <div class="row">
        <?php
        foreach ($abas as $k => $v) {
            if ($activeNav == $k) {
                $situacao = $btn[1];
            } elseif ($activeNav > $k || @$v['ativo'] == 1) {
                $situacao = $btn[2];
            } else {
                $situacao = $btn[0];
            }
            ?>
        <div class="col" style="padding: 0">
                <?php
                if ($activeNav >= $k || @$v['ativo'] == 1) {
                    ?>
                    <form action="<?php echo @$v['link'] ?>" method="POST">
                        <?php
                        if (!empty($v['hidden'])) {
                            echo form::hidden($v['hidden'], 1);
                        }
                    }
                    ?>
                    <input type="hidden" name="<?php echo $aba ?>" value="<?php echo $k ?>" />
                    <button class="border btn btn-<?php echo $situacao ?> btnAba" type="<?php echo empty($v['type']) ? 'submit' : $v['type'] ?>">
                        <?php echo @$v['nome'] ?>
                    </button>
                    <?php
                    if ($activeNav >= $k || @$v['ativo'] == 1) {
                        ?>
                    </form>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
