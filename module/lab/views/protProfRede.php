<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$id_ce = intval(@$_POST['id_ce']);
?>
<style>
    td{
        padding:3px
    }
</style>
<?php
if (!empty($id_ce)) {
    $arr = $model->emppretimo($id_ce, 1);
    if ($arr) {
        if ($arr['dt_inicio'] && $arr['dt_inicio'] !='0000-00-00') {
            $dataFinal = substr($arr['dt_inicio'], 0, 10);
        } else {
            $dataFinal = date("Y-m-d");
        }
        ?>
        <div style="text-align: center">
            <img src="<?= HOME_URI ?>/includes/images/educBarueri.jpg"/>
        </div>
        <br /><br />
        <div style="text-align: center; font-weight: bold; font-size: 16px">
            TERMO DE RESPONSABILIDADE PELA GUARDA E USO DE EQUIPAMENTO
        </div>
        <br /><br />
        <p style="text-align: justify">
            Com base no DECRETO Nº 9.134, DE 28 DE ABRIL DE 2020, declaro que, recebi do Município de Barueri, através da Secretaria Municipal de Educação, o equipamento abaixo citado em perfeitas condições de uso, a título de comodato para uso na Secretaria de Educação e nas dependências das Unidades Escolares e fora dela, em atividades profissionais.
        </p>
        <p style="text-align: justify">
            Comprometendo-me a mantê-lo em perfeito estado de conservação, ficando ciente de que:
        </p>
        <ul>
            <li>
                Em caso de dano, manutenção e inutilização do equipamento devo comparecer ao Departamento Técnico de Tecnologia da Informação Educacional da Secretaria de Educação, com o equipamento, para solicitar uma Ordem de Serviço.
            </li>
            <li>
                Em caso de extravio, devo realizar o Boletim de Ocorrências e encaminhá-lo pessoalmente até o Departamento Técnico de Tecnologia da Informação Educacional da Secretaria de Educação, para registrar o incidente.
            </li>
            <li>
                No caso de desligamento ou afastamentos previstos na legislação vigente, tais como readaptação, licença sem vencimentos, licença para atividade política, além de cessão de servidor e demais casos entendidos pela Secretaria de Educação que não façam jus ao uso do equipamento, o servidor ficará responsável por entregar o Equipamento com o carregador e em perfeito estado de conservação, ao Departamento Técnico de Tecnologia da Informação Educacional da Secretaria de Educação.
            </li>
        </ul>
        <br />
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold">
                    Equipamento
                </td>
            </tr>
            <tr>
                <td>
                    Número de Série
                </td>
                <td>
                    <?= $arr['serial'] ?>
                </td>
            </tr>
            <?php
            if (!empty($arr['mac'])) {
                ?>
                <tr>
                    <td>
                        MAC (mac address)
                    </td>
                    <td>
                        <?= $arr['mac'] ?>
                    </td>
                </tr>

                <?php
            }
            ?>
            <tr>
                <td>
                    Modelo
                </td>
                <td>
                    <?= $arr['n_cm'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Acessório
                </td>
                <td>
                    1 (um)  Carregador Original
                </td>
            </tr>
        </table>
        <br />
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    <?= $arr['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    E-mail
                </td>
                <td>
                    <?= $arr['emailgoogle'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    CPF
                </td>
                <td>
                    <?= $arr['cpf'] ?>
                </td>
            </tr>
        </table>
        <div style="text-align: center; width: 50%; float: right; margin-top: 20px">
            <div style="padding-bottom: 100px; text-align: right">
                Barueri, <?= data::porExtenso($dataFinal) ?>
            </div>
            ___________________________________________________
            <br />
            <?= $arr['n_pessoa'] ?>
        </div>
        <div style="clear: both"></div>
        <?php
    }
    tool::pdf('P', 'c', 'A4', 0, '', 15, 15, 16, 2);
} else {
    ?>
    Não tenho a menor ideia do que você esta fazendo aqui :(
    <?php
}
