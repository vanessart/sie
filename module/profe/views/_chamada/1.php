<?php
if (!defined('ABSPATH'))
    exit;
$chamada = $model->dadosChamada($id_pl, $id_turma, $id_disc, $data);

if (empty($erro)) {
    ?>
    <form action="" method="post">
        <?php
    } else {
        ?>
        <div class="alert alert-danger" style="margin-top: 20px">
            <?= $erro ?>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-3 text-center">
            <input type="button" class="rounded-button_min border" style="background: green; cursor: default"> Presente
        </div>
        <!--
        <div class="col text-center">
            <input type="button" class="rounded-button_min border" style="background: yellow; cursor: default"> Remoto
        </div>
        -->
        <div class="col-3 text-center">
            <input type="button" class="rounded-button_min border" style="background: red; cursor: default"> Faltou
        </div>
        <div class="col-6 text-center">
            <?php
            if ($chamada) {
                ?>
                <div class="alert alert-success" style="width: 90%; padding: 3px">
                    Chamada Salva
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-warning" style="width: 90%; padding: 3px">
                    Chamada Não Salva
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    foreach ($alunos as $aluno) {
        $transferido = $aluno['fk_id_tas'];
        ?>
        <div class=" border border-aluno <?= $transferido ? 'alert alert-danger' : null ?>">
            <div class="row" >
                <div class="col-9">
                    <div class="row">
                        <div class="col-2">
                            <?php
                            if (!$transferido) {
                                ?>
                                <button type="button" onclick="FPRporAluno('<?= $aluno['id_pessoa'] ?>', '<?= count($aulasPorDia) ?>')" id="<?= $aluno['id_pessoa'] ?>" class="num_chamada  border"><?= $aluno['chamada'] ?></button>
                                <?php
                            } else {
                                ?>
                                <button class="border num_chamada" type="button" ><?= $aluno['chamada'] ?></button>
                                <?php
                            }
                            ?>        
                        </div>
                        <div class="col-10">
                            <label class="nome_aluno">
                                <?= toolErp::abrevia($aluno['n_pessoa'], 20) ?>
                                <?php
                                if ($transferido) {
                                    ?>
                                    <br />
                                    <?= $aluno['situacao'] ?>
                                    <?php
                                }
                                ?>
                            </label>
                        </div>
                    </div>
                    <br />
                    <?php
                    if (!$transferido) {
                        foreach (range(1, $aulas) as $i) {
                            if (in_array($i, $aulasPorDia) && !$transferido) {
                                if (empty($chamada[$i][$aluno['id_pessoa']])) {
                                    $background = 'red';
                                    $btnTitle = 'F';
                                } elseif ($chamada[$i][$aluno['id_pessoa']] == 'P') {
                                    $background = 'green';
                                    $btnTitle = 'P';
                                } elseif ($chamada[$i][$aluno['id_pessoa']] == 'R') {
                                    $background = 'yellow';
                                    $btnTitle = 'R';
                                } elseif ($chamada[$i][$aluno['id_pessoa']] == 'F') {
                                    $background = 'red';
                                     $btnTitle = 'F';
                                } else {
                                    $background = 'red';
                                    $btnTitle = 'F';
                                }
                                ?>
                                <input type="button" class="rounded-button button-aula border" id="<?= $aluno['id_pessoa'] . $i ?>"  onclick="FaltaPresencaRemoto('<?= $aluno['id_pessoa'] ?>', '<?= $i ?>')" value="<?= $btnTitle ?>" style="background: <?= $background ?>;color: white">
                                <input name="frequencia[<?= $i ?>][<?= $aluno['id_pessoa'] ?>]" type="hidden" id="<?= $aluno['id_pessoa'] . $i . "hd" ?>" class="hd_sit" value="<?= empty($chamada[$i][$aluno['id_pessoa']]) ? 'F' : $chamada[$i][$aluno['id_pessoa']] ?>">
                                <?php
                            } else {
                                ?>
                                <input type="button" class="rounded-button border" value="&nbsp;" style="background: Gainsboro;">
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div class="col-3 ">
                    <?php
                    if (file_exists(ABSPATH . '/pub/fotos/' . $aluno['id_pessoa'] . '.jpg')) {
                        ?>  

                        <img onclick="foto(<?= $aluno['id_pessoa'] ?>)" style="width:100%; cursor: pointer"src="<?= HOME_URI . '/pub/fotos/' . $aluno['id_pessoa'] . '.jpg' ?>" alt="..." ><br><br>

                        <?php
                    } else {
                        ?>
                        <img onclick="foto()" style="width:100%; height: 120px; cursor: pointer"src="<?= HOME_URI . '/'. INCLUDE_FOLDER .'/images/anonimo.jpg' ?>" alt="..." ><br><br>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    if (empty($erro)) {
        ?>

        <div class="text-center" >
            <?=
            formErp::submit('Enviar')
            . formErp::hiddenToken('salvarFrequencia')
            . formErp::hidden($hidden)
            . formErp::hidden([
                'data' => $data,
                'atual_letiva' => $dataFiltro['atual_letiva'],
                'calendario' => $data
            ])
            ?>
        </div>
    </form>

    <?php
}
toolErp::modalInicio();
?>
<iframe name="frame" style="height: 80vh; width: 100%; border: none; text-align: center"></iframe>
<?php
toolErp::modalFim();
?>

<form method="POST" id="form" target="frame" action="<?= HOME_URI ?>/profe/def/foto.php">
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
</form>

<!-- JAVASCRIPT -->
<script>

    var botao = document.getElementById("presence-button")
    var botaoChamada = document.getElementsByClassName("button-aula")
    var hiddenBoxes = document.getElementsByClassName("hd_sit")
    botao.onclick = () => {
        switch (botao.value) {

            case "P":
                if (confirm("Deseja aplicar presença para todos os alunos?")) {
                    botao.classList.remove('btn-success');
                    botao.classList.add('btn-danger');
                    botao.value = "F";
                    botao.textContent = 'Falta para Todos';
                    for (var i = 0; i < botaoChamada.length; i++) {
                        botaoChamada[i].style.background = "green";
                        botaoChamada[i].value = "P";
                    }
                    for (var i = 0; i < hiddenBoxes.length; i++) {
                        hiddenBoxes[i].value = "P";

                    }
                }

                break;

            case "F":
                if (confirm("Deseja aplicar falta para todos os alunos?")) {
                    botao.classList.remove('btn-danger');
                    botao.classList.add('btn-success');
                    botao.value = "P";
                    botao.textContent = 'Presença para Todos';
                    for (var i = 0; i < botaoChamada.length; i++) {
                        botaoChamada[i].style.background = "red";
                        botaoChamada[i].value = "F";
                    }
                    for (var i = 0; i < hiddenBoxes.length; i++) {
                        hiddenBoxes[i].value = "F";

                    }
                }

                break;

            case "R":
                if (confirm("Deseja aplicar presença remota para todos os alunos?")) {
                    botao.classList.remove('btn-warning');
                    botao.classList.add('btn-success');
                    botao.value = "P";
                    botao.textContent = 'Presença para Todos';
                    for (var i = 0; i < botaoChamada.length; i++) {
                        botaoChamada[i].style.background = "yellow"
                    }
                    for (var i = 0; i < hiddenBoxes.length; i++) {
                        hiddenBoxes[i].value = "R"
                    }
                }
                break;





        }
    }


    function FaltaPresencaRemoto(id, contagem) {
        if (document.getElementById(id + contagem + "hd").value === 'F') {
            document.getElementById(id + contagem).style.background = "green";
            document.getElementById(id + contagem).value = "P";
            document.getElementById(id + contagem + "hd").value = "P";
        } else if (document.getElementById(id + contagem + "hd").value === 'P') {
            document.getElementById(id + contagem).style.background = "red";
            document.getElementById(id + contagem).value = "F";
            document.getElementById(id + contagem + "hd").value = "F";
        }
    }


    function FPRporAluno(id) {
<?php
foreach ($aulasPorDia as $i) {
    ?>
            var contagem = <?= $i ?>;
            if (document.getElementById(id + contagem + "hd").value === 'F') {
                document.getElementById(id + contagem).style.background = "green";
                document.getElementById(id + contagem).value = "P";
                document.getElementById(id + contagem + "hd").value = "P";
            } else if (document.getElementById(id + contagem + "hd").value === 'P') {
                document.getElementById(id + contagem).style.background = "red";
                document.getElementById(id + contagem).value = "F";
                document.getElementById(id + contagem + "hd").value = "F";
            }

    <?php
}
?>

    }


    function foto(id) {
        if (id) {
            document.getElementById('id_pessoa').value = id;
        } else {
            document.getElementById('id_pessoa').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }





</script>

