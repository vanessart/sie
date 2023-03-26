<?php

$dadosAluno = $model->pegadadosaluno($_POST['id_pessoa'], $_POST['fk_id_turma']);
$dados = $model->pegaalunohab($_POST['id_pessoa'], $_POST['fk_id_turma']);


?>
                                <pre>
                                    <?php
                                    print_r($dados)
                                    ?>
                                </pre>
                                <?php
