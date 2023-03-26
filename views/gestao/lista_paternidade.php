<?php
ob_start();
$escola = new escola();

$cor = '#F5F5F5';
?>
<head>
    <style>
        td{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .quebra {
            page-break-before: always;
        }
    </style>
</head>

<?php
foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idTumas[] = $v;
    }
}
$idTumas = implode(",", $idTumas);

$wsql = "Select id_pessoa, n_pessoa, fk_id_turma, chamada, codigo_classe, sexo From pessoa "
        . " JOIN ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa"
        . " Where ge_turma_aluno.fk_id_turma  in (" . $idTumas . ") And situacao = 'Frequente'"
        . " order by chamada";

$query = $model->db->query($wsql);
$listapiloto = $query->fetchAll();

foreach ($listapiloto as $v) {
    $cla[$v['fk_id_turma']][] = $v;
    $resp[] = $v['id_pessoa'];
}
$resp = implode(',', $resp);

$sql = "SELECT * FROM ge_aluno_responsavel WHERE fk_id_pessoa_aluno IN ($resp) and fk_id_rt IN (1, 2) ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    if ($v['fk_id_rt'] == 1) {
        $mae[$v['fk_id_pessoa_aluno']] = $v['fk_id_pessoa_resp'];
    } else {
        $pai[$v['fk_id_pessoa_aluno']] = $v['fk_id_pessoa_resp'];
    }
}

foreach ($mae as $k => $v) {
    $sql = "SELECT p.id_pessoa, p.n_pessoa, p.cpf FROM pessoa p"
            . " WHERE p.id_pessoa = $v";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $w) {
        $mae[$k] = [
            'mae' => $w['n_pessoa'],
            'cpfmae' => $w['cpf']
        ];
    }
}
foreach ($pai as $k => $v) {
    $sql = "SELECT p.id_pessoa, p.n_pessoa, p.cpf FROM pessoa p"
            . " WHERE p.id_pessoa = $v";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $w) {
        $pai[$k] = [
            'pai' => $w['n_pessoa'],
            'cpfpai' => $w['cpf']
        ];
    }
}

foreach ($cla as $kw => $w) {
    $per = explode('|', $model->completadadossala($kw));
    $prof = $model->pegaprof($kw);
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold;  font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        <?php echo $escola->_nome ?>
    </div>
    <div style="font-weight:bold; border: 1px solid; font-size:10pt; color:red; text-align: center">
        Lista Filiação - Alunos Frequentes - Simples Conferência
    </div>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $per[2] . ' - Prof. ' . $prof ?>
    </div>
    <table class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td rowspan="2">
                    Nº Ch       
                </td>
                <td rowspan="2">
                    RSE       
                </td> 
                <td rowspan="2">
                    Nome do Aluno
                </td>
                <td colspan="2">
                    Filiação
                </td>
                <td colspan="2">
                    CPF     
                </td>
            </tr>
            <tr>
                <td>
                    Pai
                </td>
                <td>
                    Mãe
                </td>
                <td>
                    Pai
                </td>
                <td>
                    Mãe
                </td>           
            </tr>
        </thead>
        <?php
        foreach ($w as $v) {
            ?>
            <tbody>
                <tr>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['chamada'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['id_pessoa'] ?>
                    </td>
                    <td style="text-align: left;background-color: <?php echo $cor ?>">
                        <?php echo addslashes($v['n_pessoa']) ?>
                    </td>    
                    <td style="text-align: left;background-color: <?php echo $cor ?>">
                        <?php echo addslashes(@$pai[$v['id_pessoa']]['pai']) ?>
                    </td>
                    <td style="text-align: left;background-color: <?php echo $cor ?>">
                        <?php echo addslashes(@$mae[$v['id_pessoa']]['mae']) ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo @$pai[$v['id_pessoa']]['cpfpai'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo @$mae[$v['id_pessoa']]['cpfmae'] ?>
                    </td>
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>

                    </td>
                </tr>
            </tbody>
            <?php
        }
        ?>
    </table>
    <?php
}
tool::pdfescola('L', @$_POST['id_inst']);
?>