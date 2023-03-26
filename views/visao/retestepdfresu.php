<?php
ob_start();
$cor = '#F5F5F5';
?>
<head>
    <style>      
        .topo{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding: 2px;

        }
        .topocab{
            font-size: 8pt;
            color: red;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding: 2px;
        }
    </style>
</head>

<?php
if (!empty($_POST['sel'])) {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $idturmas[] = $v;
        }
    }
} else {
    $idturmas[] = $_POST['rel_turma'];
}

if (!empty($idturmas)) {

    $aln = $model->pegaclasse($idturmas, 'retesteresu');

    if (empty($aln)) {
        echo 'Não existe dados para relatório';
    } else {
        @$idescola = $_POST['rel_escola'];
        $ta = $model->totalalunos($idturmas, @$idescola);
        $peri = ['M' => 'Manhã', 'T' => 'Tarde', 'G' => 'Geral', 'N' => 'Noite'];

        $acr = $model->auxtabela('cv_teste_papel');
        $sir = $model->auxtabela('cv_sinais');

        foreach ($aln as $v) {
            $al[$v['id_turma']][] = $v;
            @$periodo[$v['id_turma']] = $peri[$v['periodo']];
            @$nomeescola[$v['id_turma']] = $v['n_inst'];
            @$serie [$v['id_turma']] = $v['n_turma'];
            @$p [$v['id_turma']] = $v['periodo_letivo'];
            @$contar[$v['id_turma']] ++;

            @$acrd[$v['id_pessoa']] = $acr[$v['reteste_direito']];
            @$acre[$v['id_pessoa']] = $acr[$v['reteste_esquerdo']];
            @$sirr[$v['id_pessoa']] = $sir[$v['reteste_sinais']];

            if ($v['encam_oftalmologista'] == 'Sim') {
                @$conta_oft[$v['id_turma']] ++;
            }

            switch ($v['reteste_sit']) {
                case 'Não Submetido':
                    @$conta_nsr[$v['id_turma']] ++;
                    break;
                case 'PASSA':
                    @$conta_par[$v['id_turma']] ++;
                    break;
                case 'FALHA':
                    @$conta_far[$v['id_turma']] ++;
                    break;
                default :
                    @$conta_totalr[$v['id_turma']] ++;
            }
        }
        $telefone = $model->pegatelescola();

        foreach ($al as $kw => $w) {
            if (!empty($proximaFolha)) {
                ?>
                <div style="page-break-after: always"></div>
                <?php
            } else {
                $proximaFolha = 1;
            }
            ?>
            <div style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
                <table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
                    <tr>
                        <td class="topocab" style="width: 35%">
                            Unidade Escolar
                        </td>
                        <td class="topocab" style="width:15%">
                            Telefone
                        </td>
                        <td class="topocab" style="width: 10%">
                            Série e Turma
                        </td>
                        <td class="topocab" style="width: 10%">
                            Turno
                        </td>
                        <td class="topocab" style="width: 25%; text-align: right">
                            Número de Alunos na Classe:
                        </td>
                        <td class="topo" style="width: 5%">
                            <?php echo intval($ta[$kw]) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="topo" style="width: 35%">
                            <?php echo $nomeescola[$kw] ?>    
                        </td>
                        <td class="topo" style="width: 15%">
                            <?php echo $telefone ?>
                        </td>
                        <td class="topo" style="width: 10%">
                            <?php echo $serie[$kw] . ' - ' . $p[$kw] ?>
                        </td>
                        <td class="topo" style="width: 10%">
                            <?php echo $periodo[$kw] ?>
                        </td>
                        <td class="topocab" style="width: 25%; text-align: right">
                            Total de Alunos Encaminhados para Oftalmologista:
                        </td>
                        <td class="topocab" style="width: 5%">
                            <?php echo intval(@$conta_oft[$kw]) ?>
                        </td> 
                    </tr>
                </table>
            </div>
            <div>
                <table class="table tabs-stacked table-bordered">
                    <thead>
                        <tr>
                            <td rowspan="3" class="topo" style="width: 3%" >
                                Seq.
                            </td>
                            <td rowspan="3" class="topo" style="width: 3%" >
                                CH
                            </td>
                            <td rowspan="3" class="topo" style="width: 23%">
                                Nome Aluno
                            </td>
                            <td rowspan="3" class="topo" style="width: 7%">
                                Data Nasc.
                            </td>
                            <td colspan="4" class="topo" style="width: 18%">
                                Valor do Reteste
                            </td>
                            <td class="topo" style="width: 20%">
                                SINAIS DE PROBLEMAS DE VISÃO OBSERVADOS
                            </td>
                            <td colspan="2" class="topo" style="width: 8%">
                                O ALUNO JÁ FAZ USO DE ÓCULOS OU LENTES?
                            </td>
                            <td colspan="2" class="topo" style="width: 8%">
                                ENCAMINHAMENTO PARA OFTALMOLOGISTA
                            </td>
                            <td colspan="2" class="topo" style="width: 8%">
                                ENCAMINHAMENTO PARA MÉDICO AOS PAIS RESPONSÁVEIS
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="topo">
                                com óculos
                            </td>
                            <td colspan="2" class="topo">
                                sem óculos
                            </td>
                            <td rowspan="2" class="topo">
                                lacrimejamento/ardência/coceira/fadiga visual/franzir a testa/tontura
                            </td>
                            <td rowspan="2" class="topo">
                                SIM
                            </td>
                            <td rowspan="2" class="topo">
                                NÃO
                            </td>
                            <td rowspan="2" class="topo">
                                SIM
                            </td>
                            <td rowspan="2" class="topo">
                                NÃO
                            </td>
                            <td rowspan="2" class="topo">
                                SIM
                            </td>
                            <td rowspan="2" class="topo">
                                NÃO
                            </td>
                        </tr>
                        <tr>
                            <td class="topo">
                                OD
                            </td>
                            <td class="topo">
                                OE
                            </td>
                            <td class="topo">
                                OD
                            </td>
                            <td class="topo">
                                OE
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $seq = 0;
                        foreach ($w as $vv) {
                            ?>
                            <tr>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php
                                    $seq += 1;
                                    echo $seq;
                                    ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo $vv['chamada'] ?>
                                </td>
                                <td style="text-align: left; background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo addslashes($vv['n_pessoa']) ?>
                                </td>
                                <td  style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo data::converteBr($vv['dt_nasc']) ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['reteste_oculos'] == 'Sim') ? $acrd[$vv['id_pessoa']] : '-' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['reteste_oculos'] == 'Sim') ? $acre[$vv['id_pessoa']] : '-' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['reteste_oculos'] == 'Não') ? $acrd[$vv['id_pessoa']] : '-' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['reteste_oculos'] == 'Não') ? $acre[$vv['id_pessoa']] : '-' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo $sirr[$vv['id_pessoa']] ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['usouoculoslentes'] == 'Sim') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['usouoculoslentes'] == 'Não') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['encam_oftalmologista'] == 'Sim') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['encam_oftalmologista'] == 'Não') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['cartao'] == 'Sim') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['cartao'] == 'Não') ? 'X' : ' ' ?>
                                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div>
                <table>
                    <tr>
                        <td style="width: 20%" class="topo">
                            Nº TOTAL DE ALUNOS <br /> QUE NÃO FORAM SUBMETIDOS AO RETESTE DE ACUIDADE VISUAL
                        </td>
                        <td style="width: 20%" class="topo">
                            Nº TOTAL DE ALUNOS <br /> AVALIADOS
                        </td>
                        <td style="width: 30%" rowspan="2" class="topo">
                            <br /><br />
                            _______________________________<br />Responsável pela triagem
                        </td>
                        <td style="width: 30%" class="topo">
                            Data: _______/_________ <?php echo date('Y') ?>.
                        </td>
                    </tr>

                    <tr>
                        <td style="height: 30px" class="topo">
                            <?php echo intval(@$conta_nsr[$kw]) ?>
                        </td>
                        <td style="height: 30px" class="topo">
                            <?php echo intval((@$conta_par[$kw] + @$conta_far[$kw])) ?>
                        </td>
                        <td class="topo">
                            <br />
                            _______________________________<br />Diretor(a)
                        </td>
                    </tr>
                </table>
            </div>

            <?php
        }
    }
} else {
    echo "Favor selecionar a classe!!!!";
}

$model->pdfvisao('L');
?>