<?php
if (!defined('ABSPATH'))
    exit;

$id_tv = filter_input(INPUT_POST, 'id_tv', FILTER_SANITIZE_NUMBER_INT);
$dados = sqlErp::get('transporte_veiculo', '*', ['id_tv' => $id_tv], 'fetch');

if (!isset($dados['fk_id_sv'])) {
    $dados['fk_id_sv'] = 1;
}
?>
<div class="body">
    <form method="POST" target="_parent" action="<?= HOME_URI ?>/transporte/cadveiculo">
        <div class="row">
            <div class="col-sm-3">
                <?php echo formErp::input('1[n_tv]', 'Nome do Veículo', @$dados['n_tv']) ?>
            </div>
            <div class="col-sm-2">
                <?php echo formErp::input('1[placa]', 'Placa', @$dados['placa']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::selectNum('1[capacidade]', [1, 50], 'Capacidade', @$dados['capacidade']) ?>
            </div>
             <div class="col-sm-3">
                 <?php echo formErp::select('1[cadeirante]', [0 => 'Não', 1 => 'Sim'], 'Cadeirante', @$dados['cadeirante']); ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-3">
                <?php echo formErp::select('1[acessibilidade]', [0 => 'Não', 1 => 'Sim'], 'Necessidades Especiais', @$dados['acessibilidade']); ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::selectDB('transporte_tipo_veiculo', '1[fk_id_tiv]', 'Tipo', @$dados['fk_id_tiv']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::selectDB('transporte_status_veiculo', '1[fk_id_sv]', 'Situação', @$dados['fk_id_sv']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::selectDB('transporte_empresa', '1[fk_id_em]', 'Empresa', @$dados['fk_id_em']) ?>
            </div>
        </div>
        <br /><br />
        <?php
        echo formErp::hidden([
            '1[id_tv]' => @$dados['id_tv']
        ]);
        ?>
        <div class="row">
            <div class="col-sm-6 text-center">
                <?php
                echo formErp::button('Salvar')
                . formErp::hiddenToken('transporte_veiculo', 'ireplace');
                ?>
            </div>

            <?php if (empty($id_tv)) { ?>
            <div class="col-sm-6 text-center">
                <a class="btn btn-warning" onclick="document.getElementById('lp').submit()" href="#">Limpar</a>
            </div>
            <?php } ?>
        </div>
    </form>
    <form id="lp" method="POST">
        <input type="hidden" name="modal" value="1" />
    </form>
</div>