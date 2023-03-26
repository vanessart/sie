<?php
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if(empty($id_pessoa)){
    $id_pessoa = tool::id_pessoa();
}
$prof = $model->devolutiva($id_pessoa);
if(!empty($prof)){
ob_start();
?>
<style>
    .tb{
        width: 100%;
    }
    .tb td{
        padding: 4px;
        border: #000000 solid 1px;
    }
</style>
<div style="text-align: center; font-weight: bold; font-size: 20px">
    Devolutiva Giz de Ouro <?php echo date("Y") ?>
</div>
<br /><br />
<table class="tb">
    <tr>
        <td>
            Projeto
        </td>
        <td>
            <?php echo stripslashes($prof['titulo']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Autor<?php echo @$prof['sexo'] == "F" ? 'a' : '' ?> 
        </td>
        <td>
            <?php echo ucwords(strtolower(stripslashes($prof['n_pessoa']))) ?> (Matr. <?php echo $prof['rm'] ?>)
        </td>
    </tr>
    <tr>
        <td>
            Escola
        </td>
        <td>
            <?php echo stripslashes($prof['n_inst']) ?> 
        </td>
    </tr>
    <tr>
        <td>
            Categoria
        </td>
        <td>
            <?php echo stripslashes($prof['n_cate']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Modalidade
        </td>
        <td>
            <?php echo stripslashes($prof['n_mod']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Classificação
        </td>
        <td>
            <?php echo $prof['class'] ?>º Lugar
        </td>
    </tr>
</table>
<br /><br />
<table class="tb">
    <tr>
        <td>
            Critérios
        </td>
        <td>
            Evidências
        </td>
    </tr>
    <tr>
        <td>
            Projeto
        </td>
        <td>
            <?php echo stripslashes($prof['just_projeto']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Portfolio
        </td>
        <td>
            <?php echo stripslashes($prof['just_portfolio']) ?>
        </td>
    </tr>
</table>
<br /><br />
<?php
$cont = 1;
foreach ($model->quest() as $k => $v) {
    ?>
    <table style="page-break-inside: auto" class="tb">
        <tr>
            <td colspan="3">
                <?php echo ucwords($k) ?>
            </td>
        </tr>
        <tr>
            <?php
            if ($k != 'entrevista') {
                ?>
                <td style="width: 16%">
                    Critérios
                </td>
                <?php
                $colspan = NULL;
            } else {
               $colspan = "colspan=\"3\""; 
            }
            ?>
            <td style="width: 16%">
                Nível Aferido
            </td>
            <?php
            if ($k == 'entrevista') {
                $w = '76%';
            } else {
                $w = '60%';
            }
            ?>
            <td <?php echo $colspan ?> style="width: <?php echo $w ?>">
                Evidências
            </td>
        </tr>
        <?php
        foreach ($v as $ki => $i) {
            ?>
            <tr>
                <?php
                if ($k != 'entrevista') {
                    ?>
                    <td>
                        <?php echo $i ?>
                    </td>
                    <?php
                }
                ?>
                <td>
                    <?php echo stripslashes($prof['eixo' . $ki]) ?>
                </td>
                <td>
                    <?php echo stripslashes($prof['eixo_t' . $ki]) ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br /><br />
    <?php
    if (in_array($cont++, [7])) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    }
}
?>
<br /><br />
<table class="tb">
    <tr>
        <td>
            Observações Finais 
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $prof['obs'] ?>
        </td>
    </tr>
</table>

<?php
tool::pdfGiz('L');
} else {
    echo 'Devolutiva não encontrada';    
}

