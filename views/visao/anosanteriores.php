<?php
$periodo = filter_input(INPUT_POST, 'periodo_letivo', FILTER_UNSAFE_RAW);
$idinst = filter_input(INPUT_POST, 'fk_id_inst', FILTER_SANITIZE_NUMBER_INT);
$tipoteste = filter_input(INPUT_POST, 'teste', FILTER_UNSAFE_RAW);
$turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

$tipo = [0 => 'Selecionar', 1 => 'Computador', 2 => 'Papel'];
$comp = [1 => 'NS', 2 => '0,06', 3 => '0,15', 4 => '0,3', 5 => '0,4', 6 => '0,5', 7 => '0,6', 8 => '0,8', 9 => '1', 10 => '1,2'];
$papel = [1 => 'NS', 2 => '0,1', 3 => '0,15', 4 => '0,2', 5 => '0,3', 6 => '0,4', 7 => '0,5', 8 => '0,6', 9 => '0,7', 10 => '0,8', 11 => '0,9', 12 => '1'];
$sin = [1 => 'Não Possui', 2 => 'Ardência', 3 => 'Coceira', 4 => 'Fadiga Visual', 5 => 'Franzir a Testa', 6 => 'Lacrijamento', 7 => 'Tontura', 8 => 'Outros'];

if (!empty($tipoteste)) {
    $anosanteriores = $model->pegaanovisao();
}
if (!empty($periodo)) {
    $escola = $model->pegaescolavisao($periodo);
}
if (!empty($idinst)) {
    $classe = $model->pegaclassevisaosec($periodo, $idinst);
}
if (!empty($turma)) {
    $dados = $model->pegadadosvisaosec($periodo, $idinst, $turma, $tipoteste);
}
?>

<div style="width: 100%; margin-left: 5px; margin-right: 5px" class="Body">
    <br />
    <div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
        Consulta
    </div>
    <br />

    <div class="row">
        <div class="col-md-2">
            <?= formErp::select('teste', @$tipo, 'Tipo Teste ', $tipoteste, 1) ?>
        </div>

        <div class="col-md-2">
            <?php
            if (!empty($tipoteste)) {
                echo formErp::select('periodo_letivo', @$anosanteriores, 'Período Letivo ', @$periodo, 1, ['teste' => @$tipoteste]);
            }
            ?>   
        </div>
        <div class="col-md-5">
            <?php
            if (!empty($periodo)) {
                echo formErp::select('fk_id_inst', @$escola, 'Nome Escola ', @$idinst, 1, ['teste' => @$tipoteste, 'periodo_letivo' => @$periodo]);
            }
            ?>
        </div>
        <div class="col-md-2">
            <?php
            if (!empty($idinst)) {
                echo formErp::select('id_turma', @$classe, 'Turma ', $turma, 1, ['teste' => @$tipoteste, 'periodo_letivo' => @$periodo, 'fk_id_inst' => @$idinst]);
            }
            ?>
        </div>
        <div class="col-md-1">
            <?php
            if (!empty($dados)) {
                if ($tipoteste == 1) {
                    $rel = 'testepdfresu';
                } else {
                    $rel = 'retestepdfresu';
                }
                ?>
                <form target = "_blank" action = "<?php echo HOME_URI ?>/visao/<?php echo $rel ?>" method = "POST">
                    <input type="hidden" name="rel_turma" value="<?php echo $turma ?>" />
                    <input type="hidden" name="rel_escola" value="<?php echo $idescola ?>" />

                    <button class = "btn btn-info">
                        Imprimir
                    </button>
                </form>

                <?php
            } else {
                ?>
                <input class="btn btn-default" type="submit" value="Imprimir" name="imprimir" />
                <?php
            }
            ?>

        </div>
    </div>  
    <br />
    <?php
    if (!empty($dados)) {
        if ($tipoteste == '1') {

            foreach ($dados as $k => $v) {
                @$dados[$k]['olho_direito'] = $comp[$v['olho_direito']];
                @$dados[$k]['olho_esquerdo'] = $comp[$v['olho_esquerdo']];
                @$dados[$k]['fk_id_sinais'] = $sin[$v['fk_id_sinais']];
            }

            $form['array'] = $dados;
            $form['fields'] = [
                'ch' => 'chamada',
                'Aluno' => 'n_pessoa',
                'Data Nasc.' => 'dt_nasc',
                'Óculos' => 'oculos',
                'OD' => 'olho_direito',
                'OE' => 'olho_esquerdo',
                'Sinais' => 'fk_id_sinais',
                'Usou Óculos - Lente' => 'usouoculoslentes',
                'Situação' => 'situacao_teste'
            ];
        } else {

            foreach ($dados as $k => $v) {
                @$dados[$k]['reteste_direito'] = $papel[$v['reteste_direito']];
                @$dados[$k]['reteste_esquerdo'] = $papel[$v['reteste_esquerdo']];
                @$dados[$k]['reteste_sinais'] = $sin[$v['reteste_sinais']];
                if ($v['reteste_sit'] == 'FALHA') {
                    @$dados[$k]['acompanhamento'] = formulario::submit('Acompanhamento', null, $v, HOME_URI . '/visao/acompanha');
                } else {
                    @$dados[$k]['acompanhamento'] = '<button type="button">Acompanhamento</button>';
                }
            }

            $form['array'] = $dados;
            $form['fields'] = [
                'ch' => 'chamada',
                'Aluno' => 'n_pessoa',
                'Data Nasc.' => 'dt_nasc',
                'Óculos' => 'reteste_oculos',
                'OD' => 'reteste_direito',
                'OE' => 'reteste_esquerdo',
                'Sinais' => 'reteste_sinais',
                'Situação' => 'reteste_sit',
                '||1' => 'acompanhamento'
            ];
        }
        tool::relatSimples($form);
    }
    ?>
</div>

