<?php


?>
<div class="body">
    <div class="fieldTop">
        Consulta SED
    </div>
    <br>
    <?php
  
    $abas[1] = [ 'nome' => "listarInscricoesEscola", 'ativo' => 1, 'hidden' => [] ];
    $abas[2] = [ 'nome' => "formacaoClasse", 'ativo' => 1, 'hidden' => [] ]; 
    $abas[3] = [ 'nome' => "listarInscricoesEscola", 'ativo' => 1, 'hidden' => [] ]; 

    $abas[4] = [ 'nome' => "listarInscricoesAluno", 'ativo' => 1, 'hidden' => [] ]; 

    $abas[5] = [ 'nome' => "exibirMatriculaClasseRA", 'ativo' => 1, 'hidden' => [] ]; 

    $abas[6] = [ 'nome' => "Matriculas<br />RA", 'ativo' => 1, 'hidden' => [] ]; 
    $abas[7] = [ 'nome' => "Ficha<br />Aluno", 'ativo' => 1, 'hidden' => [] ]; 
    
                  
       $aba = report::abas($abas);
        include ABSPATH . "/module/sed/views/_consulta/$aba.php";
             
    ?>
</div>
