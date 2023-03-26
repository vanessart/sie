<?php
if (!defined('ABSPATH'))
    exit;
$ce = sql::idNome('coord_campo_experiencia');

$hidden['codigo'] = $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_NUMBER_INT);
$hidden['id_cur'] = $id_cur = filter_input(INPUT_POST, 'id_cur', FILTER_SANITIZE_NUMBER_INT);
$hidden['id_disc'] = $id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$hidden['id_ciclo'] = $id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$hidden['nHab'] = $nHab = filter_input(INPUT_POST, 'nHab', FILTER_SANITIZE_STRING);
$hidden['atual_letiva'] = $atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING);
$hidden['id_gh'] = $id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_STRING);
$id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_cur)) {
    $curso = sqlErp::get('ge_cursos', '*', ['id_curso' => $id_cur], 'fetch');

    if (empty($curso['qt_letiva']) || $curso['qt_letiva'] == 1) {
        $letiva = [];
    } else {
        foreach (range(1, $curso['qt_letiva']) as $v) {
            $letiva[$v] = $v . 'º';
        }
    }
    $grupSelect = coordena::grupoHabCurso($id_cur);

    $grupoHab = coordena::setGrupCurso();
    if (empty($id_gh)) {
        $id_gh = $hidden['id_gh'] = @$grupoHab[$id_cur];
    }
}
if (toolErp::id_nilvel() == 24) {
    if ($profTp == 1) {
        $cursos = [
            1 => 'Ensino Fundamental',
            5 => 'EJA 1º Segmento',
            9 => 'EJA 2º Segmento'
        ];
    } elseif ($profTp == 2) {
        $cursos = [
            7 => 'Berçário',
            8 => 'Maternal',
            3 => 'Pré-Escola'
        ];
    } elseif ($profTp == 12 || $profTp == 21) {
        $cursos = [
            1 => 'Ensino Fundamental',
            5 => 'EJA 1º Segmento',
            9 => 'EJA 2º Segmento',
            7 => 'Berçário',
            8 => 'Maternal',
            3 => 'Pré-Escola'
        ];
    } else {
        $cursos = [
            1 => 'Ensino Fundamental',
            5 => 'EJA 1º Segmento',
            9 => 'EJA 2º Segmento',
            7 => 'Berçário',
            8 => 'Maternal',
            3 => 'Pré-Escola'
        ];
    }
} else {
    $cursos = [
        1 => 'Ensino Fundamental',
        5 => 'EJA 1º Segmento',
        9 => 'EJA 2º Segmento',
        7 => 'Berçário',
        8 => 'Maternal',
        3 => 'Pré-Escola'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Habilidades
    </div>
    <br />
    <div class="row">
        <div class="col-5">
            <?php echo formErp::select('id_cur', $cursos, 'Curso', $id_cur, 1); ?>
        </div>
        <div class="col-5">
            <?php
            if (!empty($id_cur)) {
                echo formErp::hidden(['id_gh' => $id_gh]);
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($id_cur)) {
        ?>
        <div class="border">
            <form method="POST">
                <div class="row">
                    <div class="col">
                        <?php echo formErp::select('id_disc', ng_main::disciplinasCurso($id_cur), 'Disciplina', $id_disc); ?>
                    </div>
                    <div class="col">
                        <?php echo formErp::select('id_ciclo', ng_main::ciclos($id_cur), 'Ciclo', $id_ciclo); ?>
                    </div>
                    <div class="col">
                        <?= formErp::select('atual_letiva', $letiva, $curso['un_letiva'], $atual_letiva) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-4">
                        <?php echo formErp::input('codigo', 'Código', $codigo) ?>
                    </div>
                    <div class="col-6">
                        <?php echo formErp::input('nHab', 'Habilidade', $nHab) ?>
                    </div>
                    <div class="col-2">
                        <input type="hidden" name="id_cur" value="<?php echo $id_cur ?>" />
                        <input type="hidden" name="id_gh" value="<?php echo $id_gh ?>" />
                        <button type="submit" class="btn btn-info">
                            Buscar
                        </button>
                    </div>
                </div>
                <br />
            </form>
        </div>
        <br />
        <div style="text-align: right; padding: 20px">
            <form target="_blank" action="<?= HOME_URI ?>/habili/pdf/habil.php" method="POST">
                <?= formErp::hidden($hidden) ?>
                <button class="btn btn-info">
                    Gerar PDF
                </button>
            </form>
        </div>
        <br />
        <?php
        $where['h.fk_id_cur'] = $id_cur;
        $where['fk_id_gh'] = $id_gh;
        $where['atual_letiva'] = $atual_letiva;
        if (!empty($id_ciclo)) {
            $where['id_ciclo'] = $id_ciclo;
        }
        if (!empty($id_disc)) {
            $where['id_disc'] = $id_disc;
        }
        if (!empty($codigo)) {
            $where['codigo'] = $codigo;
        }
        if (!empty($nHab)) {
            $where['n_hab'] = '%' . $nHab . '%';
        }
        $conta = coordena::habGrupo($where, ' count(id_hab) as ct', [0, 1])[0]['ct'];
        $pag = report::pagination(100, $conta, $hidden);
        ?>
        <br /><br />
        <?php
        $hab = coordena::habGrupo($where, NULL, [$pag, 100]);

        $habCiclo = coordena::habCiclo(array_column($hab, 'id_hab'));
        foreach ($hab as $kh => $h) {
            unset($hc);
            if (!empty($habCiclo[$h['id_hab']])) {
                foreach ($habCiclo[$h['id_hab']] as $ky => $y) {
                    $hc[] = $ky . (!empty($y) ? (' (' . $y . ' bimestre)') : null);
                }
                @$hab[$kh]['letivas'] = implode('<br /><br />', $hc);
            }
            if (in_array($id_cur, [3, 7, 8])) {
                @$hab[$kh]['n_ce'] = @$ce[$h['fk_id_ce']];
            }
            $hab[$kh]['acessar'] = '<button type="button" onclick="cad(' . $h['id_hab'] . ')" class="btn btn-info">Visualizar</button>';
        }
        if ($hab) {
            $token = formErp::token('coord_hab', 'delete');
            $form['array'] = $hab;
            $form['fields'] = [
                'Código' => 'codigo',
                'Habilidade' => 'descricao',
                '<span style="white-space: nowrap;">Ciclo (Unid. Letiva)</span>' => 'letivas',
                'Disciplina' => 'n_disc',
            ];
            if (in_array($id_cur, [3, 7, 8])) {
                $form['fields']['Campo de Experiência'] = 'n_ce';
                $form['fields']['Mês Inicial'] = 'inicio';
                $form['fields']['Mês Final'] = 'fim';
            }
            $form['fields']['||1'] = 'acessar';
        }
        if (!empty($form)) {
            report::simple($form);
        }
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/habili/def/formHabVer.php" target="frame" id="form" method="POST">
    <?= formErp::hidden($hidden) ?>
    <input type="hidden" name="id_hab" id="id_hab" value="" />
</form>
<div class="modal fade" id="cadModal" tabindex="-1" aria-labelledby="cadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadModalLabel">Habilidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
            </div>
        </div>
    </div>
</div>
<script>
    function cad(id) {
        if (id) {
            document.getElementById("id_hab").value = id;
        } else {
            document.getElementById("id_hab").value = "";
        }
        document.getElementById('form').submit();
        var myModal = new bootstrap.Modal(document.getElementById('cadModal'), {
            keyboard: true
        });

        myModal.show();
    }

</script>