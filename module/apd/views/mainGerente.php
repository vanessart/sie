<?php
if (!defined('ABSPATH'))
    exit;
$totais = $model->dashGet();
$faltas = $model->dashGetFaltas();
?>
<div class="body">
    <?php dashBoard::divInicio() ?>
        <div class="row">
            <div class="col">
        	   <?php dashBoard::divDash_totais('Total de Atendimentos',$totais['1'], 'success') ?>
            </div>
            <div class="col">
        	   <?php dashBoard::divDash_totais('Total de Faltas',$totais['0'], 'danger') ?>
            </div>
            <div class="col">
        	   <?php dashBoard::divDash_percent('Porcentagem de Faltas',$totais['faltas'], 'warning') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-8">  
                <?php dashBoard::divDash_chart('Faltas por Mês','myAreaChart') ?>
            </div>
            <div class="col-4">
                <?php dashBoard::divDash_pie('Atendimentos','myPieChart') ?>
                <div class="mt-4 text-center " style="font-weight: bold">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success">Presença</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger">Faltas</i> 
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php dashBoard::divFim() ?>
</div>
<?php   
$coresMouse = " '#17a673', '#e74a3b' ";
$cores = " '#1cc88a', '#dd7369' ";
$legendas = '"Presença", "Faltas"';
$dados = '\'' . $totais['1'] . '\',\'' . $totais['0'] . '\'';
$id = 'myPieChart';
?>
<script>
    <?php dashBoard::scriptInicio() ?>
    
    // Pie Chart
    <?php dashBoard::script_pie($coresMouse,$cores,$legendas,$dados,$id) ?>
   
    // Area Chart
    <?php dashBoard::script_chart($faltas['meses'],$faltas['faltas'], 'myAreaChart','Faltas') ?>
</script>

