<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <form id="formEnvia" method="POST" target="_parent" action="<?= HOME_URI ?>/htpc/presenca">
        <div class="row">
            <div class="col col-form-label">
                <?php formErp::textarea('1[justificativa]', '', 'Justificativa',100) ?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col col-form-label text-center">
                <?=
                formErp::hidden([
                    'id_ata' => $id_ata,
                    '1[fk_id_ata]' => $id_ata,
                    '1[fk_id_pessoa_reg]' => toolErp::id_pessoa(),
                    '1[fk_id_pessoa]' => $fk_id_pessoa,
                    '1[rm]' => $rm,
                ]);

                if ($acao == 'R') {
                    echo formErp::hidden(['1[presente]' => '0']);
                } else {
                    echo formErp::hidden([
                        'fk_id_pessoa' => $fk_id_pessoa,
                        'rm' => $rm,
                    ]);
                }

                echo formErp::hiddenToken('htpc_presenca', 'ireplace')
                . formErp::button(($acao == 'R') ? 'Remover Presença' : 'Atribuir Presença', NULL, 'validate()', null, 'salvar');
                ?>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
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

        justificativa = document.getElementsByName('1[justificativa]')[0].value;

        if(justificativa == ""){
            alert("Descreva a Justificativa.");
            document.getElementsByName('1[justificativa]')[0].focus();
            return false;
        }

        var el = document.getElementsByName('salvar')[0];
        funcDisableButton(el);
        document.getElementById('formEnvia').submit();
    }

    jQuery(function($){
        setTimeout(function(){
            document.getElementsByName('1[justificativa]')[0].focus();
        }, 300)
    })
</script>