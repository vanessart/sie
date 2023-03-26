<?php
if (!defined('ABSPATH'))
    exit;
$totais = $model->dashGet(toolErp::id_inst());
// $faltas = $model->dashGetFaltas(toolErp::id_inst());
?>
<div class="body">
    <?php dashBoard::divInicio() ?>
        <div class="row">
            <div class="col">
        	   <?php dashBoard::divDash_totais('Total de Inclusos<br>Nesta Semana',$totais['i'], 'success') ?>
            </div>
            <div class="col">
        	   <?php dashBoard::divDash_totais('Total de Encerrados<br>Nesta Semana',$totais['e'], 'danger') ?>
            </div>
            <div class="col">
        	   <?php dashBoard::divDash_totais('Total de Aguardando Deferimentos<br>Nesta Semana',$totais['a'], 'warning') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <!--div class="col-8">  
                <?php // dashBoard::divDash_chart('Faltas por Mês','myAreaChart') ?>
            </div-->
            <div class="col-12">
                <?php dashBoard::divDash_pie('Atendimentos','myPieChart') ?>
                <div class="mt-4 text-center " style="font-weight: bold">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success">Inclusos</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger">Encerrados</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning">Aguardando Deferimentos</i> 
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php dashBoard::divFim() ?>
</div>
<?php
$coresMouse = " '#17a673', '#e74a3b', '#f6c23e' ";
$cores = " '#1cc88a', '#dd7369', '#f9d578' ";
$legendas = '"Inclusos", "Encerrados", "Aguardando Deferimentos"';
$dados = '\'' . $totais['i'] . '\',\'' . $totais['e'] . '\',\'' . $totais['a'] . '\'';
$id = 'myPieChart';
?>
<script>
    <?php dashBoard::scriptInicio() ?>
    
    // Pie Chart
    <?php dashBoard::script_pie($coresMouse,$cores,$legendas,$dados,$id) ?>
   
    // Area Chart
    <?php // dashBoard::script_chart($faltas['meses'],$faltas['faltas'], 'myAreaChart','Faltas') ?>
</script>

