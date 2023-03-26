<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
?>
<style>
    td{
        padding: 3px;
    }
</style>
<?php
$pdf = new pdf();
$pdf->id_inst = 13;
$pdf->headerAlt = '<img src="' . ABSPATH . '/includes/images/topo.jpg"/>';
$n_equipamento = filter_input(INPUT_POST, 'n_equipamento', FILTER_SANITIZE_STRING);
$serial = filter_input(INPUT_POST, 'n_serial', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_move = filter_input(INPUT_POST, 'id_move', FILTER_SANITIZE_NUMBER_INT);
$comodato = filter_input(INPUT_POST, 'comodato', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$gerente = $model->gerente();
$prof = $model->funcionarios($id_inst,$gerente);
$itens = $model->itensGet(null,null,$id_move);
$e = new escola($id_inst);
$email = 'Não Cadastrado';
if (array_key_exists($id_pessoa, $prof)) {
    $func = sql::get(['pessoa', 'ge_funcionario'], 'n_pessoa, rm, cpf, pessoa.email, sexo, pessoa.emailgoogle, tel1,tel2,tel3', ['id_pessoa' => $id_pessoa], 'fetch');
    $email = !empty($func['emailgoogle']) ? $func['emailgoogle'] : $func['email'];
    $arrTel = [$func['tel1'],$func['tel2'],$func['tel3']];
    $foneFunc = "";
    foreach ($arrTel as $v) {
        if (!empty($v)) {
           $foneFunc = (!empty($foneFunc) ? $foneFunc.", ".$v : $v); 
        }  
    }
} else {
    $alu = new aluno($id_pessoa);
    $alu->endereco();
    $arrTel = [$alu->_tel1,$alu->_tel2,$alu->_tel3];
    $foneAluno = "";
    foreach ($arrTel as $v) {
        if (!empty($v)) {
            $foneAluno = (!empty($foneAluno)&&!empty($v) ? $foneAluno.", ".$v : $v);
        }
    }
    $arrMail = [$alu->_responsEmail,$alu->_emailGoogle];
    $mailAluno = "";
    foreach ($arrMail as $v) {
        if (!empty($v)) {
            $mailAluno = (!empty($mailAluno) ? $mailAluno.", ".$v : $v);
        }
    }
}
if ($comodato == 1) {
    $mensagem = 'Este equipamento permanece vinculado ao registro de matrícula do funcionário por tempo indeterminado até sua devolução nos termos legais.';
    $titulo_comodato = 'Em Comodato';
}else{
    $mensagem = 'Este equipamento permanece no inventário da escola sob responsabilidade do emprestante, o qual se compromete a efetuar a devolução até a data determinada.';
}
?>
<div style="text-align: center; font-size: 22px; font-weight: bold">
    Termo de Empréstimo <?= @$titulo_comodato ?> ‐ Guarda e Uso de Equipamento
</div>
<br /><br />
<p style="text-align: justify">
    Declaro que, nesta data recebi da U.E. <?= $e->_nome ?>, O equipamento abaixo citado em
    perfeitas condições de uso sob o título de comodato gratuito
    comprometendo-me a mante‐lo em perfeito estado de
    conservação, ficando ciente de que:
</p>
<ul>
    <li style="text-align: justify">
        <?= $mensagem ?>
    </li>
    <li style="text-align: justify">
        Em caso de dano manutenção, inutilização ou extravio do equipamento deverei requerer uma Ordem de
        serviço através da Unidade Escolar que formulará a requisição através do canal próprio no Departamento
        de Informática da Seretaria da Educação e se for o caso apresentar B.O, tendo ciência de que os custos
        de eventual reparo ou substituição acontecerá por minha conta em caso de mau uso;
    </li style="text-align: justify">
    <li>
        Comprometo‐me a devolver o referido equipamento, conforme acordado, 
        após o período estabelecido e/ou chamamento de devolução por parte da escola.
    </li>
</ul>
<?php
if (!empty($func)) {
    ?>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td>
                Funcionári<?= toolErp::sexoArt($func['sexo']) ?>
            </td>
            <td>
                <?= $func['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td>
                RM
            </td>
            <td>
                <?= $func['rm'] ?>
            </td>
        </tr>
        <tr>
            <td>
                CPF 
            </td>
            <td>
                <?= $func['cpf'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Telefone 
            </td>
            <td>
                <?= $foneFunc ?>
            </td>
        </tr>
        <tr>
            <td>
                E-mail
            </td>
            <td>
                <?= $email ?>
            </td>
        </tr>
        <tr>
            <td>
                Equipamento
            </td>
            <td>
                <?= $n_equipamento ?>
            </td>
        </tr>
        <tr>
            <td>
                Número de Série
            </td>
            <td>
                <?= $serial ?>
            </td>
        </tr>
        <?php
        if (!empty($itens)) {
            ?>
            <tr>
                <td>
                    Itens inclusos
                </td>
                <td>
                    <?= $itens ?>
                </td>
            </tr>
            <?php
        }?>
    </table>
    <?php
} elseif (!empty($alu)) {
    ?>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td>
                Alun<?= toolErp::sexoArt($alu->_sexo) ?>
            </td>
            <td>
                <?= $alu->_nome ?>
            </td>
        </tr>
        <tr>
            <td>
                RA
            </td>
            <td>
                <?= $alu->_ra ?>
            </td>
        </tr>
        <tr>
            <td>
                Equipamento
            </td>
            <td>
                <?= $n_equipamento ?>
            </td>
        </tr>
        <tr>
            <td>
                Número de Série
            </td>
            <td>
                <?= $serial ?>
            </td>
        </tr>
        <?php
        if (!empty($itens)) {
            ?>
            <tr>
                <td>
                    Itens inclusos
                </td>
                <td>
                    <?= $itens ?>
                </td>
            </tr>
            <?php
        }?>
        <tr>
            <td>
                Nome do Responsável pel<?= toolErp::sexoArt($alu->_sexo) ?> Alun<?= toolErp::sexoArt($alu->_sexo) ?>
            </td>
            <td>
                <?= $alu->_responsavel ?>
            </td>
        </tr>
        <tr>
            <td>
                CPF do Responsável
            </td>
            <td>
                <?= $alu->_responsCpf ?>
            </td>
        </tr>
        <tr>
            <td>
                RG do Responsável
            </td>
            <td>
                <?= $alu->_responsRg ?>
            </td>
        </tr>
        <tr>
            <td>
                Grau de parentesco
            </td>
            <td>
                <?php
                if ($alu->_responsCpf == $alu->_maeCpf) {
                    echo 'Mãe';
                } elseif ($alu->_responsCpf == $alu->_paiCpf) {
                    echo 'Pai';
                } else {
                    echo 'Resposável Legal';
                }
                ?>

            </td>
        </tr>
        <tr>
            <td>
                Endereço
            </td>
            <td>
                <?= $alu->_logradouro ?>, <?= $alu->_num ?>
                <br />
                <?php
                if ($alu->_complemento) {
                    echo $alu->_complemento . '<br />';
                }
                ?>
                <?= $alu->_bairro ?>, <?= $alu->_cidade ?> - <?= $alu->_uf ?>  
            </td>
        </tr>
        <tr>
            <td>
                Telefone
            </td>
            <td>
                <?= $foneAluno ?> 
            </td>
        </tr>
        <tr>
            <td>
                E-mail
            </td>
            <td>
                <?= $mailAluno ?>
            </td>
        </tr>
    </table>
    <?php
}
?>
<br /><br /><br />
<table style="width: 100%">
    <tr>
        <td style="width: 35%">&nbsp;</td>
        <td style="width: 5%">&nbsp;</td>
        <td>
            Baruei, <?= data::porExtenso(date("Y-m-d")) ?>
        </td>
    </tr>
    <tr>
        <td>
            Nome:
            <hr>
            Função/cargo:
            <hr>
            Matrícula:
            <hr>
        </td>
        <td style="width: 5%">&nbsp;</td>
        <td>
            Responsável:
            <hr>
        </td>
    </tr>
</table>
<?php
$pdf->exec();