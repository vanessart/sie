<?php
if (!defined('ABSPATH'))
    exit;
$alterado = $model->salvaCromeRede();
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
$serial = trim(filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING));
$escola = $model->escolasOpt();
if (!empty($id_ch) || !empty($serial)) {
    $ch = $model->chromebook($id_ch, $serial);
    $id_ch = $ch['id_ch'];
    $serial = $ch['serial'];
} else {
    ?>
    <div class="alert alert-danger" style="text-align: center; font-weight: bold">
        Não sei o que você está fazendo aqui?
    </div>
    <?php
    exit();
}
$sit = sql::idNome('lab_chrome_status');

$destinoSet = $model->destino($ch['fk_id_inst'], $ch['id_pessoa'], $ch['fk_id_ms']);


?>
<div class="body">
    <?php
    if ($ch) {
        if (!empty($alterado)) {
            ?>
            <div class="alert alert-success text-center">
                Alterado com sucesso!
            </div>
            <?php
        }
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Número de Série
                </td>
                <td>
                    <?= $ch['serial'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    MAC
                </td>
                <td>
                    <?= $ch['mac'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Status
                </td>
                <td>
                    <?= $ch['n_cs'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Destino
                </td>
                <td>
                    <?= $destinoSet ?>
                </td>
            </tr>
            <?php
            if (!empty($ch['n_inst'])) {
                ?>
                <tr>
                    <td>
                        Escola
                    </td>
                    <td>
                        <?= $ch['n_inst'] ?>
                    </td>
                </tr>
                <?php
            }
            if (!empty($ch['n_pessoa'])) {
                ?>
                <tr>
                    <td>
                        Reponsável
                    </td>
                    <td>
                        <?php
                        echo $ch['n_pessoa'];
                        if (!empty($ch['rm'])) {
                            echo ' (' . $ch['rm'] . ')';
                        } else {
                            echo ' (' . $ch['id_pessoa'] . ' ';
                        }
                        ?>

                    </td>
                </tr>
                <?php
                if (!empty($ch['emailgoogle'])) {
                    ?>
                    <tr>
                        <td>
                            E-mail
                        </td>
                        <td>
                            <?= $ch['emailgoogle'] ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>

        </table>
    </div>
    <div class="border5">
        <form method="POST">
            <div class="row">
                <div class="col-4">
                    <?php
                    if (in_array(tool::id_pessoa(), [1, 5, 1327])) {
                        unset($sit[3]);
                        echo formErp::select('1[fk_id_cs]', $sit, 'Situação', @$ch['fk_id_cs'], null, null, ' required ');
                    } else {
                        if (in_array($ch['fk_id_cd'], [2, 3])) {
                            echo formErp::input(null, 'Situação', 'Emprestado', ' readonly');
                            echo formErp::hidden(['1[fk_id_cs]' => 3]);
                        } else {
                            unset($sit[3]);
                            echo formErp::select('1[fk_id_cs]', $sit, 'Situação', @$ch['fk_id_cs'], null, null, ' required ');
                        }
                    }
                    ?>
                </div>
            </div>
            <br />
            <?php
            if (!empty($ch['n_pessoa'])) {
                ?>
                <div class="alert alert-danger">
                    Os dados não podem ser alterados enquanto o chromebook estiver emprestado  
                </div>
                <?php
            } else {
                ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::select('1[fk_id_inst]', $escola, ['Escola', 'Secretaria de Educação'], @$ch['fk_id_inst']) ?>
                    </div>
                </div>
                <br /><br />
                <div style="text-align: center">
                    <?=
                    formErp::hidden([
                        'id_ch' => $id_ch,
                        'serial' => $serial,
                        '1[id_ch]' => $id_ch,
                    ])
                    . formErp::hiddenToken('salvaCromeRede')
                    ?>
                    <button type="submit" class="btn btn-success">
                        Salvar
                    </button>
                </div>
                <?php
            }
            ?>
        </form>
        <?php
    } else {
        ?>
        <div class="alert alert-danger">
            Chromebook não encontrado
        </div>
        <?php
    }
    ?>
</div>

