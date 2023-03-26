<?php

ob_start();
$esc = new escola();
echo $esc->cabecalho();
?>
<br /><br />
                                <?php
   if (!empty(@$_POST['id_turma'])) {
        $aluno = turmas::classe(@$_POST['id_turma']);
            include ABSPATH . '/views/gestao/carometro_tab.php';
    }

tool::pdf();
