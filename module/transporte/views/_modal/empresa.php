<?php
if (!defined('ABSPATH'))
    exit;

$id_em = filter_input(INPUT_POST, 'id_em', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_em)) {
    $dados = transporteErp::getEmpresa($id_em);
}

if (!isset($dados['ativo'])) {
    $dados['ativo'] = 1;
}
?>
<div class="body">
    <form method="POST" target="_parent" action="<?= HOME_URI ?>/transporte/empresa">
        <div class="row form-control-sm">
            <div class="col-sm-6">
                <?php echo formErp::input('1[n_em]', 'Nome', @$dados['n_em'], ' required') ?>
            </div>
            <div class="col-sm-6">
                <?php echo formErp::input('1[razao_social]', 'Razão Social', @$dados['razao_social'], ' required') ?>
            </div>
            
        </div>
        <div class="row form-control-sm">
            <div class="col-sm-6">
                <span class="text-muted">Use `;` para separar os e-mails</span>
                <?php echo formErp::input('1[email]', 'E-mail(s)', @$dados['email']) ?>
            </div>
            <div class="col-sm-4">
                &nbsp;
                <?php echo formErp::input('1[nome_contato]', 'Nome Contato', @$dados['nome_contato']) ?>
            </div>
            <div class="col-sm-2">
                &nbsp;<?php echo formErp::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$dados['ativo']) ?>
            </div>
        </div>
        <div class="row form-control-sm">
            <div class="col-sm-4">
                <?php
                echo formErp::hidden(['1[id_em]'=>@$id_em]);
                echo formErp::hiddenToken('transporte_empresa', 'ireplace');
                echo formErp::button('Salvar');
                ?>
            </div>
        </div>
    </form>
</div>
