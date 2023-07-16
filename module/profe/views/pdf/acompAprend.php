<?php
if (!defined('ABSPATH'))
    exit;

ob_start();
$id = @$_GET['id'];
$id_pessoaAlu = filter_input(INPUT_POST, 'id_pessoaAlu', FILTER_SANITIZE_NUMBER_INT);
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
$semestre = filter_input(INPUT_POST, 'semestre', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_pessoaAlu)) {
    $dados = sqlErp::get('profe_acompanhamento', '*', ['fk_id_pessoa_alu' => $id_pessoaAlu, 'ano' => $ano, 'semestre' => $semestre], 'fetch');
} elseif (!empty($id)) {
    $it = explode(',', $id);

    $id_pessoaAlu = $it[0];
    $timesStamp = str_replace('a', ' ', $it[1]);
    $dados = sqlErp::get('profe_acompanhamento', '*', ['fk_id_pessoa_alu' => $id_pessoaAlu, 'times_stamp' => $timesStamp], 'fetch');
}
if (!empty($dados['id_acomp'])) {
    $n_inst = sql::get('instancia', 'n_inst', ['id_inst' => $dados['fk_id_inst']], 'fetch')['n_inst'];
    $n_turma = sql::get('ge_turmas', 'n_turma', ['id_turma' => $dados['fk_id_turma']], 'fetch')['n_turma'];
    $pdf = new pdf();
    $pdf->mgt = '50';
    $pdf->mgb = '30';
    $pdf->headerAlt = '<img src="' . ABSPATH . '/'. INCLUDE_FOLDER .'/images/topo.jpg"/>' . '<div style="text-align: center; font-weight: bold; font-size: 14px"><p>Acompanhamento Semestral do Desenvolvimento e da Aprendizagem</p></div>';
    ?>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
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
        }
         ?>
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
                    $end = $_SERVER['HTTP_HOST'].HOME_URI . '/profe/pdf/acompAprend.php?id=' . $id_pessoaAlu . ',' . str_replace(' ', 'a', $dados['times_stamp']);
                    $code = HOME_URI . '/app/code/php/qr_img.php?d=' . $end . '&.PNG';
                    ?>
                    <img style="width: 100px" src = "<?php echo $code ?>"/>
                </td>
                <td>
                    Autenticado pela Secretaría de Educação de <?= CLI_CIDADE ?>
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
    $pdf->exec();
}