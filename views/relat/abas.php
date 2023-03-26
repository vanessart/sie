<div>
    <?php
    $btn = [
        5 => "default",
        6 => "primary",
        2 => "warning",
        3 => "success",
        4 => "danger",
    ];

    if (!empty($_POST['activeNav'])) {
        $activeNav = $_POST['activeNav'];
    } else {
        $activeNav = 1;
    }
    $col_md = 100 / count($abas);
    foreach ($abas as $k => $v) {
        if ($activeNav == $k) {
            $situacao = "primary";
        } elseif ($v['ativo'] == 0) {
            $situacao = "default";
        } elseif ($v['ativo'] == 1) {
            $situacao = "warning";
        } else {
            $situacao = $btn[$v['ativo']];
        }
        ?>
        <div style="float: left; width: <?php echo $col_md ?>%">
            <?php
            if ($v['ativo'] <> 0) {
                ?>
                <form action="<?php echo @$v['link'] ?>" method="POST">
                    <input type="hidden" name="activeNav" value="<?php echo $k ?>" />
                    <?php
                    if (!empty($v['hidden'])) {
                        echo formulario::hidden($v['hidden']);
                    }
                }
                ?>
                <button class="btn btn-<?php echo $situacao ?>" style="width: 100%">
                    <span style="float: left">                               
                        <?php echo @$v['nome'] ?>
                    </span>
                    <span style="float: right" class="badge"><?php echo $k ?></span>

                </button>
                <?php
                if ($v['ativo'] <> 0) {
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