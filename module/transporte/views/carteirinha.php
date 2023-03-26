<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = toolErp::id_inst();
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
$turma = [];
if (empty($ano)) {
    $ano = date("Y");
}
?>
<br />
<div class="body">
    <div class="fieldTop">
        Carteirinha
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4">
            <?php
            if (!empty($id_inst)) {
                $transLinha = transporteErp::nomeLinha($id_inst);
                if (!empty($transLinha)) {
                    echo formErp::select('id_li', $transLinha, 'Linha', $id_li, 1);
                } else {
                    ?>
                    <div class="alert alert-danger">
                        Não há ônibus alocado nesta Escola
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-md-4">
            <button type="button" style = "width: 100%" class="btn btn-info" onclick="check()">
                Visualizar
            </button>
        </div>
    </div>
    <?php if ($id_li) { ?>
    <div>
        <form target="_blank" action="<?php echo HOME_URI ?>/transporte/pdfcrachatransp" id="cart" method="POST">       
            <?php
            $turma = sqlErp::get(['pessoa', 'transporte_aluno'], '*', 'WHERE fk_id_li =' . $id_li . " AND fk_id_sa = 1 ORDER BY n_pessoa");
            foreach ($turma as $k => $v) {
                $turma[$k]['tur'] = formErp::checkbox('sel[]', $v['id_pessoa'], NULL, NULL, 'id="' . $v['id_pessoa'] . '"');
            }
            $form['array'] = $turma;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Nome Aluno' => 'n_pessoa',
                formErp::checkbox('chkAll', 1, 'Todos', null, 'onClick="checkAll(this)"') => 'tur'
            ];
            report::simple($form);
            ?>
            <br />
        </form>
    </div>
    <?php } ?>
</div>
<script>
    function check() {
        teste = 0;
        <?php foreach ($turma as $v) { ?>
            if (document.getElementById("<?php echo @$v['id_pessoa'] ?>").checked) {
                teste = 1;
            }
        <?php } ?>

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

