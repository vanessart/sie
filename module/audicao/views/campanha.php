<?php
if (!defined('ABSPATH'))
    exit;
$campanha = sql::get('audicao_campanha','id_campanha,n_campanha,at_campanha,liberar_aviso,liberar_form');

if ($campanha) {
    //$token = formErp::token('audicao_campanha', 'delete');
    foreach ($campanha as $k => $v) {
        if ($v['at_campanha'] == 1) {
            $ativo = 'Campanha Ativa';
            $id_campanha_ativa = $v['id_campanha'];
        }else{
            $ativo = '';
        }
        $campanha[$k]['edit'] = '<button class="btn btn-outline-info" onclick="edit(' . $v['id_campanha'] . ')"> Nome</button>';
        $campanha[$k]['conf'] = '<button class="btn btn-outline-info" onclick="conf(' . $v['id_campanha'] . ')">Configurar</button>';
        $campanha[$k]['at'] = $ativo;
        $campanha[$k]['liberar'] =  toolErp::simnao($campanha[$k]['liberar_aviso']);
        $campanha[$k]['form'] =  toolErp::simnao($campanha[$k]['liberar_form']);
        //$campanha[$k]['del'] = formErp::submit('Inativar', $token, ['1[id_campanha]' => $v['id_campanha'];
    }
    $form['array'] = $campanha;
    $form['fields'] = [
        'ID' => 'id_campanha',
        'Campanha' => 'n_campanha',
        'Campanha Ativa' => 'at',
        'Questionários Liberados' => 'form',
        'Avisos Liberados' => 'liberar',
       // '||2' => 'del',
        '||1' => 'edit',
        '||3' => 'conf'
    ];
}
?>
<div class="body">
    <div class="fieldTop"> Cadastro e Ativação de Campanhas</div>
    <div class="row">
        <div class="col-3">
            <button class="btn btn-info" onclick="edit()">
                Nova Campanha
            </button>
        </div>
        <div class="col-3">
            <button class="btn btn-warning" onclick="ativa()">
                Ativar Campanha
            </button>
        </div>
        
    </div>
    
    <br><br>
    <div>
        <form id="form" target="frame" action="<?= HOME_URI ?>/audicao/campanhaCad" method="POST">
            <input type="hidden" name="id_campanha" id="id_campanha" value="" />
            <input type="hidden" name="id_campanha_ativa" id="id_campanha_ativa" value="<?= @$id_campanha_ativa ?>" />
        </form>
        <form id="formEdit" target="frameConf" action="<?= HOME_URI ?>/audicao/campanhaEdit" method="POST">
            <input type="hidden" name="id_campanha" id="id_campanha_edit" value="" />
        </form>
        <form id="formAt" target="frameAt" action="<?= HOME_URI ?>/audicao/campanhaAt" method="POST">
            <input type="hidden" name="id_campanha" id="id_campanha" value="" />
            <input type="hidden" name="id_campanha_ativa" id="id_campanha_ativa" value="<?= @$id_campanha_ativa ?>" />
        </form>
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
        toolErp::modalInicio();
        ?>
            <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
        toolErp::modalInicio(null,null,'ativo');
        ?>
            <iframe name="frameAt" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
        toolErp::modalInicio(null,null,'conf');
        ?>
            <iframe name="frameConf" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    </div>
</div>
<script>
    function edit(id) {
         $('#myModal').on('hidden.bs.modal', function () {
            document.getElementById("form").action = '<?= HOME_URI ?>/audicao/campanhaCad';
        });
        if (id) {
            document.getElementById("id_campanha").value = id;
        } else {
            document.getElementById("id_campanha").value = "";
        }
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function conf(id) {
         $('#conf').on('hidden.bs.modal', function () {
            document.getElementById("formEdit").action = '<?= HOME_URI ?>/audicao/campanhaEdit';
        });
        document.getElementById("id_campanha_edit").value = id;
        document.getElementById("formEdit").submit();
        $('#conf').modal('show');
        $('.form-class').val('');
    }
    function ativa() {
         $('#ativo').on('hidden.bs.modal', function () {
            document.getElementById("formAt").action = '<?= HOME_URI ?>/audicao/campanhaAt';
        });
        document.getElementById("formAt").submit();
        $('#ativo').modal('show');
        $('.form-class').val('');
    }
</script>
