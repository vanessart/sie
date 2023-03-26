<?php
ob_start();

$cargos = sql::idNome('cadam_cargo');

$cadam = $model->getCadampe($_POST['id_cad']);

@$trabalhado = $model->trabalhado($_POST['id_cad'], $_POST['mes']);

@$diasEHoras = $model->diasEHoras($trabalhado)
?>
<style>
    td{
        padding: 5px;
    }
</style>
<div style="text-align: center; font-size: 22; font-weight: bold">
    FICHA DE FREQUÊNCIA SUBSTITUIÇÃO EVENTUAL - CADAMPE
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
        <td>
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
            Tipo
        </td>
        <td>
            Horas/Aulas
        </td>
        <td>
            Professor substituído / Aluno
        </td>
        <td>
            RM / RSE
        </td>
        <td>
            Turma(s)
        </td>
        <td>
            Período
        </td>
        <td>
            Disciplina
        </td>
        <td>
            Motivo
        </td>
        <td>
            Unidade Escolar
        </td>
    </tr>
    <?php
    foreach ($trabalhado as $v) {
        ?>
        <tr  style="background-color: white; color: black;">
            <td>
                <?php echo $v['dia'] ?>
            </td>
            <td>
                <?php echo $v['tipo'] ?>
            </td>
            <td>
                <?php echo $v['horas'] ?>
            </td>
            <td>
                <?php echo $v['n_pessoa'] ?>
            </td>
            <td>
                <?php echo $v['rm'] ?>
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
                <?php echo gtMain::periodoDoDia($v['periodo']) ?>
            </td>
            <td>
                <?php echo @$cargos[$v['fk_id_cargo']] ?>
            </td>
            <td>
                <?php echo $v['n_mot'] ?>
            </td>
            <td>
                <?php echo substr($v['n_inst'], 0, 30) ?>
            </td>
        </tr>

        <?php
    }
    ?>
</table>
<?php
tool::pdfSecretaria('L');
