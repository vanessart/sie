<?php
ob_start();
$escola = new escola();
$seq = 1;
$cor = '#F5F5F5';

@$evento = @$_POST['evento'];
@$aba = @$_POST['tabClass'];
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

if (@$aba == 4) {

    $aluno = "SELECT DISTINCT ea.id_ea, p.id_pessoa, p.n_pessoa, p.ra, p.rg, p.dt_nasc, p.tel1,"
            . " ge.id_grupo_e, ge.onibus_g, ge.descricao_grupo, e.titulo_evento,"
            . " p.sexo, e.evento, e.dt_evento, e.h_inicio, e.h_final, e.local_evento, e.ev_resp,"
            . " ta.codigo_classe, ta.fk_id_turma"
            . " FROM ge_evento_aluno ea"
            . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ea.fk_id_pessoa"
            . " JOIN pessoa p on p.id_pessoa = ea.fk_id_pessoa"
            . " JOIN ge_grupo_evento ge on ge.fk_id_evento = ea.fk_id_evento AND ge.id_grupo_e = ea.fk_id_grupo_e"
            . " JOIN ge_eventos e on e.id_evento = ea.fk_id_evento"
            . " JOIN ge_turmas tu ON tu.id_turma = ta.fk_id_turma"
            . " WHERE ea.fk_id_evento = '" . $id_eve . "' AND ge.id_grupo_e = '" . $grupo . "'"
            . " AND tu.fk_id_pl = 24 AND ta.situacao = '" . 'Frequente' . "'"
            . " ORDER BY p.n_pessoa";

    $query = $model->db->query($aluno);
    $alunos = $query->fetchAll();

    foreach ($alunos as $v) {
        $tit = $v['titulo_evento'];
        $onibus = $v['onibus_g'];
    }
} else {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $a[] = $v;
        }
    }

    $a = implode(',', $a);

    $dados = "Select id_pessoa, n_pessoa, fk_id_turma, chamada, situacao, codigo_classe,"
            . " titulo_evento, sexo, ra, rg, tel1, dt_nasc"
            . " From pessoa"
            . " JOIN ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa"
            . " JOIN ge_eventos on ge_eventos.fk_id_inst = ge_turma_aluno.fk_id_inst"
            . " Where pessoa.id_pessoa in (" . $a . ") and id_evento = '"
            . $_POST['id_eve'] . "'"
            . " order by chamada";

    $query = $model->db->query($dados);
    $alunos = $query->fetchALL();

    if (empty($alunos)) {
        echo ("O cadastro do aluno está incompleto");
    }foreach ($alunos as $v) {
        $tit = $v['titulo_evento'];
    }
}

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

<?php
if (@$aba == 4) {
    ?>
    <div class="topo">
        <?php echo "Grupo: " . $evento . " Ônibus: " . $onibus ?>
    </div>
    <?php
}
?>
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
                    <?php echo $v['tel1'] ?>
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