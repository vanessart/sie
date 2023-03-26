<?php
if (!defined('ABSPATH'))
    exit;
if (!empty($apd_componente)) {
  $componente = $apd_componente[0]["n_componente"];
}
$cid = $model->cidGetPessoa($id_pessoa);
?>
<div class="row">
    <div class="col titulo_anexo">DEPARTAMENTO EDUCACIONAL ESPECIALIZADO</div>
</div>
<div class="row">
    <div class="col sub_anexo">ANEXO I</div>
</div>
<br>
<div class="row">
    <div class="col sub_anexo">ADAPTAÇÃO CURRICULAR</div>
</div>
<div class="row">
    <div class="col sub_anexo">PEQUENO PORTE</div>
</div>
<br>

<table class="tabela">
    <tr>
        <td>
            <span class="tit_table">Aluno:</span> <?= $n_pessoa ?>
        </td>
        <td>
            <span class="tit_table">DN:</span> <?= dataErp::converteBr($dt_nasc) ?>
        </td>
        <td>
            <span class="tit_table">CID:</span> <?= $cid ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_table">Professor sala Comum:</span> <?= $prof_turma ?>
        </td>
        <td>
            <span class="tit_table">Componente Curricular:</span> <?= $componente ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_table">Professor do AEE:</span> <?= $prof_AEE ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_table">Coordenador Pedagógico:</span>
        </td>
    </tr>
    
    <tr>
        <td colspan="3">
            <span class="tit_table">Ano/Turma:</span> <?= $n_turma ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_table">Período de Adaptação Curricular:</span> <?= $bimestre ?>º Bimestre
        </td>
    </tr>
    
    
</table>
<br>
<br>
<?php if (!empty($apd_componente)) { ?>
    <table class="tabela">
        <tr class="sub_anexo">
            <td>
                Unidade Temática
            </td>
            <td >
                Objeto de Conhecimento
            </td>
            <td >
                Habilidades
            </td>
            <td >
                Situação Didática
            </td>
            <td >
                Recursos
            </td>
        </tr>
        <?php
        foreach ($apd_componente as $k => $v) {?>
            <tr >
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
                    <?= $v["sit_didatica"] ?>
                </td>   
                <td >
                    <?= $v["recurso"] ?>
                </td>               
            </tr>
        <?php }
        ?>
    </table>
    <?php
}
?>

<br><br>


<div class="row">
    <div class="col titulo_anexo">DEPARTAMENTO EDUCACIONAL ESPECIALIZADO</div>
</div>
<div class="row">
    <div class="col sub_anexo">ANEXO I</div>
</div>
<br>
<div class="row">
    <div class="col sub_anexo">ADAPTAÇÃO CURRICULAR</div>
</div>
<div class="row">
    <div class="col sub_anexo">PEQUENO PORTE</div>
</div>
<br>
<br>
<?php if (!empty($apd_parecer["atvd_estudo"])) { ?>
    <table class="tabela">
        <tr class="tit_parecer">
            <td >
                ATIVIDADES DE ESTUDO
            </td>
        </tr>
        <tr>
            <td style="text-align: left;" >
                <?= $apd_parecer["atvd_estudo"] ?>
            </td>
        </tr>
    </table>
    <?php
}
?>
<br>
<?php if (!empty($apd_parecer["instr_avaliacao"])) { ?>
    <table class="tabela">
        <tr class="tit_parecer">
            <td >
                INSTRUMENTOS DE AVALIAÇÃO
            </td>
        </tr>
        <tr>
            <td style="text-align: left;" >
                <?= $apd_parecer["instr_avaliacao"] ?>
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

