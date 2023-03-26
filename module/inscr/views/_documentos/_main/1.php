<?php
if (!defined('ABSPATH'))
    exit;
?>
<br />
<?php
if (!in_array($dados['fk_id_vs'], [1,2])) {
    $disabled = '';
    ?>
    <form method="POST">
        <?php
    } else {
        $disabled = 'disabled';
    }
    ?>
    <div class="row">
        <div class="col">
            <?= formErp::input('1[pis]', 'Número do PIS/PASEP', $dados['pis'], ' required ' . $disabled) ?>
        </div>
    </div>
    <br />
    <div class="border">
        <div style="text-align: center; padding: 10px; font-size: 1.4em">
            Dados Bancários - Conta Corrente
        </div>
        <div class="alert alert-danger">
            A conta bancária NÃO pode ser CONTA POUPANÇA nem CONTA CONJUNTA.
        </div>
        <div class="row">
            <div class="col">
                <div class="border text-center" style="min-height: 150px">
                    <div style="padding-bottom: 10px">
                        Número do Banco
                    </div>
                    <?= formErp::input('1[conta_banco]', null, $dados['conta_banco'], ' required id="banco" ' . $disabled) ?>
                    <?php
                    if (!in_array($dados['fk_id_vs'], [1,2])) {
                        ?>
                        <br />
                        <div style="text-align: center">
                            <button onclick=" $('#myModal').modal('show');$('.form-class').val('')" type="button" class="btn btn-primary" style="width: 100%">
                                Achar o número do banco
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col">
                <div class="border text-center" style="min-height: 150px">
                    <div style="padding-bottom: 10px">
                        Número da Agência
                    </div>
                    <?= formErp::input('1[conta_agencia]', null, $dados['conta_agencia'], ' required ' . $disabled) ?>
                </div>
            </div>
            <div class="col">
                <div class="border text-center" style="min-height: 150px">
                    <div style="padding-bottom: 10px">
                        Número da Conta Corrente
                    </div>
                    <?= formErp::input('1[conta_num]', null, $dados['conta_num'], ' required ' . $disabled) ?>
                </div>
            </div>
            <div class="col">
                <div class="border text-center" style="min-height: 150px">
                    <div style="padding-bottom: 10px">
                        Dígito da Conta Corrente
                    </div>
                    <?= formErp::input('1[conta_dig]', null, $dados['conta_dig'], $disabled) ?>
                </div>
            </div>
        </div>
        <br />
    </div>
    <?php
    if (!in_array($dados['fk_id_vs'], [1,2])) {
        ?>
        <div style="text-align: center; padding: 20px">
            <?=
            formErp::hidden([
                'activeNav' => 2
            ])
            . formErp::hiddenToken('inscr_incritos_3', 'ireplace', null, ['id_cpf' => $cpf])
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
    <?php
}
toolErp::modalInicio(0, null, null, 'Procurar o Número do Banco');
include ABSPATH . "/module/inscr/views/_documentos/_main/bancos.php";
toolErp::modalFim();
?>