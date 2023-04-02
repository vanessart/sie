<?php
if (!defined('ABSPATH'))
    exit;
$data = filter_input(INPUT_POST, 'data');
$idadeMin = filter_input(INPUT_POST, 'idadeMin', FILTER_SANITIZE_NUMBER_INT);
$idadeMax = filter_input(INPUT_POST, 'idadeMax', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_UNSAFE_RAW);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_UNSAFE_RAW);
$id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$at_set = filter_input(INPUT_POST, 'at_set', FILTER_SANITIZE_NUMBER_INT);
$id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$at_sonda = filter_input(INPUT_POST, 'at_sonda', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_UNSAFE_RAW);
$hidden = $_POST;
if ($id_ce) {
    $ce = " and fk_id_ce = $id_ce";
} else {
    $ce = null;
}
$habAlu = hab::sondagem($id_curso, date("Y"), $id_pessoa, $id_disc, $at_set);
if (empty($habAlu)) {
    $habAlu = [];
}
$sql = "SELECT `id_hab`, `descricao`, `codigo`, fk_id_ce, inicio, fim FROM coord_hab h "
        . " JOIN coord_hab_ciclo ci on ci.fk_id_hab = h.id_hab and ci.fk_id_ciclo = $id_ciclo "
        . " AND h.fk_id_gh = $id_gh AND h.fk_id_disc = $id_disc AND at_hab = 1 "
        . " $ce ";
$query = pdoSis::getInstance()->query($sql);
$hab = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($hab as $k => $v) {
    if (($idadeMin && $v['inicio'] && $idadeMin > $v['inicio']) || ($idadeMin && $v['fim'] && $idadeMin > $v['fim'])) {
        $excl = 1;
    } else {
        $excl = null;
    }
    if (empty($excl)) {
        if ($idadeMax && $v['fim'] && $idadeMax < $v['fim']) {
            $excl = 1;
        } else {
            $excl = null;
        }
    }
    if ($excl) {
        unset($hab[$k]);
    } else {
        if ($v['inicio'] && $v['fim']) {
            $mesesHab = ' (de ' . $v['inicio'] . ' a ' . $v['fim'] . ' meses)';
        } elseif ($v['inicio']) {
            $mesesHab = ' (A partir dos ' . $v['inicio'] . ' meses)';
        } elseif ($v['fim']) {
            $mesesHab = ' (Até os ' . $v['fim'] . ' meses)';
        } else {
            $mesesHab = null;
        }
        $hab[$k]['descricao'] = $v['codigo'] . ' - ' . $v['descricao'] . $mesesHab;
        if ($at_set == $at_sonda) {
            $hab[$k]['set'] = '<input onchange="setar(' . $v['id_hab'] . ', this, ' . $v['fk_id_ce'] . ')" ' . (in_array($v['id_hab'], $habAlu) ? 'checked' : null) . ' id="switch-shadow-' . $v['id_hab'] . '" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-' . $v['id_hab'] . '"></label>';
            $hab[$k]['set'] .= '<table style="margin-top: 8px"><tr><td><div id="' . $v['id_hab'] . '" style="border-radius: 50%; width: 10px; height: 10px; background-color: #dddddd"></div></td></tr></table>';
        } else {
            $hab[$k]['set'] = formErp::checkbox(null, null, null, (in_array($v['id_hab'], $habAlu) ? 'checked' : null), ' readonly disabled ');
        }
    }
}
$form['array'] = $hab;
$form['fields'] = [
    'Habilidades' => 'descricao',
    '||1' => 'set'
];
css::switchButton();
?>



<div class="body">
    <div class="row">
        <div class="col">
            <?php
            if (in_array($id_curso, [3, 7, 8])) {
                echo formErp::selectDB('coord_campo_experiencia', 'id_ce', 'Campo Experiência', $id_ce, 1, $hidden, null, ['fk_id_gh' => $id_gh]);
            }
            ?>
        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<script>
    function setar(id, t, id_ce) {
        btn = document.getElementById(id);
        if (t.checked) {
            sit = 1;
        } else {
            sit = 0;
        }
        dados = 'sondagem=' + sit
                + '&id_hab=' + id
                + '&id_ce=' + id_ce
                + '&id_disc=<?= $id_disc ?>'
                + '&id_curso=<?= $id_curso ?>'
                + '&id_ciclo=<?= $id_ciclo ?>'
                + '&id_pl=<?= $id_pl ?>'
                + '&id_pessoa=<?= $id_pessoa ?>'
                + '&id_gh=<?= $id_gh ?>'
                + '&id_turma=<?= $id_turma ?>'
                + '&id_inst=<?= $id_inst ?>'
                + '&data=<?= $data ?>'
                + '&at_sonda=<?= $at_set ?>';
        fetch('<?= HOME_URI ?>/profe/cadSondagem', {
            method: "POST",
            body: dados,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
                .then(resp => resp.text())
                .then(resp => {
                    if (resp != 1) {
                        alert('Houve um erro na conexão. Por Favor, tente mais tarde')
                        btn.style.backgroundColor = '#dddddd';
                        if (sit == 1) {
                            t.checked = false;
                        } else {
                            t.checked = true;
                        }


                    } else {
                        btn.style.backgroundColor = 'blue';
                    }
                });

    }
</script>