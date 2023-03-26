<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$n_inst = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
$chrome = null;
$serial_pesq = trim(filter_input(INPUT_POST, 'serial_pesq', FILTER_SANITIZE_STRING));
if ($serial_pesq) {
    $chrome = $model->chromebook(null, $serial_pesq);
}
$id_pessoa = tool::id_pessoa();
?>
<div class="body">
    <?php
    if (!$serial_pesq) {
        ?>
        <form method="POST">
            <table>
                <tr>
                    <td style="min-width: 500px">
                        <?= formErp::input('serial_pesq', 'Número de Série', null, '  style="text-transform:uppercase" required') ?>
                    </td>
                    <td>
                        &nbsp;&nbsp; 
                    </td>
                    <td style="width: 10px">
                        <?=
                        formErp::hidden([
                            'id_inst' => $id_inst
                        ])
                        . formErp::button('Continuar')
                        ?>
                    </td>
                </tr>
            </table>
        </form>
        <?php
    } else {
        ?>
        <div style="text-align: justify; padding: 50px;line-height: 1.6;">
            <?php
            if (!$chrome) {
                ?>
                <p>
                    Não encontramos o número de série "<?= $serial_pesq ?>".
                </p>
                <p>
                    Se o número estiver correto, informe no formúlario abaixo a situação do Chromebook.
                </p>
                <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[serial]', 'Número Serial', $serial_pesq, ' required readonly ') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col">
                            <?= formErp::selectDB('lab_chrome_mov_adm_motivo', '1[fk_id_cmam]', 'Motivo', null, null, null, null, null, 'required') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col">
                            <?= formErp::textarea('1[obs]', null, 'Relate aqui a situação do chromebook') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col text-center">
                            <button type="button" class="btn btn-warning" onclick="document.getElementById('voltar').submit();">
                                Voltar
                            </button>
                        </div>
                        <div class="col text-center">
                            <?=
                            formErp::hidden([
                                '1[fk_id_inst]' => $id_inst,
                                'id_inst' => $id_inst,
                                '1[fk_id_pessoa]' => tool::id_pessoa(),
                                '1[sem_registro]'=>1
                            ])
                            . formErp::hiddenToken('lab_chrome_mov_adm', 'ireplace')
                            . formErp::button('Enviar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
                <?php
            } elseif ($chrome['fk_id_inst'] == $id_inst) {
                ?>
                <p>
                    O Chromebook com número de série <span style="font-weight: bold"><?= $chrome['serial'] ?></span>, modelo <span style="font-weight: bold"><?= $chrome['n_cm'] ?></span> está cadastrado nesta escola.
                </p>
                <p>
                    Para excluí-lo, preencha o formulário abaixo.
                </p>
                <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[serial]', 'Número Serial', $serial_pesq, ' required readonly ') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col">
                            <?= formErp::textarea('1[obs]', null, 'Relate aqui a situação do chromebook') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col text-center">
                            <button type="button" class="btn btn-warning" onclick="document.getElementById('voltar').submit();">
                                Voltar
                            </button>
                        </div>
                        <div class="col text-center">
                            <?=
                            formErp::hidden([
                                '1[fk_id_inst]' => $id_inst,
                                'id_inst' => $id_inst,
                                '1[fk_id_pessoa]' => tool::id_pessoa(),
                                '1[fk_id_cmam]' => 2
                            ])
                            . formErp::hiddenToken('lab_chrome_mov_adm', 'ireplace')
                            . formErp::button('Enviar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
                <br />
                <?php
            } elseif (empty($chrome['fk_id_inst'])) {
                ?>
                <p>
                    O Chromebook com número de série <span style="font-weight: bold"><?= $chrome['serial'] ?></span>, modelo <span style="font-weight: bold"><?= $chrome['n_cm'] ?></span> está sob a responsabilidade da <span style="font-weight: bold">Secretaria da Educação</span>.
                </p>
                <br />
                <p>
                    Para requisitá-lo, preencha o formulário abaixo.
                </p>
                <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[serial]', 'Número Serial', $serial_pesq, ' required readonly ') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col">
                            <?= formErp::textarea('1[obs]', null, 'Relate aqui a situação do chromebook') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col text-center">
                            <button type="button" class="btn btn-warning" onclick="document.getElementById('voltar').submit();">
                                Voltar
                            </button>
                        </div>
                        <div class="col text-center">
                            <?=
                            formErp::hidden([
                                '1[fk_id_inst]' => $id_inst,
                                'id_inst' => $id_inst,
                                '1[fk_id_pessoa]' => tool::id_pessoa(),
                                '1[fk_id_cmam]' => 1
                            ])
                            . formErp::hiddenToken('lab_chrome_mov_adm', 'ireplace')
                            . formErp::button('Enviar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
                <?php
            } else {
                ?>
                <p>
                    O Chromebook com número de série <span style="font-weight: bold"><?= $chrome['serial'] ?></span>, modelo <span style="font-weight: bold"><?= $chrome['n_cm'] ?></span> está sob a responsabilidade da <span style="font-weight: bold"><?= $chrome['n_inst'] ?></span>.
                </p>
                <br />
                <p>
                    Para requisitá-lo, preencha o formulário abaixo.
                </p>
                <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[serial]', 'Número Serial', $serial_pesq, ' required readonly ') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col">
                            <?= formErp::textarea('1[obs]', null, 'Relate aqui a situação do chromebook') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col text-center">
                            <button type="button" class="btn btn-warning" onclick="document.getElementById('voltar').submit();">
                                Voltar
                            </button>
                        </div>
                        <div class="col text-center">
                            <?=
                            formErp::hidden([
                                '1[fk_id_inst]' => $id_inst,
                                'id_inst' => $id_inst,
                                '1[fk_id_pessoa]' => tool::id_pessoa(),
                                '1[fk_id_cmam]' => 1
                            ])
                            . formErp::hiddenToken('lab_chrome_mov_adm', 'ireplace')
                            . formErp::button('Enviar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>
<form id="voltar" action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
    <?=
    formErp::hidden([
        'id_inst' => $id_inst,
    ])
    ?>
</form>
