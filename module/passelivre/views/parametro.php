<?php
if (!defined('ABSPATH'))
    exit;

$dados = sql::get('pl_parametro', '*', NULL, 'fetch');

if (!empty($dados)) {
    $id_parametro = $dados['id_parametro'];
    $data_atz = Date('Y-m-d');
    ?>
    <form method="POST">
        <div class="body">
            <div class="row">
                <div class="col-12" style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 5pt">
                    Atualização de Dados
                </div>  
            </div>
            <br />
            <div class="row">
                <div class="col-1">
                    <?= formErp::input(NULL, 'ID', @$dados['id_parametro'], ' readonly ') ?>    
                </div>
                <div class="col-4">
                    <?= formErp::input('1[salario_minimo]', 'Salário Mínimos Nacional: R$ ', @$dados['salario_minimo'], ' required ', 'Digite somente número, casas decimais separados com .', 'double') ?>    
                </div>
                <div class="col-3">
                    <?= formErp::input('1[qtde_passe]', 'Quantidade de Passe Livre: ', @$dados['qtde_passe'], ' required ', 'Digite somente número', 'number') ?>                
                </div>
                <div class="col-4">
                    <?= formErp::input(NULL, 'Data da última atualização: ', data::converteBr($dados['dt_atualizacao']), ' readonly ') ?>                
                </div>
            </div>
            <br /><br />
            <div style="font-weight:bolder; font-size:12pt; text-align:left; color:red; padding: 5pt">
                Salário Múnimo: Digite somente número, casas decimais separados com . (Ponto).
            </div>
            <br /><br />
            <div style="text-align: center">
                <?=
                formErp::hidden([
                    '1[id_parametro]' => $id_parametro,
                    'id_parametro' => $id_parametro,
                    '1[dt_atualizacao]' => $data_atz,
                    'dt_atualizacao' => $data_atz
                ])
                . formErp::hiddenToken('pl_parametro', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </div>
    </form>
    <?php
}