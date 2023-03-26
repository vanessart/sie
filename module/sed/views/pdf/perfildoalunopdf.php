<?php
ob_start();

$escola = new escola();
$sel = filter_input(INPUT_POST, 'sel', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
?>
<head>
    <style>
        .titulocab{
            font-weight: bolder;
            font-size: 12px;
            background-color: #000000;
            color:#ffffff;
            border-width: 2px;
            text-align: center;
            height: 5px;
            border: 10px solid;

        }

        .topo{
            height: 5px;
            font-size: 10pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 5px;
        }
        .topo2{
            font-size: 10pt;
            text-align: left;
            color: red;
            border: 1px solid;
        }
        table {
            width: 100%;
        }
    </style>
</head>

<?php
if ($sel) {
    $where = " Where ta.fk_id_turma in (" . implode(', ', $sel) . ")";
} elseif ($id_pessoa) {
    $where = " Where ta.fk_id_pessoa = $id_pessoa ";
}
$fields = " DISTINCT p.id_pessoa , p.n_pessoa , p.dt_nasc, "
        . " p.sexo , p.ra , p.rg "
        . ", p.rg_oe , p.rg_uf ,"
       // . " p.certidao, concat(novacert_cartorio, '-', novacert_acervo, '-', "
        //. " novacert_regcivil, '-', novacert_ano, '-', novacert_tipolivro, "
      //  . "'-', novacert_numlivro, '-', novacert_folha,'-', novacert_termo, "
      //  . "'-', novacert_controle) as certidao_nova, "
        . " p.pai "
        . ", p.mae , p.nacionalidade , p.cidade_nasc "
        . ", p.cor_pele , "
        . " GROUP_CONCAT(tel.num) AS tel1 ,"
        . " p.uf_nasc, p.emailgoogle, p.cpf "
        . ", e.`cep`, e.`logradouro_gdae`, e.`num_gdae`, e.`logradouro`, "
        . " e.`num`, e.`complemento`, e.`bairro`, e.`cidade`, e.`uf`";

$dados = "Select $fields from pessoa p"
        . " Join ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
        . " left Join endereco e on e.fk_id_pessoa = p.id_pessoa "
        . " left JOIN telefones tel on tel.fk_id_pessoa = ta.fk_id_pessoa"
        . $where
        . " group by ta.fk_id_pessoa "
        . " Order by n_pessoa ";

$query = $model->db->query($dados);
$alunos = $query->fetchALL();
if (!$alunos) {
    echo 'Alunos Não encontrados';
}
foreach ($alunos as $v) {
    $alu = new ng_aluno($v['id_pessoa']);
    $responsavel = $alu->responsaveis();

    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }

    //   echo $escola->cabecalho();
    ?>
    <div style="width: 100%" class="titulocab">
        <?php echo $t = (@$v['sexo'] != "F") ? 'PERFIL DO ALUNO' : 'PERFIL DA ALUNA' ?>
    </div>
    <div style="text-align: center; font-size: 12px; font-weight: bolder; border: 1px solid; width: 679px" >
        <?php echo @$v['n_pessoa'] ?>
    </div>

    <div style="border: 1px solid">
        <table>
            <tr>
                <td style="width: 118px" class="topo">
                    Data Nasc.
                </td>
                <td style="width: 82px" class="topo">
                    Sexo
                </td>
                <td style="width: 130px" class="topo">
                    RSE
                </td>
                <td style="width: 130px" class="topo">
                    RA
                </td>
                <td style="width: 130px" class="topo">
                    RG
                </td>
                <td rowspan="4" style="width: 89px">
                    <?php
                    if (file_exists(ABSPATH . "/pub/fotos/" . @$v['id_pessoa'] . ".jpg")) {
                        ?>
                        <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo @$v['id_pessoa'] ?>.jpg" width="89" height="89" alt="foto"/>
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="89" height="89" alt="foto"/>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="topo">
                    <?php echo data::converteBr(@$v['dt_nasc']) ?>&nbsp;
                </td>
                <td class="topo">
                    <?php echo @$v['sexo'] ?>
                </td>
                <td class="topo">
                    <?php echo @$v['id_pessoa'] ?>
                </td>
                <td class="topo">
                    <?php echo @$v['ra'] ?>
                </td>
                <td class="topo">
                    <?php echo @$v['rg'] ?>
                </td>
            </tr>
            <tr>
                <td colspan ="5" class="topo2">
                    Endereço
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: left" class="topo">
                    <?php
                    if ($v == 1) {
                        echo '&nbsp;';
                    } else {

                        if (!empty($v['logradouro_gdae'])) {
                            $end = $v['logradouro_gdae'];
                        } else {
                            $end = $v['logradouro'];
                        }
                        echo $end . ' nº ' . $v['num'] . ' ' . $v['complemento'];
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="width: 350px" class="topo">
                    Bairro
                </td>
                <td colspan="2" style="width: 300px" class="topo">
                    Cidade - Estado
                </td>
                <td colspan="2" style="width: 29px" class="topo">
                    CEP
                </td>
            </tr>

            <tr>
                <td colspan="2" style="width: 350px" class="topo">
                    <?php echo @$v['bairro'] ?>
                </td>
                <td colspan="2" style="width: 300px" class="topo">
                    <?php
                    if ($v == 1) {
                        echo '&nbsp;';
                    } else {
                        echo @$v['cidade'] . '-' . @$v['uf'];
                    }
                    ?>
                </td>
                <td colspan="2" style="width: 29px" class="topo">
                    <?php echo @$v['cep'] ?>
                </td>
            </tr>
        </table>
    </div>
    <table>
        <tr>
            <td colspan="5" style="width: 679px" class="topo2">
                Responsável pela retirada <?php echo $t = (@$v['sexo'] != "F") ? 'do aluno' : 'da aluna' ?>
            </td>
        </tr>
        <tr>
            <td style="width: 300px" class="topo">
                Nome
            </td>
            <td style="width: 60px" class="topo">
                Grau Parentesco
            </td>
            <td style="width: 94px" class="topo">
                CPF
            </td>
            <td style="width: 70px" class="topo">
                Telefone
            </td>
            <td style="width: 155px" class="topo">
                Assinatura
            </td>
        </tr>

        <?php
        if (!empty($responsavel)) {
            $countResp = count($responsavel);
            foreach ($responsavel as $v) {
                if ($v['retirada'] == 1) {
                    $tel = current($v['tel']);
                    ?>
                    <tr>
                        <td style="width: 300px; height: 30px; text-align: left" class="topo">
                            <?php echo $v['n_pessoa'] ?>
                        </td>
                        <td style="width: 60px; height: 30px; text-align: left" class="topo">
                            <?php echo $v['n_rt'] ?>
                        </td>
                        <td style="width: 94px; height: 30px; text-align: left" class="topo">
                            <?php echo $v['cpf'] ?>
                        </td>
                        <td style="width: 70px; height: 30px; text-align: left" class="topo">
                            <?php echo '(' . @$tel['ddd'] . ')' . @$tel['num'] ?>
                        </td>
                        <td style="width: 155px; height: 30px" class="topo">
                            &nbsp;
                        </td>
                    </tr>
                    <?php
                }
            }
        } else {
            $countResp = 0;
        }
        foreach (range(1, (10 - $countResp)) as $v) {
            ?>
            <tr>
                <td style="width: 280px; height: 30px; text-align: left" class="topo">
                    &nbsp;
                </td>
                <td style="width: 80px; height: 30px; text-align: left" class="topo">
                    &nbsp;
                </td>
                <td style="width: 84px; height: 30px; text-align: left" class="topo">
                    &nbsp;
                </td>
                <td style="width: 80px; height: 30px; text-align: left" class="topo">
                    &nbsp;
                </td>
                <td style="width: 155px; height: 30px" class="topo">
                    &nbsp;
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <table>
        <tr>
            <td colspan="2" style="width: 679px" class="topo2">
                Transporte Escolar
            </td>
        </tr>
        <tr>
            <td style="widht: 600px" class="topo">
                Nome do Condutor
            </td>
            <td style="width: 69px" class="topo">
                Telefone
            </td>
        </tr>
        <tr>
            <td style="widht: 600px; height:20px" class="topo">
                <?php echo ' ' ?>
            </td>
            <td style="width: 69px; height: 20px" class="topo">
                <?php echo ' ' ?>
            </td>
        </tr>
    </table>
    <div style="border: 1px solid; padding: 5px">
        <table>
            <tr>
                <td style="padding-left: 120px; color: red; font-size:10px; text-align: left">
                    A criança já teve:
                </td>
                <td style="padding-left: 120px; color: red; font-size:10px; text-align: left">
                    Outras informações:
                </td>
            </tr>
            <tr>
                <td style="font-size: 9px; text-align: left; padding-left: 120px">
                    (  ) Catapora
                </td>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Vacinação em dia

                </td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Sarampo
                </td>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Fez alguma cirurgia
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Meningite
                </td>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Já tomou Benzetacil
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Rubeola
                </td>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Já teve convulsão
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Caxumba
                </td>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Enxerga bem
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Hepatite
                </td>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    (  ) Escuta bem
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    ( ) Bronquite
                </td>
                <td style="font-size: 10px; text-align: left; padding-left: 120px">
                    ( ) Fala Direito
                </td>
            </tr>
        </table>
    </div>

    <div style="border: 1px solid; padding: 5px; font-size: 10px; color: red">
        Obs.: (Alergia a Medicação)
        <br /><br /><br />
    </div>
    <div style="border: 1px solid; padding: 5px; font-size: 10px; color: red">
        Obs.: (Restrição Alimentar e Outros)
        <br /><br /><br />
    </div>
    <?php
}

tool::pdfSemRodape();
?>
