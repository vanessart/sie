<?php
if (!defined('ABSPATH'))
    exit;
$doc = filter_input(INPUT_POST, 'doc', FILTER_SANITIZE_STRING);
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao();
$turmas = gtTurmas::idNome($id_inst, $id_pl);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    @$id_ciclo = sql::get('ge_turmas', 'fk_id_ciclo', ['id_turma' => $id_turma], 'fetch')['fk_id_ciclo'];
    $print = [
        'declaracaopdf' => 'Declaração Escolaridade',
        'listapersonalizadapdf' => 'Lista Piloto Personalizada',
    ];
    if ($id_ciclo == 9) {
        $print['decl_conclusao'] = 'Declaração Conclusão 9º Ano';
    }
     if ($id_ciclo == 30) {
        $print['decl_conclusaoeja'] = 'Declaração Conclusão 4º Termo';
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Convocação/Declaração
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turmas', $id_turma, 1, ["id_pl" => $id_pl]) ?>
        </div>
        <div class="col">
            <?php
            if ($id_turma) {
                echo formErp::select('doc', $print, 'Documento', $doc, 1, ["id_pl" => $id_pl, 'id_turma' => $id_turma]);
            }
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if ($doc) {
        ?>
        <form target="_blank" action="<?php echo HOME_URI ?>/sed/pdf/<?= $doc ?>.php" id="doc" method="POST">
            <div class="row">
                <div class="col-9">
                    <?php
                    if (!empty($id_turma)) {
                        $cod = sql::get(['pessoa', 'ge_turma_aluno'], '*', 'where fk_id_inst =' . tool::id_inst() . " and fk_id_turma ='" . $id_turma . "' And situacao = 'Frequente' order by chamada");
                        foreach ($cod as $k => $v) {
                            $cod[$k]['tur'] = formErp::checkbox('sel[]', $v['id_pessoa'], NULL, NULL, 'id="' . $v['id_pessoa'] . '"');
                        }
                        $form['array'] = $cod;
                        $form['fields'] = [
                            'Chamada' => 'chamada',
                            'RSE' => 'id_pessoa',
                            'Nome Aluno' => 'n_pessoa',
                            'Situação' => 'situacao',
                            formErp::checkbox('chkAll', 1, 'Todos', null, 'onClick="checkAll(this)"') => 'tur'
                        ];
                        report::simple($form);
                    }
                    ?>
                </div>
                <div class="col-3">
                    <?php
                    if ($doc == 'listapersonalizadapdf') {
                        echo formErp::input('titulo_evento', 'Título', null, ' required ', 'Obrigatório').'<br /><br />';
                    }
                    ?>
                    <input type="hidden" name="id_eve" value="" />
                    <button type="button" class="btn btn-info" onclick="check()">
                        Imprimir
                    </button>
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
foreach ($cod as $v) {
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
            document.getElementById('doc').submit();
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