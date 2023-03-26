<?php
if (!defined('ABSPATH'))
    exit;
$evento = sql::get('inscr_evento', '*', ['id_evento' => 3], 'fetch');
?>
<div class="body">
    <table style="width: 100%">
        <tr>
            <td style="width: 200px">
                <img style="width: 100%;" src="<?= HOME_URI ?>/includes/images/topo1.png"/>
            </td>
            <td style="text-align: center; font-weight: bold; font-size: 2em">
                <?= $evento['n_evento'] ?>
            </td>
            <td style="width: 200px">
                <img style="width: 100%;" src="<?= HOME_URI ?>/includes/images/topo2.png"/>
            </td>
        </tr>
    </table>
    <div class="alert alert-info" style="margin-top: 20px; font-weight: bold">
        <?= $evento['descr_evento'] ?>
    </div>
    <iframe style=" width: 100%; height: 60vh" src="https://portal.educ.net.br/ge/pub/inscrOnline/cadampe.pdf"></iframe>
</div>
<?php
if (true) {
    ?>
    <div style="text-align: center;padding: 20px">
        <form action="https://portal.educ.net.br/ge/inscr/inscr/3">
            <button type="submit" class="btn btn-success">
                Recursos
            </button>
        </form>
    </div>
    <?php
}
