<?php
if (!defined('ABSPATH'))
    exit;
?>
<div class="body">
    <div class="fieldTop">
        Validar Inscrição
    </div>
    <form method="POST">
        <div class="row">
            <div class="col-2">
                <?= formErp::radio('1[validado]', 2, 'NÃO Validado', $dados['validado']) ?>
            </div>
            <div class="col-2">
                <?= formErp::radio('1[validado]', 1, 'Validado', $dados['validado']) ?>
            </div>
            <div class="col-4">
                <?=
                formErp::hidden([
                    'id_ec' => $id_ec,
                    '1[id_ec]' => $id_ec,
                    'activeNav' => 4
                ])
                . formErp::hiddenToken('inscr_evento_categoria', 'ireplace')
                ?>
                <button class="btn btn-primary">
                    Salvar
                </button>
            </div>
        </div>
        <br />
        <?= formErp::textarea('1[validado_obs]', $dados['validado_obs'], 'Obs:') ?>
    </form>
    <br />
    <?php
    if ($dados['validado'] == 1) {
        ?>
        <br /><br /><br />
        <div class="row">
            <div class="col text-center">
                <form target="_blank" action="<?= HOME_URI ?>/inscr/pdf/acumulo" method="POST">
                    <?=
                    formErp::hidden([
                        'cpf' => $dados['id_cpf'],
                        'id_ec' => $id_ec,
                    ])
                    ?>
                    <button class="btn btn-info">
                        Declaração de Acumulo de Cargo
                    </button>
                </form>
            </div>
            <div class="col text-center">
                <form target="_blank" action="<?= HOME_URI ?>/inscr/pdf/compromisso" method="POST">
                    <?=
                    formErp::hidden([
                        'cpf' => $dados['id_cpf'],
                        'id_ec' => $id_ec,
                    ])
                    ?>
                    <button class="btn btn-info">
                        Termo de Compromisso
                    </button>
                </form>
            </div>
        </div>
        <br />
        <?php
    }
    ?>
</div>
