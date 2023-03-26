<?php
if (!defined('ABSPATH'))
    exit;
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_NUMBER_INT);
if ($_POST) {
    foreach ($_POST as $k => $v) {
        if (is_numeric($k)) {
            foreach ($v as $ky => $y) {
                if (!empty($y)) {
                    $uniqid = uniqid() . uniqid();
                    $turmaDisc[$k . '_' . $ky] = [
                        'id_turma' => $k,
                        'iddisc' => $ky,
                        'titulo' => $y,
                        'uniqid' => $uniqid
                    ];
                    $ins['fk_id_inst'] = toolErp::id_inst();
                    $ins['fk_id_turma'] = $k;
                    $ins['iddisc'] = $ky;
                    $ins['dt_dt'] = $data;
                    $ins['token'] = $uniqid;
                    $id = $model->db->insert('profe_diario_tmp', $ins, 1);
                    if (empty($id)) {
                        unset($turmaDisc[$k . '_' . $ky]);
                    }
                }
            }
        }
    }
}
?>
<div class="body">
    <?php
    if (!empty($turmaDisc)) {
        ?>
        <div class="fieldTop">
            Links temporários para o dia <?= data::porExtenso($data) ?>
        </div>
        <br /><br />
        <div class="alert alert-info">
            <?php
            $texto = '';
            foreach ($turmaDisc as $v) {
                $link = 'https://portal.educ.net.br/ge/profe/cadam?token=' . $v['uniqid'];
                echo tool::n_inst() . "<br>";
                echo $v['titulo'] . ' para o dia ' . (data::porExtenso($data)) . '<br>';
                echo '<a target="_blank" href="' . $link . '">' . $link . '</a><br><br><br>';
                $texto .= tool::n_inst() . "\n";
                $texto .= $v['titulo'] . ' para o dia ' . (data::porExtenso($data)) . "\n";
                $texto .= $link . "\n\n";
            }
            ?>
        </div>

        <div class="row">
            <div class="col">
                <?= formErp::input(null, null, null, 'id="num"', 'Telefone com DDD (só número) ex: 11999999999') ?>
            </div>
            <div class="col">
                <button onclick="enviar()" class="btn btn-outline-info">
                    Enviar pelo WhatsApp
                </button>
            </div>
            <div class="col">
                <button onclick="copia()" class="btn btn-outline-primary">
                    Copiar Texto
                </button>
            </div>
        </div>
        <br />
    </div>
    <div style="position: relative">

        <textarea style="width: 0px; height: 0px; position: absolute; top: 5px; left: 5px; border: none; overflow: no-display" id="texto"><?= ($texto) ?></textarea>
        <div style="width: 20px; height: 20px; position: absolute; top: 0; left: 0; background-color: white "></div>
    </div>
    <script>
        function enviar() {
            if (num.value) {
                var win = window.open("https://web.whatsapp.com/send?1=pt_BR&phone=55" + num.value + "&text=<?= urlencode($texto) ?>", 'novo');
                win.focus();
            } else {
                alert("Inclua um número")
            }

        }
        function copia() {
            var content = document.getElementById('texto');

            content.select();
            document.execCommand('copy');

            alert("Copiado!");
        }
    </script>
    <?php
} else {
    ?>
    <div class="alert alert-danger">
        Selecione, pelo menos, uma Turma/Disciplina
    </div>

    <?php
}
?>
