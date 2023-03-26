<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma');
$id_turma = filter_input(INPUT_POST, 'id_turma');
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$super = $model->supervisor(toolErp::id_inst(), $id_curso);
$ins = @$_POST[1];
if ($ins['fk_id_sf'] == 5) {
    $sitFinal = 'Retido p/ Conselho';
} else {
    $sitFinal = 'Promovido p/ Conselho';
}
@$matricula = sqlErp::get('ge_funcionario', 'rm', ['fk_id_pessoa' => toolErp::id_pessoa()], 'fetch')['rm'];

$pess = sqlErp::get('pessoa', 'n_pessoa, sexo', ['id_pessoa' => $id_pessoa], 'fetch');
$pl = sqlErp::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];
$textTop = "A escola " . toolErp::n_inst() . " realizou alterações na ATA da turma " . $n_turma . " no período letivo " . (is_numeric($pl) ? 'de' : 'do') . " " . $pl . ".<br /><br />"
        . "Segue abaixo, cópia da ementa<br /><br />";
?>
<div class="body">
    <div class="col-10">
        <div class="fieldTop">
            Alun<?= toolErp::sexoArt($pess['sexo']) ?> <?= $pess['n_pessoa'] ?> no Período Letivo <?= is_numeric($pl) ? 'de' : 'do' ?> <?= $pl ?>
        </div>
    </div>
    <div style="font-weight: bold; font-size: 1.2em; line-height: 1.5;">
        O texto abaixo será incluído nas Atas da turma <?= $n_turma ?> do período letivo <?= is_numeric($pl) ? 'de' : 'do' ?> <?= $pl ?> e enviado para 
        o e-mail <?= $super['emailgoogle'] ?> d<?= toolErp::sexoArt($super['sexo']) ?> supervisor<?= $super['sexo'] == 'F' ? 'a' : '' ?> <?= $super['n_pessoa'] ?>.

    </div>
    <br /><br />
    <div class="alert alert-dark" style="font-weight: bold; text-align: justify; font-size: 1.4em;  line-height: 1.5;">
        <?php
        echo $texto = "No dia " . strtolower(data::porExtenso(date("Y-m-d"))) . ", o funcionário "
        . toolErp::n_pessoa() . ", matrícula " . $matricula
        . ", acessou os registros d" . toolErp::sexoArt($pess['sexo']) . " alun" . toolErp::sexoArt($pess['sexo']) . " " . $pess['n_pessoa']
        . " e alterou a situação final para $sitFinal";
        ?>
    </div>
    <div style=" padding: 50px">
        <?php
        $confirma = 'Eu, ' . toolErp::n_pessoa() . ', matrícula ' . $matricula . ' reafirmo o texto acima';
        echo formErp::checkbox(null, 1, $confirma, null, ' onclick="confirma(this)"');
        ?>
    </div>
    <div style="text-align: center; padding: 50px; display: none" id="btn">
        <form target="_parent" action="<?= HOME_URI ?>/avalia/ataEdit" method="POST">
            <?=
            formErp::hidden($ins)
            . formErp::hidden([
                'id_turma' => $id_turma,
                'id_pessoa'=>$id_pessoa,
                'texto' => $texto,
                'superEmail' => $super['emailgoogle'],
                'superNome' => $super['n_pessoa'],
                'superSexo' => $super['sexo'],
                'textTop' => $textTop
            ])
            . formErp::hiddenToken('peloConselhoSet')
            ?>
            <button class="btn btn-danger" >
                Enviar
            </button>
        </form>
    </div>
    <div style="text-align: center; padding: 50px" id="btnx">
        <button class="btn btn-secondary" onclick="alert('Clique no termo de reafirmo para liberar o envio')" >
            Enviar
        </button>
    </div>
</div>
<script>
    function confirma(c) {

        if (c.checked) {
            btn.style.display = '';
            btnx.style.display = 'none';
        } else {
            btn.style.display = 'none';
            btnx.style.display = '';
        }
    }
</script>