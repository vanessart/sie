<?php
if (!defined('ABSPATH'))
    exit;
$pl = $model->PlTurmas();
$cur = $model->cursosTodosPl();
$st = ['DEF', 'IND', 'MAT', 'AP', 'CON', 'APR', 'REP', 'DES', 'NAP'];
extract(tool::postFilter($st));
$col = 0;

foreach ($st as $v) {
    $col += $$v;
}
?>
<div class="body">
    <div class="fieldTop">
        Relatório geral
    </div>
    <br />
      <div style="text-align: center">
        <form target="_blank" action="<?= HOME_URI ?>/quali/pdf/geral.php">
            <button class="btn btn-info">
                Exportar todos os dados
            </button>
        </form>
    </div>
    <br />
    <form method="POST">
        <div class="alert alert-primary">
            <table style="width: 100%">
                <tr>
                    <td>
                        <?= form::checkbox('DEF', 1, 'DEF = Deferido', $DEF) ?> 
                    </td>
                    <td>
                        <?= form::checkbox('IND', 1, 'IND = Indeferido', $IND) ?> 
                    </td>
                    <td>
                        <?= form::checkbox('AP', 1, 'AP = Acessaram a Plataforma', $AP) ?> 
                    </td>
                    <td rowspan="3">
                        <?= form::button('Enviar') . form::hidden(['enviar' => 1]) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= form::checkbox('NAP', 1, 'NAP = Não acessaram a Plataforma', $NAP) ?> 
                    </td>
                    <td>
                        <?= form::checkbox('DES', 1, 'DES = Desistiram', $DES) ?> 
                    </td>
                    <td>
                        <?= form::checkbox('MAT', 1, 'MAT = Matriculados', $MAT) ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= form::checkbox('CON', 1, 'CON = Concluiram o Curso', $CON) ?> 
                    </td>
                    <td>
                        <?= form::checkbox('APR', 1, 'APR = Aprovados', $APR) ?> 
                    </td>
                    <td>
                        <?= form::checkbox('REP', 1, 'REP = Reprovados', $REP) ?> 
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <?php
    if (!empty($_POST['enviar']) && $col > 0) {
        $geral = $model->relatGeral();
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td rowspan="2">
                    Curso/Ciclo
                </td>
                <?php
                $cor = 1;
                foreach ($pl as $k => $v) {
                    ?>
                    <td colspan="<?= $col ?>" class="alert alert-<?= tool::classAlert($cor++) ?>" style="text-align: center">
                        <?= $v ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php
                $cor = 1;
                foreach ($pl as $k => $v) {
                    foreach ($st as $s) {
                        if (!empty($$s)) {
                            ?>
                            <td class="alert alert-<?= tool::classAlert($cor) ?>">
                                <?= $s ?>
                            </td>
                            <?php
                        }
                    }
                    $cor++;
                }
                ?> 
            </tr>
            <?php
            foreach ($cur as $kc => $c) {
                if ($kc != 102) {
                    ?>
                    <tr>
                        <td>
                            <?= $c ?>
                        </td>
                        <?php
                        $cor = 1;
                        foreach ($pl as $k => $v) {
                            foreach ($st as $s) {
                                if (!empty($$s)) {
                                    ?>
                                    <td class="alert alert-<?= tool::classAlert($cor) ?>">
                                        <?php
                                        if (!empty(@$geral[$k][$kc][$s])) {
                                            echo intval($geral[$k][$kc][$s]);
                                            @$soma[$k][$s] += $geral[$k][$kc][$s];
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                            }
                            $cor++;
                        }
                        ?>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <td>
                    Total
                </td>
                <?php
                $cor = 1;
                foreach ($pl as $k => $v) {
                    foreach ($st as $s) {
                        if (!empty($$s)) {
                            ?>
                            <td class="alert alert-<?= tool::classAlert($cor) ?>">
                                <?php
                                if (!empty(@$soma[$k][$s])) {
                                    echo @$soma[$k][$s];
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <?php
                        }
                    }
                    $cor++;
                }
                ?>
            </tr>
        </table>                          
        <?php
    }
    ?>
</div>
