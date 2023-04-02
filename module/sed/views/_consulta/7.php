<?php
$ra = filter_input(INPUT_POST, 'ra', FILTER_UNSAFE_RAW);
$uf = filter_input(INPUT_POST, 'uf', FILTER_UNSAFE_RAW);
if ($ra && $uf) {
    $dados = rest::exibirFichaAluno($ra, $uf);
}

?>
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::input('ra', 'RA', $ra) ?>
        </div>
        <div class="col">
            <?= formErp::input('uf', 'UF', $uf) ?>
        </div>
        <div class="col">

            <?=
            formErp::hidden([
                'activeNav' => 7
            ])
            . formErp::button('Buscar');
            ?>


        </div>

    </div>
</form>
<br />
<table class="table table-bordered table-hover table-striped">
    <?php
    if (!empty($dados)) {
        foreach ($dados as $kt => $t) {
            if (is_array(@$t)) {
                ?>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold">
                        <?= str_replace('out', '', $kt) ?>
                    </td>
                </tr>
                <?php
                foreach ($t as $k => $v) {
                    if ($v) {
                        if (is_array($v)) {
                            foreach ($v as $ky => $y) {
                                ?>
                                <tr>
                                    <td>
                                        <?= str_replace('out', '', $ky) ?>
                                    </td>
                                    <td>
                                        <?= $y ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td>
                                    <?= str_replace('out', '', $k) ?>
                                </td>
                                <td>
                                    <?= $v ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
            }
        }
    }
    ?>
</table>
<?php

##################            
?>
  <pre>   
    <?php
      print_r($dados);
    ?>
  </pre>
<?php
###################