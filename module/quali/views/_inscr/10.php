<style>
    body{
        background-color: #F2F5EC;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$id_cur = filter_input(INPUT_POST, 'id_cur', FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
$dt_nasc = filter_input(INPUT_POST, 'dt_nasc', FILTER_SANITIZE_STRING);

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$form = $i = @$_data[2];
if ($i) {
    $model->_form = $i;
    $inscSet = sql::get(['quali_inscr', 'gt_periodo_letivo'], 'gt_periodo_letivo.dt_inicio as inicioAula, quali_inscr.*', ['id_inscr' => $i], 'fetch');

    $sql = "SELECT id_inscr FROM `quali_incritos_" . $i . "` "
            . ' limit 0, 1';
    try {
        $query = pdoSis::getInstance()->query($sql);
    } catch (Exception $ex) {
        unset($inscSet);
    }
}
if (!empty($cpf) && empty($_POST['SalvaFormInscr'])) {
    if (strlen($dt_nasc) != 10) {
        tool::alert('Data de nascimento inválida');
        $cpf = null;
    } else {
        $dt_nasc = data::converteUS($dt_nasc);
    }
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (!tool::cpfValida($cpf)) {
        tool::alert('CPF Inválido');
        $cpf = null;
    } elseif (empty($dt_nasc)) {
        tool::alert('Insira a data de nascimento');
        $cpf = null;
    } elseif (data::calcula($dt_nasc, $inscSet['inicioAula']) < 18) {
        tool::alert('Para participar do Projeto Meu Futuro é necessário ter 18 anos ou mais');
        $cpf = null;
    } elseif (data::calcula($dt_nasc, $inscSet['inicioAula']) > 100) {
        tool::alert('Data de nascimento inválida');
        $cpf = null;
    } elseif ($model->emailExiste($email, $cpf)) {
        tool::alert("Outro usuário do sistema já está usando o e-mail '$email'. Escolha outro e-mail");
        $cpf = null;
    }
}
if (!empty($inscSet)) {
    if (!empty($_POST['SalvaFormInscr'])) {
        $salvo = $model->SalvaFormInscr();
    }
}
if (!empty($inscSet['cursos'])) {

    $sql = "SELECT id_cur, n_cur, objetivo, conteudo FROM `gt_curso` WHERE `id_cur` in (" . $inscSet['cursos'] . ") order by n_cur ";
    $query = pdoSis::getInstance()->query($sql);
    $cursosCompleto_ = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cursosCompleto_ as $v) {
        $cursosCompleto[$v['id_cur']] = $v;
    }
    $cursos = tool::idName($cursosCompleto);
    if (!empty($inscSet['quant'])) {
        $quant = json_decode($inscSet['quant'], true);
        $sql = "SELECT fk_id_cur, COUNT(id_inscr) ct FROM quali_incritos_" . $model->_form . " "
                . " where situacao != 3 "
                . "  or situacao is null "
                . " GROUP BY fk_id_cur";
        $query = pdoSis::getInstance()->query($sql);
        $quantCur = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($quantCur as $v) {
            if ($v['ct'] >= $quant[$v['fk_id_cur']]) {
                unset($cursos[$v['fk_id_cur']]);
                unset($cursosCompleto[$v['fk_id_cur']]);
            }
        }
    }
} else {
    $cursos = null;
}
$ceps_ = sql::get('quali_cepsBairroBarueri', 'id_cep');
foreach ($ceps_ as $v) {
    $ceps[$v['id_cep']] = substr($v['id_cep'], 0, 5) . '-' . substr($v['id_cep'], -3);
}
?>
<div class="body">
    <div style="text-align: center">
        <img style="width: 300px" src="<?= HOME_URI ?>/includes/images/assinco/MeuFuturo_logo2.png"/>
    </div>
    <div style="text-align: center; font-weight: bold; font-size: 20px; padding: 10px; background-color: #ACC84D">
        Centro de Qualificação Profissional
        <br />
        Secretaria de Indústria, Comércio e Trabalho
    </div>
    <br />
    <?php
    if (!empty($inscSet)) {
        ?>
        <div style="text-align: center; font-weight: bold; font-size: 18px; padding: 10px;">
            <?= $inscSet['n_inscr'] ?>
        </div>
        <hr>
        <div style="padding: 10px; font-weight: bold; text-align: center">
            <?= $inscSet['txt_inscr'] ?>
        </div>
        <hr>
        <?php
    }

    if (!empty($salvo)) {
        ?>
        <div class="caixag alert alert-success" style="text-align: center; font-size: 20px">
            Parabéns <?= $_POST[1]['nome'] ?>! Sua pré-inscrição foi realizada com sucesso.
            <br /><br />
            <form>
                <button class="btn btn-primary">
                    Sair
                </button>
            </form>
        </div>
        <?php
    } elseif (empty($inscSet)) {
        ?>
        <div class="caixag alert alert-danger" style="text-align: center; font-size: 20px">
            Página não encontrada
        </div>
        <?php
    } elseif ($inscSet['at_inscr'] != 1) {
        ?>
        <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
            As inscrições não estão abertas
        </div>
        <?php
    } elseif ($inscSet['dt_inicio'] > date("Y-m-d")) {
        ?>
        <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
            As inscrições serão abertas no dia <?= data::porExtenso($inscSet['dt_inicio']) ?>
        </div>
        <?php
    } elseif ($inscSet['dt_fim'] < date("Y-m-d")) {
        if ($inscSet['ver_aprovados'] == 1) {

            include ABSPATH . '/module/quali/views/_deferido/body.php';
        } else {
            ?>
            <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
                As inscrições estão encerradas.
            </div>
            <?php
        }
    } elseif (empty($cpf)) {
        if (!empty($cursosCompleto)) {
            ?>
            <div class="caixag">
                <form method="POST">
                    <div class="row">
                        <div class="col">
                            <?= form::input('cpf', 'CPF', null, ' required ') ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <?= form::input('dt_nasc', 'Data de Nasc', null, ' required  OnKeyUp="mascaraData(this);" maxlength="10" ') ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <?= form::input('email', 'E-mail', null, ' required ', null, 'email') ?>
                        </div>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col text-center">
                            <?= form::button("Enviar") ?>
                        </div>
                    </div>
                    <br />
                </form>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-danger" style="width: 400px; margin: auto; text-align: center">
                Inscrições fechadas
            </div>
            <?php
        }
    } elseif ($nome = $model->jaInscrito($cpf, $i)) {
        ?>
        <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
            <?= $nome ?>, sua pré-inscrição já está cadastrada.
            <br /><br />
            Se não foi você que realizou a pré-inscrição, Contate a equipe do projeto Meu Futuro pelo telefone (11) 94296-0853
            <br /><br />
            <form>
                <button class="btn btn-primary">
                    Sair
                </button>
            </form>
        </div>
        <?php
    } elseif (empty($id_cur)) {
        ?>
        <div class="fieldTop">
            Escolha o Curso
        </div>
        <br /><br />
        <div class="row">
            <?php
            if (!empty($cursosCompleto)) {
                foreach ($cursosCompleto as $v) {
                    ?>
                    <div class="col-sm-4" style="padding: 10px">
                        <div class="border" style="width: 100%">
                            <div style="text-align: center; font-weight: bold; font-size: 20px" class="alert alert-success">
                                <?= $v['n_cur'] ?>
                            </div>
                            <br />
                            <div class="alert alert-success" style="min-height: 100px">
                                <p>Objetivo do Curso:</p>
                                <?= $v['objetivo'] ?>
                            </div>
                            <br />
                            <div class="alert alert-success" style="min-height: 300px">
                                <p>Parte do conteúdo a ser abordado:</p>
                                <ul>
                                    <?php
                                    if (!empty($v['conteudo'])) {
                                        foreach (explode(';', $v['conteudo']) as $y) {
                                            ?>
                                            <li>
                                                <?= $y ?>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                            <form method="POST">
                                <div style="text-align: center">
                                    <?=
                                    form::hidden([
                                        'cpf' => $cpf,
                                        'dt_nasc' => $dt_nasc,
                                        'email' => $email,
                                        'id_cur' => $v['id_cur']
                                    ])
                                    ?>
                                    <button class="btn btn-success">
                                        Selecionar
                                    </button>
                                </div>
                            </form>
                            <br />
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="alert alert-danger" style="width: 400px; margin: auto; text-align: center">
                    Inscrições fechadas
                </div>
                <?php
            }
            ?>

        </div>
        <br /><br /><br />
        <?php
    } else {
        ?>
        <form id="formEnvio" method="POST">
            <div class="caixag">
                <?= form::input('1[nome]', 'Nome Completo&nbsp;<span style="color: red">*</span>', null, ' required style="text-transform: uppercase" ') ?>
            </div>
            <div class="caixag">
                <?= form::input('1[email]', 'E-mail&nbsp;<span style="color: red">*</span>', $email, ' onblur="checarEmail(this)" readonly ', null, 'email') ?>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <?= form::input(null, 'CPF', substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, -2), ' readonly ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::input('1[rg]', 'RG&nbsp;<span style="color: red">*</span>', null, ' required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::input('1[dt_nasc]', 'Data de Nascimento&nbsp;<span style="color: red">*</span>', $dt_nasc, ' readonly ', null, 'date') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <?= form::selectDB('civil', '1[fk_id_civil]', 'Estado Civil&nbsp;<span style="color: red">*</span>', null, null, null, null, null, ' required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo&nbsp;<span style="color: red">*</span>', null, null, null, ' required ') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <?= form::selectDB('escolaridade', '1[fk_id_esc]', 'Escolaridade&nbsp;<span style="color: red">*</span>', null, null, null, null, null, ' required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::selectDB('sit_emprego', '1[fk_id_se]', 'Situação Atual&nbsp;<span style="color: red">*</span>', null, null, null, null, null, ' required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::selectDB('sit_familia', '1[fk_id_sf]', 'Situação na família&nbsp;<span style="color: red">*</span>', null, null, null, null, null, ' required ') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <?= form::input('1[celular]', 'Celular com DDD&nbsp;<span style="color: red">*</span>', null, ' required onkeyup="mascara(this, mtel)" maxlength="15" ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <table>
                            <tr>
                                <td>
                                    <?php
                                    if (tool::mobile()) {
                                        ?>
                                        <img class="small" style="height: 40px" src="<?= HOME_URI ?>/includes/images/imoji/paraCima.png"/>
                                        <?php
                                    } else {
                                        ?>
                                        <img class="big" style="height: 40px" src="<?= HOME_URI ?>/includes/images/imoji/paraEsquerda.png"/>
                                        <?php
                                    }
                                    ?>
                                    &nbsp;&nbsp;&nbsp;
                                </td>
                                <td>
                                    <?= form::checkbox('1[whatsapp]', 1, 'Meu Celular tem WhatsApp') ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::input('1[recado]', 'Telefone Recado', null, ' onkeyup="mascara(this, mtel)" maxlength="15" ') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col-sm-12 col-md-9">
                        <?= form::input('1[logradouro]', 'Endereço&nbsp;<span style="color: red">*</span>', null, ' required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <?= form::input('1[num]', 'Número&nbsp;<span style="color: red">*</span>', null, ' required ') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col">
                        <?= form::input('1[complemento]', 'Complemento') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <?= form::input('1[bairro]', 'Bairro&nbsp;<span style="color: red">*</span>', null, ' required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::select('1[cep]', $ceps, ['CEP&nbsp;<span style="color: red">*</span>', 'Sem CEP']) ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <?= form::input('1[cidade]', 'Cidade', CLI_CIDADE, ' readonly ') ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <?= form::input('1[uf]', 'Estado', 'SP', ' readonly ') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col" style="font-weight: bold">
                        Selecione o curso desejado
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <?= form::select('1[fk_id_cur]', $cursos, '1ª Opção&nbsp;<span style="color: red">*</span>', $id_cur, null, null, ' onchange="optSet(\'fk_id_cur_\')" required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <?= form::select('1[fk_id_cur_2]', $cursos, '2ª Opção', null, null, null, '  onchange="optSet(\'fk_id_cur_2_\')" ') ?>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col" style="font-weight: bold">
                        <div class="row">
                            <div class="col">
                                Já foi aluno do Programa Meu Futuro? 
                            </div>
                            <div class="col">
                                <?= form::radio('1[aluno]', 1, 'Sim', 1, ' onclick="$(\'#fases\').show()" ') ?>
                            </div>
                            <div class="col">
                                <?= form::radio('1[aluno]', 0, 'Não', null, ' onclick="$(\'#fases\').hide()"') ?>
                            </div>
                        </div>
                        <br />
                    </div>
                </div>
                <br />
                <div id="fases" style="display: none">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="row">
                                <div class="col" style="font-weight: bold">
                                    qual (ou quais) fase(s)?
                                </div>
                            </div>
                            <br />
                        </div>
                    </div>
                    <br />
                    <div class="row" >
                        <div class="col">
                            <div class="row">
                                <?php
                                foreach (range(20, 1) as $v) {
                                    ?>
                                    <div class="col- col-md-2">
                                        <?= form::checkbox('fases_ant[' . $v . ']', 1, $v . 'ª') ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <br />

                        </div>
                    </div>
                </div>
            </div>
            <div class="caixag">
                <div class="row">
                    <div class="col" style="font-weight: bold">
                        Como você ficou sabendo dos cursos?
                    </div>
                </div>
                <br />
                <div class="row">
                    <?php
                    foreach (['Internet', 'Facebook', 'Jornal', 'Indicação de amigos', 'Outros'] as $v) {
                        ?>
                        <div class="col-sm-6 col-md-<?= strlen($v) > 15 ? '3' : '2' ?>">
                            <?= form::radio('1[conheceu]', $v, $v) ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col">
                    </div>
                </div>
            </div>
            <div class="caixag">
                <p style="text-align: justify">
                    Termo de ciência: O acesso à plataforma de ensino se dará apenas para as matriculas efetivadas (1260 PRIMEIROS INSCRITOS QUE CUMPREM OS REQUISITOS). Revise seus dados antes de finalizar o cadastro, é necessário informar CPF, endereço de e-mail e número de telefone válidos, caso contrário você será automaticamente desclassificado do processo.
                </p>
                <div style="text-align: center; width: 100px; margin: 0 auto">
                    <?= form::checkbox(null, 1, 'Ciente', null, ' id="cient" ') ?>
                </div>
            </div>
            <div class="caixag alert alert-warning" style="padding: 20px; height: 200px; text-align: center; font-weight: bold; font-size: 20px">
                <p>
                    Atenção! Após o envio, este formulário não poderá ser refeito.
                </p>
                <button onmouseover="ciente()" type="submit" id="btn"  class="btn btn-success" style=" margin: 0 auto" >
                    Enviar
                </button>
                <div style="text-align: left; font-size: 16px">
                    <span style="color: red">*</span> Campos obrigatórios
                </div>
            </div>
            <?=
            form::hidden([
                'cpf' => $cpf,
                '1[cpf]' => $cpf,
                'SalvaFormInscr' => uniqid()
            ])
            ?>
        </form>

        <?php
    }
    ?>
</div>
<?php
javaScript::email();
javaScript::telMascara();
javaScript::mascara();
?>
<script>
    function  ciente(c) {
        if (!document.getElementById('cient').checked) {
            alert("Primeiro aceite o Termo de Ciência");
            $('#btn').attr('type', 'button')
        } else {
            $('#btn').attr('type', 'submit')
        }
    }
    function optSet(id) {
        opt1 = document.getElementById('fk_id_cur_');
        opt2 = document.getElementById('fk_id_cur_2_');
        if (opt1.value === opt2.value) {
            alert("A 2º Opção deve ser diferente da 1º Opção");
            document.getElementById(id).value = false;
        }
    }

    function mascaraData(campoData) {
        var data = campoData.value;
        if (data.length == 2) {
            data = data + '/';
            document.forms[0].dt_nasc.value = data;
            return true;
        }
        if (data.length == 5) {
            data = data + '/';
            document.forms[0].dt_nasc.value = data;
            return true;
        }
    }
</script>