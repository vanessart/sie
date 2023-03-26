<?php
if (!defined('ABSPATH'))
    exit;
$nota = $model->nota();
?>
<!-- <div class="row">
    <div class="col" style="text-align:right; padding-right:40px;">
        <button class="btn btn-warning" onclick='edit(<?= $id_adapt ?>,<?= $bimestre ?>,<?= $id_pessoa ?>, "<?= $n_turma ?>", "<?= $n_pessoa ?>")'>IMPRIMIR</button>
    </div>
</div> -->
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
    <div class="col sub_anexo"><?= strtoupper($porte) ?></div>
</div>
<br>

<table class="tabela">
    <tr>
        <td colspan="3">
            <span class="tit_table">Nome do aluno:</span> <?= $n_pessoa ?>
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
            <span class="tit_table">Percentual de frequência do bimestre:</span> 30%
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
<br>
<br>

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
    <div class="col sub_anexo"><?= strtoupper($porte) ?></div>
</div>
<br>

<div class="row">
    <div class="col comp">COMPONENTES CURRICULARES</div>
</div>
<?php if (!empty($apd_componente)) { ?>
    <table class="tabela">
        <tr class="sub_anexo">
            <td >
                Componente Curricular
            </td>
            <td>
                Unidade Temática
            </td>
            <td >
                Objeto de Conhecimento
            </td>
            <td >
                Habilidades
            </td>
            <td ></td>
            <td >
                Classificação Geral
            </td>
        </tr>
        <?php
        //$i=count($apd_componente); 
        $j = 0;
        $i = 0;
        $nota_total = 0;
        foreach ($apd_componente as $k => $v) {
            $j++;
            $nota_hab = $v['fk_id_nota'];
            $nota_total = $nota_total + $nota["$nota_hab"]["valor"];
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
                <?php if ($i == 0) { ?>
                    <td rowspan="<?= $j ?>" class="sub_anexo">
                        <?= $nota_bimestre ?>
                    </td>
                    <?php
                    $i = 1;
                }
                ?>                   
            </tr>
        <?php }
        ?>
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
<br><br>

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
    <div class="col sub_anexo"><?= strtoupper($porte) ?></div>
</div>
<br>
<?php if (!empty($apd_parecer)) { ?>
    <table class="tabela">
        <tr class="tit_parecer">
            <td >
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

<form id="form" method="POST" target="frame" action="">
    <input type="hidden" name="id_pessoa" id="id_pessoa"  />
    <input type="hidden" name="n_pessoa" id="n_pessoa"  />
    <input type="hidden" name="n_turma" id="n_turma"  />
    <input type="hidden" name="id_adapt" id="id_adapt"  />
    <input type="hidden" name="bimestre" id="bimestre"  />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>

<script>
    function edit(id_adapt, bimestre, id, n_turma, n_pessoa) {
        if (id) {
            document.getElementById("n_pessoa").value = n_pessoa;
            document.getElementById("n_turma").value = n_turma;
            document.getElementById("id_adapt").value = id_adapt;
            document.getElementById("id_pessoa").value = id;
            document.getElementById("bimestre").value = bimestre;
            document.getElementById("form").action = '<?= HOME_URI ?>/apd/boletimPDF';
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    }
</script>

