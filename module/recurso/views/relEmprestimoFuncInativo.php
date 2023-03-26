<?php
if (!defined('ABSPATH'))
    exit;
$relatorio = $model->emprestadoFuncionarioInativo();
?>
<div class="body">
    <br><br>
    <div class="row">
        <div class="col" style="font-weight:bold; font-size:20px; text-align: center;">
            Relatório de Funcionários Inativos com Equipamento emprestado
            <br><br>
            Categoria: <?= $_SESSION['userdata']['n_categoria'] ?>
            <?= $model->info("Para alterar a Categoria, utilize a página 'Início' no menu") ?>
        </div>
    </div>
    <br><br><br><br>
    <?php 
    if (!empty($relatorio)) {
        report::simple($relatorio);
    }else{?>
        <br><br>
         <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
            <div class="row" style="padding-bottom: 15px;">
                <div class="col" style="font-weight: bold; text-align: center;">
                    Não há Empréstimos.
                </div>
            </div>
        </div>
        <?php
    }?>
</div>