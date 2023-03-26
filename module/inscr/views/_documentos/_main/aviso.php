<?php
if (!defined('ABSPATH'))
    exit;
if (empty($dados['fk_id_vs'])) {
    ?>
    <div class="alert alert-info" style="font-weight: bold; text-align: center">
        Preencha as ABAS “Dados Complementares” e “Uploads dos Documentos”, e depois, vá a ABA “Enviar para Validação” para o finalizar.
    </div>
    <?php
} elseif ($dados['fk_id_vs'] == 1) {
    ?>
    <div class="alert alert-success" style="font-weight: bold; text-align: center">
        Os seus dados foram analisados e validados com sucesso!
    </div>
    <?php
} elseif ($dados['fk_id_vs'] == 2) {
    ?>
    <div class="alert alert-warning" style="font-weight: bold; text-align: center">
        Estamos analisando os dados e documentos enviados.
        &nbsp;
        Volte mais tarde para confirmar a validação.
    </div>
    <?php
} elseif ($dados['fk_id_vs'] == 3) {
    ?>
    <div class="alert alert-danger" style="font-weight: bold; text-align: center">
        <?php
        echo toolErp::bom() . ', ' . explode(' ', $dados['nome'])[0];
        ?>
        <p>
            Analisamos os documentos e dados enviados e encontramos a seguinte inconsistência:
        </p>
        <p>
            <?= $dados['obs'] ?>
        </p>
    </div>
    <?php
}
