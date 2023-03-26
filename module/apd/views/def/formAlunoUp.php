<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa_apd = filter_input(INPUT_POST, 'id_pessoa_apd', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_aluno)) {
    $id_aluno = @$_POST['last_id'];
}
//print_r($_POST);

?>
<style>
    /* Esconde o input */
    input[type='file'] {
        display: none
    }

    /* Aparência que terá o seletor de arquivo */
    .labelSet {
        background-color: #3498db;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        margin: 0 auto;
        padding: 6px;
        width: 300px;
        text-align: center
    }
</style>
<div class="body">
   <div class="fieldTop">
        Laudo do Aluno - AEE
   </div>
   <form action="<?= HOME_URI ?>/apd/apd" target="_parent" method="POST" enctype="multipart/form-data" name="formapdup" id="formapdup">
        <label class="labelSet" id="label" for='selecao-arquivo'>Anexar Laudo</label>
        <input onchange="send()" required="required" type="file" name="arquivo" value="" id='selecao-arquivo' />

        <?= formErp::hidden([
                'id_aluno' => $id_aluno
            ])
        .formErp::hiddenToken('upload');
        ?>
    </form>
</div>
<script>
    function send() {
        input = document.getElementById('selecao-arquivo');
        tamanho = input.files[0].size / 1024 / 1024;
        if (tamanho > 5) {
            alert("O tamanho máximo é 5 megabytes ");
        } else {
            document.getElementById('formapdup').submit();
        }
    }
</script>