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
    $aln = $model->pegaclasse($idturmas, 'testeresu');
   
    if (empty($aln)) {
        echo 'Não existe dados para relatório';
    } else {
        $peri = ['M' => 'Manhã', 'T' => 'Tarde', 'G' => 'Geral', 'N' => 'Noite'];

        $ac = $model->auxtabela('cv_teste_computador');
        $si = $model->auxtabela('cv_sinais');

        foreach ($aln as $v) {
            $al[$v['id_turma']][] = $v;
            @$periodo[$v['id_turma']] = $peri[$v['periodo']];
            @$nomeescola[$v['id_turma']] = $v['n_inst'];
            @$serie [$v['id_turma']] = $v['n_turma']; 
            @$p [$v['id_turma']] = $v['periodo_letivo'];
            @$conta[$v['id_turma']] ++;

            $acd[$v['id_pessoa']] = $ac[$v['olho_direito']];
            @$ace[$v['id_pessoa']] = $ac[$v['olho_esquerdo']];
            @$sir[$v['id_pessoa']] = $si[$v['fk_id_sinais']];

            switch ($v['situacao_teste']) {
                case 'Não Submetido':
                    @$conta_ns[$v['id_turma']] ++;
                    break;
                case 'PASSA':
                    @$conta_pa[$v['id_turma']] ++;
                    break;
                case 'FALHA':
                    @$conta_fa[$v['id_turma']] ++;
                    break;
                default :
                    @$conta_total[$v['id_turma']] ++;
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
                            <?php echo intval($conta[$kw]) ?>
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
                            Total de Alunos Encaminhados para o RETESTE:
                        </td>
                        <td class="topocab" style="width: 5%">
                            <?php echo intval(@$conta_fa[$kw]) ?>
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
                            <td rowspan="3" class="topo" style="width: 27%">
                                Nome Aluno
                            </td>
                            <td rowspan="3" class="topo" style="width: 7%">
                                Data Nasc.
                            </td>
                            <td colspan="4" class="topo" style="width: 20%">
                                Valor do Teste
                            </td>
                            <td class="topo" style="width: 20%">
                                SINAIS DE PROBLEMAS DE VISÃO OBSERVADOS
                            </td>
                            <td colspan="2" class="topo" style="width: 10%">
                                O ALUNO JÁ FAZ USO DE ÓCULOS OU LENTES?
                            </td>
                            <td colspan="2" class="topo" style="width: 10%">
                                ENCAMINHADO PARA RETESTE
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
                                    <?php echo ($vv['oculos'] == 'Sim') ? $acd[$vv['id_pessoa']] : '-' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['oculos'] == 'Sim') ? $ace[$vv['id_pessoa']] : '-' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['oculos'] == 'Não') ? $acd[$vv['id_pessoa']] : '-' ?> 
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['oculos'] == 'Não') ? $ace[$vv['id_pessoa']] : '-' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo $sir[$vv['id_pessoa']] ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['usouoculoslentes'] == 'Sim') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['usouoculoslentes'] == 'Não') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['reteste'] == 'Sim') ? 'X' : ' ' ?>
                                </td>
                                <td style="background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo ($vv['reteste'] == 'Não') ? 'X' : ' ' ?>
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
                            Nº TOTAL DE ALUNOS <br /> QUE NÃO FORAM SUBMETIDOS AO TESTE DE ACUIDADE VISUAL
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
                            <?php echo intval(@$conta_ns[$kw]) ?>
                        </td>
                        <td style="height: 30px" class="topo">
                            <?php echo intval((@$conta_pa[$kw] + @$conta_fa[$kw])) ?>
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