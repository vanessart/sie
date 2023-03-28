<style>
    body{
        background-color: #F2F5EC;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$situacao = filter_input(INPUT_POST, 'situacao', FILTER_SANITIZE_STRING);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_NUMBER_INT);
$curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_NUMBER_INT);
$opt = filter_input(INPUT_POST, 'opt', FILTER_SANITIZE_NUMBER_INT);
$pag = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
$formulario = filter_input(INPUT_POST, 'formulario', FILTER_SANITIZE_NUMBER_INT);
$id_inscr = filter_input(INPUT_POST, 'id_inscr', FILTER_SANITIZE_NUMBER_INT);

if ($formulario) {
    $model->_form = $id_inscr;
    $inscSet = sql::get('quali_inscr', '*', ['id_inscr' => $formulario], 'fetch');
    $inscrito = sql::get('quali_incritos_' . $formulario, '*', ['id_inscr' => $id_inscr], 'fetch');
}

if (!empty($inscSet['cursos'])) {
    $sql = "SELECT id_cur, n_cur FROM `gt_curso` WHERE `id_cur` in (" . $inscSet['cursos'] . ") ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    $cursos = tool::idName($array);
} else {
    $cursos = null;
}
$ceps_ = sql::get('quali_cepsBairroBarueri', 'id_cep');
foreach ($ceps_ as $v) {
    $ceps[$v['id_cep']] = substr($v['id_cep'], 0, 5) . '-' . substr($v['id_cep'], -3);
}
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/quali/defere" id="formEnvio" method="POST">
        <div class="caixag">
            <?= form::input('1[nome]', 'Nome Completo&nbsp;<span style="color: red">*</span>', @$inscrito['nome'], ' required ') ?>
        </div>
        <div class="caixag">
            <?= form::input('1[email]', 'E-mail&nbsp;<span style="color: red">*</span>', @$inscrito['email'], ' required onblur="checarEmail(this)"', null, 'email') ?>
        </div>
        <div class="caixag">
            <div class="row">
                <div class="col">
                    <?= form::input('1[cpf]', 'CPF(Só Números)', @$inscrito['cpf'], ' required ') ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <?= form::input('1[rg]', 'RG&nbsp;<span style="color: red">*</span>', @$inscrito['rg'], ' required ') ?>
                </div>
                <div class="col-sm-12 col-md-6">
                    <?= form::input('1[dt_nasc]', 'Data de Nascimento&nbsp;<span style="color: red">*</span>', @$inscrito['dt_nasc'], ' required ', null, 'date') ?>
                </div>
            </div>
        </div>
        <div class="caixag">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <?= form::selectDB('civil', '1[fk_id_civil]', 'Estado Civil&nbsp;<span style="color: red">*</span>', @$inscrito['fk_id_civil'], null, null, null, null, ' required ') ?>
                </div>
                <div class="col-sm-12 col-md-4">
                    <?= form::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo&nbsp;<span style="color: red">*</span>', @$inscrito['sexo'], null, null, ' required ') ?>
                </div>
            </div>
        </div>
        <div class="caixag">
            <div class="row">
                <div class="col">
                    <?= form::selectDB('escolaridade', '1[fk_id_esc]', 'Escolaridade&nbsp;<span style="color: red">*</span>', @$inscrito['fk_id_esc'], null, null, null, null, ' required ') ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <?= form::selectDB('sit_emprego', '1[fk_id_se]', 'Situação Atual&nbsp;<span style="color: red">*</span>', @$inscrito['fk_id_se'], null, null, null, null, ' required ') ?>
                </div>
                <div class="col-sm-12 col-md-6">
                    <?= form::selectDB('sit_familia', '1[fk_id_sf]', 'Situação na família&nbsp;<span style="color: red">*</span>', @$inscrito['fk_id_sf'], null, null, null, null, ' required ') ?>
                </div>
            </div>
        </div>
        <div class="caixag">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <?= form::input('1[celular]', 'Celular com DDD&nbsp;<span style="color: red">*</span>', @$inscrito['celular'], ' required onkeyup="mascara(this, mtel)" maxlength="15" ') ?>
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
                                <?= form::checkbox('1[whatsapp]', 1, 'Meu Celular tem WhatsApp', @$inscrito['whatsapp']) ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-12 col-md-4">
                    <?= form::input('1[recado]', 'Telefone Recado', @$inscrito['recado'], ' onkeyup="mascara(this, mtel)" maxlength="15" ') ?>
                </div>
            </div>
        </div>
        <div class="caixag">
            <div class="row">
                <div class="col-sm-12 col-md-9">
                    <?= form::input('1[logradouro]', 'Endereço&nbsp;<span style="color: red">*</span>', @$inscrito['logradouro'], ' required ') ?>
                </div>
                <div class="col-sm-12 col-md-3">
                    <?= form::input('1[num]', 'Número&nbsp;<span style="color: red">*</span>', @$inscrito['num'], ' required ') ?>
                </div>
            </div>
        </div>
        <div class="caixag">
            <div class="row">
                <div class="col">
                    <?= form::input('1[complemento]', 'Complemento', @$inscrito['complemento']) ?>
                </div>
            </div>
        </div>
        <div class="caixag">
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <?= form::input('1[bairro]', 'Bairro&nbsp;<span style="color: red">*</span>', @$inscrito['bairro'], ' required ') ?>
                </div>
                <div class="col-sm-12 col-md-4">
                    <?= form::select('1[cep]', $ceps, ['CEP&nbsp;<span style="color: red">*</span>', 'Sem CEP'], @$inscrito['cep']) ?>
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
                    Curso desejado - A Primeira Opção é a válida para matrícula
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <?= form::select('1[fk_id_cur]', $cursos, '1ª Opção&nbsp;<span style="color: red">*</span>', @$inscrito['fk_id_cur'], null, null, ' required ') ?>
                </div>
                <div class="col-sm-12 col-md-6">
                    <button class="btn btn-warning" type="button" style="width: 100%">
                        <?php
                        if (!empty($inscrito['fk_id_cur_2'])) {
                            echo '2ª Opção: ' . @$cursos[$inscrito['fk_id_cur_2']];
                        } else {
                            ?>
                            Não fez a 2ª Opção
                            <?php
                        }
                        ?>
                    </button>
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
                            <?= form::radio('1[aluno]', 1, 'Sim', @$inscrito['aluno'], ' onclick="$(\'#fases\').show()" ') ?>
                        </div>
                        <div class="col">
                            <?= form::radio('1[aluno]', 0, 'Não', @$inscrito['aluno'], ' onclick="$(\'#fases\').hide()"') ?>
                        </div>
                    </div>
                    <br />
                </div>
            </div>
        </div>

        <div class="caixag alert alert-warning" style="padding: 20px; height: 200px; text-align: center; font-weight: bold; font-size: 20px">
            <div class="row">
                <div class="col text-center">
                    <?= form::textarea('1[obs]', @$inscrito['obs'], 'Observações') ?>
                </div>
                <div class="col">
                    <?= form::select('1[situacao]', [1 => 'Deferido', 2 => 'Indeferido'], ['Situação', 'Aguardando Deferimento'], @$inscrito['situacao']) ?>
                    <div style="text-align: center; padding: 25px">
                        <button  type="submit" id="btn"  class="btn btn-success" style=" margin: 0 auto" >
                            Enviar
                        </button>
                    </div>
                </div>
            </div>
            <br />
            <div style="text-align: left; font-size: 16px">
                <span style="color: red">*</span> Campos obrigatórios
            </div>
        </div>
        <?=
        form::hidden([
            '1[id_inscr]' => $id_inscr,
            'SalvaFormInscrEdit' => uniqid(),
            'nome' => $nome,
            'situacao' => $situacao,
            'curso' => $curso,
            'opt' => $opt,
            'pagina' => $pag,
            'formulario' => $formulario
        ])
        ?>
    </form>
</div>
<?php
javaScript::email();
javaScript::telMascara();
javaScript::mascara();
?>
