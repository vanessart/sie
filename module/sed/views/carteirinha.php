<?php
if (!defined('ABSPATH'))
    exit;

$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$relatorio = filter_input(INPUT_POST, 'relatorio', FILTER_UNSAFE_RAW);

if (empty($ano)) {
    $ano = date("Y");
}
?>

<div class="body">
    <div class="fieldTop">
        Carteira Municipal
    </div>
    <div class="row">
        <div class="col-3">
            <?php
            if ($_SESSION['userdata']['id_nivel'] != 24) {
                echo formErp::selectNum('ano', [date("Y"), (date("Y") + 1)], 'Ano', $ano, 1, ['id_turma' => $id_turma]);
            }
            ?>
        </div>
        <div class="col-3">
            <?php
            $options = turmas::option(null, null, null, $ano);
            echo formErp::dropDownList('id_turma', $options, 'Classe', $id_turma, 1, ['ano' => $ano]);
            ?>
        </div>
        <div class="col-3">
            <?php
            $op = ['pdfcarteirinha.php' => 'Carteirinha', 'pdfcracha.php' => 'Crachá'];
            echo formErp::dropDownList('relatorio', $op, 'Tipo Relatório', $relatorio, 1, ['ano' => $ano, 'id_turma' => $id_turma]);
            ?>
        </div>
        <div class="col-3">
            <button type="button" style = "width: 100%" class="btn btn-info" onclick="check()">
                Visualizar
            </button>
        </div>
    </div>
    <?php
    if ($id_turma) {
        ?>
        <form target="_blank" action="<?php echo HOME_URI ?>/sed/pdf/<?= $relatorio ?>" id="cart" method="POST">
            <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />
            <input type="hidden" name="relatorio" value="<?= $relatorio ?>" />
            <div class="row">
                <div class="col">
                    <?php
                    $turma = sql::get(['pessoa', 'ge_turma_aluno'], '*', 'where fk_id_inst =' . tool::id_inst() . " and fk_id_turma ='" . $id_turma . "' And situacao = 'Frequente' order by chamada");
                    foreach ($turma as $k => $v) {
                        $turma[$k]['tur'] = formErp::checkbox('sel[]', $v['id_pessoa'], NULL, NULL, 'id="' . $v['id_pessoa'] . '"');
                    }
                    $form['array'] = $turma;
                    $form['fields'] = [
                        'Chamada' => 'chamada',
                        'RSE' => 'id_pessoa',
                        'Nome Aluno' => 'n_pessoa',
                        formErp::checkbox('chkAll', 1, 'Todos', null, 'onClick="checkAll(this)"') => 'tur'
                    ];
                    report::simple($form);
                    ?>
                </div>
            </div>
            <br />
        </form>
        <?php
    }
    ?>
</div>

<script>
    
function check() {
        teste = 0;
<?php
foreach ($turma as $v) {
    ?>
     if (document.getElementById("<?php echo @$v['id_pessoa'] ?>").checked) {
         teste = 1;
     }
    <?php
}
    ?>
    if (teste === 0) {
        alert('Favor selecionar pelo menos 1 aluno');
    } else {
        document.getElementById('cart').submit();
    }

    }
    function checkAll(o) {

        var boxes = document.getElementsByTagName("input");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }
</script>

