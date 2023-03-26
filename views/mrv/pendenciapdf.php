<?php
ob_start();
?>
<head>
    <style>
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 2px;
        }
        .topo2{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 1px;
        }

    </style> 
</head>

<?php
$a = $model->verificastatus();

if ($a == 'Pendente') {
    $pend = $model->auditoriaitb();
} else {
    $pend = "OK";
}

$cie_escola = tool::cie();
$idinst = tool::id_inst();

If ($pend != "OK") {
    $d = $model->pegapendencias();

    ?>
    <div style="font-weight:bold; font-size:12pt; text-align: center">
        PENDÊNCIAS
    </div>
    <?php
    if (!empty($d)) {
        ?>
        <div style="font-weight:bold; font-size:10pt; text-align: center">
            Alunos Não Cadastrados - Favor Cadastrar com Não Há Interesse(Se for o Caso)
        </div>
        <?php
        foreach ($d as $v) {
            echo '<br />' . $v['id_pessoa'] . ' - ' . $v['n_pessoa'] . ' - ' . $v['codigo'];
        }
    }

    if (!empty($pend[$cie_escola])) {
        ?>
        <div style="font-weight:bold; font-size:10pt; text-align: center">
            Verificar as Notas dos Alunos
        </div>
        <?php
        foreach ($pend[$cie_escola] as $key => $w) {
            foreach ($pend[$cie_escola][$key]['Nota'] as $key2 => $w2) {
                echo '<br />' . $key . '-' . $pend[$cie_escola][$key]['Nota'][$key2];
            }
        }
    }

    if (!empty($pend[$idinst]['Categoria'])) {
        ?>
        <div style="font-weight:bold; font-size:10pt; text-align: center">
            Verificar Categoria
        </div>
        <?php
        foreach ($pend[$idinst]['Categoria'] as $key3 => $w3) {
            echo '<br />' . $key3 . '-' . $pend[$idinst]['Categoria'][$key3];
        }
    }

    if (!empty($pend[$idinst]['Inscricao'])) {
        ?>
        <div style="font-weight:bold; font-size:10pt; text-align: center">
            Verificar Inscrição
        </div>
        <?php
        echo $pend[$idinst]['Inscricao'];
        //  foreach ($pend[$idinst] as $w4) {
        //  echo '<br />' . $w4;
        //     }
        if ($pend[$idinst]['Inscricao'] == 'Existe aluno pendente no Menu Cadastro') {
            $ver = $model->verificaalunocadastro();
            if (!empty($ver)) {
                echo $ver;
            }
        }
    }
    if (!empty($pend[$idinst]['Morador'])) {
          foreach ($pend[$idinst]['Morador'] as $k => $v){
              echo $k . "==>" . $v;
          }
    }
} else {
    echo "OK";
}

tool::pdfSemRodape();
?>

