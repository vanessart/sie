<?php
if (!defined('ABSPATH'))
    exit;
if ($_SESSION['TMP']['SIT'] == 1) {
    ?>
    <div style="text-align: center">
        <form id="fim" method="POST">
            <?=
            formErp::hidden([
                'id_cate' => $id_cate,
                'activeNav' => 3
            ])
            . formErp::hiddenToken('fim')
            ?>
        </form>
        <div class="alert alert-info">
            <p>
                Declaro que as informações prestadas na inscrição e na documentação anexada é de minha inteira responsabilidade, estando ciente de que poderá acarretar responsabilidade Civil, Penal e ou Administrativa a qualquer momento, no caso de serem prestadas informações inverídicas ou utilizados documentos falsos, o que acarretará a desclassificação e o indeferimento do cadastro do  Processo Seletivo.
            </p>
            <p>
                <input class="form-check-input" type="checkbox" value=""  onclick="verifica()" id="invalidCheck" required>
                <label class="form-check-label" for="invalidCheck" >
                    Ciente
                </label>
            </p>
        </div>
        <button onclick="fim()" id="btnConcluir" class="btn btn-secondary">
            Concluir Inscrição
        </button>
    </div>
    <script>
        function verifica() {
            if (invalidCheck.checked) {
                btnConcluir.classList.remove("btn-secondary");
                btnConcluir.classList.add("btn-success");
            } else {
                btnConcluir.classList.remove("btn-success");
                btnConcluir.classList.add("btn-secondary");
            }
        }
        function fim() {
            if (invalidCheck.checked == true) {
                if (confirm("Após concluir a inscrição, não será possível editar os dados ou anexar novos documentos. concluir?")) {

                    document.getElementById('fim').submit();
                }
            } else {
                alert('Para concluir a inscrição, confirmar que está ciente');
            }
        }
    </script>
    <?php
} else {
    ?>
    <div style="text-align: center">
        <form target="_blank" action="<?= HOME_URI ?>/inscr/pdf/protocolo" method="POST">
            <button onclick="fim()" class="btn btn-primary">
                Imprimir Ficha de Inscrição
            </button>
        </form>
    </div>
    <?php
}
?>