<?php
$class = $model->sitCor();
$id_situacao = filter_input(INPUT_POST, 'id_situacao', FILTER_SANITIZE_NUMBER_INT);
$id_serial = filter_input(INPUT_POST, 'id_serial', FILTER_SANITIZE_NUMBER_INT);
$id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_cate)) {
   $id_cate = $_SESSION['userdata']['id_cate'];; 
}
$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$equipamentoGet = $model->equipamentoSelect();
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
    'id_inst' => $id_inst,
    'id_serial' => $id_serial,
    'id_situacao' => $id_situacao,
    'id_equipamento' => $id_equipamento
];
$gerente = $model->gerente();
if (!empty($id_inst)) {
    $equipamentosBotao = $model->equipamentoGet(null,$id_inst,null,$id_equipamento);
    if (!empty($equipamentosBotao)) {
        $totalBotao = $model->getTotaisSerial($equipamentosBotao);
    }
    $equipamentos = $model->equipamentoGet($id_situacao,$id_inst,$id_serial,$id_equipamento);
    if (!empty($equipamentos)) {
        foreach ($equipamentos as $num => $local) {
            foreach ($local as $n_serial => $v) {
                
                $filtroList[$v['id_serial']] = $v['n_serial'] . (!empty($v['n_pessoa']) ? ' - ' . $v['n_pessoa'] . ' (' . $v['id_pessoa'] . ')' : '');
                
                if (!empty($v['id_situacao'])) {
                    if ($v['id_situacao'] == 8) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-outline-danger';
                        $reparado[] = $v;
                        @$sitTotal[8]++;
                    }elseif ($v['id_situacao'] == 1) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-success';
                        @$sitTotal[1]++;
                    }elseif ($v['id_situacao'] == 2) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-info';
                        @$sitTotal[2]++;
                    } elseif ($v['id_situacao'] == 3) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-primary';
                        @$sitTotal[3]++;
                    }elseif ($v['id_situacao'] == 4) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-danger';
                        @$sitTotal[4]++;
                    }elseif ($v['id_situacao'] == 5) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-outline-secondary';
                        @$sitTotal[5]++; 
                    } elseif ($v['id_situacao'] == 6) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-warning';
                        @$sitTotal[6]++; 
                    } elseif ($v['id_situacao'] == 7) {
                        $equipamentos[$num][$n_serial]['btn'] = 'btn btn-primary';
                        @$sitTotal[7]++; 
                    } 
                } else {
                    $equipamentos[$num][$n_serial]['btn'] = 'btn btn-outline-danger';
                    @$sitTotal[0]++;
                }
            }
        }
    }
}
$sitTotal = !empty($sitTotal) ? $sitTotal : [];
$totalBotao = !empty($totalBotao) ? $totalBotao : [];
?>
<div class="body">
    <br>
    
        <div class="row">
            <div class="col-4 text-center">
            </div>
            <div class="col-4" style="font-weight:bold; font-size:20px; text-align: center;">
                Categoria: <?= $_SESSION['userdata']['n_categoria'] ?>
                <?= $model->info("Para alterar a Categoria, utilize a página 'Início' no menu") ?>
            </div>
            <?php
            if (!empty($id_inst)) {?>
                <div class="col-2 text-center">
                    <form target="_blank" action="<?= HOME_URI ?>/recurso/relatEscola" method="POST">
                        <?= formErp::hidden(['id_inst' => $id_inst]) ?>
                        <button type="submit" class="btn btn-warning">
                            Inventário
                        </button>
                    </form>
                </div>
                <div class="col-2 text-center">
                    <form target="_blank" action="<?= HOME_URI ?>/recurso/pdf/plan.php" method="POST">
                        <?= formErp::hidden($hidden) ?>
                        <button type="submit" class="btn btn-warning">
                            Exportar
                        </button>
                    </form>
                </div>    
                <?php
            }?>
        </div>    
    <br>
    <div class="border">
        <div class="row">
                <?php
                if (!empty($escola)) {?>
                    <div class="col">
                        <?php
                        echo formErp::select('id_inst', $escola, 'Escola', $id_inst, 1, $hidden);?>
                     </div>
                        <?php
                }?>
           
            <div class="col">
                <?= formErp::select('id_equipamento', $equipamentoGet, ['Modelo/Lote','Todos'], $id_equipamento, 1, $hidden) ?>
            </div>
            <?php
            if (!empty($filtroList)) {
                ?>    
                <div class="col">
                    <?= formErp::select('id_serial', $filtroList, 'Filtro', $id_serial, 1, ['id_inst' => $id_inst,'id_situacao' => $id_situacao]) ?>
                </div>
                <?php
            }
            ?>
            <div class="col">
                <?= formErp::submit('Limpar Filtro', null, ['id_inst' => $id_inst]) ?>
            </div>  
        </div>
        <br>
        
            <form name="situ" method="POST">
                <div class="row">
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>1 ? "-outline" : "" ?>-success" style="width: 100%" onclick="situacao(1);">
                            Regular (<?= intval(@$totalBotao[1]) ?>)
                        </button>
                    </div>
                    <?php if ($id_inst == 13) {?>
                        <div class="col">
                            <button  class="btn btn<?= $id_situacao<>3 ? "-outline" : "" ?>-primary" style="width: 100%" onclick="situacao(3);">
                                Em Manutenção (<?= intval(@$totalBotao[3]) ?>)
                            </button>
                        </div>
                        <?php
                    }?>
                    <?php if ($id_inst == 13) {?>
                        <div class="col">
                            <button  class="btn btn<?= $id_situacao<>3 ? "-outline" : "" ?>-primary" style="width: 100%" onclick="situacao(7);">
                                Danificado (<?= intval(@$totalBotao[7]) ?>)
                            </button>
                        </div>
                        <?php
                    }?>
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>4 ? "-outline" : "" ?>-danger" style="width: 100%" onclick="situacao(4);">
                            Extraviado (<?= intval(@$totalBotao[4]) ?>)
                        </button>
                    </div>
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>6 ? "-outline" : "" ?>-warning" style="width: 100%" onclick="situacao(6);">
                            Não Devolvido (<?= intval(@$totalBotao[6]) ?>)
                        </button>
                    </div>
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>2 ? "-outline" : "" ?>-info" style="width: 100%" onclick="situacao(2);">
                            Emprestado (<?= intval(@$totalBotao[2]) ?>)
                        </button>
                    </div>
                    <?php if ($id_inst == 13) {?>
                        <div class="col">
                            <button  class="btn btn<?= $id_situacao<>5 ? "-outline" : "" ?>-secondary" style="width: 100%" onclick="situacao(5);">
                                Inservível (<?= intval(@$totalBotao[5]) ?>)
                            </button>
                        </div>
                        <?php
                    }?>
                </div>
                <input id="id_situacao" type="hidden" name="id_situacao" value="" />
                <?= formErp::hidden(['id_inst' => $id_inst]) ?>
                <?= formErp::hidden(['id_equipamento' => $id_equipamento]) ?>
            </form>
        <br />
    </div>
    <br>
    <?php
    if (!empty($id_inst)) {
        $n_local = sql::idNome('recurso_local');

        if (!empty($equipamentos)) {
            foreach ($equipamentos as $num => $local) {
                ?>
                <div class="border" style="padding: 20px">
                    <div>
                        <?php
                        if ($num<1) {
                            ?>
                            Não Alocado (<?= count($local) ?> Equipamentos)
                            <?php
                        } else {
                            ?>
                            <?=$n_local[$num] ?> (<?= count($local) ?> Equipamentos)
                            <?php
                        }?>
                        <br /><br />
                        <div class="row">  
                            <?php
                            foreach ($local as $v) {
                                ?>
                                <div class="col" style="padding: 3px">
                                    <?php
                                    if ($v['id_situacao'] != 2) {
                                        ?>
                                        <button onclick="hist(<?= $v['id_serial'] ?>,<?= $v['fk_id_local'] ?>,'<?= $v['n_equipamento'] ?>','<?= $v['n_serial'] ?>')" class="<?= $v['btn'] ?>" style="text-align: center; width: 100%">
                                            N/S: <?= $v['n_serial'] ?>
                                        </button>
                                        <?php
                                    } else {
                                        ?>
                                        <div style="width: 100%" class="<?= $v['btn'] ?>">
                                            <table style="width: 100%" > 

                                                <tr>
                                                    <td>
                                                        <div onclick="hist(<?= $v['id_serial'] ?>,<?= $v['fk_id_local'] ?>,'<?= $v['n_equipamento'] ?>','<?= $v['n_serial'] ?>')" style="text-align: center; width: 100%">
                                                            N/S: <?= $v['n_serial'] ?>
                                                            <?= '<br />' . $v['n_pessoa'] . ' (' . $v['id_pessoa'] . ')' ?>
                                                        </div>
                                                    </td>
                                                    <td class="btn btn-dark" onclick="document.getElementById('f<?= $v['id_serial'] ?>').submit()" >
                                                        P
                                                        <form id="f<?= $v['id_serial'] ?>" target="frame" action="<?= HOME_URI ?>/recurso/termoEmprestimo" method="POST">
                                                            <?=
                                                            formErp::hidden([
                                                                'id_inst' => $id_inst,
                                                                'id_pessoa' => $v['id_pessoa'],
                                                                'id_serial' => $v['id_serial'],
                                                                'id_move' => $v['id_move'],
                                                                'n_serial' => $v['n_serial'],
                                                                'n_equipamento' => $v['n_equipamento'],
                                                                'id_equipamento' => $v['id_equipamento'],
                                                            ])
                                                            ?>
                                                        </form>       
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <br /><br />
                <?php
            }
        } else {
            ?>
            <div class="alert alert-warning">
                Não Existem Dados referente a esta consulta.
            </div>
            <?php
        }
        ?>
        <?php
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/recurso/def/histSerial.php" target="Frame" id="formFrame" method="POST">
    <input id="id_serial" type="hidden" name="id_serial" value="" />
    <input id="id_local" type="hidden" name="id_local" value="" />
    <?= formErp::hidden(['id_inst' => $id_inst,'id_equipamento' => $id_equipamento,'action' => 'relGeral']) ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="Frame" style="border: none; width: 100%; height: 80vh"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function hist(id,id_local,n_equipamento,n_serial) {
        document.getElementById('id_serial').value = id;
        document.getElementById('id_local').value = id_local;
        texto = '<div style="text-align: center; color: #7ed8f5;">'+n_equipamento+' - N/S: '+n_serial+'</div>'
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('')
    }
    function situacao(id_situacao) {
        document.getElementById('id_situacao').value = id_situacao;
        document.getElementById('situ').submit();
    }
</script>