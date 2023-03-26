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
$id_modem = filter_input(INPUT_POST, 'id_modem', FILTER_SANITIZE_NUMBER_INT);
if ($id_modem) {
    $modem = sql::get(['lab_modem', 'lab_modem_modelo'], 'serial, n_mm', ['id_modem' => $id_modem], 'fetch');
}
$serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$prof = $model->funcionarios($id_inst);
$e = new escola($id_inst);
if (array_key_exists($id_pessoa, $prof)) {
    $func = sql::get(['pessoa', 'ge_funcionario'], 'n_pessoa, rm, cpf, emailgoogle, sexo ', ['id_pessoa' => $id_pessoa], 'fetch');
} else {
    $alu = new aluno($id_pessoa);
    $alu->endereco();
}
?>
<div style="text-align: center; font-size: 22px; font-weight: bold">
    Termo de Empréstimo ‐ Guarda e Uso de Dispositivo CHROMEBOOK
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
                Matrícula
            </td>
            <td>
                <?= $func['rm'] ?>
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
        if (!empty($modem)) {
            ?>
            <tr>
                <td>
                    Modem
                </td>
                <td>
                    <?= $modem['serial'] ?> modelo "<?= $modem['n_mm'] ?>"
                </td>
            </tr>
            <?php
        }
        ?>
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
                E-mail
            </td>
            <td>
                <?= $func['emailgoogle'] ?>
            </td>
        </tr>
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
                Número de Série
            </td>
            <td>
                <?= $serial ?>
            </td>
        </tr>
        <?php
        if (!empty($modem)) {
            ?>
            <tr>
                <td>
                    Modem
                </td>
                <td>
                    <?= $modem['serial'] ?> modelo "<?= $modem['n_mm'] ?>"
                </td>
            </tr>
            <?php
        }
        ?>
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
                <?= $alu->_tel1 ?>, <?= $alu->_tel2 ?>, <?= $alu->_tel3 ?> 
            </td>
        </tr>
        <tr>
            <td>
                E-mail
            </td>
            <td>
                <?= $alu->_responsEmail ?>; <?= $alu->_email ?>; <?= $alu->_emailGoogle ?>
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
tool::pdf();
