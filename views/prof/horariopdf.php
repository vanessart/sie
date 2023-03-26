<?php
ob_start();
$cor = '#F5F5F5';

$id_turma = $_POST['turma'];
$descrcurso = $_POST['curso'];

$turma = sql::get('ge_turmas', 'n_turma, fk_id_ciclo, periodo, codigo, fk_id_grade', ['id_turma' => $id_turma], 'fetch');

$tipoensino = ['EF' => 'Ensino Fundamental', 'EI' => 'Ensino Infantil', 'EJ' => 'Eja', 'EXT' => 'Extracurricular'];
$periodo = ['M' => 'Matutino', 'T' => 'Vespertino', 'G' => 'Integral', 'N' => 'Noturno', 'I' => 'Integral'];
$rel = $model->pegahorariopdf($id_turma);
?>

<head>
    <style type="text/css">
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 2px;
            padding-left: 2px;
            padding-right: 2px;   
        }
        .topo2{
            font-size: 10pt;
            font-weight:bolder;
            text-align: center;
            border-style: double;
            border-width: 2px;
            padding-left: 2px;
            padding-right: 2px;
        }
    </style>
</head>

<div class="conteiner">
    <table style="widht: 100%">
        <thead>
            <tr>
                <td colspan ="2" class="topo" style="background-color: black; color: white; width: 20%">
                    Grade de Horários
                </td>
                <td class="topo" style="background-color: black; color: white; width: 20%">
                    <?php echo $a = $model->pegacursodescricao($descrcurso) ?>         
                </td>
                <td class="topo" style="background-color: black; color: white; width: 20%">
                    <?php echo $periodo[$turma['periodo']] ?>
                </td>
                <td class="topo" style="background-color: black; color: white; width: 20%">
                    <?php echo $turma['n_turma'] ?>
                </td>
                <td class="topo" style="background-color: black; color: white; width: 20%">
                    <?php echo $turma['codigo'] ?>
                </td>
            </tr>
            <tr>
                <td class="topo" style="color: red; width: 10% ">
                    Aula/Semana
                </td>
                <td class="topo" style="color: red; width: 18%">
                    Segunda-Feira
                </td>
                <td class="topo" style="color: red; width: 18%">
                    Terça-Feira
                </td>
                <td class="topo" style="color: red; width: 18%">
                    Quarta-Feira
                </td>
                <td class="topo" style="color: red; width: 18%">
                    Quinta-Feira
                </td>
                <td class="topo" style="color: red; width: 18%">
                    Sexta-Feira
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($x = 1; $x <= 5; $x++) {
                ?>
                <tr>
                    <td class="topo2" style="border-style: ridge; border-width: 3px; width: 10%">
                        <br /><br /><br /><br />
                        <?php echo $x . 'ª Aula' ?>
                        <br /><br />
                    </td>
                    <?php
                    for ($y = 1; $y <= 5; $y++) {
                        ?>
                        <td class="topo2" style="border-style: ridge; border-width: 3px; width: 18%">
                            <br /><br /><br />
                            <?php echo $rel['disciplina'][$y][$x] ?>
                            <br /><br />
                            <?php echo tool::abrevia($rel['professor'][$y][$x], 20) ?>
                            <br />
                        </td>                   
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>

</div>

<?php
tool::pdfescola('L');
?>