<?php
ob_start();
$escola = new escola();
$seq = 1;
$cor = '#F5F5F5';

@$evento = @$_POST['evento'];
@$tur = @$_POST['id_turma'];
@$id_eve = @$_POST['id_eve'];
@$grupo = @$_POST['id_grupo'];
?>
<head>
    <style>
        td{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 3px;
        }
        .titulocab{
            font-weight:bolder;
            font-size: 10px;
            background-color: #000000;
            color:#ffffff;
            border-width: 1px;
            text-align: center;
            padding-left: 5px;
            padding-right: 5px;
            border: solid;
            border-style: border;
            height: 5px
        }
        .topo{
            height: 5px;
            font-size: 9pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>   
</head>
<?php
set_time_limit(120);
if (!empty($_POST['sel'])) {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $a[] = $v;
        }
    }
} else {
    exit();
}

$a = implode(',', $a);

$dados = "Select id_pessoa, n_pessoa, fk_id_turma, chamada, situacao, codigo_classe,"
        . " sexo, ra, rg, dt_nasc"
        . " From pessoa"
        . " JOIN ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa "
        . " join ge_turmas t on t.id_turma = ge_turma_aluno.fk_id_turma "
        . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
        . " Where pessoa.id_pessoa in (" . $a . ") "
        . " order by chamada";

$query = $model->db->query($dados);
$alunos = $query->fetchALL();
$sql = "SELECT `ddd`, `num`, fk_id_pessoa FROM `telefones` WHERE `fk_id_pessoa` IN (" . $a . ") ";
$query = pdoSis::getInstance()->query($sql);
$t = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($t as $v) {
    if (is_numeric($v['num']) && empty($tel[$v['fk_id_pessoa']])) {
        $tel[$v['fk_id_pessoa']] = '(' . $v['ddd'] . ') ' . $v['num'];
    }
}
if (empty($alunos)) {
    echo ("O cadastro do aluno está incompleto");
}
$tit = filter_input(INPUT_POST, 'titulo_evento');

if (!empty($proximaFolha)) {
    ?>
    <div style="page-break-after: always"></div>
    <?php
} else {
    $proximaFolha = 1;
}
?>

<div class="titulocab">
    <?php echo $tit ?>
</div>

<table class="table tabs-stacked table-bordered">;
    <thead>
        <tr>
            <td style="color: red">
                Seq.
            </td>
            <td style="color:red"> 
                Cod.Classe
            </td>
            <td style="color:red">
                RSE
            </td>
            <td style="color:red">
                RA
            </td>
            <td style="color:red">
                RG
            </td>
            <td style="color:red">
                Data Nasc.
            </td>
            <td style="color:red">
                Sexo
            </td>
            <td style="color:red">
                Telefone
            </td>
            <td style="color: red">
                Nome Aluno
            </td>
            <td style="color:red">
                Observação
            </td>
        </tr>
    </thead>
    <?php
    foreach ($alunos as $v) {
        ?>
        <tbody>
            <tr>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $seq++ ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['codigo_classe'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['ra'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['rg'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['sexo'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?= @$tel[$v['id_pessoa']] ?>
                </td>
                <td style="text-align: left;  background-color: <?php echo $cor ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo " " ?>
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>
            </tr>
        </tbody>
        <?php
    }
    ?>
</table>
<?php
tool::pdfEscola();
?>