<?php
ob_start();
$escola = new escola();
$wciclo = [19, 20, 21, 22, 23, 24];
$idturma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
?>
<head>
    <style>
        .titulocab{
            font-weight:bolder;
            font-size: 9px;
            background-color: #000000;
            color:#ffffff;
            border-width: 1px;
            text-align: center;
            padding-left: 3px;
            padding-right: 3px;
            border: solid;
            border-style: border;
            height: 4px
        }
        .subtitulocab{
            height: 4px;
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 3px;
            padding-right: 3px;
            color: red;
        }
        .topo{
            height: 4px;
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 3px;
            padding-right: 3px;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<?php
set_time_limit(120);
foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $a[] = $v;
    }
}
$a = implode(',', $a);

$sql = "SELECT * FROM `ge_aluno_responsavel` WHERE `fk_id_pessoa_aluno` IN ($a) and fk_id_rt in (1, 2) ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    $trab[$v['fk_id_pessoa_aluno']][$v['fk_id_rt']] = [
        'trabalho' => $v['trabalho'],
        'trabalho_endereco' => $v['trabalho_endereco']
    ];
}

$fields = "DISTINCT pessoa.id_pessoa , pessoa.n_pessoa , dt_nasc "
        . ", sexo , ra , ra_dig,rg, rg_dig"
        . ", rg_oe , rg_uf , certidao , novacert_cartorio, novacert_acervo "
        . ", novacert_regcivil, novacert_ano, novacert_tipolivro, novacert_numlivro "
        . ", novacert_folha, novacert_termo, novacert_controle "
        . ", pai, mae , nacionalidade , cidade_nasc "
        . ", cor_pele , tel1 , uf_nasc, pessoa.email, pessoa.cpf, pessoa.cpf_respons "
        . ", cep, logradouro_gdae, num_gdae, logradouro, num, complemento, bairro, cidade, uf "
        . ", trabalho_pai, end_trab_pai, trabalho_mae, end_trab_mae, fk_id_ciclo, nis ";

$dados = "Select $fields from pessoa"
        . " Join ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa And ge_turma_aluno.situacao = 'Frequente'"
        . " LEFT Join ge_turmas ON ge_turmas.id_turma = ge_turma_aluno.fk_id_turma"
        . " Left join ge_periodo_letivo ON ge_periodo_letivo.id_pl = ge_turmas.fk_id_pl And at_pl IN(1,2)"
        . " Left Join endereco on endereco.fk_id_pessoa = pessoa.id_pessoa"
        . " Where pessoa.id_pessoa IN ($a) "
        . " AND ge_turma_aluno.fk_id_turma = " . $idturma
        . " Order by n_pessoa ";

$query = $model->db->query($dados);
$alunos = $query->fetchALL();

if (empty($alunos)) {
    echo ("O cadastro do aluno está incompleto");
}

foreach ($alunos as $v) {
    $sql = "SELECT t.ddd, t.num, p.emailgoogle as email, p.cpf FROM `ge_aluno_responsavel` r "
            . "join pessoa p on p.id_pessoa = r.fk_id_pessoa_resp"
            . " join telefones t on t.fk_id_pessoa = p.id_pessoa"
            . "  WHERE r.`fk_id_pessoa_aluno` = " . $v['id_pessoa'] . " AND r.`responsavel` = 1 ";
    $query = pdoSis::getInstance()->query($sql);
    $resp = $query->fetch(PDO::FETCH_ASSOC);

    if (!empty($v['novacert_cartorio'])) {
        $certidao = $v['novacert_acervo'] . '-' . $v['novacert_ano'] . '-' . $v['novacert_cartorio'] . '-' . $v['novacert_controle'] . '-' . $v['novacert_folha'] . '-' . $v['novacert_numlivro'] . '-' . $v['novacert_regcivil'] . '-' . $v['novacert_termo'] . '-' . $v['novacert_tipolivro'];
    } else {
        $certidao = $v['certidao'];
    }
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>
    <div class="titulocab" >FICHA CADASTRAL DO ALUNO</div>
    <div class="subtitulocab">Identificação do Aluno</div>

    <div style="border: 1px solid">
        <div style="text-align: center"> <b><?php echo addslashes($v['n_pessoa']) ?></b></div>
        <table>
            <tr>
                <td style="width:70px; height: 12px" class="topo" >RSE</td>
                <td style="width:90px; height: 12px" class="topo" >RA</td>
                <td style="width:90px; height: 12px" class="topo" >NIS</td>
                <td style="width:130px; height: 12px" class="topo" >RG</td>
                <td style="width:75px; height: 12px" class="topo" > Data Nasc</td>
                <td style="width:60px; height: 12px" class="topo" >Sexo</td>
                <td style="width:75px; height: 12px" class="topo" >Raça/Cor</td>
                <td rowspan="4">
                    <?php
                    if (file_exists(ABSPATH . "/pub/fotos/" . $v['id_pessoa'] . ".jpg")) {
                        ?>
                        <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $v['id_pessoa'] ?>.jpg" width="89" height="89" alt="foto"/>
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
                <td style="width:70px; height: 12px" class="topo" >
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td style="width:90px; height: 12px" class="topo" >
                    <?php echo $v['ra'] . '-' . $v['ra_dig'] ?>
                </td>
                <td style="width:90px; height: 12px" class="topo" >
                    <?php echo $v['nis'] ?>
                </td>
                <td style="width:130px; height: 12px" class="topo" >
                    <?php echo $v['rg'] . '-' . $v['rg_dig'] . '-' . $v['rg_uf'] ?>
                </td>
                <td style="width:75px; height: 12px" class="topo" >
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td style="width:60px; height: 12px" class="topo" >
                    <?php echo $v['sexo'] ?>
                </td>
                <td style="width:75px; height: 12px" class="topo" >
                    <?php
                    echo (empty($v['cor_pele'])) ? 'Não Informado' : $v['cor_pele'];
                    ?>
            </tr>
            <tr>
                <td colspan="3" style="height: 15px" class="topo" > Certidão Nasc.</td>
                <td colspan="2" style="height: 15px;" class="topo" >Naturalidade</td>
                <td colspan="1" style="height: 15px;" class="topo" >Estado</td>
                <td colspan="1" style="height: 15px;" class="topo" >Nacionalidade</td>
            </tr>
            <tr>
                <td colspan="3" style="height: 15px; font-size: 8pt" class="topo" >
                    <?php echo $certidao ?>
                </td>
                <td colspan="2" style="height: 15px;" class="topo" >
                    <?php echo $v['cidade_nasc'] ?>
                </td>
                <td colspan="1" style="height: 15px;" class="topo" >
                    <?php echo $v['uf_nasc'] ?>
                </td>
                <td colspan="1" style="height: 15px;" class="topo" >
                    <?php echo $v['nacionalidade'] ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="subtitulocab">Filiação</div>
    <table>
        <tr>
            <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
                Pai: <?php echo $v['pai'] ?>
            </td>
            <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
                Mãe: <?php echo $v['mae'] ?>
            </td>
        </tr>
        <?php ?>
        <?php
        if (in_array($v['fk_id_ciclo'], $wciclo)) {
            $nLinha = 5;
            ?>
            <tr>
                <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
                    Trabalho Pai: <?= @$trab[$v['id_pessoa']][2]['trabalho'] ?>
                </td>
                <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
                    Trabalho Mãe: <?= @$trab[$v['id_pessoa']][1]['trabalho'] ?>
                </td>
            </tr>
            <tr>
                <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
                    Endereço: <?= @$trab[$v['id_pessoa']][2]['trabalho_endereco'] ?>
                </td>
                <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
                    Endereço: <?= @$trab[$v['id_pessoa']][1]['trabalho_endereco'] ?>
                </td>
            </tr>
            <?php
        } else {
            $nLinha = 10;
        }
        ?>
    </table>
    <div class="subtitulocab">Endereço</div>
    <table>
        <tr>
            <td style="width:679px; height: 15px; text-align: left" class="topo" >
                <?php
                if (!empty($v['logradouro_gdae'])) {
                    $end = $v['logradouro_gdae'];
                } else {
                    $end = $v['logradouro'];
                }
                if (!empty($v['num_gdae'])) {
                    echo $end . ' nº ' . $v['num_gdae'] . ' ' . $v['complemento'] . ' ' . $v['bairro'] . ' ' . $v['cidade'] . '-' . $v['uf'];
                } else {
                    echo $end . ' nº ' . $v['num'] . ' ' . $v['complemento'] . ' ' . $v['bairro'] . ' ' . $v['cidade'] . '-' . $v['uf'];
                }
                ?>
            </td>
        </tr>
    </table>
    <div class="subtitulocab">Outras Informações</div>
    <table>
        <thead>
            <tr>
                <th style="width:100px; height: 15px" class="topo" >Telefone</th>
                <th style="width:479px; height: 15px" class="topo" >Email</th>
                <th style="width:200px; height: 15px" class="topo" >CPF Responsável</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:100px; height: 15px" class="topo" >
                    <?= @$resp['ddd'] ?>-<?= @$resp['num'] ?>
                </td>
                <td style="width:479px; height: 15px" class="topo" >
                    <?= @$resp['email'] ?>
                </td>
                <td style="width:200px; height: 15px" class="topo" >
                    <?= @$resp['cpf'] ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="subtitulocab">Procedência do Aluno</div>

    <table>
        <thead>
            <tr>
                <th style="width:450px; height: 12px" class="topo" >Escola ou Equivalente</th>
                <th style="width:229px; height: 12px" class="topo" >Cidade-Estado ou País</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:450px; height: 12px" class="topo">
                    <?php echo ' ' ?>
                </td>
                <td style="width:229px; height: 12px" class="topo">
                    <?php echo ' ' ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th style="width:250px; height: 12px" class="topo" >Ensino</th>
                <th style="width:100px; height: 12px" class="topo" >Série/Ano</th>
                <th style="width:329px; height: 12px" class="topo" >Curso/Habilidade</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:250px; height: 12px" class="topo" >
                    <?php echo ' ' ?>
                </td>
                <td style="width:100px; height: 12px" class="topo" >
                    <?php echo ' ' ?>
                </td>
                <td style="width:329px; height: 12px" class="topo" >
                    <?php echo ' ' ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="subtitulocab">Matrícula e Renovação de Matrícula</div>

    <div class="topo" >

        <div style="text-align: justify" >
            Solicito Matrícula na _______ Série/Ano do Ensino ____________________________ Curso/Habilitação _____________________________
        </div>
        <div style="text-align: left" >
            Declaro acatar as normas regimentais desse estabelecimento de ensino.
        </div>
        <div style="text-align: justify" >
            Assinatura _________________________________________________
        </div>
        <div style="text-align: right; padding-right: 20px">
            <?= CLI_CIDADE ?>, ____ de _______________________ 20____.
        </div>
    </div>



    <div class="subtitulocab">Para uso da Escola</div>
    <table>
        <tr>
            <td style="width:254px; height: 12px" class="topo" >Movimentação do Aluno</td>
            <td style="width:275px; height: 25px" class="topo" >Responsável</td>
            <td style="width:50px; height: 25px" class="topo" >Idade</td>
            <td style="width:100px; height: 25px" class="topo">Diretor</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width:62.5px; height: 12px" class="topo" >Ano</td>
            <td style="width:53.5px; height: 12px" class="topo">Período</td>
            <td style="width:56px; height: 12px" class="topo" >Série/Ano</td>
            <td style="width:52px; height: 12px" class="topo" >Turma</td>
            <td style="width:30px; height: 12px" class="topo" >Ch</td>
            <td style="width:275.5px; height: 12px" class="topo" >/////////////////////////////////////////////////////////</td>
            <td style="width:50px; height: 12px" class="topo" >/////////////</td>
            <td style="width:50px; height: 12px" class="topo" >Desp.</td>
            <td style="width:50px; height: 12px" class="topo" >Visto</td>
        </tr>
        <?php
        for ($x = 1; $x < $nLinha; $x++) {
            ?>
            <tr>
                <td style="width:62.5px; height: 12px" class="topo" ></td>
                <td style="width:53.5px; height: 12px" class="topo"></td>
                <td style="width:56px; height: 12px" class="topo" ></td>
                <td style="width:52px; height: 12px" class="topo" ></td>
                <td style="width:30px; height: 12px" class="topo" ></td>
                <td style="width:275.5px; height: 12px" class="topo" ></td>
                <td style="width:50px; height: 12px" class="topo" ></td>
                <td style="width:50px; height: 12px" class="topo" ></td>
                <td style="width:50px; height: 12px" class="topo" ></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <div class="subtitulocab">Transferência</div>
    <div class="topo">Solicito Transferência de estudos para outro estabelecimento de ensino</div>
    <table>
        <tr>
            <td style="width:279px; height: 12px" class="topo" >Responsável</td>
            <td style="width:400px; height: 12px" class="topo" >Diretor</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <td style="width:279px; height: 12px" class="topo" >Assinatura</td>
                <td style="width:200px; height: 12px" class="topo" >Despacho</td>
                <td style="width:200px; height: 12px" class="topo" >Assinatura</td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="width:279px; height: 12px" class="topo" ></td>
                <td style="width:200px; height: 12px" class="topo" ></td>
                <th style="width:200px; height: 12px" class="topo" ></th>
            </tr>
        </tbody>
    </table>

    <div class="subtitulocab">Autorização</div>
    <div class="topo">
        <div style="text-align: justify; font-size: 8pt" >
            Eu, ________________________________________________________, R.G. ___________________ responsável legal <?php echo $s = ($v['sexo'] == "M") ? 'pelo aluno' : 'pela aluna' ?> <b><?php echo $v['n_pessoa'] ?><?php ?></b>,
            autorizo o uso de materiais produzidos <?php echo $s = ($v['sexo'] == "M") ? 'pelo meu filho' : 'pela minha filha' ?>, bem como a divulgação de sua imagem pela Secretaria de Educação, sem nenhum ônus lucrativo, para todos os fins educacionais.
        </div >
        <div style="text-align: right; padding-right: 20px"><?= CLI_CIDADE ?>, ____ de _______________________ 20____.</div>

        <div style="text-align: right; padding-right: 20px" >
            <br />
            <span style= "text-decoration: overline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura do Responsável&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>

    </div>
    <div class="subtitulocab">Declaração</div>

    <div class="topo">
        <div style="text-align: justify; font-size: 8pt" >
            Eu, _________________________________________________________, R.G. ________________________ responsável legal <?php echo $s = ($v['sexo'] == "M") ? 'pelo aluno acima identificado.' : 'pela aluna acima identificada.' ?>
            <br />
            Declaro, sob as penas da lei, que resido no município de <?= CLI_CIDADE ?>, conforme documentação
            anexa, e que <?php echo $s = ($v['sexo'] == "M") ? 'o aluno' : 'a aluna' ?>
            estuda na Rede Municipal de Ensino de <?= CLI_CIDADE ?>.
            <br />
            Declaro-me ciente, ainda, de que qualquer tipo de falsidade constante na declaração,
            inclusive, nos documentos residenciais, poderá acarretar na adoção de medidas
            pertinentes a rever a matrícula
            <?php echo $s = ($v['sexo'] == "M") ? 'do aluno' : 'da aluna' ?>,
            bem como na comunicação da conduta criminal ao Ministério Público para apuração
            e aplicação das sanções penais pertinentes, sem prejuízo da adoção das medidas
            nas esferas administrativa e cível.
        </div >
        <div style="text-align: right; padding-right: 20px"><?= CLI_CIDADE ?>, ____ de _______________________ 20____.</div>

        <div style="text-align: right; padding-right: 20px" >
            <br />
            <span style= "text-decoration: overline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura do Responsável&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>

    </div>
    <?php
}
tool::pdfSemRodape();
?>
