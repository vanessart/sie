<?php
if (!defined('ABSPATH'))
    exit;
$sql = "SELECT p.adapta_curriculo, p.recursos FROM coord_plano_aula p "
        . " JOIN coord_plano_aula_turmas t on t.fk_id_plano = p.id_plano "
        . " WHERE t.fk_id_turma = $id_turma "
        . " AND p.dt_inicio <= '$data' "
        . " AND p.dt_fim >= '$data' "
        . " AND p.iddisc like '$id_disc'";
$query = pdoSis::getInstance()->query($sql);
$a_c = $query->fetch(PDO::FETCH_ASSOC);
if ($a_c) {
    $adapta_curriculo = $a_c['adapta_curriculo'];
    $recursos = $a_c['recursos'];
} else {
    $adapta_curriculo = null;
    $recursos = null;
}

$habilidades = $model->retornaHabilidades($id_ciclo, $id_disc, $dataFiltro['atual_letiva'], $id_turma, $data);

$opt = [
    'p' => 'Plano de Aula',
    'b' => $dataFiltro['un_letiva'],
    'a' => 'Ano Inteiro',
];
foreach ($opt as $k => $v) {
    $diplay[$k] = 'none';
    $btn[$k] = 'btn btn-outline-warning';
}

if (!empty($habilidades['p'])) {
    $btn['p'] = 'btn btn-warning';
    $diplay['p'] = 'block';
} else if (!empty($habilidades['b'])) {
    $btn['b'] = 'btn btn-warning';
    $diplay['b'] = 'block';
} else {
    $btn['a'] = 'btn btn-warning';
    $diplay['a'] = 'block';
} //FIM 
$filter = [
    'id_turma' => $id_turma,
    'id_disc' => $id_disc,
    'data' => $data
];
$mongo = new mongoCrude('Diario');
$aulaObj = $mongo->query('Aula.' . $id_pl, $filter);
if (!empty($aulaObj)) {
    $aula = (array) $aulaObj[0];
}
?>
<script>
<?php
foreach ($opt as $k => $v) {
    ?>
        $(document).ready(function () {
            $("#myInput<?= $k ?>").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable<?= $k ?> tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    <?php
}
?>
</script>
<div class="border">
    <div class="row">
        <?php
        if (empty($erro)) {

            foreach ($opt as $k => $v) {
                ?>
                <div class="col">
                    <label style="white-space: nowrap;" onclick="habilidades('<?= $k ?>')">
                        <button id="btn_<?= $k ?>" class="<?= $btn[$k] ?> rounded-button_min border"></button>
                        <?= $v ?>
                    </label>
                </div>
                <?php
            }
            ?>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?php
                foreach ($opt as $k => $v) {
                    ?>
                    <div style="display: <?= $diplay[$k] ?>;" id="<?= $k ?>">
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton<?= $k ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Habilidades - <?= $v ?>
                            </button>
                            <?php
                            if (!empty($habilidades[$k])) {
                                ?>
                                <div style="height: 480px;" class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $k ?>">
                                    <div style="padding: 10px">
                                        <input class="form-control" id="myInput<?= $k ?>" type="text" placeholder="Pesquisa.." >
                                    </div>
                                    <div style="height: 400px; overflow: auto">
                                        <table id="myTable<?= $k ?>">
                                            <?php
                                            foreach ($habilidades[$k] as $kh => $h) {
                                                ?>
                                                <tr>
                                                    <td style="padding: 3px">
                                                        <div onclick="habilidadeSet(<?= $kh ?>, 'i')" class="alert alert-dark" style="width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify">
                                                            <?= $h ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $k ?>">
                                    <div class="alert alert-dark" style="width: 98%; margin: auto;">
                                        Sem Cadastro de Habilidades
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
            <div class="col">
                <?php
                if (!empty($aula)) {
                    ?>
                    <div class="alert alert-success" style="width: 90%; padding: 3px; text-align: center">
                        Está Salvo
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-warning" style="width: 90%; padding: 3px; text-align: center">
                        Não Está Salvo
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div style="margin: 10px auto" id="habilSet"></div>
        <br /><br />
        <form method="POST">
            <?= formErp::textArea('1[descritivo]', (empty($aula['descritivo']) ? $recursos : $aula['descritivo']), 'Descritivo da aula', 200) ?>
            <br />
            <?php //echo formErp::textArea('1[apd]', (empty($aula['apd']) ? $adapta_curriculo : $aula['apd']), 'Adaptação curricular' . (empty($aula['apd']) ? '<br>Não Salvo' : '')) //campo oculto com javascript ?>
            <br />
            <?= formErp::textArea('1[ocorrencia]', @$aula['ocorrencia'], 'Ocorrências') ?>
            <div class="row">
                <div class="col text-center" style="padding: 20px">
                    <?=
                    formErp::hidden($hidden)
                    . formErp::hidden([
                        '1[id_turma]' => $id_turma,
                        '1[id_disc]' => $id_disc,
                        '1[id_curso]' => $id_curso,
                        '1[id_ciclo]' => $id_ciclo,
                        '1[id_inst]' => toolErp::id_inst(),
                        '1[data]' => $data,
                        '1[id_pessoa]' => toolErp::id_pessoa(),
                        '1[timeStamp]' => date("Y-m-d H:i:s"),
                        'hlo' => 2,
                        'data' => $data,
                    ])
                    . formErp::hiddenToken('salvaDiario')
                    ?>
                    <button type="submit" class="btn btn-success" id="btnEnviar">Enviar</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            habilidadeSet(0, 'u');
        });
        function habilidades(id) {
            document.getElementById('btn_p').classList.remove('btn-warning');
            document.getElementById('btn_b').classList.remove('btn-warning');
            document.getElementById('btn_a').classList.remove('btn-warning');
            document.getElementById('btn_p').classList.add('btn-outline-warning');
            document.getElementById('btn_b').classList.add('btn-outline-warning');
            document.getElementById('btn_a').classList.add('btn-outline-warning');
            document.getElementById('btn_' + id).classList.remove('btn-outline-warning');
            document.getElementById('btn_' + id).classList.add('btn-warning');
            document.getElementById('p').style.display = 'none';
            document.getElementById('b').style.display = 'none';
            document.getElementById('a').style.display = 'none';
            document.getElementById(id).style.display = 'block';
        }
        function habilidadeSet(id, op) {
            const dados = 'id_hab=' + id + '&op=' + op + '&data=<?= $data ?>&id_turma=<?= $id_turma ?>&id_disc=<?= $id_disc ?>&id_pl=<?= $id_pl ?>&id_pessoa=<?= toolErp::id_pessoa() ?>';
            fetch(
                    '<?= HOME_URI ?>/profe/pdf/habilSet.php'
                    , {
                        method: "POST",
                        body: dados,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
            )
                    .then(resp => resp.text())
                    .then(resp => {
                        document.getElementById('habilSet').innerHTML = resp;
                    });
        }
        function habDel(id) {
            if (confirm('Excluir esta habilidade desta aula?')) {
                const dados = 'id_hab=' + id + '&op=d&data=<?= $data ?>&id_turma=<?= $id_turma ?>&id_disc=<?= $id_disc ?>&id_pl=<?= $id_pl ?>&id_pessoa=<?= toolErp::id_pessoa() ?>$timeStamp=<?= date("Y-m-d H:i:s") ?>';
                fetch(
                        '<?= HOME_URI ?>/profe/pdf/habilSet.php'
                        , {
                            method: "POST",
                            body: dados,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        }
                )
                        .then(resp => resp.text())
                        .then(resp => {
                            document.getElementById('habilSet').innerHTML = resp;
                        });
            }
        }
    </script>
    <?php
} else {
    ?>
    <div class="alert alert-danger" style="margin-top: 20px; margin: auto">
        <?= $erro ?>
    </div>
    <?php
}