<?php
if (!defined('ABSPATH'))
    exit;
 
 $manut = sql::get(['recurso_serial','recurso_equipamento'], 'n_equipamento,id_serial,n_serial,recurso_serial.unico_id', ['recurso_serial.fk_id_situacao' => 3]);
?>

<div class="body">
    <div class="fieldTop">
        Manutenção de Equipamentos
    </div>
    <div class="row">
        <div class="col-3">
            <button onclick="ocorrencia()" class="btn btn-warning">
                Registrar Ocorrência
            </button>
        </div>
    </div>
    <br />
    <?php
    if (!empty($manut)) {
        foreach ($manut as $k => $v) {
            $movimentacao = sql::get('recurso_movimentacao', 'dt_update, id_move', ['unico_id' => $v['unico_id'],'fk_id_situacao' => 3 ], 'fetch');
            if (!empty($movimentacao)) {  
                $equipamento = $v['n_equipamento']." - ".$v['n_serial'];
                $manut[$k]['edit'] = formErp::button('Acessar', null, 'acesso(' . $movimentacao['id_move'] .',' . $v['id_serial'] .','. ' \'' . $equipamento . '\' )', 'info');
                $manut[$k]['data'] = $movimentacao['dt_update'];
            } else {
                unset($manut[$k]);
            }
        }
        $form['array'] = $manut;
        $form['fields'] = [
            'Equipamento' => 'n_equipamento',
            'Número de Série' => 'n_serial',
            'Data de Entrada' => 'data',
            '||2' => 'edit'
        ];
    }

    if (!empty($manut)) {
        report::simple($form);
    }

    ?>
    <form id="formFrame" target="frame" action="" method="POST">
        <input type="hidden" id="id_move" name="id_move" value="" />
        <input type="hidden" id="id_serial" name="id_serial" value="" />
        <input type="hidden" id="manutencao" name="manutencao" value="" />
        <input type="hidden" id="id_situacao" name="id_situacao" value="3" />
    </form>
</div>
<?php
toolErp::modalInicio();
?>
<iframe id="fframe" style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    //faz reload da pagina após inserir um pedido no banco
    $('#myModal').on('hidden.bs.modal', function () {
        el=document.getElementById('fframe');
        if (typeof el == null) return;
        item = el.contentWindow.document.getElementsByName('closeModal')[0].value;
        if (item == 1)
            window.location.reload();
    });
    function acesso(id,id_serial,equipamento) {
        if (id) {
            document.getElementById('id_move').value = id;
            document.getElementById('id_serial').value = id_serial;
            document.getElementById('manutencao').value = 1;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/devolver.php";
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Movimentação</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function ocorrencia() {
        document.getElementById('id_move').value = '';
            document.getElementById('id_serial').value = '';
        document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/ocorrencia.php";
        texto = '<div style="text-align: center; color: #7ed8f5;">Registrar Nova  Ocorrência</div>';
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>