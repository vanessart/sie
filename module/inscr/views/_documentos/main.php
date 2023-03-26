<?php
if (!defined('ABSPATH'))
    exit;
?>
<div style="text-align: right; padding: 10px">

    <form id="sair" method="POST">
        <input type="hidden" name="sair" value="1" />

    </form>
</div>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <td>
            Inscriç<?= (count($categoria) > 1 ? 'ões' : 'ão') ?>
        </td>
        <td colspan="2">
            <?php
            foreach ($categoria as $v) {
                echo $v['id_ec'] . ' - ' . $v['n_cate'] . '<br>';
            }
            ?>
        </td>
        <td style="padding: 20px; text-align: center; width: 100px">
            <button onclick="if (confirm('Sair do Sistema?')) {
                        sair.submit();
                    }" class="btn btn-danger">
                Sair
            </button>
        </td>
    </tr>
    <tr>
        <td>
            Nome
        </td>
        <td>
            CPF
        </td>
        <td>
            RG
        </td>
    </tr>
    <tr>
        <td>
            <?= $dados['nome'] ?>
        </td>
        <td>
            <?= $cpf ?>
        </td>
        <td>
            <?= $dados['rg'] ?>-<?= $dados['rg_dig'] ?>
        </td>
    </tr>
</table>
<?php
//include ABSPATH . "/module/inscr/views/_documentos/_main/aviso.php";
$abas[1] = ['nome' => "Dados Complementares", 'ativo' => 1, 'hidden' => []];
$abas[2] = ['nome' => "Uploads dos Documentos", 'ativo' => 1, 'hidden' => []];
$abas[3] = ['nome' => "Enviar para Validação", 'ativo' => 1, 'hidden' => []];
if (!empty($dados['fk_id_vs']) && empty($_POST['activeNav'])) {
    $abaFim = 3;
    $_POST['activeNav'] = 3;
}
$aba = report::abas($abas);
if (!empty($abaFim)) {
    $aba = $abaFim;
}
include ABSPATH . "/module/inscr/views/_documentos/_main/$aba.php";
