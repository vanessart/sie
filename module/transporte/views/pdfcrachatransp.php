<?php
if (!defined('ABSPATH'))
    exit;
?>
<head>
    <style>
        .bloco {
            position: absolute;
            left: 392px;
            top: 45px;
            border-style: solid;
            border-width: 0.5px;
            width: 265px;
            height: 100px;
            font-size: 8px;
            padding: 5px;
            border-radius: 10px;
        }

    </style>
</head>
<?php
if ($_POST['sel']) {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $alunos[] = $v;
        }
    }
}

$dados = $model->pegaalunoscarteirinha($alunos);
?>
<body>
    <?php
    $topo = 20;
    $conta = 0;

    if (!empty($dados)) {
        foreach ($dados as $v) {
            $alu = new ng_aluno($v['id_pessoa']);
            $responsavel = $alu->responsaveis();
            ?>  
            <div style="position: absolute; top: <?= $topo ?>px; left: 20px; border-style: solid; border-width: 0.5px; padding: 20px; width: 655px; height: 200px">
                <div style="position: absolute; top: 25px; left: 20px">
                    <img src="<?= HOME_URI ?>/includes/images/CarteiraFrente.png" width="320px" height="200px" alt="Carteirinha"/> 
                </div>
                <div style="position: absolute; top: 25px; left: 370px">
                    <img src="<?= HOME_URI ?>/includes/images/CarteiraVerso.png" width="320px" height="200px" alt="Carteirinha">
                </div>
                <div style="position:absolute; top: 175px; left: 395px; background-color: white; width: 280px; height: 30px; border: solid 1px black; border-radius: 10px">
                    <div style="font-size: 7px; font-weight: bold; text-align: center; padding-top: 10px ">
                        <?= $v['n_inst'] ?>
                    </div>           
                </div>
                <div style="position: absolute; top: 90px; left: 54px; width: 280px; height:60px; background-color: white">
                    <div style="position:absolute; left: 110px">
                        <img src="<?= $model->fotoEnd($v['id_pessoa']) ?>" alt="foto" style="border-radius: 50%; width: 60px; height: 60px"/>
                    </div>                                    
                </div>
                <div style="position: absolute; top: 155px; left: 54px; width: 280px; font-size: 8px; font-weight: bold; text-align: center">
                    <?= $v['n_pessoa'] ?>
                    <br/>
                    <span style="font-size: 7px">
                        <?= $v['n_turma'] ?> RSE: <?= $v['id_pessoa'] ?>  DN: <?= dataErp::converteBr($v['dt_nasc']) ?> 
                    </span>
                </div>
                <div style="position:absolute; top: 30px; left: 392px; width: 267px; height: 5px; color: red; font-size: 8px; font-weight: bold; border: solid 1px black; border-radius: 10px; padding: 4px">
                    Linha: <?= $v['n_li'] ?>
                </div>
                <div class="bloco">
                    <?php
                    $topo2 = 0;
                    $tipoResp = ['Mãe' => 'Mãe', 'Pai' => 'Pai', 'Responsável Legal' => 'Resp. Legal', 'Parente' => 'Parente', 'Transporte Escolar' => 'Transp. Escolar', 'Conhecido' => 'Conhecido'];
                    if (!empty($responsavel)) {
                        foreach ($responsavel as $w) {
                            if ($w['retirada'] == 1) {
                                $tel = current($w['tel']);
                                if ($w['n_rt'] == 'Mãe' || $w['n_rt'] == 'Pai') {
                                    ?>
                                    <div style="position: absolute; left: 10px; top: <?= $topo2 ?>px">
                                        <?= (empty($w['n_rt'])) ? '-' : $tipoResp[$w['n_rt']] ?>
                                    </div>
                                    <div style="position: absolute; left: 60px; top: <?= $topo2 ?>px">
                                        <?= (empty($tel['num'])) ? '-' : $tel['num'] ?>
                                    </div>
                                    <div style="position: absolute; left: 110px; top: <?= $topo2 ?>px">
                                        <?php
                                        $caracter = strlen($w['n_pessoa']);
                                        echo ($caracter > 20) ? $nome = $model->nomeultimo($w['n_pessoa']) : $w['n_pessoa'];
                                        ?>
                                    </div>
                                    <?php
                                    $topo2 = $topo2 + 10;
                                }
                            }
                        }
                        ?>
                        <div style="position: absolute; left: 10px; top: <?= $topo2 + 10 ?>px">
                            <?= 'Motorista' ?>
                        </div>
                        <div style="position: absolute; left: 60px; top: <?= $topo2 + 10 ?>px">
                            <?= $v['tel1'] ?>
                        </div>
                        <div style="position: absolute; left: 110px; top: <?= $topo2 + 10 ?>px">
                            <?php
                            $caracter = strlen($v['motorista']);
                            echo ($caracter > 25) ? $model->nomeultimo($v['motorista']) : $v['motorista'];
                            ?>
                        </div>
                        <div style="position: absolute; left: 10px; top: <?= $topo2 + 20 ?>px">
                            <?= 'Monitor(a)' ?>
                        </div>
                        <div style="position: absolute; left: 60px; top: <?= $topo2 + 20 ?>px">
                            <?= $v['tel2'] ?>
                        </div>
                        <div style="position: absolute; left: 110px; top: <?= $topo2 + 20 ?>px">
                            <?php
                            $caracter = strlen($v['monitor']);
                            echo ($caracter > 25) ? $model->nomeultimo($v['monitor']) : $v['monitor'];
                            ?>
                        </div>

                        <?php
                    }
                    ?>
                </div>
                <?php
                $topo = $topo + 261;
                if ($conta <= 4) {
                    $conta = $conta++;
                } else {
                    ?>
                    <div style="page-break-after: always"></div>
                    <?php
                    $conta = 0;
                }
                ?>
            </div>
            <?php
        }
    }
    ?>
</body>
