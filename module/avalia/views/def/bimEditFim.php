<?php
if (!defined('ABSPATH'))
    exit;
$motivo = filter_input(INPUT_POST, 'motivo');
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$model->editNota($id_pessoa, $id_turma);
$pess = $model->_pessoa;
$notas = $model->_notaFalta;
$turma = $model->_turma;
$pl = sqlErp::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turma['id_pl']], 'fetch')['n_pl'];
$super = $model->_supervisor;
$disciplinas = $model->_disciplinas; // a chave é o id_disc
$notaEdit = @$_POST['notaEdit'];
$faltaEdit = @$_POST['faltaEdit'];
@$matricula = sqlErp::get('ge_funcionario', 'rm', ['fk_id_pessoa' => toolErp::id_pessoa()], 'fetch')['rm'];
$textTop = "A escola " . toolErp::n_inst() . " realizou alterações na ATA da turma " . $turma['n_turma'] . " no período letivo " . (is_numeric($pl) ? 'de' : 'do') . " " . $pl . ".<br /><br />"
        . "Segue abaixo, cópia da ementa<br /><br />";
if (empty($matricula)) {
    ?>
    <div style="padding: 50px; text-align: center; font-weight: bold; font-size: 1.4em">
        <p>
            Algo errado não está certo :(
        </p>
    </div>
    <?php
    die();
}
foreach ($notaEdit as $bim => $ns) {
    foreach ($ns as $id_disc => $n) {
        if ($notas[$bim]['media_' . $id_disc] != $n) {
            if ($n == 'i' || $n == 'I') {
                $textoAlt[] = " excluiu a nota " . str_replace('.', ',', @$notas[$bim]['media_' . $id_disc]) . " da disciplina de " . $disciplinas[$id_disc]['n_disc']
                        . " do " . $bim . "º " . $turma['un_letiva'] . " deixando o " . $turma['un_letiva'] . " fora do cálculo da média final";
                $alterado['nf[' . $bim . '][media_' . $id_disc . ']'] = 'I';
                $anterior['na[' . $bim . '][media_' . $id_disc . ']'] = $notas[$bim]['media_' . $id_disc];
            } else {
                $n = str_replace(',', '.', $n);
                $di = explode('.', $n);
                if (empty($di[1])) {
                    $n = intval($n);
                    $d5 = 0;
                } else {
                    $d5 = $di[1];
                }

                if (str_replace(',', '.', $notas[$bim]['media_' . $id_disc]) != $n) {
                    if (($n > 10) || ($n < 0) || !is_numeric($n) || !in_array($d5, [0, 5])) {
                        $erro[] = '"' . $n . '"';
                    } else {
                        $textoAlt[] = ' na disciplina de ' . $disciplinas[$id_disc]['n_disc'] . ' do ' . $bim . "º " . $turma['un_letiva']
                                . ', mudou a nota de "' . str_replace('.', ',', @$notas[$bim]['media_' . $id_disc]) . '" para "' . str_replace('.', ',', $n) . '" ';
                        $alterado['nf[' . $bim . '][media_' . $id_disc . ']'] = str_replace(',', '.', $n);
                        $anterior['na[' . $bim . '][media_' . $id_disc . ']'] = $notas[$bim]['media_' . $id_disc];
                    }
                }
            }
        }
    }
}
foreach ($faltaEdit as $bim => $ns) {
    foreach ($ns as $id_disc => $n) {
        if (intval($notas[$bim]['falta_' . $id_disc]) != intval($n)) {
            if (!is_numeric($n)) {
                $erro[] = '"' . $n . '"';
            } else {
                $textoAlt[] = ' na disciplina ' . $disciplinas[$id_disc]['n_disc'] . ' do ' . $bim . "º " . $turma['un_letiva']
                        . ', mudou  de "' . intval(@$notas[$bim]['falta_' . $id_disc]) . '" para "' . intval($n) . '" o número de faltas';
                $alterado['nf[' . $bim . '][falta_' . $id_disc . ']'] = intval($n);
                $anterior['na[' . $bim . '][falta_' . $id_disc . ']'] = $notas[$bim]['falta_' . $id_disc];
            }
        }
    }
}
if (!empty($textoAlt)) {
    $texto = "No dia " . strtolower(data::porExtenso(date("Y-m-d"))) . ", o funcionário "
            . toolErp::n_pessoa() . ", matrícula " . $matricula
            . ", acessou os registros d" . toolErp::sexoArt($pess['sexo']) . " alun" . toolErp::sexoArt($pess['sexo']) . " " . $pess['n_pessoa']
            . " e fez " . (count($textoAlt) > 1 ? 'as seguintes alterações:' : 'a seguinte alteração:' . " ");
    $texto .= $model->pontVirgulaE($textoAlt) . '.';
    $texto .= "&nbsp;&nbsp;&nbsp;" . toolErp::n_pessoa() . ' justificou as alterações da seguinte maneira: "' . (str_replace(['"', "'"], '', $motivo)) . '". ';
}
?>
<div class="body">
    <div class="row">
        <div class="col-10">
            <div class="fieldTop">
                Alun<?= toolErp::sexoArt($pess['sexo']) ?> <?= $pess['n_pessoa'] ?> no Período Letivo <?= is_numeric($pl) ? 'de' : 'do' ?> <?= $pl ?>
            </div>
        </div>
        <div class="col-2">
            <div class="fieldTop">
                <form action="<?= HOME_URI ?>/avalia/def/bimEdit" method="POST">
                    <?=
                    formErp::hidden([
                        'id_turma' => $id_turma,
                        'id_pessoa' => $id_pessoa,
                    ])
                    . formErp::button('Refazer')
                    ?>
                </form>
            </div>
        </div>
    </div>
    <br />
    <?php
    if (!empty($erro)) {
        ?>
        <div class="alert alert-danger" style="font-weight: bold;">
            <?php
            if (count($erro) > 1) {
                ?>
                Os valores <?= toolErp::virgulaE($erro) ?> não são válidos e foram desconsiderados.
                <?php
            } else {
                ?>
                O valor <?= current($erro) ?> não é válido e foi desconsiderado.
                <?php
            }
            ?>
        </div>
        <?php
    }
    if (empty($textoAlt)) {
        ?>
        <div class="alert alert-warning">
            Nada a ser alterado.
        </div>
        <?php
        die();
    } elseif (empty($motivo)) {
        ?>
        <div class="alert alert-danger" style="font-weight: bold; text-align: center">
            <p>
                O motivo é campo obrigatório.
            </p>
        </div>
        <?php
        die();
    } else {
        ?>
        <div style="font-weight: bold; font-size: 1.2em; line-height: 1.5;">
            O texto abaixo será incluído nas Atas da turma <?= $turma['n_turma'] ?> do período letivo <?= is_numeric($pl) ? 'de' : 'do' ?> <?= $pl ?> e enviado para 
            o e-mail <?= $super['emailgoogle'] ?> d<?= toolErp::sexoArt($super['sexo']) ?> supervisor<?= $super['sexo'] == 'F' ? 'a' : '' ?> <?= $super['n_pessoa'] ?>.

        </div>
        <br /><br />
        <div class="alert alert-dark" style="font-weight: bold; text-align: justify; font-size: 1.4em;  line-height: 1.5;">
            <?= $texto ?>
        </div>
        <?php
    }
    ?>
    <div style=" padding: 50px">
        <?php
        $confirma = 'Eu, ' . toolErp::n_pessoa() . ', matrícula ' . $matricula . ' reafirmo o texto acima';
        echo formErp::checkbox(null, 1, $confirma, null, ' onclick="confirma(this)"');
        ?>
    </div>
    <div style="text-align: center; padding: 50px; display: none" id="btn">
        <form target="_parent" action="<?= HOME_URI ?>/avalia/ataEdit" method="POST">
            <?=
            formErp::hidden($alterado)
            . formErp::hidden($anterior)
            . formErp::hidden([
                'id_turma' => $id_turma,
                'texto' => $texto,
                'id_pessoa' => $id_pessoa,
                'id_curso' => $turma['id_curso'],
                'id_pl' => $turma['id_pl'],
                'superEmail' => $super['emailgoogle'],
                'superNome' => $super['n_pessoa'],
                'superSexo' => $super['sexo'],
                'textTop' => $textTop
            ])
            . formErp::hiddenToken('ementaSet')
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