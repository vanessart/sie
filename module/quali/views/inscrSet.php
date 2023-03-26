<?php
if (!defined('ABSPATH'))
    exit;
$is = sql::get('quali_inscr', 'id_inscr, n_inscr, at_inscr', ['<' => 'id_inscr']);
foreach ($is as $k => $v){
    $is[$k]['end']= '<a target="_blank" href="'.DOMINIO.'/'.HOME_URI.'/quali/inscr/'.$v['id_inscr'].'">'.DOMINIO.'/'.HOME_URI.'/quali/inscr/'.$v['id_inscr'].'</a>';
    $is[$k]['ac']='<button onclick="editar('.$v['id_inscr'].')" class="btn btn-info">Acessar</button>';
    $is[$k]['at']= tool::simnao($v['at_inscr']);
}
$form['array'] = $is;
$form['fields'] = [
    'ID' => 'id_inscr',
    'Título' => 'n_inscr',
    'Ativo'=>'at',
    'Endereço' => 'end',
    '||1' => 'ac'
];
?>
<div class="body">
    <div class="fieldTop">
        Configuração de inscrições on-line
    </div>
    <br /><br />
    <div class="row">
        <div class="col-4">
            <button onclick="editar()" class="btn btn-info">
                Nova Inscrição
            </button>
        </div>
    </div>
    <br />
    <?php
    report::simple($form);
    ?>
</div>
<form id="formFrame" target="frame" action="<?= HOME_URI ?>/quali/def/formInscrSet.php" method="POST">
    <input type="hidden" id="id_inscr" name="id_inscr" value="" />
</form>
<?php
tool::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    tool::modalFim();
    ?>
<script>
    function editar(id) {
        if (id) {
            document.getElementById('id_inscr').value = id;
        }
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('')
    }
</script>