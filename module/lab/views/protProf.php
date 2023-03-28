<?php
ob_start();
?>
<style>
    td{
        padding:3px
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_STRING);
if (!empty($id_ch)) {
    $sql = "SELECT c.id_ch, c.serial, m.n_cm, p.n_pessoa, p.sexo, p.cpf, f.rm, c.dt_cad, c.recad_ip, p.emailgoogle, c.mac "
            . " FROM `lab_chrome` c "
            . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
            . " LEFT JOIN pessoa p on (p.emailgoogle like c.email_google or p.id_pessoa = c.fk_id_pessoa) "
            . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
            . "WHERE id_ch = " . $id_ch;
    try {
        $query = pdoSis::getInstance()->query($sql);
    } catch (Exception $exc) {
        ?>
        Algo errado não está certo
        <?php
        exit();
    }

    $arr = $query->fetch(PDO::FETCH_ASSOC);
    if ($arr) {
        ?>
        <br /><br />
        <div style="text-align: center; font-weight: bold; font-size: 16px">
            TERMO DE RESPONSABILIDADE PELA GUARDA E USO DE CHROMEBOOK
        </div>
        <br /><br />
        <p style="text-align: justify">
            Com Base no DECRETO Nº 9.134, DE 28 DE ABRIL DE 2020, declaro que, recebi do Município de <?= CLI_CIDADE ?>, através da Secretaria Municipal de Educação, o equipamento abaixo citado em perfeitas condições de uso, a título de comodato para uso na Secretaria de Educação e nas dependências das Unidades Escolares e fora dela, em atividades profissionais em ambientes externos;
        </p>
        <p style="text-align: justify">
            Comprometendo-me a mantê-lo em perfeito estado de conservação, ficando ciente de que:
        </p>
        <ul>
            <li>
                Em caso de dano, manutenção, inutilização ou extravio do equipamento deverei requerer uma Ordem de Serviço através do SIEB, na seção InfoEducação e posteriormente entregar o equipamento no Departamento Técnico de Tecnologia da Informação Educacional da Secretaria de Educação.
            </li>
            <li>
                No caso dos afastamentos previstos na legislação vigente, tais como readaptação, licença sem vencimentos, licença para atividade política, além de cessão de servidor e demais casos entendidos pela Secretaria de Educação que não façam jus ao uso do equipamento, o servidor ficará responsável por entregar o chromebook com o carregador e em perfeito estado de conservação, ao Departamento Técnico de Tecnologia da Informação Educacional da Secretaria de Educação.
            </li>
        </ul>
        <br /><br />
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold">
                    Chromebook
                </td>
            </tr>
            <tr>
                <td>
                    número de Série
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
                    Acessorio
                </td>
                <td>
                    1 (um)  Carregador Original
                </td>
            </tr>
        </table>
        <br /><br />
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold">
                    Documento Assinado digitalmente por:
                </td>
            </tr>
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
                    Matrícula
                </td>
                <td>
                    <?= $arr['rm'] ?>
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
            <tr>
                <td>
                    data e horário
                </td>
                <td>
                    <?= data::porExtenso(substr($arr['dt_cad'], 0, 10)) ?> as <?= substr($arr['dt_cad'], 11, 5) ?> horas
                </td>
            </tr>
            <tr>
                <td>
                    IP (Internet Protocol)
                </td>
                <td>
                    <?= $arr['recad_ip'] ?>
                </td>
            </tr>
        </table>
        <?php
    }
    tool::pdf('P', 'c', 'A4', 0, '', 15, 15, 16, 2);
} else {
    ?>
    Não tenho a menor ideia do que você esta fazendo aqui
    <?php
}
