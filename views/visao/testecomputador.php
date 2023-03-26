<div class="fieldBody">
    <?php
    if (empty($_REQUEST['tamanho'])) {
        $tamanho = sql::get('cv_tamanho', '*', ['id_inst' => tool::id_inst()], 'fetch')['tam'];
        if (empty($tamanho)) {
            $tamanho = 1;
        }
    } else {
        $tamanho = $_REQUEST['tamanho'];
        $insert['id_inst'] = tool::id_inst();
        $insert['tam'] = $tamanho;

        $model->db->ireplace('cv_tamanho', $insert, 1);
    }
    $img = intval(@$_REQUEST['img']);

    $images = ['0,06', '0,15', '0,3', '0,4', '0,5', '0,6', '0,8', '1,0', '1,2'];
    $images1 = ['0,06', '0,15', '0,3', '0,4', '0,5', '0,6', '0,8', '1.0', '1.2'];
    $tam = ['360', '532', '749', '750', '750', '750', '756', '775', '766'];
    ?>
    <div class=" row">
        <div class="col-sm-4" style="text-align: center">
            <?php
            if ($img != 0) {
                ?>
                <a href="<?php echo HOME_URI ?>/visao/testecomputador?img=<?php echo ($img - 1) ?>">
                    Voltar
                </a>
                <?php
            } else {
                ?>
                Escala: 
                <a style="font-weight: bold; font-size: 30px" href="<?php echo HOME_URI ?>/visao/testecomputador?img=0&tamanho=<?php echo $tamanho + 0.1 ?>" >+</a>
                <a style="font-weight: bold; font-size: 30px" href="<?php echo HOME_URI ?>/visao/testecomputador?img=0&tamanho=<?php echo $tamanho - 0.1 ?>" >-</a>
                (largura correta: 7,2 cm)
                <?php
            }
            ?>
        </div>
        <div class="col-sm-4" style="text-align: center">
            <?php
            if ($img != 0) {
                ?>
                <a href="<?php echo HOME_URI ?>/visao/testecomputador?img=0">
                    Início
                </a>
                <?php
            }
            ?>
        </div>
        <div class="col-sm-4" style="text-align: center">
            <?php
            if ($img < 8) {
                ?>
                <a href="<?php echo HOME_URI ?>/visao/testecomputador?img=<?php echo ($img + 1) ?>">
                    Próximo
                </a>
                <?php
            } else {
                ?>
                <a href = "javascript:window.close()">
                    Fechar
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <br /><br />
    <div style="text-align: center">
        <?php echo $images1[$img] ?>
    </div>
    <br /><br />
    <div style="text-align: center">
        <img id="im" style="width: <?php echo ($tam[$img] * $tamanho) ?>px"  src="<?php echo HOME_URI ?>/views/_images/<?php echo $images[$img] ?>.jpg"/>
    </div>
</div>