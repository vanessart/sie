<br />
<?php

if (!empty($_POST['id_turma'])) {
    $dados = $model->geraclassehab($_POST['id_turma']);  
} else {
    echo "Classe não definida";
}
?>
                                <pre>
                                    <?php
                                    print_r($dados)
                                    ?>
                                </pre>
                                <?php
?>