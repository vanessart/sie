<?php
ob_start();

if (empty($_POST['as'])) {
    ?>
    <div class="alert alert-danger text-center">
        Selecione ao menos um ALUNO!
    </div>
    <?php
} else {
    $escola = new escola();
    $id_turma = $_POST['id_turma'];
    ?>

    <head>
        <style>
            .topo{
                font-size: 8pt;
                font-weight:bolder;
                text-align: center;
                border-style: solid;
                padding: 2px;
            }
            .topo2{
                font-size: 7pt;
                font-weight:bolder;
                text-align: center;
                border-style: solid;
                padding: 1px;
            }

        </style> 
    </head>

    <?php
    foreach ($_POST['as'] as $v) {
        if (!empty($v)) {
            $id_aluno[] = $v;
        }
    }

    $alunoitb = $model->alunosselecionados($id_aluno);

    foreach ($alunoitb as $v) {
        ?>
        <br /><br /><br /><br /><br />
        <div style="font-weight:bold; font-size:12pt; text-align: center">
            COMPROVANTE DE MÉDIA
        </div>
        <br /><br /><br />
        <div style="text-align:justify">
            Tem este Comprovante a finalidade de comunicar ao aluno abaixo identificado a média por ele alcançado no Sistema de Reserva de Vagas 2022/2023, para Escola Técnica mantidas pela FIEB.      
        </div>
        <br /><br /><br /><br />

        <table class="table tabs-stacked table-bordered">
            <tr>
                <td colspan="6" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="color:red" class="topo">
                    <b>RSE</b>
                </td>
                <td colspan="3" style="color:red" class="topo">
                    <b>Cód. Classe</b>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="topo">
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td colspan="3" class="topo">
                    <?php echo $v['turma_ben'] ?>
                </td>
            </tr>
            <tr >
                <td rowspan="2" style="width: 40%" class="topo">
                    Disciplinas
                </td>
                <td rowspan="2" style="width: 10%" class="topo">
                    6º Ano
                </td>
                <td rowspan="2" style="width: 10%"  class="topo">
                    7º Ano
                </td>  
                <td rowspan="2" style="width: 10%" class="topo">
                    8º Ano
                </td>
                <td colspan="2" style="width: 20%" class="topo2">
                    9º Ano
                </td>
            </tr>
            <tr>
                <td style="width: 10%" class="topo2"> 
                    1º Bimestre
                </td>
                <td style="width: 10%" class="topo2"> 
                    2º Bimestre
                </td>
            </tr>

            <?php
            $al = $model->digitacaonotas($v['id_pessoa']);

            $linha = [9 => 'Língua Portuguesa', 13 => 'História', 14 => 'Geografia', 12 => 'Ciências Naturais', 6 => 'Matemática', 11 => 'Educação Física', 10 => 'Arte', 15 => 'L.E.Inglês'];
            $coluna = ['6', '7', '8', 'b1', 'b2'];
            ?>

            <?php
            foreach ($linha as $kl => $l) {
                ?>      
                <tr>
                    <td class="topo">
                        <?php echo $l ?>
                    </td>
                    <?php
                    foreach ($coluna as $c) {
                        ?>                
                        <td class="topo">
                            <?php echo $al[$l][$c] ?>
                        </td>    
                        <?php
                    }
                    ?>                    
                </tr>
                <?php
            }
            //$res = 'APD';
            $res = '*';
            ?>

            <tr>
                <td style="width: 40%" class="topo">
                    Média Aritmética
                </td>
                <td style="width: 10%" class="topo">
                    <?php echo ($v['media6_ben'] == 0 ? $res: $v['media6_ben']) ?>
                </td>
                <td style="width:10%"  class="topo">
                    <?php echo ($v['media7_ben'] == 0 ? $res: $v['media7_ben']) ?>
                </td>  
                <td style="width: 10%" class="topo">
                    <?php echo ($v['media8_ben'] == 0 ? $res: $v['media8_ben']) ?>
                </td>
                <td colspan="2" style="width: 20%" class="topo"> 
                    <?php echo ($v['media9_ben'] == 0 ? $res: $v['media9_ben']) ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold; font-size:8pt; background-color: #000000; color:#ffffff; text-align: center; width: 10% ">
                    Média Final
                </td>
                <td style="font-weight:bold; font-size:8pt; background-color: #000000; color:#ffffff; text-align: center; width: 10% ">
                    <?php echo $v['media_final_ben'] ?>
                </td>
                <td style="font-weight:bold; font-size:8pt; background-color: #000000; color:#ffffff; text-align: center; width: 10% "> 
                    Status
                </td>
                <td colspan="3" style="font-weight:bold; font-size:8pt; background-color: #000000; color:#ffffff; text-align: center; width: 30% ">
                    <?php echo $v['status_ben'] ?>
                </td>
            </tr>
        </table>

        <div style="text-align: right; font-size: 10pt"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
        <br /><br /><br /><br /><br />
        <div style="text-align: center; font-size: 10pt">_____________________________________</div>
        <div style="text-align:center; font-size: 10pt">Carimbo e Assinatura da Escola</div>
        <br /><br /><br /><br /><br /><br /><br />
        <div style="font-weight:bold; font-size:7pt; text-align: center; border: 1px solid">
            Obs. Este comprovante não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
        </div>
        <div style="page-break-after: always"></div>
        <?php
    }
}
tool::pdfSemRodape();
?>