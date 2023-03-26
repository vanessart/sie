<?php
if (!defined('ABSPATH'))
    exit;

$dados = sql::get(['pl_passelivre', 'pl_checklist'], '*', ['id_passelivre' => $id_passelivre], 'fetch');
$status = $model->pegastatus();
$con = filter_input(INPUT_POST, 'con', FILTER_SANITIZE_NUMBER_INT);
/*
  if (tool::id_nivel() == '8') {
  // unset($status[7]);
  unset($status[1]);
  } else {
  unset($status[7]);
  }
 * 
 */
$sm = sql::get('pl_parametro', 'salario_minimo', ['id_parametro' => 1], 'fetch')['salario_minimo'];

if (empty($dados)) {
    $cad = $model->cadastrackeck($id_passelivre);
    $dados = sql::get(['pl_passelivre', 'pl_checklist'], '*', ['id_passelivre' => $id_passelivre], 'fetch');
}
?>
<form method="POST">
    <?php
    $idcheck = $dados['id_checklist'];
    ?>
    <div class="body">
        <div class="row">
            <div class="col-12" style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 5pt">
                Confirmação dos Dados
            </div>  
        </div>
        <div class="row">
            <div class = "col-6" style="font-weight:bolder; font-size:12pt; text-align: center; color: black; border: double">
                Check List
            </div>
            <div class = "col-6" style="font-weight:bolder; font-size:12pt; text-align: center; color: black; border: double">
                Observação
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-6">
                <?= formErp::checkbox('1[idade_c]', 1, 'Idade: Maior de 12 anos', @$dados['idade_c']) ?>    
            </div>
            <div class="col-6">
                <?php
                if ($model->calculaidade($dados['dt_nasc']) < 12) {
                    ?>
                    <p style="color:red;font-weight: bolder">Não atendem aos requisitos do Decreto Nº 9.415 de 14 de Setembro de 2021.</p>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">   
                <?= formErp::checkbox('1[endereco_c]', 1, 'Comprovante de Endereço', @$dados['endereco_c']) ?>
            </div>
            <div class="col-6">
                <?php
                if (strcasecmp($dados['municipio'], 'BARUERI') != 0) {
                    ?>
                    <p style="color:red;font-weight: bolder">Não Munícipe.</p>
                    <?php
                } else {
                    
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= formErp::checkbox('1[distancia_c]', 1, 'Reside em um raio igual ou superior a 2 KM', @$dados['distancia_c']) ?>
            </div>
            <div class="col-6">
                <?php
                if (@$dados['distancia_escola'] < 2) {
                    ?>
                    <p style="color:red;font-weight: bolder">Reside a menos de 2 km da Escola.</p>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= formErp::checkbox('1[declaracao_escolar_c]', 1, 'Declaração de Escolaridade', @$dados['declaracao_escolar_c']) ?>
            </div>
            <div class="col-6">
                <?php echo '' ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= formErp::checkbox('1[renda_familiar_c]', 1, 'Renda Bruta Familiar igual ou inferior a 2 (dois) Salários Mínimos Nacionais', @$dados['renda_familiar_c']) ?>
            </div>
            <div class="col-6">
                <?php
                if (@$dados['renda_familiar'] > @$sm) {
                    ?>
                    <p style="color:red;font-weight: bolder">Não atendem aos requisitos do Decreto Nº 9.415 de 14 de Setembro de 2021.</p>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= formErp::checkbox('1[reg_mun_c]', 1, 'Cópia do Registro Municipal', @$dados['reg_mun_c']) ?>
            </div>
            <div class="col-6">
                <?php echo '' ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= formErp::checkbox('1[rg_c]', 1, 'Cópia do RG', @$dados['rg_c']) ?>
            </div>
            <div class="col-6">
                <?php echo '' ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= formErp::checkbox('1[cpf_c]', 1, 'Cópia do CPF', @$dados['cpf_c']) ?> 
            </div>
            <div class="col-6">
                <?php echo '' ?>
            </div>
        </div>    
        <!--
        <div class="row">
            <div class="col-6">
        <?= formErp::checkbox('1[certidao_nasc_c]', 1, 'Cópia do Certidão de Nascimento', @$dados['certidao_nasc_c']) ?>
            </div>
            <div class="col-6">
        <?php echo '' ?>
            </div>
        </div>   
        <div class="row">
            <div class="col-6">
        <?= formErp::checkbox('1[acompanhante_c]', 1, 'Acompanhante', @$dados['acompanhante_c']) ?> 
            </div>
            <div class="col-6">
        <?php
        if (strcasecmp($dados['acompanhante'], 'SIM') == 0) {
            ?>
                           <p style="color:red;font-weight: bolder"><?php echo @$dados['nome_acompanhante'] . '.' ?></p>
            <?php
        }
        ?>
            </div>
        </div>
        -->
        <br /> 
        <?php
        if (tool::id_nivel() == '10') {
            ?>
            <div class="row">
                <div class="col-6">
                    <?php
                    unset($status[7]);
                    echo formErp::select('fk_id_pl_status', $status, 'Status', $dados['fk_id_pl_status'], 1);
                    ?>
                </div>
                <div class="col-6">
                    <?php
                    if ($dados['fk_id_pl_status'] == 7) {
                        $obs_ = " Status Ativo";
                    } else {
                        $obs_ = "";
                    }
                    ?>
                    <p style="color:red;font-weight: bolder">Obs.: <?php echo $dados['observacao'] . $obs_ ?> </p>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-6">
                    <?= formErp::input(null, 'Status', $status[$dados['fk_id_pl_status']], ' readonly ') ?>
                </div>
                <div class="col-6">
                    <p style="color:red;font-weight: bolder">Obs.: <?php echo $dados['observacao'] ?> </p>
                </div>
            </div>
            <?php
        }
        ?>
        <br />
        <div class="row"> 
            <div class="col-6">
                <?=
                formErp::hidden([
                    '1[id_checklist]' => $idcheck,
                    'id_checklist' => $idcheck,
                    'fk_id_passelivre' => $id_passelivre,
                    '1[fk_id_passelivre]' => $id_passelivre
                ])
                . formErp::hiddenToken('pl_checklist', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
            <div class="col-6">
                <button  onclick="document.getElementById('rel').submit()" class="btn btn-info" type="button" >
                    Visualizar
                </button>
                <?php
                if (tool::id_nivel() == '10') {
                    ?>

                    <button  onclick="document.getElementById('retornar').submit()" class="btn btn-info" type="button" >
                        Voltar
                    </button>
                    <?php
                }
                ?>
            </div>
        </div>
        <input type="hidden" name="activeNav" value="3" />
        <input type="hidden" name="cie" value = <?php echo $cie ?> />
        <input type="hidden" name="id_passelivre" value = <?php echo $id_passelivre ?> />
        <input type="hidden" name="gravacheck" value = <?php echo 1 ?> />
    </div>
</form>

<form target="_blank" id="rel" action="<?= HOME_URI ?>/passelivre/pdf/pdfreq.php" method="POST">
    <input type="hidden" name="id_passelivre" value="<?= $id_passelivre ?>" />
</form>

<form id="retornar" action="<?= HOME_URI ?>/passelivre/deferimento" method="POST">
    <?php
    if ($con != 1) {
        ?>
        <input type="hidden" name="cie" value=<?php echo $cie ?> />
        <?php
    }
    ?>

</form>
<!-- activeNav -->
