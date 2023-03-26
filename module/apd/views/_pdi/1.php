<?php
if (!defined('ABSPATH'))
    exit;

$bimestre = sql::get(['ge_cursos'], 'qt_letiva, un_letiva', ['id_curso' => 1], 'fetch');
$o_a = toolErp::sexoArt(toolErp::sexo_pessoa($id_pessoa_aluno));
$hidden = [
    'id_pessoa' => $id_pessoa_aluno,
    'n_pessoa' => $n_pessoa,
    'n_turma' => $n_turma,
    'id_turma' => $id_turma,
    'id_turma_AEE' => $id_turma_AEE,
    'id_pdi' => @$id_pdi,
    'activeNav' => 1,
];

if ($bimestre) {
    for ($i=1; $i <= $bimestre["qt_letiva"]; $i++) { 
        $bimestres[$i]['bimestre'] =  $i."º ".$bimestre["un_letiva"];
        $bimestres[$i]['impr'] = '<button class="btn btn-outline-warning" onclick="impr(' . $i . ')">Imprimir</button>';
        $bimestres[$i]['desc'] = '<button class="btn btn-outline-info" onclick="desc(' . $i . ')">Descritivo</button>';
        $bimestres[$i]['hab'] = '<button class="btn btn-outline-info" onclick="hab(' . $i . ')">Habilidades</button>';
        $bimestres[$i]['atend'] = '<button class="btn btn-outline-info" onclick="atend(' . $i . ')">Atendimentos</button>';
    }
    $form['array'] = $bimestres;
    $form['fields'] = [
        'Bimestre' => 'bimestre',
        '||3' => 'impr',
        '||1' => 'hab',
        '||2' => 'atend',
        '||0' => 'desc',
    ];
}?>

<style type="text/css">
    .table-active{
        display: none;
    }
</style>
<div class="body">
    <?php 
    if (empty($id_pdi)) {?>
        <div class="row">
            <div class="col">
                <?= toolErp::divAlert('warning', 'Para Iniciar o PDI, preencha o nome do Coordenador clicando em "Habilidades Iniciais"') ?>
            </div>
        </div>
       <?php  
    }?>
    <br><br>   
    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-outline-info" onclick='info("<?= $n_pessoa ?>")'>
               Habilidades Iniciais - PDI
            </button>
        </div>
    </div>
    <br><br>
    <?php toolErp::chat(1,toolErp::id_pessoa(),$id_pessoa_aluno,$hidden, "Neste espaço os professores podem trocar informações ou sugerir ações para $o_a ".explode(' ', $n_pessoa)[0]); ?>
    <br><br>
    <?php
        if (!empty($form) && !empty($id_pdi)) {
        report::simple($form);
        }?>

    <form id="form"action="<?= HOME_URI ?>/apd/pdi" method="POST">
        <input type="hidden" name="activeNav" id="activeNav" value="" />
        <input type="hidden" name="bimestre" id="bimestre" value="" />
        <?=
        formErp::hidden([
            'id_pessoa' => $id_pessoa_aluno,
            'n_pessoa' => $n_pessoa,
            'id_turma' => $id_turma,
            'id_turma_AEE' => $id_turma_AEE,
            'id_pdi' => @$id_pdi,
        ]);
        ?>   
    </form>

    <form id="formPDF" method="POST" target="_blank" action="<?= HOME_URI ?>/apd/PDFInicio">
        <input type="hidden" name="bimestre" id="bimestrePDF" value="" />
        <?=
        formErp::hidden([
            'id_pessoa' => $id_pessoa_aluno,
            'n_pessoa' => $n_pessoa,
            'n_turma' => $n_turma,
            'id_turma' => $id_turma,
            'id_turma_AEE' => $id_turma_AEE,
            'id_pdi' => @$id_pdi,
            'activeNav' => 1,
        ]);
        ?>   
    </form>

    <form id="formIn" target="frame" action="<?= HOME_URI ?>/apd/infoInicial" method="POST">
        <?=
        formErp::hidden([
            'id_pessoa' => $id_pessoa_aluno,
            'n_pessoa' => $n_pessoa,
            'id_turma' => $id_turma,
            'id_turma_AEE' => $id_turma_AEE,
            'id_pdi' => @$id_pdi,
            'activeNav' => 1,
        ]);
        ?>   
    </form>

    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>

</div>

<script>
    function hab(bimestre){
        if (bimestre){
            document.getElementById("bimestre").value = bimestre;
            document.getElementById("activeNav").value = "2";
        }
        document.getElementById("form").submit();
    }
    function desc(bimestre){
        if (bimestre){
            document.getElementById("bimestre").value = bimestre;
            document.getElementById("activeNav").value = "4";
        }
        document.getElementById("form").submit();
    }
    function atend(bimestre){
        if (bimestre){
            document.getElementById("bimestre").value = bimestre;
            document.getElementById("activeNav").value = "3";
        }
        document.getElementById("form").submit();
    }
    function info(n_pessoa){
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = n_pessoa;
        document.getElementById("formIn").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function impr(bimestre){
        if (bimestre){
            document.getElementById("bimestrePDF").value = bimestre;
        }
        document.getElementById("formPDF").submit();
    }
</script>
