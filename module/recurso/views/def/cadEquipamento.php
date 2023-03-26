<?php
if (!defined('ABSPATH'))
    exit;
$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$equipamento = sql::get('recurso_equipamento', 'id_equipamento, n_equipamento', ['id_equipamento' => $id_equipamento],'fetch');
$categorias = sql::idNome('recurso_cate_equipamento', ['at_cate' => 1]);
$escolas['-1'] = 'Mesmo local de Empréstimo';
$escolas['13'] = 'Secretaria de Educação';
if (empty($equipamento['fk_id_inst_devolve_geral'])) {
   $equipamento['fk_id_inst_devolve_geral'] = -1; 
}
if (empty($equipamento['fk_id_inst_devolve_prof'])) {
   $equipamento['fk_id_inst_devolve_prof'] = 13; 
}
if (empty($equipamento['prazo_max'])) {
   $equipamento['prazo_max'] = 12; 
}
if (empty($equipamento['fk_id_cate'])) {
   $equipamento['fk_id_cate'] = $_SESSION['tmp']['id_cate'];; 
}
$prazo_max = [
'1' => '1 mês',
'2' => '2 meses',
'3' => '3 meses',
'4' => '4 meses',
'5' => '5 meses',
'5' => '5 meses',
'7' => '7 meses',
'8' => '8 meses',
'9' => '9 meses',
'10' => '10 meses',
'11' => '11 meses',
'12' => '12 meses',
]
    ?>
<div class="body">
    <br><br>
    <form target="_parent" action="<?= HOME_URI ?>/recurso/cadEquipamento" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_cate]', $categorias, 'Categoria', @$equipamento['fk_id_cate'], null, null,' required ') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
            <?= formErp::input('1[n_equipamento]', 'Modelo/Lote', @$equipamento['n_equipamento'], 'required') ?>
            </div>
        </div>
        <br> 
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descricao]', @$equipamento['descricao'], 'Descrição') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4">
                <span style='font-size: 15px; font-weight: bold'>Este equipamento poderá ser emprestado ?</span>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <label for="_empresta1" style='font-size: 15px; color: black; cursor:pointer;'><input type="radio" name="1[empresta]" value="1" class="emprestEquip" id="_empresta1" /> Sim</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <label for="_empresta2" style='font-size: 15px; color: black; cursor:pointer;'><input type="radio" name="1[empresta]" value="0" checked class="emprestEquip" id="_empresta2" /> Não</label>
                </div>
            </div>
        </div>
        <br>
        <div class="row viewEmprestaEquip" style="display: none;">
            <?php   
            if (!empty($escolas)) {?>
                <div class="col-8 text-center">
                <?php
                if (!empty($escolas)) {
                    echo formErp::select('1[fk_id_inst_devolve_prof]', $escolas, 'Local de Devolução para empréstimos em comodato', @$equipamento['fk_id_inst_devolve_prof']);
                }
                ?>
                </div>
            <?php 
        }?>
        </div>
        <br><br>
        <div class="row viewEmprestaEquip" style="display: none;">
            <?php   
            if (!empty($escolas)) {?>
                <div class="col-8 text-center">
                <?php
                if (!empty($escolas)) {
                    echo formErp::select('1[fk_id_inst_devolve_geral]', $escolas, 'Local de Devolução para empréstimos com prazo', @$equipamento['fk_id_inst_devolve_geral']);
                }
                ?>
                </div>
                <?php 
            }?>
        </div>
        <br><br>
        <div class="row viewEmprestaEquip" style="display: none;">
            <div class="col">
                <?= formErp::select('1[prazo_max]', $prazo_max, 'Prazo Máximo de Empréstimo', @$equipamento['prazo_max']); ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col" align="center">
                <?php
                if (!empty($id_equipamento)) {
                   echo formErp::hidden([   
                    '1[id_equipamento]' => $id_equipamento,
                    ]); 
                }
                ?>
                <?=
                    /*formErp::hidden([   
                    '1[fk_id_inst]' => @$id_inst,    
                    ])
                    .*/ formErp::hiddenToken('recurso_equipamento', 'ireplace')
                    . formErp::button('Salvar');
                ?>
            </div>
        </div>
        <br>
    </form>
</div>
<script type="text/javascript">
    jQuery(function($){
        $('.emprestEquip').click(function(){
            if ($(this).val() == 1){
                $('.viewEmprestaEquip').show();
            } else {
                $('.viewEmprestaEquip').hide();
            }
        });
    });
</script>