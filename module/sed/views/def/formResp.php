<style>
    span{
        font-weight: bold;
    }
</style>
<?php
$id_rt = filter_input(INPUT_POST, 'id_rt');
$nome = filter_input(INPUT_POST, 'nome');
$id_pessoa = filter_input(INPUT_POST, "id_pessoa", FILTER_VALIDATE_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_pessoa_resp = filter_input(INPUT_POST, "id_pessoa_resp", FILTER_VALIDATE_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
if (!empty($id_pessoa_resp)) {
    $pessoa = sql::get("pessoa", "id_pessoa, n_pessoa, sexo, cpf, email, emailgoogle ", ['id_pessoa' => $id_pessoa_resp], "fetch");
    $resp = sql::get("ge_aluno_responsavel", "*", ['fk_id_pessoa_aluno' => $id_pessoa, 'fk_id_pessoa_resp' => $id_pessoa_resp], 'fetch');
    ExibirFormulario($pessoa, $id_pessoa, $model);
} else if (empty($cpf)) {
    ?>
    <form method="post">
        <div class="row">
            <div class="col-9">
                <?= formErp::input("cpf", "CPF", $cpf, javaScript::cfpInput(), null, 'number') ?>
                <?=
                formErp::hidden([
                    "id_pessoa" => $id_pessoa,
                    'id_turma' => $id_turma,
                    'n_turma' => $n_turma,
                    'id_rt' => $id_rt,
                    'nome' => $nome
                ])
                ?>
            </div>
            <div class="col">
                <?= formErp::button("Pesquisar") ?>
            </div>
        </div>
    </form>

    <?php
} else {
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (validar::Cpf($cpf)) {
        $pessoa = sql::get("pessoa", "id_pessoa, n_pessoa, sexo, cpf, email, emailgoogle", ['cpf' => $cpf], "fetch");
        if (!empty($pessoa)) {
            $resp = sql::get("ge_aluno_responsavel", "*", ['fk_id_pessoa_aluno' => $id_pessoa, 'fk_id_pessoa_resp' => $pessoa['id_pessoa']], 'fetch');
        } else {
            $pessoa['cpf'] = $cpf;
        }

        ExibirFormulario($pessoa, $id_pessoa, $model, $id_rt, $nome);
    } else {
        ?>
        <div class="alert alert-danger">
            CPF inválido. Reinicie o processo de cadastro.
        </div>
        <?php
    }
}

function ExibirFormulario($pessoa, $id_pessoa, $model, $id_rt = null, $nome = null) {
    if ($id_rt == 1) {
        $sexoRt = "F";
    } elseif ($id_rt == 2) {
        $sexoRt = "M";
    } else {
        $sexoRt = null;
    }
    $sexoArt = toolErp::sexoArt(sql::get('pessoa', 'sexo', ['id_pessoa' => $id_pessoa], 'fetch')['sexo']);
    $princ = $model->resposavel($id_pessoa, 1);
    $tel = sql::get('telefones', '*', ['fk_id_pessoa' => @$pessoa['id_pessoa']]);
    $respTipo = sql::idNome('ge_responsavel_tipo');
    $spe = sql::get('pessoa', 'pai', ['id_pessoa' => $id_pessoa], 'fetch')['pai'];
    if ($spe == 'SPE') {
        unset($respTipo[2]);
    }
    if (!empty($pessoa)) {
        $resp = sql::get("ge_aluno_responsavel", "*", ['fk_id_pessoa_aluno' => $id_pessoa, 'fk_id_pessoa_resp' => @$pessoa['id_pessoa']], 'fetch');
    }
    ?>

    <form id="form" action="<?= HOME_URI . "/sed/aluno" ?>" target="_parent" method="post">
        <div class="fieldTop">
            Cadastro de Responsáveis
        </div>
        <div class="row">
            <div class="col-8">
                <?= formErp::input('1[n_pessoa]', 'Nome', !empty($pessoa['n_pessoa']) ? $pessoa['n_pessoa'] : $nome, ' required ') ?>
            </div>
            <div class="col-4">
                <?php
                if (empty($princ) || (@$princ['id_pessoa'] == @$pessoa['id_pessoa'] && !empty($princ))) {
                    echo formErp::checkbox("2[responsavel]", 1, "Responsavel Principal", @$resp['responsavel'], ' onclick="bloq(this)" ');
                }
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-4">
                <?= formErp::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', (!empty($pessoa['sexo']) ? $pessoa['sexo'] : @$sexoRt), null, null, ' required ') ?>
            </div>
            <div class="col-8">
                <?= formErp::select('2[fk_id_rt]', $respTipo, "Relação com $sexoArt alun$sexoArt", !empty($resp['fk_id_rt']) ? $resp['fk_id_rt'] : $id_rt) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-5">
                <?= formErp::input(null, 'CPF', @$pessoa['cpf'], ' required readonly ' . javaScript::cfpInput()) ?>
            </div>
            <div class="col-7">
                <?php
                if (!empty($pessoa['emailgoogle'])) {
                    $emailValidos = array_column(sql::get('user_email_valido'), 'n_uev');
                    $e = explode('@', $pessoa['emailgoogle']);
                    if (!empty($e[1])) {
                        $posfixo = '@' . $e[1];
                    }
                    if (in_array($posfixo, $emailValidos)) {
                        $instucional = 1;
                    }
                }
                if (empty($instucional)) {
                    echo formErp::input('1[emailgoogle]', 'E-mail', @$pessoa['emailgoogle'], null, null, 'email');
                } else {
                    echo formErp::input(null, 'E-mail', @$pessoa['emailgoogle'], ' readonly ');
                }
                ?>
            </div>
        </div>
        <br />
        <?php
        foreach (range(0, 1) as $v) {
            if (!empty($tel[$v])) {
                $id_tel = $tel[$v]['id_tel'];
                @$num = preg_replace('/[^\d]/', '', @$tel[$v]['num']);
                if (!empty($num) && !empty($tel[$v]['ddd'])) {
                    $dddNum = '(' . $tel[$v]['ddd'] . ') ' . $num;
                } elseif (empty($tel[$v]['ddd']) && strlen($num > 10)) {
                    $dddNum = '(' . substr($num, -2) . ') ' . substr($num, 0, 9);
                } elseif (!empty($num)) {
                    $dddNum = '(11) ' . substr($num, 0, 9);
                } else {
                    $dddNum = null;
                }
            } else {
                $dddNum = null;
                $id_tel = null;
            }
            echo formErp::hidden(['tel[' . $v . '][id_tel]' => $id_tel]);
            ?>
            <div class="row">
                <div class="col-5">
                    <?= formErp::input('tel[' . $v . '][num]', 'Telefone', $dddNum, " onkeyup='mascara(this, mtel)' id='m" . $v . "'", null, 'tel') ?>
                </div>
                <div class="col-7">
                    <?= formErp::selectDB('telefones_tipo', 'tel[' . $v . '][fk_id_tt]', 'Tipo Telefone', @$tel[$v]['fk_id_tt']) ?>
                </div>
            </div>
            <br />
            <?php
        }
        ?>

        <div class="row">
            <div class="col">
                <?= formErp::checkbox('2[retirada]', 1, 'Pode Retirar o Aluno', @$resp['retirada'], ' id="retirada" ') ?>
            </div>
            <div class="col">
                <?= formErp::checkbox('2[app]', 1, 'Pode Acessar o APP do Aluno', @$resp['app'], ' id="app" ') ?>
            </div>
        </div>
        <br />
        <div class="border">
            <div class="fieldTop">
                Campos destinados ao Ensino Infantil
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::input('2[trabalho]', 'Empregador', @$resp['trabalho']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::input('2[trabalho_endereco]', 'Endereço de Trabalho', @$resp['trabalho_endereco']) ?>
                </div>
            </div>
            <br />
        </div>
        <br /><br />
        <?php
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
        echo formErp::hidden([
            'id_pessoa' => $id_pessoa,
            'id_turma' => $id_turma,
            'n_turma' => $n_turma,
            'activeNav' => 2,
            '1[id_pessoa]' => @$pessoa['id_pessoa'],
        ])
        . formErp::hiddenToken("cadastroResponsavelSet", null, null, ['cpf' => @$pessoa['cpf']])
        . formErp::hidden(['id_pessoa' => $id_pessoa])
        ?>
    </form>
    <div style="text-align: center">
        <button onclick="envia()" class="btn btn-success">
            Enviar
        </button>
    </div>
    <?php
}

javaScript::cpf();
javaScript::telMascara();
?>
<script>
    function envia() {
        erro = false;
<?php
foreach (range(0, 1) as $v) {
    ?>
            if (document.getElementById("m<?= $v ?>").value) {
                if (!document.getElementById("<?= $v ?>_").value) {
                    erro = true;
                }
            }
    <?php
}
?>
        if (!document.getElementById('fk_id_rt_').value) {
            erro = true;
        }
        if (erro) {
            alert('Defina o Tipo de Telefone e a Relação do Contato');
        } else {
            document.getElementById('form').submit();
        }
    }

    function bloq(b) {
        if (b.checked) {
            document.getElementById('retirada').value = 1;
            document.getElementById('app').value = 1;
        } else {
            document.getElementById('retirada').value = 0;
            document.getElementById('app').value = 0;
        }
    }
</script>