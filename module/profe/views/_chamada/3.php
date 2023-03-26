<style>
    .td{
        width: 10%;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
foreach ($instrumentos as $v) {
    if (!empty($v->notas)) {
        $salvo[$v->uniqid] = 1;
    }
}
$alunos_ = $alunos;
unset($alunos);
foreach ($alunos_ as $v) {
    $alunos[$v['id_pessoa']] = $v;
}
?>
<div class="body">
    <?= toolErp::modalInicio() ?>

    <iframe src="<?= HOME_URI ?>/profe/def/instrumentoAvaliativo.php" frameborder="0" name="instrumentosAva" style="height:60vh; width:100%"></iframe>

    <?= toolErp::modalFim() ?>
    <div class="row" style="margin-bottom:20px">
        <?php
        $conta = 1;
        foreach ($instrumentos as $key => $value) {
            if ($value->ativo != 1) {
                $class = 'danger';
            } elseif (!empty($value->notas)) {
                $class = 'success';
            } else {
                $class = 'warning';
            }
            if (!empty($value->notas) && $value->ativo == 1) {
                foreach ($value->notas as $kn => $n) {
                    $media[$kn][] = $n;
                }
                $notas[$value->uniqid] = (array) $value->notas;
            } elseif ($value->ativo != 1) {
                $instrumentos[$key]->instrumentoNome = $instrumentos[$key]->instrumentoNome . ' (Desativado)';
            }
            ?> 
            <div class="col" style="text-align: center">
                <input onclick="botaoAtividade('<?= $instrumentos[$key]->instrumentoNome ?>', '<?= $value->uniqid ?>', '<?= $value->ativo ?>')" type="button" class="btn btn-outline-info rounded-button_min alert alert-<?= $class ?> border" id="<?= $instrumentos[0]->uniqid ?>" value="<?= substr($value->instrumentoNome, 0, 2) ?>" style="padding: 2px; font-weight: bold; width: 50px; height: 50px">
            </div>
            <?php
            if ($conta++ % 5 == 0) {
                ?>
            </div>
            <div class="row">
                <?php
            }
        }
        ?>
    </div>


    <div class="alert alert-primary" id="alertAtividade"style="min-height: 80px">
        <table style="width: 100%; font-size: 1.5em">
            <tr>
                <td id="NomeAtividade" style="text-align: center">

                </td>
                <td style="width: 50px">
                    <form style="display:none;" id="alertNome" onclick="$('#myModal').modal('show');$('.form-class').val('')" action="<?= HOME_URI ?>/profe/def/instrumentoAvaliativo.php" target="instrumentosAva" method="post">
                        <button type="submit" class="btn btn-success rounded-button_min border" style="padding: 2px; font-weight: bold; width: 40px; height: 40px">E</button>
                        <?=
                        formErp::hidden([
                            "id_pl" => $id_pl,
                            "id_ciclo" => $id_ciclo,
                            "id_pessoa" => $id_pessoa,
                            "id_turma" => $id_turma,
                            "id_inst" => $id_inst,
                            'atual_letiva' => $dataFiltro['atual_letiva'],
                            'id_curso' => $id_curso,
                            'id_disc' => $id_disc,
                            "nome_disc" => $nome_disc,
                            "nome_turma" => $nome_turma,
                            "escola" => $escola,
                        ])
                        ?>
                        <input type="hidden" name="uniqid" id="uniqid" value="">
                    </form> 
                </td>
            </tr>
        </table>
    </div>

    <form method="post">
        <?php
        foreach ($alunos as $key => $value) {
            ?>
            <div class="border" role="alert" style="font-size:17px; margin-bottom:30px">

                <table style="width: 100%">
                    <tr>
                        <td style="padding: 5px">
                            <p style="font-size:22px">
                                <?= "Nº " . $value['chamada'] . ' - ' . $value['n_pessoa'] ?>
                            </p>  
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 120px">
                                        <div style="display: none" class="notas">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Nota</span>
                                                <input name="nota[<?= $value['id_pessoa'] ?>]" id="nota<?= $value['id_pessoa'] ?>" type="text" class="form-control" describedby="basic-addon1" oninput="notaRange<?= $value['id_pessoa'] ?>.value=this.value.replace(',', '.')">
                                            </div>  
                                        </div>  
                                    </td>
                                    <td style="text-align: center">
                                        <?php
                                        if (!empty($media[$value['id_pessoa']])) {
                                            $md = round(array_sum($media[$value['id_pessoa']]) / count($media[$value['id_pessoa']]), 1);
                                        } else {
                                            $md = 0;
                                        }
                                        ?>
                                        Média: <?= $md ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 80px">
                            <?php if (file_exists(ABSPATH . '/pub/fotos/' . $value['id_pessoa'] . '.jpg')) { ?>
                                <img style="width: 100%" src="<?= HOME_URI . '/pub/fotos/' . $value['id_pessoa'] . '.jpg' ?>">
                            <?php } else { ?>
                                <img style="width: 100%" src="<?= HOME_URI . '/includes/images/anonimo.jpg' ?>">
                            <?php } ?>  
                        </td>
                    </tr>
                </table>
                <div class="range" style="display: none">
                    <input type="range" value="0" id="notaRange<?= $value['id_pessoa'] ?>" class="form-control-range" style="width: 100%" oninput="nota<?= $value['id_pessoa'] ?>.value=this.value.replace('.', ',')" step="0.5" min="0" max="10">
                    <table style="width: 100%">
                        <tr>
                            <?php
                            $pa = 0;
                            foreach (range(0, 10) as $n) {
                                $pa = $n != 10 ? $pa : 0;
                                ?>
                                <td class="td"><div style="padding-left: <?= $pa ?>px"><?= $n ?></div></td>
                                <?php
                                $pa += 0.8;
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
        <div style="text-align: center; display: none" id="salvar" >
            <input type="hidden" name="uniqid" id="unicoId">
            <?=
            formErp::hidden([
                $key => $alunos[$key]['id_pessoa'],
                'hlo' => $hlo,
                'atual_letiva' => $dataFiltro['atual_letiva'],
                'id_inst' => $id_inst,
                'id_pessoa' => $id_pessoa,
                'data' => $data
            ])
            . formErp::hidden($hidden)
            . formErp::hiddenToken('cadastroInstrumentoNota')
            ?>
            <button class="btn btn-success">
                Salvar
            </button>
        </div>
    </form>

</div>

<script>
    const botao = document.getElementById("c_instrumento")
    botao.onclick = () => {
        $('#myModal').modal('show');
        $('.form-class').val('')
    }

    function botaoAtividade(nome, uniqid, ativo) {
        if (ativo == 1) {
            const verNotas = document.querySelectorAll('.notas');
            document.getElementById('alertAtividade').className = "alert alert-primary"

            verNotas.forEach(e => {
                e.style.display = 'block';
            });
            const range = document.querySelectorAll('.range');
            range.forEach(e => {
                e.style.display = 'block';
            });
            salvar.style.display = 'block';
        } else {
            const verNotas = document.querySelectorAll('.notas');
            document.getElementById('alertAtividade').className = "alert alert-danger"


            verNotas.forEach(e => {
                e.style.display = 'none';
            });
            const range = document.querySelectorAll('.range');
            range.forEach(e => {
                e.style.display = 'none';
            });
            salvar.style.display = 'none';

        }
        const nomeAtividade = document.getElementById('NomeAtividade')
<?php
if (!empty($notas)) {
    ?>
            var h = [];
    <?php
    foreach ($notas as $key => $value) {
        ?>
                h['<?= $key ?>'] = <?= json_encode($value) ?>;
        <?php
    }
    ?>
            if (h[uniqid]) {
                at = h[uniqid];
                for (var k in at) {
                    document.getElementById("notaRange" + k).value = at[k];
                    document.getElementById("nota" + k).value = at[k].toString().replace('.', ',');
                }
            } else {
    <?php
    foreach ($alunos as $v) {
        ?>
                    notaRange<?= $v['id_pessoa'] ?>.value = 0;
                    nota<?= $v['id_pessoa'] ?>.value = 0;

        <?php
    }
    ?>
            }
    <?php
}
?>
        if (nome) {
            nomeAtividade.innerHTML = nome;

        } else {
            nomeAtividade.innerHTML = 'Não há nome atribuído a esta atividade';

        }
        document.getElementById('uniqid').value = uniqid;
        document.getElementById('unicoId').value = uniqid;
        document.getElementById('alertNome').style.display = 'block';

    }
</script>