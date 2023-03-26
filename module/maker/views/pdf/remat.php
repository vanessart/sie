<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_NUMBER_INT);
$n_inst = $periodo = filter_input(INPUT_POST, 'n_inst');
$n_polo = $periodo = filter_input(INPUT_POST, 'n_polo');
$a = @$_POST[1];
foreach ($a as $v) {
    if ($v) {
        $ids[] = $v;
    }
}
if (!empty($ids)) {
    if ($id_inst && $origem) {
        if ($origem == 2) {
            $al = $model->alunoList($id_inst);
            foreach ($al as $y) {
                if (in_array($y['RSE'], $ids)) {
                    $alunos[] = [
                        'id_pessoa' => $y['RSE'],
                        'n_pessoa' => $y['Nome'],
                        'n_turma' => $y['Turma de Origem'],
                        'sexo' => $y['sexo'],
                        'maker' => $y['Turma Maker']
                    ];
                }
            }
        } else {
            $al = $model->alunosEscolaComTurma($id_inst);
            if ($al[1]) {
                foreach ($al[1] as $v) {
                    foreach ($v as $y) {
                        if (in_array($y['id_pessoa'], $ids)) {
                            $alunos[] = [
                                'id_pessoa' => $y['id_pessoa'],
                                'n_pessoa' => $y['n_pessoa'],
                                'n_turma' => $y['n_turma'],
                                'sexo' => $y['sexo'],
                                'maker' => null
                            ];
                        }
                    }
                }
            }
        }
    }
}
if (!empty($alunos)) {
    ob_clean();
    ob_start();
    ?>
    <style>
        div{
            line-height: 2.0;
        }
    </style>
    <?php
    $pdf = new pdf();
    $pdf->headerAlt = '<div style="text-align: center; font-weight: bold; font-size: 1.8em"><img src="' . HOME_URI . '/includes/images/maker/img.jpg" width="234" height="94"/><br>PROJETO - SALA MAKER</div><div style="text-align: center; font-weight: bold">Termo de Requerimento de Inscrição / Rematrícula - de 30/05 a 11/06/2022</div>';
    $ct = 1;
    foreach ($alunos as $v) {
        ?>
        <div style="text-align: justify; padding-top: 80px">
            Eu____________________________________________________, portador do RG ______________________________________ responsável legal pel<?= toolErp::sexoArt($v['sexo']) ?> alun<?= toolErp::sexoArt($v['sexo']) ?> <?= $v['n_pessoa'] ?>, RSE: <?= $v['id_pessoa'] ?>, matriculad<?= toolErp::sexoArt($v['sexo']) ?> no <?= substr($v['n_turma'], 0, 1) ?>º ano, turma: <?= $v['n_turma'] ?> na ESCOLA <?= $n_inst ?>, solicito e autorizo a participação d<?= toolErp::sexoArt($v['sexo']) ?> alun<?= toolErp::sexoArt($v['sexo']) ?> no Projeto Cultura Maker/Robótica, de acordo com o cronograma e demais regulamentos já estabelecidos pela Secretaria Municipal de Educação no ato da matrícula no Ensino Fundamental.
        </div>
        <div style="text-align: center; font-weight: bold; padding-top: 20px">
            Preenchimento Obrigatório:
        </div>
        <div style="text-align: justify; font-weight: bold; padding-top: 20px">
            ( <?= $origem == 2 ? 'X' : '&nbsp;&nbsp;' ?> ) <?= strtoupper(toolErp::sexoArt($v['sexo'])) ?> alun<?= toolErp::sexoArt($v['sexo']) ?> já é participante do “Projeto Maker”, solicita sua REMATRÍCULA para o segundo semestre de  2022, no polo <?= $n_polo ?>;
        </div>
        <div style="text-align: justify; font-weight: bold; color: red">
            * Estou ciente que após três faltas consecutivas, sem justificativa, a vaga será cedida para outro candidato.
        </div>
        <div style="padding-top: 20px">
            Informações Complementares:
        </div>
        <ul>
            <li>
                Alergia Alimentar?  -      (  ) Não   ou   (  ) Sim: Qual? ____________________________
            </li>
        </ul>
        <div>
            Sem mais,
        </div>
        <div style="text-align: right; padding-top: 40px">
            Barueri,______ de _____________________de 2022
        </div>
        <div style="text-align: right; padding-top: 60px">
            ___________________________________________________
            <br />
            Assinatura: Responsável Legal
        </div>
        <div>
            **  Devolver preenchido na Secretaria da sua própria escola.
        </div>
        <?php
        if ($ct++ < count($alunos)) {
            ?>
            <div style="page-break-before: always;"></div>
            <?php
        }
    }
    $pdf->exec();
}

