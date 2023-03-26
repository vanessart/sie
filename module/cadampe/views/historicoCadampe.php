<?php
if (!defined('ABSPATH'))
    exit;
?>
<style type="text/css">
    .titulo{
        font-weight: bold;
        text-align: center;
        font-size: 24px;
        padding: 10px;
    }
</style>
<div class="body">
    <?php
    if (!empty($formhist2['array'])) {?>
        <div class="row titulo">  
            <div class="col"> 
                Categoria <?= $n_categoria ?>
            </div>
        </div>
        <br>
        <?php
        report::simple($formhist2);
    }
    ?>
     <?php
    if (!empty($formhist['array'])) {?>
        <div class="row titulo"> 
            <div class="col">  
                Todas as Categorias
            </div>
        </div>
        <br>
         <?php
        report::simple($formhist);
    }
    ?>
</div>

