<?php
if (!defined('ABSPATH'))
    exit;

//ob_start();

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

/*
  $pdf = new pdf();

  $pdf->headerAlt = 'Carteirinha Municipal';

  $pdf->mgt = 5;
  $pdf->mgh = 5;
  $pdf->mgr = 10;
  $pdf->mgl = 10;
  $pdf->mgf = 5;

 */
if ($_POST['sel']) {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $alunos[] = $v;
        }
    }
}

$dados = $model->pegaalunos($alunos, $id_turma);
?>
<body>
    <?php
    $topo = 20;
    $conta = 0;
    if (!empty($dados)) {
        foreach ($dados as $v) {
            ?>  
            <div style="position: absolute; top: <?= $topo ?>px; left: 20px; border-style: solid; border-width: 0.5px; padding: 20px; width: 655px; height: 200px">
                <div style="position: absolute; top: 25px; left: 20px">
                    <img src="<?= HOME_URI ?>/includes/images/CarteiraFrente.png" width="320px" height="200px" alt="Carteirinha"/> 
                </div>
                <div style="position: absolute; top: 25px; left: 370px">
                    <?php
                    $password_hash = new PasswordHash(8, FALSE);
                    $token = $password_hash->HashPassword($v['dt_nasc']);
                    $end = "https://portal.educ.net.br//ge/sed/pdf/declaracaoQr.php" . '?id=' . $v['id_pessoa'] . '&token=' . $token;
                    ?>
                    <img src="<?= HOME_URI ?>/includes/images/CarteiraVerso.png" width="320px" height="200px" alt="Carteirinha">
                    <div style="position: absolute; left: 30px; top: 10px">
                        <img src="<?= $model->fotoEnd($v['id_pessoa']) ?>" alt="foto" style="border-radius: 50%; width: 60px; height: 60px"/>                      
                    </div>
                    <div style="position: absolute; left: 250px; top: 10px">
                        <img src="<?= HOME_URI ?>/app/code/php/qr_img.php?d=<?= urlencode($end) ?>&.PNG" width="60" height="60"/>
                    </div>
                    <div style="position: absolute; left: 30px; top: 75px; font-size: 6pt; font-weight: bolder">
                        <?= $v['n_pessoa'] ?>
                    </div>
                    <div style="position: absolute; left: 30px; top: 85px; font-size: 6pt">
                        RSE: <?= $v['id_pessoa'] . '  RA: ' . $v['ra'] . '-' . $v['ra_dig'] . ' ' . $v['ra_uf'] ?>
                    </div>
                    <div style="position: absolute; left: 30px; top: 95px; font-size: 6pt">
                        Data Nascimento: <?= data::converteBr($v['dt_nasc']) ?>
                    </div>
                    <div style="position: absolute; left: 30px; top: 110px; font-size: 6pt; font-weight: bolder">
                        <?= $v['n_inst'] ?>
                    </div>
                    <div style="position: absolute; left: 30px; top: 120px; font-size: 6pt">
                        <?= $v['n_turma'] ?> <span style="color: red">Validade: 31/03/<?= $v['ano'] + 1 ?></span>                          
                    </div> 
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
<?php
//$pdf->exec();
?>