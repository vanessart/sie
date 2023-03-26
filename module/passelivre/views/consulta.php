
<?php
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_NUMBER_INT);
$criterio = filter_input(INPUT_POST, 'criterio', FILTER_SANITIZE_STRING);
$criterio1 = filter_input(INPUT_POST, 'criterio1', FILTER_SANITIZE_STRING);
$id_passelivre = filter_input(INPUT_POST, 'id_passelivre', FILTER_SANITIZE_NUMBER_INT);
$id_pl_status = filter_input(INPUT_POST, 'id_pl_status', FILTER_SANITIZE_NUMBER_INT);
$cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_NUMBER_INT);
$tipocad = filter_input(INPUT_POST, 'id_tipo_cadastro', FILTER_SANITIZE_STRING);
#### Carrossel de Escolas ########################
$tt= ['1' => 'NOVA', '2' => 'RENOVAÇÃO'];





$escolasGeral = $model->escolasGeral();
$st = $model->pegastatus();

if (empty($id_passelivre)) {
    $id_passelivre = $model->db->last_id;
}
if ($id_passelivre) {
    $req = sql::get('pl_passelivre', '*', ['nome' => $nome], 'fetch');
}
if (tool::id_nivel() == '8'){
    $cie = tool::cie();
}
if (tool::id_nivel() == '10') {
        if ($cie) {
        $model->cie = $cie;
        $model->escola = $escolasGeral[$cie];
        $pgaluno = $model->filtroaluno($cie);
    }
    
    
}
    if (tool::id_nivel() == '8') {
 $pgaluno = $model->filtroaluno($model->cie);
    }
 
    
    
    
?>
<style>
    .topo{
        font-size: 12pt;
        font-weight:bold;
        text-align: center;
        border-style: solid;
        border-width: 0.5px;
        padding-left: 10px;
        padding-right: 50px;
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>



<div class="body" style="width:100%">   
    <div>
        <?php
        if (tool::id_nivel() == '10') {
            formErp::select('cie', $escolasGeral, 'Escola', $cie, 1);
        }
        ?>
        
    </div>
      
    
    
    <div>
        
        
            <div class="row"> 
                <div class="col-3"> 
                                      
                    <?= formErp::select('id_passelivre', @$pgaluno, 'Selecione o Aluno', @$id_passelivre, 1, ["cie" => $model->cie]) ?>
                </div>
                <div class="col-3" style="text-align: right">
                    <?= formErp::select('id_pl_status', $st, 'Status', @$id_pl_status, 1, ["cie" => $cie]) ?>
                                                                                                                       
                    <?php
                   $criterio = "WHERE cie = $cie";
                    if ((!empty($id_passelivre))) {
                       $criterio = "WHERE id_passelivre = $id_passelivre AND cie = $cie";
                    } else if (!empty($id_pl_status)) {
                        $criterio = "WHERE fk_id_pl_status =  $id_pl_status AND cie = $cie";
                    }
                    ?>
                    

                </div>
                <!-- cod -->
                     
                
            
        <div class="col-3">
            
                    <?php
                    
                    if ((empty($id_passelivre)) & (empty($id_pl_status))) {
                      ?>
                        <input class="btn btn-default" style="width: 30%" type="button" value="Pesquisar"/>
                      <?php  
                    }else{
                        
                    
                    
                    ?>
            
        <form method="POST">
                
                    <input type="hidden" name="cie" value="<?= $cie ?>"/>
                    <input type="hidden" name="inf" value="1"/>
                    <input type="hidden" name="criterio1" value="<?= $criterio ?>" /> 
                    <input class="btn btn-info" style="width: 30%" type="submit" name="criter" value="Pesquisar"/>
        
        
        </form>
            
            <?php
                    }
            ?>
            
        </div>
            </div>
        
        
        <?php
        if (!empty($_POST['inf'])){
        $zz = $model->buscapasselivre($criterio1);
               
        
        
        if (!empty($zz)) {
            ?>
            <div>
                <table style="width:100%">
                    <thead>

                        <tr>
                            <td class="topo">
                                Nome
                            </td>
                            <td class="topo">
                                RA
                            </td>
                            <td class="topo">
                                CPF
                            </td>
                            <td class="topo">
                                Lote
                            </td>
                             <td class="topo">
                                Data de Inicio
                            </td>
                             <td class="topo">
                                Requerimento
                            </td>
                            <td class="topo">
                                Tipo de Cadastro
                            </td>
                        </tr>
                    </thead>
                    <?php
                    foreach ($zz as $z) {
                        ?>
                        <div>       
                            <tbody>
                                <tr>
                                    <td class="topo">
                                        <?= $z['nome'] ?>
                                    </td>
                                    <td class="topo">
                                        <?= $z['ra'] ?>
                                    </td>
                                    <td class="topo">
                                        <?= $z['cpf'] ?>
                                    </td>
                                    <td class="topo">
                                        <?= $z['lote'] ?>
                                    </td>
                                    <td class="topo">
                                        <?= data::converteBr ($z['dt_inicio_passe']) ?>
                                    </td>
                                    <td class="topo">
                                        <?= $st[$z['fk_id_pl_status']] ?>
                                    </td>
                                    <td class="topo">
                                        <?= @$tt[@$z['fk_tipo_cadastro']] ?>
                                    </td>
                                </tr>
                            </tbody>
                            <?php
                            }
                            ?>
                    </table>
                </div> 
            </div>
        
        <?php
          
}else{
    
    
    
    echo "<script>alert('Não há dados para este Status !!!');</script>;";
    
}
?>
 </div>  
<?php
        }
        ?>
