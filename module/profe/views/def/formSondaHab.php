<?php
if (!defined('ABSPATH'))
    exit;
$data = filter_input(INPUT_POST, 'data');
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$at_set = filter_input(INPUT_POST, 'at_set', FILTER_SANITIZE_NUMBER_INT);
$id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$at_sonda = filter_input(INPUT_POST, 'at_sonda', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
$id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);

$hidden = $_POST;
$alunos = ng_escola::alunoPorTurma($id_turma);
css::switchButton();
$habAlu = hab::sondagemAluno($id_curso, date("Y"), $id_disc, $id_turma, $at_set);
if (empty($habAlu)) {
    $habAlu = [];
}
?>
<div class="body">
    <?php
    foreach ($alunos as $v) {
        ?>
        <div class="border" role="alert" style="font-size:17px; margin-bottom:30px">
            <table style="width: 100%">
                <tr>
                    <td style="width: 80px">
                        <?php if (file_exists(ABSPATH . '/pub/fotos/' . $v['id_pessoa'] . '.jpg')) { ?>
                            <img style="width: 100%" src="<?= HOME_URI . '/pub/fotos/' . $v['id_pessoa'] . '.jpg' ?>">
                        <?php } else { ?>
                            <img style="width: 100%" src="<?= HOME_URI . '/includes/images/anonimo.jpg' ?>">
                        <?php } ?>  
                    </td>
                    <td style="padding: 15px">
                        <p style="font-size:22px">
                            <?= "Nº " . $v['chamada'] . ' - ' . toolErp::abreviaLogradouro($v['n_pessoa']) ?> (RSE:<?= $v['id_pessoa'] ?>)
                        </p>  
                    </td>
                    <td style="text-align: right; padding: 5px; width: 10px">

                        <?php
                        if (!empty($habAlu[$v['id_pessoa']])) {
                            if (in_array($id_hab, $habAlu[$v['id_pessoa']])) {
                                $setado = 1;
                            } else {
                                $setado = null;
                            }
                        } else {
                            $setado = null;
                        }


                        if ($at_set == $at_sonda) {
                            echo '<input onchange="setar(' . $v['id_pessoa'] . ', this, ' . $id_ce . ')" ' . ($setado ? 'checked' : null) . ' id="switch-shadow-' . $v['id_pessoa'] . '" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-' . $v['id_pessoa'] . '"></label>';
                            echo '<table style="margin-top: 8px"><tr><td><div id="' . $v['id_pessoa'] . '" style="border-radius: 50%; width: 10px; height: 10px; background-color: #dddddd"></div></td></tr></table>';
                        } else {
                            echo formErp::checkbox(null, null, null, (in_array($v['id_pessoa'], $habAlu) ? 'checked' : null), ' readonly disabled ');
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
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
                + '&id_hab=' + <?= $id_hab ?>
        + '&id_ce=' + id_ce
                + '&id_disc=<?= $id_disc ?>'
                + '&id_curso=<?= $id_curso ?>'
                + '&id_ciclo=<?= $id_ciclo ?>'
                + '&id_pl=<?= $id_pl ?>'
                + '&id_pessoa=' + id
                + '&id_gh=<?= $id_gh ?>'
                + '&id_turma=<?= $id_turma ?>'
                + '&data=<?= $data ?>'
                + '&id_inst=<?= $id_inst ?>'
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