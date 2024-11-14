<?php
if (!defined('ABSPATH'))
    exit;
if (!in_array(tool::id_pessoa(), [1, 6])) {
    echo 'Área de Desenvolvimeto';
    exit();
}

foreach (range(1, 5) as $idPolo){
    
}

$sql="INSERT INTO `" . $model::$sistema . "_turma` (`id_turma`, `n_turma`, `periodo`, `fk_id_pl`, `horario`, `fk_id_curso`, `fk_id_polo`) VALUES (NULL, '1', '1', '1', '1', '1', '1');";
?>
<div class="body">
    <div class="fieldTop">

    </div>
</div>