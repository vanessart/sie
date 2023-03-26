<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$n_inst = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
$chrome = null;
$serial_pesq = filter_input(INPUT_POST, 'serial_pesq', FILTER_SANITIZE_STRING);
if ($serial_pesq) {
    $chrome = $model->chromebook(null, $serial_pesq);
    if (!$chrome) {
        ?>
        <div class="alert alert-danger" style="text-align: center; font: bold">
            Chromebook não encontrado
        </div>
        <?php
    }
}
$id_pessoa = tool::id_pessoa();
?>
<div class="body">
    <?php
    if (!$chrome) {
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
            if ($chrome['block'] != 1 && $chrome['fk_id_inst'] != $id_inst) {
                ?>
                <p>
                    O Chromebook com número de série <span style="font-weight: bold"><?= $chrome['serial'] ?></span>, modelo <span style="font-weight: bold"><?= $chrome['n_cm'] ?></span> está disponível.
                </p>
                <br />
                <p>
                    Deseja cadastrá-lo para a <?= $n_inst ?>?
                </p>
                <div class="row" style="padding: 50px">
                    <div class="col text-center">
                        <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                            <?=
                            formErp::hidden([
                                'id_inst' => $id_inst,
                            ])
                            . formErp::button('Voltar')
                            ?>
                        </form>
                    </div>
                    <div class="col text-center">
                        <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                            <?=
                            formErp::hiddenToken('chromeIncluirEsc')
                            . formErp::hidden([
                                'id_inst' => $id_inst,
                                'id_ch' => $chrome['id_ch']
                            ])
                            . formErp::button('Cadastrar')
                            ?>
                        </form>
                    </div>
                </div>
                <?php
            } elseif ($chrome['fk_id_inst'] == $id_inst) {
                ?>
                <p>
                    O Chromebook com número de série <span style="font-weight: bold"><?= $chrome['serial'] ?></span>, modelo <span style="font-weight: bold"><?= $chrome['n_cm'] ?></span> está cadastrado nesta escola.
                </p>
                <?php
                if ($chrome['block'] == 1) {
                    ?>
                    <p style="font-weight: bold; padding: 10px">
                        Deseja liberá-lo para transferência?
                    </p>
                    <p>
                        Obs: O chromebook permanecerá na sua lista até outra escola realizar a transferência.
                    </p>
                    <?php
                } else {
                     ?>
                    <p style="font-weight: bold; padding: 10px">
                        Este chromebook está liberado para transferência. Deseja Bloquear?
                    </p>
                    <?php 
                }
                ?>

                <div class="row" style="padding: 50px">
                    <div class="col text-center">
                        <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                            <?=
                            formErp::hidden([
                                'id_inst' => $id_inst,
                            ])
                            . formErp::button('Voltar')
                            ?>
                        </form>
                    </div>
                    <div class="col text-center">
                        <?php
                        if ($chrome['block'] == 1) {
                            ?>
                            <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                                <?=
                                formErp::hiddenToken('chromeLibera')
                                . formErp::hidden([
                                    'id_inst' => $id_inst,
                                    'id_ch' => $chrome['id_ch']
                                ])
                                . formErp::button('Liberar para Transferência')
                                ?>
                            </form>
                            <?php
                        } else {
                            ?>
                            <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                                <?=
                                formErp::hiddenToken('chromeBlock')
                                . formErp::hidden([
                                    'id_inst' => $id_inst,
                                    'id_ch' => $chrome['id_ch']
                                ])
                                . formErp::button('Bloquear para Transferência')
                                ?>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <br />
                <?php
            } elseif (empty($chrome['fk_id_inst'])) {
                ?>
                <p>
                    O Chromebook com número de série <span style="font-weight: bold"><?= $chrome['serial'] ?></span>, modelo <span style="font-weight: bold"><?= $chrome['n_cm'] ?></span> está sob a responsabilidade da <span style="font-weight: bold">Secretaria da Educação</span>.
                </p>
                <br />
                <p>
                    Solicite à <span style="font-weight: bold">Secretaria da Educação</span> a transferência.
                </p>
                <div style="padding: 50px; text-align: center">
                    <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                        <?=
                        formErp::hidden([
                            'id_inst' => $id_inst,
                        ])
                        . formErp::button('Voltar')
                        ?>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <p>
                    O Chromebook com número de série <span style="font-weight: bold"><?= $chrome['serial'] ?></span>, modelo <span style="font-weight: bold"><?= $chrome['n_cm'] ?></span> está sob a responsabilidade da <span style="font-weight: bold"><?= $chrome['n_inst'] ?></span>.
                </p>
                <br />
                <p>
                    Para requisitá-lo, solicite à <span style="font-weight: bold"><?= $chrome['n_inst'] ?></span> que libere o chromebook para transferência.
                </p>
                <div style="padding: 50px; text-align: center">
                    <form action="<?= HOME_URI ?>/lab/mov" target="_parent" method="POST">
                        <?=
                        formErp::hidden([
                            'id_inst' => $id_inst,
                        ])
                        . formErp::button('Voltar')
                        ?>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>
