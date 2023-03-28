<?php
ob_start();

$escola = new escola();

@$vag = $_POST['id_vaga_c'];


$vaga = sql::get('ge_decl_vaga_comp', 'nome_solicitante, rg, sexo, nome_aluno, rse, h_inicio, h_final, sexo_aluno, codigo, dt_comp', ['id_vaga_c' => $vag]);
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
        .dc{
            font-size: 6pt;
            text-align: right;

        }
    </style> 
</head>

<?php
foreach ($vaga as $v) {
    // echo $escola->cabecalho();

    $descricao = descricaocodigoclasse($v['codigo']);
    ?>
    <br /><br /><br /><br /><br /><br /><br />
    <div style="font-weight:bold; font-size:12pt; text-align: center">
        DECLARAÇÃO DE COMPARECIMENTO
    </div>
    <br /><br /><br /><br /><br />
    <div style="text-align:justify"> A direção desta escola declara, para os devidos fins, que <b><?php echo addslashes($v['nome_solicitante']) ?></b>
        , RG nº. <?php echo $v['rg'] ?>, esteve presente nesta Unidade Escolar no dia <?php echo data::converteBr($v['dt_comp']) ?>,
        no período das <?php echo substr($v['h_inicio'], 0, 5) ?> às <?php echo substr($v['h_final'], 0, 5) ?> horas, para tratar de assunto referente
    <?php echo $a = ($v['sexo_aluno'] == 'M') ? 'ao aluno' : 'à aluna' ?>
        <b><?php echo addslashes($v['nome_aluno']) ?></b>,
        RSE nº <?php echo $v['rse'] ?>, regularmente <?php echo $b = ($v['sexo_aluno'] == 'M') ? 'matriculado' : 'matriculada' ?> no
    <?php echo $descricao ?>.

    </div>

    <br /><br />
    <div>Nada mais.</div>
    <br /><br /><br /><br />

    <div style="text-align: right"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
    <br /><br /><br /><br /><br /><br />
    <div style="text-align: center">_____________________________________</div>
    <div style="text-align:center">Carimbo e Assinatura</div>
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    <div style="font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
        Obs. Este documento não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
    </div>
    <?php
}
tool::pdfSemRodape();

function descricaocodigoclasse($cod) {

    switch (substr($cod, 0, 2)) {
        case "EF":
        case "EJ":
            $d = " do Ensino Fundamental";
            break;

        case "EI":
        case "EM":
        case"EB":
            $d = " da Educação Infantil";
            break;
        default :
            $d = "-";
            break;
    }
    $sql = "Select n_turma from ge_turmas Where fk_id_inst = '" . tool::id_inst() . "' And codigo = '" . $cod . "' And periodo_letivo = '" . date('Y') . "'";
    $query = pdoSis::getInstance()->query($sql);
    $c = $query->fetch(PDO::FETCH_ASSOC);

    return $c['n_turma'] . $d;
}
