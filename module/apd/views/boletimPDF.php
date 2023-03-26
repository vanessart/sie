<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_adapt = filter_input(INPUT_POST, 'id_adapt', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$nota = $model->nota();
$n_inst = toolErp::n_inst();
$porte = $model->getPorte($id_pessoa);
$adaptacao = sql::get('apd_aluno_adaptacao', 'id_aluno_adaptacao, fk_id_pessoa_prof, fk_id_turma_aluno', ['id_aluno_adaptacao' => $id_adapt, 'qt_letiva' => $bimestre], 'fetch');
$prof = $model->getProfAEE($id_pessoa);
if (!empty($prof)){
    $prof_AEE = $prof["n_pessoa"];
} else {
    $prof_AEE = "";
}
$turma = sql::get('ge_aloca_prof', 'rm', ['fk_id_turma' => $adaptacao["fk_id_turma_aluno"],'iddisc' => 'nc'], 'fetch');

$prof_turma = $model->profePEBI($id_pessoa);
$apd_componente = sql::get('apd_componente', 'n_componente, unidade, objeto, habilidade, fk_id_nota', ['fk_id_aluno_adaptacao' => $adaptacao["id_aluno_adaptacao"]]);
$apd_parecer = sql::get('apd_parecer', 'n_parecer', ['fk_id_aluno_adaptacao' => $adaptacao["id_aluno_adaptacao"]], 'fetch');

?>
<style type="text/css">
    .titulo_anexo{
        color: grey;
        font-weight: bold;
        text-align: center;
    }
    .sub_anexo{
        font-weight: bold;
        text-align: center;
    }
    .comp{
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        color: red;
    }
    .tit_table{
        font-weight: bold;]
    }
    .tabela{
        width: 100%;
        border: 1;
        font-size: 12px;
        cellspacing: 0;
        cellpadding: 2;
    }
    .tabela td{
        padding: 4px;
    }
</style>
<div class="body">
    <?= toolErp::cabecalhoSimples() ?>
    <br />

<div class="row">
    <div class="col titulo_anexo">DEPARTAMENTO EDUCACIONAL ESPECIALIZADO</div>
</div>
<div class="row">
    <div class="col sub_anexo">ANEXO III</div>
</div>
<br>
<div class="row">
    <div class="col sub_anexo">APD - AVALIAÇAO PARECER DESCRITIVO</div>
</div>
<div class="row">
    <div class="col sub_anexo">ADAPTAÇÃO DE <?= strtoupper($porte) ?></div>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; " border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td colspan="2">
            <span class="tit_table">Nome do aluno:</span> <?= $n_pessoa ?>
        </td>
        <td>
            <span class="tit_table">RSE:</span> <?= $id_pessoa ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_table">Unidade Escolar:</span> <?= $n_inst ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_table">Ano/Turma:</span> <?= $n_turma ?>
        </td>
        <td>
            <span class="tit_table">Bimestre:</span> <?= $bimestre ?>
        </td>
        <td>
            <span class="tit_table">Percentual de frequência do bimestre:</span>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_table">Professor da sala regular PEB I:</span> <?= $prof_turma ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_table">Professor coordenador PEB II:</span>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_table">Professor do AEE:</span> <?= $prof_AEE ?>
        </td>
    </tr>
</table>
<p style="page-break-before: always;"></p>

<div class="row">
    <div class="col comp">COMPONENTES CURRICULARES</div>
</div>
<?php   
if (!empty($apd_componente)) {?>

    <div class="row">
    <div class="col titulo_anexo">DEPARTAMENTO EDUCACIONAL ESPECIALIZADO</div>
    </div>
    <div class="row">
        <div class="col sub_anexo">ANEXO III</div>
    </div>
    <br>
    <div class="row">
        <div class="col sub_anexo">APD - AVALIAÇAO PARECER DESCRITIVO</div>
    </div>
    <div class="row">
        <div class="col sub_anexo">ADAPTAÇÃO DE <?= strtoupper($porte) ?></div>
    </div>
    <br>

    <table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
        <tr class="sub_anexo" >
            <td style="font-weight: bold;text-align: center;">
               Componente Curricular
            </td>
            <td style="font-weight: bold;text-align: center;">
               Unidade Temática
            </td>
            <td style="font-weight: bold;text-align: center;">
               Objeto de Conhecimento
            </td>
            <td  style="font-weight: bold;text-align: center;">
               Habilidades
            </td>
            <td ></td>
            <td  style="font-weight: bold;text-align: center;">
               Classificação Geral
            </td>
        </tr>
        <?php
        //$i=count($apd_componente); 
        $j = 0;
        $i=0;
        $nota_total = 0;
        foreach ($apd_componente as $k => $v) {
            $j++;
            $nota_hab = $v['fk_id_nota'];
            $nota_total = $nota_total+$nota["$nota_hab"]["valor"];
        }
        $valor_bimestre = $nota_total / $j;
        $nota_bimestre = $model->nota_bimestre($valor_bimestre);
           
        foreach ($apd_componente as $k => $v) {
            $nota_hab = $v['fk_id_nota'];
            ?>
            <tr >
                <td >
                   <?= $v["n_componente"] ?>
                </td>
                <td >
                   <?= $v["unidade"] ?>
                </td>
                <td >
                   <?= $v["objeto"] ?>
                </td>
                <td >
                   <?= $v["habilidade"] ?>
                </td>
                <td >
                    <?= $nota["$nota_hab"]["sigla"] ?>
                </td>
                <?php   
                if ($i == 0) {?>
                    <td rowspan="<?= $j ?>" class="sub_anexo">
                        <?= $nota_bimestre ?>
                    </td>
                <?php
                $i = 1;
                } ?>                   
        </tr>
    <?php
    } ?>
</table>
<?php  
}
 ?>

<br>
<div class="row">
    <div class="col tit_table">Legenda</div>
</div>
<div class="row">
    <div class="col">(ED) - Em desenvolvimento</div>
</div>
<div class="row">
    <div class="col">(B) - Bom</div>
</div>
<div class="row">
    <div class="col">(MB) - Muito Bom</div>
</div>
<p style="page-break-before: always;"></p>

<?php   
if (!empty($apd_parecer)) {?>

    <div class="row">
    <div class="col titulo_anexo">DEPARTAMENTO EDUCACIONAL ESPECIALIZADO</div>
    </div>
    <div class="row">
        <div class="col sub_anexo">ANEXO III</div>
    </div>
    <br>
    <div class="row">
        <div class="col sub_anexo">APD - AVALIAÇAO PARECER DESCRITIVO</div>
    </div>
    <div class="row">
        <div class="col sub_anexo">ADAPTAÇÃO DE <?= strtoupper($porte) ?></div>
    </div>
    <br>

    <table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
        <tr class="tit_parecer" >
            <td style="font-weight: bold;text-align: center;">
               PARECER DESCRITIVO
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" >
               (Descrever neste campo as habilidades adquiridas ao longo do bimestre)
            </td>
        </tr>
        <tr>
            <td style="text-align: left;" >
               <?= $apd_parecer["n_parecer"] ?>
            </td>
        </tr>
    </table>
<?php  
}
?>

<script type="text/javascript">
    window.onload = function() {
         this.print();
    }
</script>
