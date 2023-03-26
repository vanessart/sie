<?php
if (!defined('ABSPATH'))
    exit;
$instancia = sql::idNome('instancia');
$equipamentosBotao = $model->equipamentoGet(null,$id_inst,null,$id_equipamento);
//die("oi2");
if (!empty($equipamentosBotao)) {
    $totalBotao = $model->getTotaisSerial($equipamentosBotao);
}
$campos =[
    'n_pessoa' => $n_pessoa,
    'cpf' => $cpf,
    'rm' => $rm,
    'email' => $email,
];

$equipamentos = $model->equipamentoGet($id_situacao,$id_inst,$id_serial,$id_equipamento,1,null,$n_pessoa,$cpf,$rm,$email,$n_serial);
if (!empty($equipamentos)) {
    foreach ($equipamentos as $num => $local) {
        foreach ($local as $n_serial => $v) {
            if (!empty($v['id_situacao'])) {
                if ($v['id_situacao'] == 8) {
                    $btn[8] = 'btn btn-outline-danger';
                    @$sitTotal[8]++;
                }elseif ($v['id_situacao'] == 1) {
                    $btn[1] = 'btn btn-success';
                    @$sitTotal[1]++;
                }elseif ($v['id_situacao'] == 2) {
                    $btn[2] = 'btn btn-info';
                    @$sitTotal[2]++;
                } elseif ($v['id_situacao'] == 3) {
                    $btn[3] = 'btn btn-primary';
                    @$sitTotal[3]++;
                }elseif ($v['id_situacao'] == 4) {
                    $btn[4] = 'btn btn-danger';
                    @$sitTotal[4]++;
                }elseif ($v['id_situacao'] == 5) {
                    $btn[5] = 'btn btn-outline-secondary';
                    @$sitTotal[5]++; 
                } elseif ($v['id_situacao'] == 6) {
                    $btn[6] = 'btn btn-warning';
                    @$sitTotal[6]++; 
                } elseif ($v['id_situacao'] == 7) {
                    $btn[7] = 'btn btn-warning';
                    @$sitTotal[7]++; 
                } 
            } else {
                $btn[0] = 'btn btn-outline-danger';
                @$sitTotal[0]++;
            }
        }
    }
}
$sitTotal = !empty($sitTotal) ? $sitTotal : [];
$totalBotao = !empty($totalBotao) ? $totalBotao : [];
if ($equipamentos) {?>
    <div class="border">
        <div class="row">
            <form name="situ" method="POST">
                <div class="row">
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>1 ? "-outline" : "" ?>-success" style="width: 100%" onclick="situacao(1);">
                            Regular (<?= intval(@$totalBotao[1]) ?>)
                        </button>
                    </div>
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>3 ? "-outline" : "" ?>-primary" style="width: 100%" onclick="situacao(3);">
                            Em Manutenção (<?= intval(@$totalBotao[3]) ?>)
                        </button>
                    </div>
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>7 ? "-outline" : "" ?>-primary" style="width: 100%" onclick="situacao(7);">
                            Danificado (<?= intval(@$totalBotao[7]) ?>)
                        </button>
                    </div>
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
                    <div class="col">
                        <button  class="btn btn<?= $id_situacao<>5 ? "-outline" : "" ?>-secondary" style="width: 100%" onclick="situacao(5);">
                            Inservível (<?= intval(@$totalBotao[5]) ?>)
                        </button>
                    </div>
                </div>
                <?= formErp::hidden(['activeNav' => @$aba]) ?>
                <?= formErp::hidden($hidden) ?>
                <?= formErp::hidden($campos) ?>
                <input id="id_situacao_form" type="hidden" name="id_situacao_form" value="" />
            </form>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($equipamentos)) {
        ksort($equipamentos);
        if (!empty($equipamentos[13])) {
            $se = $equipamentos[13];
            unset($equipamentos[13]);
            $equipamentos[13] = $se;
        }
        foreach ($equipamentos as $k => $v) {?>
            <div class="border">
                <?= @$instancia[$k]; ?>
                <br /><br />
                <?php
                foreach ($v as $c) {
                    ?>
                    <div style="float: left; width: 34px; padding: 2px">
                        <button onclick="hist(<?= $c['id_serial'] ?>,<?= $c['fk_id_local'] ?>,<?= $c['fk_id_inst'] ?>,'<?= $c['n_equipamento'] ?>','<?= $c['n_serial'] ?>')" class="<?= $btn[$c['id_situacao']] ?>" style="width: 10px" data-toggle="tooltip" data-placement="top" title="N/S: <?= $c['n_serial'] ?>" >
                        </button>
                    </div>
                    <?php
                }
                ?>
                <div style="clear: left"></div>
            </div>
            <br /><br />
            <?php
        }
    }
} else {
    ?>
    <br /><br />
    <div class="alert alert-danger" style="text-align: center; width:  300px; margin: 0 auto">
        Não Encontrado
    </div>
    <?php
}