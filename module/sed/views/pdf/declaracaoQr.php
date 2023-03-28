<?php
//302251
$token = @$_GET['token'];
$id_pessoa = intval(@$_GET['id']);
$id_turma = @$_GET['id_turma'];
if (!$token || !$id_pessoa) {
    echo 'Falta Dados';
    exit();
}


if (empty($id_turma)) {
    $sql = "Select p.id_pessoa, p.n_pessoa, p.dt_nasc, ta.fk_id_turma, ta.chamada, ta.situacao, t.periodo, t.codigo as codigo_classe, t.n_turma, t.fk_id_inst, p.ra, p.rg, t.periodo_letivo, p.sexo"
            . " From pessoa p"
            . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma and fk_id_ciclo != 32 "
            . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
            . " Where p.id_pessoa = '" . $id_pessoa . "' "
            . " and ta.situacao = 'Frequente' "
            . " and pl.at_pl = 1 "
            . " order by ta.chamada";
} else {
    $sql = "Select p.id_pessoa, p.n_pessoa, p.dt_nasc, ta.fk_id_turma, ta.chamada, ta.situacao, t.periodo, t.codigo as codigo_classe, t.n_turma, t.fk_id_inst, p.ra, p.rg, t.periodo_letivo, p.sexo"
            . " From pessoa p"
            . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma and id_turma = $id_turma "
            . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
            . " Where p.id_pessoa = '" . $id_pessoa . "' "
            . " and ta.situacao = 'Frequente' "
            . " and pl.at_pl = 1 "
            . " order by ta.chamada";
}
$query = pdoSis::getInstance()->query($sql);
$aluno = $query->fetch(PDO::FETCH_ASSOC);
if (!$aluno) {
    echo 'Aluno Não Encontrado';
    exit();
}
$password_hash = new PasswordHash(8, FALSE);
//tokem para aessoa a este pdf
$tokenData = $password_hash->HashPassword($aluno['dt_nasc']);
if ($password_hash->CheckPassword($aluno['dt_nasc'], $token)) {

    ob_start();

    $pdf = new pdf();
    $pdf->autenticaSistema($tokenData, '/sed/pdf/declaracaoQr.php', $id_pessoa);
    $pdf->id_inst = $aluno['fk_id_inst']
    ?>

    <head>
        <style>
            .topo{
                font-size: 8pt;
                font-weight:bolder;
                text-align: center;
                border-style: solid;
                padding: 5px;
            }

        </style> 
    </head>

    <br /><br /><br />
    <div style="font-weight:bold; font-size:10pt; text-align: center">
        <b>DECLARAÇÃO DE ESCOLARIDADE</b>
    </div>
    <br /><br /><br /><br /><br />
    <p style="text-align: justify">A direção desta escola declara, para fins de comprovação de escolaridade, que <b><?php echo $aluno['n_pessoa'] ?> </b>RSE: nº <?php echo addslashes($aluno['id_pessoa']) ?> RG: nº
        <?php echo $aluno['rg'] ?>, RA: nº <?php echo $aluno['ra'] ?> é alun<?php echo tool::sexoArt($aluno['sexo']) ?> regularmente matriculad<?php echo tool::sexoArt($aluno['sexo']) ?> no <?= $aluno['n_turma'] ?> no Ano Letivo de <?php echo $aluno['periodo_letivo'] ?>, nesta Unidade Escolar, frequentando as aulas de segunda a sexta-feira, no período <?= gtMain::periodoDoDia($aluno['periodo']) ?>.</p>
    <p>Por ser verdade, firmamos o presente.</p>
    <?php
    ?>
    <br /><br /><br /><br /><br />
    <div style="text-align: right"><?= CLI_CIDADE ?>, <?= data::porExtenso(date("Y-m-d")) ?></div>


    <?php
    $pdf->exec();
} else {
    echo 'Incompatibilidade de Token';
}
?>