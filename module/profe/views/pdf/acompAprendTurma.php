<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$n_inst = filter_input(INPUT_POST, 'escola');
$n_turma = filter_input(INPUT_POST, 'n_turma');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
$semestre = filter_input(INPUT_POST, 'semestre', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma && $ano && $semestre) {
    $dadosList = sqlErp::get('profe_acompanhamento', '*', ['fk_id_turma' => $id_turma, 'ano' => $ano, 'semestre' => $semestre]);

    if ($dadosList) {
        $pdf = new pdf();
        $pdf->mgt = '50';
        $pdf->mgb = '30';
        $pdf->headerAlt = '<img src="' . ABSPATH . '/includes/images/topo.jpg"/>' . '<div style="text-align: center; font-weight: bold; font-size: 14px"><p>Acompanhamento Semestral do Desenvolvimento e da Aprendizagem</p></div>';

        foreach ($dadosList as $dados) {
            $id_pessoaAlu = $dados['fk_id_pessoa_alu'];
            ?>
            <table style="width: 100%;" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
                <tr>
                    <td style="width: 150px">
                        Escola
                    </td>
                    <td>
                        <?= $n_inst ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome
                    </td>
                    <td>
                        <?= toolErp::n_pessoa($id_pessoaAlu) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Turma
                    </td>
                    <td>
                        <?= $n_turma ?>
                    </td>
                </tr>
                <?php 
                if (!empty($dados['fk_id_pessoa_prof'])) {?>
                    <tr>
                        <td>
                            Professor<?= (toolErp::sexo_pessoa($dados['fk_id_pessoa_prof']) == 'M' ? 'a' : null) ?>
                        </td>
                        <td>
                            <?= toolErp::n_pessoa($dados['fk_id_pessoa_prof']) ?>
                        </td>
                    </tr>
                    <?php 
                }?>
                <tr>
                    <td>
                        Período
                    </td>
                    <td>
                        <?= $dados['semestre'] ?>º Semestre de <?= $dados['ano'] ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold; background-color: black; color: white">
                        Parecer Descritivo
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <pre>
                            <?= $dados['parecer'] ?> 
                        </pre>
                    </td>
                </tr>
                <?php
                if ($dados['situacao'] == 2) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            if (!empty($id_pessoaAlunx)) {
                                $id_pessoaAlu = $id_pessoaAlunx;
                            }
                            $end = $_SERVER['HTTP_HOST'].HOME_URI .'/profe/pdf/acompAprend.php?id=' . $id_pessoaAlu . ',' . str_replace(' ', 'a', $dados['times_stamp']);
                            $code = HOME_URI . '/app/code/php/qr_img.php?d=' . $end . '&.PNG';
                            ?>
                            <img style="width: 100px" src = "<?php echo $code ?>"/>
                        </td>
                        <td>
                            Autenticado pela Secretaría de Educação de Barueri
                            <br />
                            <p style="text-align: justify">
                                Confira a autenticidade deste documento utilizando o Qr Code ao lado ou acesse o endereco:
                            </p>
                            <p>
                                <?= $end ?>
                            </p>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
            if (count($dadosList) > @++$conts) {
                ?>
                <div style="page-break-after: always"></div>
                <?php
            }
        }

        $pdf->exec();
    } else {
        echo 'Não há lançamento de acompanhamento semestral'; 
    }
}