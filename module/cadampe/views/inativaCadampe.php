<?php
if (!defined('ABSPATH'))
    exit;

?>
<div class="body">
    <form id="inativa" method="POST">
        <div class="row">  
            <div class="col">
                <?= formErp::select('1[fk_id_pessoa_cadampe]', $cadampes, ['Cadampe', 'Selecione'], null, null, null, "required") ?>
            </div>
        </div>
        <br>
        <div class="row">  
            <div class="col-md-">
                <?php formErp::textarea('1[justificativa]', NULL, 'Justificativa') ?>
            </div>
        </div>
        <br />
       
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[fk_id_pessoa_responsavel]' => toolErp::id_pessoa(),
                    'inativa' => 1,
                    'closeModal' => 1
                ])
                . formErp::hiddenToken('cadampe_inativo', 'ireplace', null,null,1)
                . formErp::button('Inativar Cadampe', NULL, 'validaJustificativa()');
                ?>
            </div>
        </div>
    </form>
  
</div>

<script>
    function closemodal(){
        $('#myModal').modal('hide');
        parent.location.href = '<?= HOME_URI ?>/cadampe/inativaCadampeList';
    }

    function validaJustificativa(){
        if (document.getElementById('fk_id_pessoa_cadampe_').value == ''){
            alert("Informe o Cadampe a ser inativado.");
            return false;
        }

        if (document.getElementsByName('1[justificativa]')[0].value.trim() == ''){
            alert("Informe a Justificativa para inativar o Cadampe.");
            return false;
        }

        document.getElementById('inativa').submit();
    }

    <?php if (!empty($closeModal)){ ?>
       closemodal();
    <?php } ?>
</script>