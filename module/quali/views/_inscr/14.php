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
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (!tool::cpfValida($cpf)) {
        tool::alert('CPF Inválido');
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
    $cursosCompleto = $query->fetchAll(PDO::FETCH_ASSOC);

    $cursos = tool::idName($cursosCompleto);
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
        <img style="width: 300px" src="<?= HOME_URI ?>/includes/images/jrrd/jrrd.jpg"/>
    </div>
    <div style="text-align: center; font-weight: bold; font-size: 20px; padding: 10px; background-color: #BF302E; color: white">
        Programa de Desenvolvimento Organizacional
        <br />
        Câmara Municipal de Barueri
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
            Parabéns <?= strtoupper($_POST[1]['nome']) ?>! Sua pré-inscrição foi realizada com sucesso.
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
    } elseif ($nome = $model->jaInscrito($cpf, $i)) {
        ?>
        <div class="caixag alert alert-warning" style="text-align: center; font-size: 20px">
            <?= $nome ?>, sua pré-inscrição já está cadastrada.
            <br /><br />
            Se não foi você que realizou a pré-inscrição, Contate a equipe do projeto Programa de Desenvolvimento Organizacional
            <br /><br />
            <form>
                <button class="btn btn-primary">
                    Sair
                </button>
            </form>
        </div>
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
                        <?= form::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo&nbsp;<span style="color: red">*</span>', null, null, null, ' required ') ?>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <?= form::input(null, 'CPF', substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, -2), ' readonly ') ?>
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
                <p style="text-align: justify">
                    Em concordância com a Lei Geral de Proteção de Dados Pessoais (LGPD), Lei nº 13.709, de 14 de agosto de 2018, os dados coletados serão utilizados única e exclusivamente para inscrição no referido curso.
                </p>
                <div style="text-align: center; width: 100px; margin: 0 auto">
                    <?= form::checkbox(null, 1, 'Aceito', null, ' id="cient" ') ?>
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
                'SalvaFormInscr' => uniqid(),
                '1[fk_id_cur]' => 1
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

</script>