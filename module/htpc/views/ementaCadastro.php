<?php
if (!defined('ABSPATH'))
    exit;

?>
<div class="body">
    <form id="formEnvia" method="POST">
        <div class="row">  
            <div class="col col-form-label">
                <?php formErp::textarea('1[descricao]', '', 'Ementa',300) ?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col col-form-label text-center">
                <?=
                formErp::hidden([
                    '1[fk_id_ata]' => $id_ata,
                    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                    'closeModal' => 1
                ])
                . formErp::hiddenToken('htpc_ementa', 'ireplace')
                . formErp::button('Adicionar Ementa', NULL, 'validate()', null, 'salvar');
                ?>
            </div>
        </div>
    </form>
</div>

<script>
    function closemodal(){
        $('#myModal').modal('hide');
        parent.location.href = '<?= HOME_URI ?>/htpc/atas';
    }

    <?php if (!empty($closeModal)){ ?>
       closemodal();
    <?php } ?>

    function funcDisableButton(el){
        if (!el) return false;

        el.setAttribute('disabled', 'disabled');
        el.classList.add("disabled");
        if (el.getAttribute('type') == 'submit'){
            el.value = 'Aguarde ...';
        } else {
            el.innerText = 'Aguarde ...';
        }
        return true;
    }

    function validate(){
        descricao = document.getElementsByName('1[descricao]')[0].value;

        if(descricao == ""){
            alert("Descreva a Ementa.");
            document.getElementsByName('1[descricao]')[0].focus();
            return false;
        }

        var el = document.getElementsByName('salvar')[0];
        funcDisableButton(el);
        document.getElementById('formEnvia').submit();
    }
</script>