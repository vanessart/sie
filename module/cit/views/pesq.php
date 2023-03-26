<?php
if (!defined('ABSPATH'))
    exit;
ini_set('memory_limit', '2000M');

//$dado = cit::funcionarios(40868, null, 'readaptacoes');
$apis = [
    null,
    'dependentes',
    'formacoes',
    'cargoorigem',
    'salarioorigem',
    'cargos',
    'salarios',
    'cargahoraria',
    'situacoes',
    'remanejamentos',
    'ferias',
    'suspestagioprob',
    'readaptacoes',
    'advertencias',
    'suspensoes',
    'temposervico'
];
?>
<div class="body">
    <table class="table table-bordered table-hover table-striped" style="width: 800px; margin: auto">
        <?php
        $c = 1;
        foreach ($apis as $api) {

            $dado = cit::funcionarios(59851, null, $api);
            ?>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 1.4em">
                    <?= $c++ ?> - api/funcionarios/<?= $api ?>
                </td>
            </tr>
            <?php
            if (!empty($dado)) {
                foreach ($dado as $d) {
                    if (is_object(@$d)) {
                        foreach ($d as $k => $v) {
                            ?>
                            <tr>
                                <td>
                                    <?= $k ?>
                                </td>
                                <td>
                                    <?= $v ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                    <tr>
                        <td colspan="2" style="text-align: center; font-weight: bold; font-size: 1.4em">
                            ----------------------------------------------
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        ?>
    </table>
</div>
<?php
exit();
?>

<div class="body">
    <form target="_blank" action="https://erpeducgp.app.br/api/professor/lote/2022" method="POST">
        <input type="text" name="login" value="cliente" />
        <input type="text" name="pswd" value="fr6%5uhmzy" />
        <input type="submit" name="" value="ir" />
    </form>
</div>

