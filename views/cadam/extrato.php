<?php
ob_start();

$cargos = sql::idNome('cadam_cargo');

$cadam = $model->getCadampe($_POST['id_cad']);

@$trabalhado = $model->extrato($_POST['id_cad'], $_POST['mes']);

@$diasEHoras = $model->diasEHoras($trabalhado);
?>
<style>
    td{
        padding: 5px
    }
</style>
<div style="text-align: center; font-size: 22; font-weight: bold">
    EXTRATO SUBSTITUIÇÃO EVENTUAL - CADAMPE
</div>
<br /><br />
<table border="1" style="width: 100%">
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold; font-size: 22px">
            <?php echo data::mes(@$_POST['mes']) ?>
            /
            <?php echo date("Y") ?>
        </td>
    </tr>
    <tr>
        <td>
            Cadampe:
            <?php echo $cadam['n_pessoa'] ?>
        </td>
        <td>
            Cadastro:
            <?php echo $cadam['cad_pmb'] ?>
        </td>
    </tr>
    <tr>
        <td>
            RG:
            <?php echo $cadam['rg'] . (!empty($cadam['rg_oe']) ? '-' . $cadam['rg_oe'] : '') ?>
        </td>
        <td>
            CPF:
            <?php echo $cadam['cpf'] ?>
        </td>
    </tr> 
    <tr>
        <td colspan="2">
            Disciplina(s):
            <?php
            $disc = explode("|", $cadam['cargos_e']);
            $ds = count($disc);
            unset($disc[0]);
            unset($disc[$ds - 1]);
            $ds = count($disc);
            $ct = 1;
            foreach ($disc as $v) {
                if ($ds == 1) {
                    echo $cargos[$v];
                } elseif ($ct < $ds) {
                    echo $cargos[$v] . ', ';
                } else {
                    echo ' e ' . $cargos[$v];
                }
                $ct++;
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Total de Dias:
            <?php echo @$diasEHoras['contaDias'] ?>
        </td>
        <td>
            Total de Horas/Aulas
            <?php echo @$diasEHoras['contaHoras'] ?>
        </td>
    </tr>

</table>
<table border="1" style="width: 100%">
    <tr>
        <td>
            Dia
        </td>
        <td>
            Unidade Escolar
        </td>
        <td>
            Professor substituído / Aluno
        </td>
        <td>
            RM / RSE
        </td>
        <td>
            Motivo
        </td>
        <td>
            Período
        </td>
        <td>
            Classe(s)
        </td>
        <td>
            Discriminação
        </td>
        <td>
            V.Un.
        </td>
        <td>
            Qt.
        </td>
        <td>
            Subtotal
        </td>
    </tr>

    <?php
    foreach ($trabalhado as $v) {
        if ($diaOld <> $v['dia']) {
            ?>
            <tr>
                <td>
                    <?php echo $v['dia'] ?>
                </td>            
                <td>
                    -
                </td>            
                <td>
                    -
                </td>           
                <td>
                    -
                </td>            
                <td>
                    -
                </td>           
                <td>
                    -
                </td>
                <td>
                    -
                </td>
                <td>
                    Adicional eventualidade
                </td>
                <td style="text-align: right">
                    <?php echo $v['v_dia'] ?>
                    <?php //echo 'R$ ' . number_format($v['v_dia'], 2, ',', '.'); ?>
                </td>
                <td style="text-align: center">
                    1
                </td>
                <td style="text-align: right">
                    <?php
                    echo $v['v_dia'];
                    //echo 'R$ ' . number_format($v['v_dia'], 2, ',', '.');
                    @$totalAd += $v['v_dia'];
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>
                <?php echo $v['dia'] ?>
            </td>
            <td>
                <?php echo substr($v['n_inst'], 0, 30) ?>
            </td>
            <td>
                <?php echo $v['n_pessoa'] ?>
            </td>
            <td>
                <?php echo $v['rm'] ?>
            </td>
            <td>
                <?php echo @$v['n_mot'] ?>
            </td>
            <td>
                <?php echo gtMain::periodoDoDia($v['periodo']) ?>
            </td>
            <td>
                <?php
                if (is_numeric(@$v['turmas'])) {
                    echo sql::get('ge_turmas', 'n_turma', ['id_turma' => @$v['turmas']], 'fetch')['n_turma'];
                } else {
                    echo @$v['turmas'];
                }
                ?>
            </td>
            <td>
                <?php echo $v['tipo'] ?>
            </td>
            <td style="text-align: right">
                <?php
                echo $v['v_hora'];
                //echo 'R$ ' . number_format($v['v_hora'], 2, ',', '.');
                ?>
            </td>
            <td style="text-align: center">
                <?php echo $v['horas'] ?>
            </td>
            <td style="text-align: right">
                <?php
                $subt = $v['horas'] * $v['v_hora'];
                echo 'R$ ' . number_format(@$subt, 2, ',', '.');
                if ($v['tp'] == 'tea') {
                    @$totalTea += $subt;
                } else {
                    @$totalSimples += $subt;
                }
                ?>
            </td>
        </tr>

        <?php
        $diaOld = $v['dia'];
    }
    ?>
    <tr>
        <td colspan="10">
            Subtotal - Adicional eventualidade
        </td>
        <td>
            <?php echo 'R$ ' . number_format((@$totalAd), 2, ',', '.'); ?>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            Subtotal - Horas/aulas (Simples)
        </td>
        <td>
            <?php echo 'R$ ' . number_format((@$totalSimples), 2, ',', '.'); ?>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            Subtotal - Horas/aulas (TEA)
        </td>
        <td>
            <?php echo 'R$ ' . number_format((@$totalTea), 2, ',', '.'); ?>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            Total
        </td>
        <td style="text-align: right">
            <?php echo 'R$ ' . number_format((@$totalAd + @$totalSimples + @$totalTea), 2, ',', '.'); ?>
        </td>
    </tr>
</table>
<?php
tool::pdfSecretaria('L');
