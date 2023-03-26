<?php
if (!defined('ABSPATH'))
    exit;
$link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_NUMBER_INT);
if (empty($link)) {
    $link = 1;
}
$links = [
    1 => '/lab/manut',
    2 => '/lab/emprestRede'
];
$id_manut = filter_input(INPUT_POST, 'id_manut', FILTER_SANITIZE_NUMBER_INT);
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_manut)) {
    $manut = $model->manut($id_manut);
    if ($manut) {
        $id_ch = $manut['fk_id_ch'];
    }
} elseif (!empty($id_ch)) {
    $manut = $model->manut(null, $id_ch, '5,4');
    if ($manut) {
        $id_ch = $manut['fk_id_ch'];
        $id_manut = $manut['id_manut'];
    }
}
$obs = @$_POST['obs'];
?>
<div class="body">
    <?php
    if (empty($id_ch)) {
        $chrome = sql::get('lab_chrome');
        $chrome = toolErp::idName($chrome, 'id_ch', 'serial');
        ?>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_ch', $chrome, 'Chromebook') ?>
                </div>
                <div class="col">
                    <?= formErp::button('Continuar') ?>
                </div>
            </div>
            <br />
        </form>
        <?php
    } else {
        $empret = $model->chromebook($id_ch);
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Nº de Série
                </td>
                <td>
                    <?= $empret['serial'] ?>
                </td>
            </tr>
            <tr>
            <tr>
                <td>
                    Modelo
                </td>
                <td>
                    <?= $empret['n_cm'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Instância
                </td>
                <td>
                    <?= empty($empret['n_inst']) ? 'Secretaria da Educação' : $empret['n_inst'] ?>
                </td>
            </tr>
            <?php
            if (!empty($empret['n_pessoa'])) {
                ?>
                <tr>
                    <td>
                        Responsável
                    </td>
                    <td>
                        <?= $empret['n_pessoa'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        E-mail
                    </td>
                    <td>
                        <?= $empret['emailgoogle'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        if (!empty($empret['id_pessoa'])) {
            ?>
            <div class="alert alert-danger">
                Primeiro faça a devolução do equipamento antes de enviar para manutenção.
            </div>
            <?php
        } else {
            ?>
            <form enctype="multipart/form-data" target="_parent" action="<?= HOME_URI . $links[$link] ?>" method="POST">
                <div class="row">
                    <div class="col">
                        Acompanha o carregador?
                    </div>
                    <div class="col">
                        <?= formErp::radio('1[carregador]', 0, 'Não', @$manut['carregador']) ?>
                    </div>
                    <div class="col">
                        <?= formErp::radio('1[carregador]', 1, 'Sim', @$manut['carregador']) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <p>
                            Estado do chrmebook e carregador e demais observações no momento do recebimento
                        </p>
                        <?= formErp::textarea('1[obs_user]', empty($obs) ? @$manut['obs_user'] : $obs) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <p>
                            Estado do chrmebook e carregador e demais observações no momento do retorno da manutenção
                        </p>
                        <?= formErp::textarea('1[obs_empresa]', @$manut['obs_empresa']) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::selectDB('lab_chrome_manutencao_status', '1[fk_id_ms]', 'Situação', empty($manut['fk_id_ms']) ? 1 : $manut['fk_id_ms']) ?>
                    </div>
                </div>
                <br />
                <div class="border">
                    <div style="text-align: center; padding: 8px">
                        Uploads
                    </div>
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('n_doc', 'Documento') ?>
                        </div>
                        <div class="col">
                            <?= formErp::input('arquivo', 'Upload', null, null, null, 'file') ?>
                        </div>
                    </div>
                    <br />
                </div>
                <br /><br />
                <div class="row">
                    <div class="col text-center">
                        <?=
                        formErp::hiddenToken('manutSalvar')
                        . formErp::hidden([
                            'id_manut' => $id_manut,
                            '1[id_manut]' => $id_manut,
                            '1[fk_id_ch]' => $id_ch,
                            'id_ch' => $id_ch,
                            'id_pessoa' => $empret['id_pessoa'],
                            'link' => $link
                        ])
                        . formErp::button('Salvar')
                        ?>
                    </div>
                </div>
                <br />
            </form>
            <?php
            $doc = sql::get('lab_chrome_doc', '*', ['fk_id_ch' => $id_ch]);
            if ($doc) {
                ?>
                <div class="row">
                    <?php
                    foreach ($doc as $v) {
                        ?>
                        <div class="col">
                            <table>
                                <td>
                                    <form target="_blank" action="<?= HOME_URI ?>/pub/labDoc/<?= $v['end'] ?>">
                                        <button class="btn btn-info">
                                            <?= empty($v['n_doc']) ? 'Documento' : $v['n_doc'] ?>
                                        </button>
                                    </form>  
                                </td>
                                <td>
                                    <form method="POST">
                                        <?=
                                        formErp::hidden([
                                            '1[id_doc]' => $v['id_doc'],
                                            'id_manut' => $id_manut,
                                            'id_ch' => $id_ch
                                        ])
                                        . formErp::hiddenToken('lab_chrome_doc', 'delete')
                                        ?>
                                        <button class="btn btn-danger">
                                            X
                                        </button>
                                    </form>
                                </td>
                            </table>

                        </div>
                        <?php
                    }
                    ?>
                </div>
                <br /><br />
                <?php
            }
            if (!empty($id_manut) && false) {
                ?>
                <div style="text-align: center">
                    <form target="_blank" action="<?= HOME_URI ?>/lab/pdf/manut_<?= $manut['fk_id_ms'] ?>.php" method="POST">
                        <input type="hidden" name="id_manut" value="<?= $id_manut ?>" />
                        <button class="btn btn-primary">Protocolo</button>
                    </form>
                </div>
                <br /><br />
                <?php
            }
        }
    }
    if (!empty($id_manut)) {
        $log = sql::get(['lab_chrome_manutencao_status_log', 'lab_chrome_manutencao_status'], '*, lab_chrome_manutencao_status_log.time_stamp', ['fk_id_manut' => $id_manut]);
        foreach ($log as $k => $v) {
            $log[$k]['data'] = substr($v['time_stamp'], 0, 10);
            $log[$k]['hora'] = substr($v['time_stamp'], 11, 5);
        }
        $form['array'] = $log;
        $form['fields'] = [
            'Data' => 'data',
            'hora' => 'hora',
            'Status' => 'n_ms'
        ];
        report::simple($form);
    }
    ?>
</div>

<form target="_parent" action="<?= HOME_URI . $links[$link] ?>" id="voltar1" method="POST">
</form>
<script>
    function volta() {
        document.getElementById('voltar1').submit();
    }
</script>