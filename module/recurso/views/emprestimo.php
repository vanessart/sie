<?php
if (!defined('ABSPATH'))
    exit;
$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
$id_emprestimo = filter_input(INPUT_POST, 'id_emprestimo', FILTER_SANITIZE_NUMBER_INT);
$emprestimo = ['1'=>'Finalizado','2'=>'Em Andamento'];
if (empty($id_emprestimo)) {
   $id_emprestimo = 2; 
}
if (in_array(user::session('id_nivel'), [10])) {
    $escola = $model->escolasOpt();
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    if (empty($id_inst)) {
       $id_inst = 13;
    } 
} else {
    $id_inst = tool::id_inst();
}
$hidden = [
'id_emprestimo' => $id_emprestimo,
'search' => $search,
'id_inst' => $id_inst
];
?>
<div class="body">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-8" style="font-weight:bold; font-size:20px; text-align: center;">
            Empréstimo de Equipamentos
            <br><br>
            Categoria: <?= $_SESSION['userdata']['n_categoria'] ?>
            <?= $model->info("Para alterar a Categoria, utilize a página 'Início' no menu") ?>
        </div>
        <div class="col-2" style="padding-top:30px">
            <?php
            if (!empty($id_inst)) {?>
                <button onclick="acesso()" class="btn btn-warning">
                    Novo empréstimo
                </button>
                <?php
            }?>
        </div>
    </div>
    <br><br><br><br>
    <div class="row">
        <?php   
        if (!empty($escola)) {?>
            <div class="col text-center">
                <?= formErp::select('id_inst', $escola, 'Escola', $id_inst, 1, $hidden); ?>
            </div>
            <?php 
        }?>
        <div class="col text-center">
            <?= formErp::select('id_emprestimo', $emprestimo, 'Situação', $id_emprestimo, 1, $hidden); ?>
        </div>
        <div class="col-5">
            <form method="POST">
                <div class="input-group">
                    <span class="input-group-text">
                        CPF, E-mail ou ID:
                    </span>
                    <input class="form-control" type="text" name="search" value="<?php echo $search ?>"  >
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>"  >
                    <span class="input-group-text" style="padding:0" >
                        <button type="submit" class="btn">
                            <span aria-hidden="true">
                                Buscar
                            </span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<br />
<?php
if (!empty($id_inst)) {
    $conta = $model->emprestimoGet($id_inst,$search,$id_emprestimo,1,[0, 1])[0]['ct'];
    $pag = report::pagination(100, $conta, $hidden);
    $emprestaList = $model->emprestimoGet($id_inst,$search,$id_emprestimo,null,[$pag, 100]);
}
if (!empty($emprestaList)) {
    foreach ($emprestaList as $k => $v) {
        if (!empty($v['n_serial'])) {
            if (!empty($v['rm'])) {
                if ($v['professor'] == 1) {
                    $emprestaList[$k]['n_turma'] = 'Professor';
                }else{
                    $emprestaList[$k]['n_turma'] = 'Funcionário';
                }
                $emprestaList[$k]['id_pessoa'] = $v['rm'];
            }
            if (empty($emprestaList[$k]['n_turma'])) {
                $emprestaList[$k]['n_turma'] = 'Funcionário';
            }
            if ($id_emprestimo == 2) {
                $protocolo = "termoEmprest";
                if ( !empty($v['devolver']) ) {
                    $emprestaList[$k]['edit'] = formErp::button('Devolver', null, 'acesso(0,' . $v['id_move'] . ','. ' \'' . $emprestaList[$k]['id_pessoa'] . '\',' . $v['id_pessoa'] .','. ' \'' . $v['n_pessoa'] . '\' )', 'primary');
                } else {
                    $emprestaList[$k]['edit'] = '';
                }
            }else{
                $protocolo = "protocoloDev";
                $emprestaList[$k]['edit'] = '';
            }
            $emprestaList[$k]['prot'] = formErp::submit('Protocolo', null,['comodato' => $v['comodato'],'n_equipamento' => $v['n_equipamento'],'id_move' => $v['id_move'],'id_serial' => $v['id_serial'],'id_inst' => $id_inst, 'id_pessoa' => $v['id_pessoa'], 'n_serial' => $v['n_serial']], HOME_URI . '/recurso/'.$protocolo, 1);
            $emprestaList[$k]['ver'] = formErp::button('Visualizar', null, 'acesso(1,' . $v['id_move'] . ','. $emprestaList[$k]['id_pessoa'] . ',' . $v['id_pessoa'] .','. ' \'' . $v['n_pessoa'] . '\' )', 'primary');
            $emprestaList[$k]['periodo'] = $v['comodato'] == 0 ? dataErp::converteBr($v['dt_inicio']).' a '.dataErp::converteBr($v['dt_fim']) : "Comodato";
            $emprestaList[$k]['nome'] = $v['n_pessoa']." - ".$emprestaList[$k]['n_turma'];
            $emprestaList[$k]['n_comodato'] = $v['comodato'] == 1 ? "SIM" : "NÃO";
        } else {
            unset($emprestaList[$k]); 
        }
    }
    
    $form['array'] = $emprestaList;
    $form['fields'] = [
        'RM/RSE' => 'id_pessoa',
        'Nome' => 'nome',
        'Modelo/Lote' => 'n_equipamento',
        'Número de Série' => 'n_serial',
        'Período' => 'periodo',
        'E-mail' => 'email',
        'Comodato' => 'n_comodato',
        '||1' => 'prot',
        '||3' => 'edit',
        '||2' => 'ver'
    ];
}

if (!empty($id_inst)) {
    if (!empty($emprestaList)) {
        report::simple($form);
    }
}
?>
<form id="formFrame" target="frame" action="" method="POST">
    <input type="hidden" id="id_move" name="id_move" value="" />
    <input type="hidden" id="novo" name="novo" value="" />
    <input type="hidden" id="id_pessoa" name="id_pessoa" value="" />
    <input type="hidden" id="n_pessoa" name="n_pessoa" value="" />
    <input type="hidden" id="id_pessoa_rm" name="id_pessoa_rm" value="" />
    <?=
    formErp::hidden(['id_inst' => $id_inst])
    ?>
</form>
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
    function acesso(action,id,id_pessoa_rm,id_pessoa,n_pessoa) {
        if (id) {
            document.getElementById('id_move').value = id;
            document.getElementById('id_pessoa').value = id_pessoa;
            document.getElementById('n_pessoa').value = n_pessoa;
            document.getElementById('id_pessoa_rm').value = id_pessoa_rm;
            document.getElementById('novo').value = 0;
            texto = '<div style="text-align: center; color: #7ed8f5;">'+n_pessoa+' - '+id_pessoa_rm+'</div>'
            if (action==1) {
                document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/emprestimoHist.php";
            }else{
                document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/devolver.php";
            }
        } else {
            document.getElementById('id_move').value = '';
            document.getElementById('novo').value = 1;
            document.getElementById('formFrame').action = "<?= HOME_URI ?>/recurso/def/emprestar.php";
            texto = "Novo Empréstimo";
            
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>