<?php
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$activeNav = empty($activeNav) ? 1 : $activeNav;
$ativo = $abas[$activeNav];
unset($abas[$activeNav]);
?>
<style>
    .btnm:hover {
        color: white;

    }
    .btnm {
        width: 100%; 
        margin-bottom: 10px; 
        background-color: white;
    }
</style>
<div class="dropdown" style="text-align: center; width: 100%">
    <a style="width: 100%" class="btn btn-info dropdown-toggle border" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= $ativo['nome'] ?>
    </a>
    <div class="dropdown-menu"  style="width: 100%; text-align: center; background-color: rgba(0,0,0,0); border: none" aria-labelledby="dropdownMenuLink">
        <?php
        foreach ($abas as $k => $v) {
            ?>
            
            <form <?= empty($v['url']) ? '' : 'action="' . $v['url'] . '"' ?> <?= empty($v['target']) ? '' : 'target="' . $v['target'] . '"' ?> method="POST">
                <?= form::hidden($v['hidden']) ?>
                <?= form::hidden(['activeNav' => $k]) ?>
                <button type="submit" class="btn btn-outline-info btnm border">
                <?= $v['nome'] ?>
            </button>
            </form>
            <?php
        }
        ?>
    </div>
</div>
