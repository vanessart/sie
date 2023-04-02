<?php
if (!defined('ABSPATH'))
    exit;
$id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
$id_reg = filter_input(INPUT_POST, 'id_reg', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$ano = date('Y');
if (!empty($id_projeto)) {
    $reg = $model->getProjetoReg($id_projeto, $id_reg);
    $projeto = sql::get('profe_projeto', 'fk_id_turma, n_projeto', 'WHERE id_projeto =' . $id_projeto, 'fetch', 'left');
    $n_projeto = $projeto['n_projeto'];
    //$titulo = 'Registro do Projeto: ' . $n_projeto.' - '.$ano;
    $titulo = 'REGISTRO DO PROJETO  - ' . $ano;
    $n_inst = escolas::n_inst_turma($projeto['fk_id_turma']);
} else {
    ?>
    <script>
        window.location.href = "<?php echo HOME_URI ?>/profe/index"
    </script>
    <?php
}
foreach ($reg as $k => $v) {
    $data = "Professor não informou data";
    if (!empty($v['dt_fim'])) {
        $data = "De " . data::converteBr($v["dt_inicio"]) . " a " . data::converteBr($v["dt_fim"]);
    } else {
        $data = dataErp::converteBr($v['dt_inicio']);
    }
    $professor = toolErp::n_pessoa($v["fk_id_pessoa"]);
}
?>
<style type="text/css">
    @media print{
        table{ page-break-inside:avoid; }
        table { page-break-inside:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    .titulo { 
        font-weight: bold;
        color: #888;
        font-size: 17px;
        text-align: center;
    }
    .td_projeto{
        -webkit-print-color-adjust: exact !important; 
        color-adjust: exact !important; 
        background-color: #a9e2f345  !important;
    }
    .mensagens {}
    .mensagens .mensagem {
        /*border: 1px solid #e1e1d1;*/
        margin: 10px auto;
        padding: 4px;
        /*min-height: 80px;*/
    }

    .nomePessoa {
        /*text-transform: capitalize;*/
        color: #888;
        font-weight: normal;
        font-size: 100%;
    }


    .descricaoCE {
        /*text-transform: capitalize;*/
        color: #888;
        font-weight: normal;
        font-size: 100%;
        padding: 8px;
    }

    .mensagens .dataMensagem { 
        font-weight: bold;
        color: #888;
        font-size: 100%;
    }
    .mensagens .corpoMensagem {
        /*display: block;
        margin-top: 10px;*/
        font-weight: normal;        
        white-space: pre-wrap !important;
        /*padding: 5px;*/
        word-break: break-all; /* webkit */
        word-wrap: normal;
        white-space: pre-wrap;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
    .mensagens .mensagemLinha-2{border-left: 5px solid #f6866f;}
    .mensagens .mensagemLinha-3{border-left: 5px solid #f9ca6e;}
    .mensagens .mensagemLinha-4{border-left: 5px solid #906ef9;}
    .mensagens .mensagemLinha-5{border-left: 5px solid #6ef972;}
    .mensagens .mensagemLinha-6{border-left: 5px solid #f76ef9;}
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
        padding-top:20px;
    }
    .tituloBox.box-0{ color: #7ed8f5;}
    .tituloBox.box-1{ color: #f3b4ef;}
    .tituloBox.box-2{ color: #f6866f;}
    .tituloBox.box-3{ color: #f9ca6e;}
    .tituloBox.box-4{ color: #906ef9;}
    .tituloBox.box-5{ color: #6ef972;}
    .tituloBox.box-6{ color: #f76ef9;}
</style>
<div class="body">
    <?= toolErp::cabecalhoSimples() ?>
    <br />
    <div class="row">
        <div class="col-md-12 titulo">
            <p>
            <div><?= str_replace('.', '', $titulo) ?></div>
            </p>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; " border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td colspan="2">
                <b >Escola:</b> <?= $n_inst ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b >Período:</b> <?= $data ?>
            </td>
        </tr>
        <tr>
            <td>
                <b >Professor:</b> <?= $professor ?>
            </td>
            <td>
                <b>Fase:</b> <?= $n_turma ?>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; border-collapse: collapse; " border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td class="td_projeto">
                <b>PROJETO: <?= $n_projeto ?></b> 
            </td>
        </tr>
        <tr>
    </table>
    <br>
    <table style="width: 100%; border-collapse: collapse; " border=1 cellspacing=0 cellpadding=10>
        <tr>
            <td align="center">
                <b>HABILIDADES</b>
            </td>
            <td align="center">
                <b>SITUAÇÃO DE APRENDIZAGEM</b>
            </td>
        </tr>
        <tr>
            <td width="50%" valign='top'>
                <table style="width: 100%; border-collapse: collapse; " border=0 cellspacing=0>
                    <tr>
                        <td>
                            <?php
                            if (!empty($reg)) {
                                foreach ($reg as $k => $v) {
                                    $id_ce = '';
                                    $br = '<br>';
                                    $inicio = 1;
                                    echo "<tr><td>";
                                    $situacao = $v["situacao"];
                                    foreach ($v["hab"] as $kk => $vv) {
                                        if ($inicio == 0 && ($vv["id_ce"] <> $id_ce)) {
                                            echo "</tr></td><tr><td>";
                                        }
                                        ?>
                                        <?= $vv["id_ce"] <> $id_ce ? '<br>' . $vv["n_ce"] : "" ?>
                                        <br>
                                        <span class="descricaoCE"> &bull; <?= $vv["descricao"] ?></span>
                                        <?php
                                        $id_ce = $vv["id_ce"];
                                        $inicio = 0;
                                    }
                                }
                            } else {
                                echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                            }
                            ?> 
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <div style="width: 100%; word-break: break-all">
                    <?= nl2br($situacao) ?> 
                </div>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    window.onload = function () {
        this.print();
    }
</script>
