<?php
if (!defined('ABSPATH'))
    exit;
$tipoUp = sql::idNome('inscr_final_tipo_up');
$tipoUp[0] = "Preenchimento do Formulário";
foreach ($tipoUp as $k => $v) {
    $falta[$k] = 1;
}
$falta[0] = 0;
$campos = ['pis', 'conta_banco', 'conta_agencia', 'conta_num'];
foreach ($campos as $v) {
    if (empty($dados[$v])) {
        $falta[0] = 1;
    }
}

$u = sql::get('inscr_final_up', 'fk_id_ftu, ativo', ['cpf' => $cpf]);
if ($u) {
    foreach ($u as $v) {
        if ($v['ativo'] == 1) {
            $falta[$v['fk_id_ftu']] = 0;
        }
    }
}
foreach (range(0, (count($tipoUp) - 1)) as $v) {
    if ($falta[$v] == 1) {
        $faltaRec = 1;
    }
}
?>
<br />
<?php
if ($dados['fk_id_vs'] == 3) {
    ?>
    <div class="alert alert-danger" style="font-weight: bold;">
        <?php
        echo toolErp::bom() . ', ' . explode(' ', trim($dados['nome']))[0];
        ?>
        <p>
            Analisamos os documentos e dados enviados e encontramos a seguinte inconsistência:
        </p>
        <p>
            <?= $dados['obs'] ?>
        </p>
    </div>
    <?php
}
if (empty($faltaRec)) {
    if (empty($dados['fk_id_vs']) || $dados['fk_id_vs'] == 3) {
        ?>
        <form id="enviar" method="POST">
            <?=
            formErp::hidden(['activeNav' => 3])
            . formErp::hiddenToken('inscr_incritos_3', 'ireplace', null, ['id_cpf' => $cpf, 'fk_id_vs' => 2])
            ?>
        </form>
        <div onclick="if (confirm('Após o envio não será mais possível editar. Enviar?')) {
                    enviar.submit()
                }" style="text-align: center">
            <button class="btn btn-danger">
                ENVIAR PARA VALIDAÇÃO
            </button>
        </div>
        <?php
    } elseif ($dados['fk_id_vs'] == 1) {
        ?>
        <div class="alert alert-success" style="font-weight: bold; font-size: 1.4em; text-align: center">
            Os seus dados foram analisados e validados com sucesso!
        </div>
        <?php
    } elseif ($dados['fk_id_vs'] == 2) {
        ?>
        <div class="alert alert-warning" style="font-weight: bold; font-size: 1.4em; text-align: center">
            <p>
                Estamos analisando os dados e documentos enviados.
            </p>
            <p>
                Volte mais tarde para confirmar a validação.
            </p>
        </div>
        <?php
    }
} else {
    ?>
    <div class="alert alert-danger" style="font-weight: bold; font-size: 1.4em; text-align: center">
        Preencha todos os requisitos para liberarmos o botão ENVIAR PARA VALIDAÇÃO
    </div>
    <?php
}
?>
<br /><br />
<table class="table table-bordered table-hover table-striped" style="width: 800px; margin: auto">
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold; font-size: 1.2em">
            Verificação dos Requisitos
        </td>
    </tr>
    <?php
    foreach (range(0, (count($tipoUp) - 1)) as $v) {
        ?>
        <tr>
            <td style="width: 30px">
                <?php
                if ($falta[$v] == 1) {
                    ?>
                    <img style="width: 30px" src="<?= HOME_URI ?>/includes/images/n.png" alt="Não"/>
                    <?php
                } else {
                    ?>
                    <img style="width: 30px" src="<?= HOME_URI ?>/includes/images/s.png" alt="Sim"/>
                    <?php
                }
                ?>
            </td>
            <td>
                <?= $tipoUp[$v] ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>


