<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Alunos
    </div>  

    <?php
    if (!empty($form)) {
        foreach ($turmas as $v){
                $n_turmas[$v['id_turma']]= $v['n_inst'].' - '.$v['n_turma'];//turma AEE
            }
        ?>
        <div class="col">
            <?= formErp::select('id_turma', $n_turmas, 'Turmas', $id_turma, 1, ["id_turma" => $id_turma]) ?>
        </div>
         <?php
        report::simple($form);
    }else{?>
        <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
            <div class="row">
                <div class="col" style="font-weight: bold; text-align: center;">
                    Verifique com a secretaria Escolar se há alunos cadastrados nesta turma
                </div>
            </div>
        </div>
   <?php
    }
        ?>
    <form id="form" target="frame" action="" method="POST">
        <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
        <input type="hidden" name="n_pessoa" id="n_pessoa" value="" />
        <input type="hidden" name="n_turma" id="n_turma" value="" />
        <input type="hidden" name="id_turma_AEE" id="id_turma_AEE" value="<?= $id_turma ?>" />
        <input type="hidden" name="id_turma" id="id_turma" value="" />
        <input type="hidden" name="id_entre" id="id_entre" value="" />
        <input type="hidden" name="id_aval" id="id_aval" value="" />
        <input type="hidden" name="id_pl" id="id_pl" value="" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" id="fframe" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>

<form id="formPDF" method="POST" target="_blank" action="">
    <input type="hidden" name="id" id="id" value="" />
    <input type="hidden" name="id_turma" id="id_turma_pdf" value="" />
    <input type="hidden" name="id_turma_AEE" id="id_turma_AEE" value="<?= $id_turma ?>" />
    <input type="hidden" name="n_pessoa" id="n_pessoa_pdf" value="" />
    <input type="hidden" name="id_pessoa" id="id_pessoa_pdf" value="" />
</form>
<form id="form2" target="frame" action="" method="POST">
    <input type="hidden" name="id_pdi" id="id_pdi" value="" />
    <input type="hidden" name="id_atend" id="id_atend" value="" />
    <input type="hidden" name="id_pessoa" id="id_pessoa_foto" value="" />
    <?=
    formErp::hidden([
        'bimestre' => $bimestre,
        'id_turma' => $id_turma,
        'atalho' => 1,
    ]);
    ?>  
</form>

<script>
    function ava(id,n_pessoa,id_turma,id_aval){
        if (id){
            document.getElementById("id_pessoa").value = id;
            document.getElementById("id_turma").value = id_turma;
            document.getElementById("id_aval").value = id_aval;
            document.getElementById("n_pessoa").value = n_pessoa;
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/avaInicial';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function entr(id,n_pessoa,id_turma,id_entre,id_pl){
        if (id){
            document.getElementById("id_pessoa").value = id;
            document.getElementById("id_turma").value = id_turma;
            document.getElementById("id_entre").value = id_entre;
            document.getElementById("id_pl").value = id_pl;
            document.getElementById("n_pessoa").value = n_pessoa;
            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/entrevista';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function pdi(id,n_pessoa,id_turma,n_turma){
        if (id){
            document.getElementById("id_pessoa").value = id;
            document.getElementById("id_turma").value = id_turma;
            document.getElementById("n_pessoa").value = n_pessoa;
            document.getElementById("n_turma").value = n_turma;
        }
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/pdi';
        document.getElementById("form").target = "";
        document.getElementById("form").submit();
    }

    function impr(id,pag,id_turma,n_pessoa,id_pessoa){ 
        document.getElementById("id").value = id;
        document.getElementById("id_turma_pdf").value = id_turma;
        document.getElementById("n_pessoa_pdf").value = n_pessoa;
        document.getElementById("id_pessoa_pdf").value = id_pessoa;
        if (pag == 1) {
            document.getElementById("formPDF").action = '<?= HOME_URI ?>/apd/PDFentre';
        }else{
            document.getElementById("formPDF").action = '<?= HOME_URI ?>/apd/PDFaval';
        }
        document.getElementById("formPDF").submit();
    }
    function edit(id_atend,id_pessoa,id_pdi,n_pessoa){
            
            document.getElementById("id_pessoa").value = id_pessoa;
            document.getElementById("id_pdi").value = id_pdi;
            document.getElementById("id_atend").value = id_atend;

            var titulo= document.getElementById('myModalLabel');
            titulo.innerHTML  = n_pessoa;
            document.getElementById("form2").action = '<?= HOME_URI ?>/apd/atendimentos';
            document.getElementById("form2").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }

    function foto(id_pessoa,n_pessoa){

        document.getElementById("id_pessoa_foto").value = id_pessoa;
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = n_pessoa;
        document.getElementById("form2").action = '<?= HOME_URI ?>/apd/apdFoto';
        document.getElementById("form2").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

</script>
