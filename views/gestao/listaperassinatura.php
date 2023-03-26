<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);

$cor = '#F5F5F5';
$seq = 1;

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
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
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
$aluno = "SELECT DISTINCT ea.id_ea, p.id_pessoa, p.n_pessoa, p.ra, p.rg, p.dt_nasc, p.tel1,"
        . " ge.id_grupo_e, ge.onibus_g, ge.descricao_grupo, e.titulo_evento,"
        . " p.sexo, e.evento, e.dt_evento, e.h_inicio, e.h_final, e.local_evento, e.ev_resp,"
        . " ta.codigo_classe, ta.fk_id_turma, ta.chamada"
        . " FROM ge_evento_aluno ea"
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ea.fk_id_pessoa"
        . " JOIN pessoa p on p.id_pessoa = ea.fk_id_pessoa"
        . " JOIN ge_grupo_evento ge on ge.fk_id_evento = ea.fk_id_evento AND ge.id_grupo_e = ea.fk_id_grupo_e"
        . " JOIN ge_eventos e on e.id_evento = ea.fk_id_evento"
        . " JOIN ge_turmas tu ON tu.id_turma = ta.fk_id_turma"
        . " WHERE ea.fk_id_evento = '" . $id_eve . "' AND ge.id_grupo_e = '" . $grupo . "'"
        . " AND  tu.fk_id_pl = 24 AND ta.situacao = '" . 'Frequente' . "'"
        . " ORDER BY p.n_pessoa";

$query = $model->db->query($aluno);
$cla = $query->fetchAll();

foreach ($cla as $v) {
    $tit = $v['titulo_evento'];
    $onibus = $v['onibus_g'];
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
<div class="topo">
    <?php echo "Grupo: " . $evento . " Ônibus: " . $onibus ?>
</div>

<table class="table tabs-stacked table-bordered">;

    <tr>
        <td >
            Seq.       
        </td>
        <td >
            RSE       
        </td>
        <td>
            Cod.Classe       
        </td>       
        <td>
            Nome do Aluno
        </td>
        <td>
            Telefone
        </td>
        <td>
            Assinatura do Responsável      
        </td>

    </tr>
    <?php
    foreach ($cla as $v) {
        ?>
        <tr>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $seq++ ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['id_pessoa'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['codigo_classe'] ?>
            </td>
            <td style="text-align: left;background-color: <?php echo $cor ?>">
                <?php echo addslashes($v['n_pessoa']) ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['tel1'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo '' ?>
                <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<?php
tool::pdfescola('P', @$_POST['id_inst']);
?>