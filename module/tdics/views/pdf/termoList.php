<?php
@$id_pl = sql::get('tdics_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];
$polos = sql::idNome('tdics_polo');
$cursos = sql::idNome('tdics_curso');

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if (!$id_inst) {
    $id_inst = toolErp::id_inst();
}
$a = $model->alunoEsc($id_pl, $id_inst);
if ($a) {
    foreach ($a as $k => $v) {
        $a[$k]['diaSem'] = $model->diaSemana($v['dia_sem']);
        $a[$k]['periodoSet'] = $v['periodo'] == 'M' ? 'Manhã' : 'Tarde';
        $a[$k]['horarioSet'] = $v['horario'] . 'º Horário';
        $a[$k]['id_pl'] = $v['fk_id_pl'];
        $a[$k]['id_polo'] = $v['fk_id_polo'];
        $a[$k]['id_curso'] = $v['fk_id_curso'];
        $a[$k]['dia'] = $model->diaSemana($v['dia_sem']);
        $a[$k]['n_polo'] = $polos[$v['fk_id_polo']];
        $a[$k]['n_curso'] = $cursos[$v['fk_id_curso']];
    }

    ob_start();
    $pdf = new pdf();
    $pdf->headerSet = null;
    $pdf->mgt = 0;
    $ct = 1;
    foreach ($a as $v) {
        ?>
        <img src="<?= HOME_URI ?>/includes/images/maker/tdics.png" alt="alt"/>
        <br /><br />
        <div style="text-align: center; font-weight: bold; font-size: 20px">
            PROJETO - PARNAÍBA - MAKER LABS
        </div>
        <br />
        <div style="text-align: center; font-weight: bold; font-size: 19px; color: red">
            Público Alvo: 5º ao 9º anos
        </div>
        <br /><br />
        <div style="text-align: center; font-weight: bold; font-size: 19px;">
            TERMO DE MATRÍCULA
        </div>
        <br /><br />
        <div style="text-align: justify; font-weight: bold; font-size: 16px;">
            Eu________________________________________________________________________________________, 
            <br /><br />
            portador (a) do RG
            __________________________________________________ responsável legal pel<?= toolErp::sexoArt($v['sexo']) ?> alun<?= toolErp::sexoArt($v['sexo']) ?>
            <?= $v['n_pessoa'] ?>, matriculad<?= toolErp::sexoArt($v['sexo']) ?> no <?= $v['turmaEsc'] ?> 
            na <?= $v['n_inst'] ?>,
            <br /><br />
            AUTORIZO a participação d<?= toolErp::sexoArt($v['sexo']) ?> alun<?= toolErp::sexoArt($v['sexo']) ?> no Projeto MAKER LABS, conforme segue:
        </div>
        <br /><br />
        <table style="width: 100%; margin: auto; font-weight: bold; font-size: 15px"  cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td>
                    NÚCLEO (Escola):
                </td>
                <td colspan="3">
                    <?= $polos[$v['id_polo']] ?>
                </td>
            </tr>
            <tr>
                <td>
                    CURSO:
                </td>
                <td>
                    <?= $cursos[$v['id_curso']] ?>
                </td>
                <td>
                    CÓD. TURMA:
                </td>
                <td>
                    <?= $v['n_turma'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    DIA DA SEMANA: 
                </td>
                <td>
                    <?= $v['dia'] ?>
                </td>
                <td>
                    HORÁRIO:
                </td>
                <td>
                    <?= $model->horario($v['id_polo'], $v['periodo'], $v['horario']) ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <div style="text-align: justify; font-weight: bold; font-size: 16px;">
            Estou de acordo com o cronograma e demais regulamentos já estabelecidos pela Secretaria
            Municipal de Educação no ato da matrícula no Ensino Fundamental.
            <br /><br />
            Sem mais,

        </div>
        <br />
        <div style="text-align: right; padding: 30px; font-weight: bold; font-size: 16px;">
            <?= CLI_CIDADE ?>, <?= dataErp::porExtenso(date("Y-m-d")) ?>
            <br /><br /><br /><br /><br /><br /><br />
            ___________________________________________________
            <br />  
            Assinatura: Responsável Legal
        </div>
        <?php
        if ($ct++ < count($a)) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        }
    }
    $pdf->exec('Termo_de_Matr.pdf');
}