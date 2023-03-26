<style>
    span{
        font-weight: bold;
    }
</style>
<?php
$id_pessoa = filter_input(INPUT_POST, "id_pessoa", FILTER_VALIDATE_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
if (!empty($id_pessoa)) {
    $pessoa = sql::get("pessoa", "id_pessoa, n_pessoa, sexo, cpf, email, emailgoogle ", ['id_pessoa' => $id_pessoa], "fetch");
    ExibirFormulario($pessoa, $model, $cpf);
} else if (empty($cpf)) {
    ?>
    <form method="post">
        <div class="row">
            <div class="col-9">
                <?= formErp::input("cpf", "CPF", $cpf, javaScript::cfpInput(), null, 'number') ?>
                <?= formErp::hidden(["id_pessoa" => $id_pessoa]) ?>
            </div>
            <div class="col">
                <?= formErp::button("Continuar") ?>
            </div>
        </div>
    </form>

    <?php
} else {
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (validar::Cpf($cpf)) {
        $pessoa = sql::get("pessoa", "id_pessoa, n_pessoa, sexo, cpf, email, emailgoogle", ['cpf' => $cpf], "fetch");
        if (empty($pessoa)) {
            $pessoa['cpf'] = $cpf;
        }

        ExibirFormulario($pessoa, $model, $cpf);
    } else {
        ?>
        <div class="alert alert-danger">
            CPF inválido. Reinicie o processo de cadastro.
        </div>
        <?php
    }
}

function ExibirFormulario($pessoa, $model, $cpf) {
    if (!empty($pessoa['id_pessoa'])) {
        @$fk_id_ee = sql::get('pl_acesso', 'fk_id_ee', ['fk_id_pessoa' => $pessoa['id_pessoa']], 'fetch')['fk_id_ee'];
    }
    ?>

    <form id="form" action="<?= HOME_URI . "/passelivre/userCad" ?>" target="_parent" method="post">
        <div class="fieldTop">
            Cadastro de Usuário
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_pessoa]', 'Nome', @$pessoa['n_pessoa'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-4">
                <?= formErp::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$pessoa['sexo'], null, null, ' required ') ?>
            </div>
            <div class="col-5">
                <?= formErp::input('1[cpf]', 'CPF', @$pessoa['cpf'], ' required readonly ' . javaScript::cfpInput()) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <?= formErp::input('1[emailgoogle]', 'E-mail', @$pessoa['emailgoogle'], null, null, 'email') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-8">
                <?= formErp::selectDB('pl_escola_externa', 'fk_id_ee', 'Escola', @$fk_id_ee) ?>
            </div>
            <div class="col-4">
                <?php
                if (!empty($pessoa['id_pessoa'])) {
                    echo formErp::checkbox('senha', 1, 'Trocar Senha');
                } else {
                    echo formErp::hidden(['senha' => 1]);
                }
                ?>
            </div>
        </div>
        <br />
        <?php
        echo formErp::hidden([
            '1[id_pessoa]' => @$pessoa['id_pessoa'],
        ])
        . formErp::hiddenToken("userCad", null, null, ['cpf' => @$pessoa['cpf']])
        ?>
        <br>
        <div style="text-align: center">
            <button type="submit" class="btn btn-success">
                Salvar
            </button>
        </div>
    </form>

    <?php
}

javaScript::cpf();
