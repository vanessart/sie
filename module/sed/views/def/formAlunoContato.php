<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$aluno = new ng_aluno($id_pessoa);
$tel = ng_aluno::telefone($id_pessoa);
$cont = empty($tel) ? 3 : count($tel) - 1;
$geral = $aluno->dadosPessoais;
?>
<div class="body">
    <div class="fieldTop">
        Meios de contatos com <?= toolErp::sexoArt($geral['sexo']) ?> Alun<?= toolErp::sexoArt($geral['sexo']) ?> <?= $geral['n_pessoa'] ?>
    </div>
    <form id="form" method="POST" action="<?= HOME_URI ?>/sed/aluno" target="_parent">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[email]', "Email", $geral['email']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[emailgoogle]', "Email Institucional", $geral['emailgoogle'], ' readonly  onclick="alert(\'Para alterar o email institucional, contacte a Secretaria de Educação\')" ') ?>   
            </div>
        </div>
        <br>
        <?php
        foreach (range(0, $cont) as $v) {
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
            <div class="border">
                <div class="row">
                    <div class="col">
                        <?= formErp::input('tel[' . $v . '][num]', 'Telefone', $dddNum, " onkeyup='mascara(this, mtel)' id='m" . $v . "' maxlength=\"15\" ", null, 'tel') ?>
                    </div>
                    <div class="col">
                        <?= formErp::selectDB('telefones_tipo', 'tel[' . $v . '][fk_id_tt]', 'Tipo', @$tel[$v]['fk_id_tt']) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::input('tel[' . $v . '][complemento]', 'Obs:', @$tel[$v]['complemento']) ?>
                    </div>
                </div>
            </div>
            <br />
            <?php
        }
        ?>
        <br />
        <div style="text-align: center">
            <?=
            formErp::hiddenToken('contatoSet')
            . formErp::hidden([
                'id_pessoa' => $id_pessoa,
                'id_turma' => $id_turma,
                'n_turma' => $n_turma,
                '1[id_pessoa]' => $id_pessoa,
                'activeNav' => 2
            ])
            ?>
        </div>
        <br />
    </form>
    <div style="text-align: center">
        <button onclick="envia()" class="btn btn-success">
            Enviar
        </button>
    </div>
</div>
<script>
    function envia() {
        erro = false;
<?php
foreach (range(0, $cont) as $v) {
    ?>
            if (document.getElementById("m<?= $v ?>").value) {
                if (!document.getElementById("<?= $v ?>_").value) {
                    erro = true;
                }
            }
    <?php
}
?>
        if (erro) {
            alert('Defina o Tipo de Telefone');
        } else {
            document.getElementById('form').submit();
        }
    }
</script>
<?php
javaScript::telMascara();
