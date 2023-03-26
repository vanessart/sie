<?php
if (!defined('ABSPATH'))
    exit;

$tipoUp = sql::idNome('inscr_final_tipo_up');
$cpf = filter_input(INPUT_POST, 'id_cpf', FILTER_SANITIZE_NUMBER_INT);
$cpf = str_pad($cpf, 11, "0", STR_PAD_LEFT);
$fk_id_vs = filter_input(INPUT_POST, 'fk_id_vs', FILTER_SANITIZE_NUMBER_INT);
if ($cpf) {
    $pe = sql::get('inscr_incritos_3', '*', ['id_cpf' => $cpf], 'fetch');
    $u = sql::get('inscr_final_up', '*', ['cpf' => $cpf]);
    if ($u) {
        foreach ($u as $v) {
            $up[$v['fk_id_ftu']][] = $v;
        }
    }
}
if (!empty($pe)) {
    ?>
    <div class="body">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    CPF
                </td>
                <td>
                    PIS/PASEP
                </td>
            </tr>
            <tr>
                <td>
                    <?= $pe['nome'] ?>
                </td>
                <td>
                    <?= $cpf ?>
                </td>
                <td>
                    <?= $pe['pis'] ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <td>
                                Banco
                            </td>
                            <td>
                                Agência
                            </td>
                            <td>
                                Conta Corrente
                            </td>
                            <td>
                                Dígito
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $pe['conta_banco'] ?>
                            </td>
                            <td>
                                <?= $pe['conta_agencia'] ?>
                            </td>
                            <td>
                                <?= $pe['conta_num'] ?>
                            </td>
                            <td>
                                <?= $pe['conta_dig'] ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <form target="_parent" action="<?= HOME_URI ?>/inscr/entregaDoc" method="POST">
        <?php
        foreach ($tipoUp as $k => $v) {
            ?>
            <div class="border">
                <div class="row">
                    <div class="col-6" style="font-size: 1.4em;">
                        <?= $v ?>
                    </div>
                </div>
                <br />
                <?php
                if (!empty($up[$k])) {
                    ?>
                    <table class="table table-bordered table-hover table-striped">
                        <?php
                        foreach ($up[$k] as $y) {
                            ?>
                            <tr>
                                <td>
                                    <?= $y['nome_origin'] ?>
                                </td>
                                <td style="width: 100px">
                                    <?= formErp::radio('up[' . $y['id_fu'] . ']', 1, 'Validado', $y['ativo']) ?>
                                </td>
                                <td style="width: 100px">
                                    <?= formErp::radio('up[' . $y['id_fu'] . ']', 0, 'Devolver', $y['ativo']) ?>
                                </td>
                                <td style="width: 100px">
                                    <?php
                                    if (file_exists(ABSPATH . '/pub/inscrOnline/' . $y['link'])) {
                                        $link = HOME_URI . '/pub/inscrOnline/' . $y['link'];
                                    }
                                    if ($y['ativo'] == 1) {
                                        $class = 'info';
                                        $nomeBtn = "Visualizar";
                                    } else {
                                        $class = 'danger';
                                        $nomeBtn = "Invalido";
                                    }
                                    ?>
                                    <button type="button" id="<?= $y['id_fu'] ?>" onclick="verPdf('<?= $link ?>', <?= $y['id_fu'] ?>)" class="btn btn-<?= $class ?>">
                                        <?= $nomeBtn ?>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <?php
                }
                ?>
            </div>
            <br />
            <?php
        }
        ?>
        <br />
        <div class="border">
            <div class="row">
                <div class="col">
                    <?= formErp::radio('1[fk_id_vs]', 2, 'Em Análise', $pe['fk_id_vs']) ?>
                </div>
                <div class="col">
                    <?= formErp::radio('1[fk_id_vs]', 3, 'Devolvido', $pe['fk_id_vs']) ?>
                </div>
                <div class="col">
                    <?= formErp::radio('1[fk_id_vs]', 1, 'Validado', $pe['fk_id_vs']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[obs]', $pe['obs'], 'Observação') ?>
                </div>
            </div>
            <br />
            <div style="text-align: center; padding: 30px">
                <?=
                formErp::hidden(['fk_id_vs' => $fk_id_vs, '1[id_cpf]' => $cpf])
                . formErp::hiddenToken('entregaSalva')
                . formErp::button('Salvar')
                ?>
            </div>
        </div>
    </form>
    <?php
}
?>
<form id="formPdf" target="framePdf" >
</form>
<?php
toolErp::modalInicio(0, null, 'pdf');
?>
<iframe name="framePdf" style="height: 200vh; width: 100%; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function verPdf(link, id) {
        formPdf.action = link;
        formPdf.submit();
        $('#pdf').modal('show');
        $('.form-class').val('');
        document.getElementById(id).classList.remove('btn-info');
        document.getElementById(id).classList.add('btn-success');
    }
</script>