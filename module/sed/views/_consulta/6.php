<?php

$ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_STRING);
$uf = filter_input(INPUT_POST, 'uf', FILTER_SANITIZE_STRING);
if($ra && $uf){
    $result = rest::listarMatriculasRA($ra, $uf);
    ##################            
?>
  <pre>   
    <?php
      print_r($result);
    ?>
  </pre>
<?php
###################
}
?>
<form method="POST">

    <div class="row">
        <div class="col">
            <?= formErp::input('ra', 'RA', $ra)?>
        </div>
        <div class="col">
            <?= formErp::input('uf', 'UF', $uf)?>
        </div>
        <div class="col">
        
            <?=
             formErp::hidden([
                'activeNav' => 6
            ]) .
                formErp::button('Buscar');
            ?>
        </div>
    </div>
    </form>
    <?php if(!empty($result)):?>

        <table class="table table-bordered table-hover table-striped">
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold">
                Dados Pessoais
            </td>
        </tr>
        <?php foreach($result['outAluno'] as $key => $value): ?>

            <tr>
               <td>
                    <b><?= str_replace('out','', $key) ?></b>
               </td> 
                <td>
                    <?= $value ?>
                </td>
            </tr>
            

        <?php endforeach;?>
       
        <?php foreach($result['outListaMatriculas'] as $key ): ?>

        <tr>
            <td colspan="2" style="text-align:center; font-weight: bold;">
                <?= $key['outAnoLetivo']?> - <?= $key['outDescNomeAbrevEscola']?>
            </td>
        </tr>

            <?php foreach($key as $k => $value):?>
                <tr>
                    <td><b><?= str_replace('out','', $k) ?></b></td> 
                        <td><?= $value ?></td>
                </tr>

            <?php endforeach; ?>


        <?php endforeach;?>

        </table>
    <?php endif; ?>
 <?php
    // echo "<pre>";
    // print_r($result);

?> 

