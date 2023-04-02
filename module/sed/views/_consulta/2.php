<?php
$codClasse = filter_input(INPUT_POST, 'codigo', FILTER_UNSAFE_RAW);
if ($codClasse) {
    $dados = rest::formacaoClasse($codClasse);
    ##################            
?>
  <pre>   
    <?php
      print_r($dados);
    ?>
  </pre>
<?php
###################
}
?>
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::input('codigo', 'codigo', $codClasse) ?>
        </div>
        <div class="col" style="padding: left 46,7px;">
            <?=
            formErp::hidden([
                'activeNav' => 2
            ])
            . formErp::button('Buscar');
            ?>


        </div>

    </div>
</form>
<br />
<?php
if (!empty($dados)) {
    ?>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold">
                Dados Pessoais
            </td>
        </tr>
        <?php
        foreach ($dados['outDadosPessoais'] as $k => $v) {
            if ($v) {
                ?>
                <tr>
                    <td>
                        <?= str_replace('out', '', $k) ?>
                        <?= $v ?>
                    </td>
                    
                </tr>
                <?php
            }
        }
        ?>
        <tr>
            <td>
                Certidão de Nascimento
            </td>
            <td>
                <?= implode('-', $dados['outCertidaoNova']) ?>
            </td>
        </tr>
    </table>
    <?php
}
?>

