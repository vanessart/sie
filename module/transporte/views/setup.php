<?php
if (!defined('ABSPATH'))
    exit;
$destino = unserialize(base64_decode($model->_setup['destino']));

?>
<div class="body">
    <div class="fieldTop">
        Configurações
    </div>
    <br /><br />
    <form method="POST" id="frmSalvar">
        <div class="row form-control-sm">
            <div class="col-sm-3">
                <?php echo formErp::select('1[aberto]', [1 => 'Sim', 0 => 'Não'], 'Permitir inclusão de Alunos', $model->_setup['aberto']) ?>
            </div>
            <div class="col-sm-4 diasSemana">
                <?php
                $dias[-1] = 'Todos os dias';
                for ($i=1; $i <= 7; $i++) {
                    $dias[$i] = dataErp::diasDaSemana($i-1);
                }

                echo formErp::select('ab_dia_sem', $dias, 'Em quais dias da Semana?', explode(',', $model->_setup['aberto_dia_semana']), null, null, 'multiple '. (empty($model->_setup['aberto']) ? 'disabled="disabled"' : ''));
                ?>
            </div>
        </div>
        <div class="row form-control-sm">
            <div class="col-sm-3">
                <?php echo formErp::select('1[abrir_falta]', [1 => 'Sim', 0 => 'Não'], 'Permitir inclusão de Faltas', $model->_setup['abrir_falta']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::selectNum('1[distancia]', [1, 10], 'Distância Máxima', $model->_setup['distancia']) ?>
            </div>
        </div>
        <div class="row form-control-sm">
            <div class="col-sm-3">
                <?php echo formErp::select('1[troca]', [1 => 'Sim', 0 => 'Não'], 'Permitir Troca de Ônibus', $model->_setup['troca']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::selectNum('1[distancia_min]', [1, 10], 'Distância Mínima', $model->_setup['distancia_min']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row fieldBorder2 form-control-sm">
            <div class="col-sm-3">
                Destino - Transporte Adaptado
            </div>
            <div class="col-sm-9">
                <table class="table table-bordered table-hover table-responsive table-striped">
                    <tr>
                        <td>
                            ID
                        </td>
                        <td>
                            Ativo
                        </td>
                        <td>
                            Destino
                        </td>
                    </tr>   
                    <?php
                    foreach (range(1, 5) as $v) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $v ?>
                            </td>
                            <td>
                                <input <?php echo isset($destino[$v]) && $destino[$v]['ativo']==1?'checked':'' ?> type="checkbox" name="1[destinoAtivo][<?php echo $v ?>]" value="1" />
                            </td>
                            <td>
                                <input style="width: 100%" type="text" name="1[destinoNome][<?php echo $v ?>]" value="<?php echo @$destino[$v]['nome'] ?>" />
                                
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div style="text-align: center">
                <input type="hidden" name="1[id_st]" value="1" />
                <input type="hidden" name="1[aberto_dia_semana]" id="aberto_dia_semana" value="" />
                <?php echo formErp::hiddenToken('transporte_setup', 'ireplace') ?>
                <button class="btn btn-success">
                    Salvar
                </button>  
            </div>
        </div>
        <br /><br />
    </form>
</div>
<script type="text/javascript">
    window.onload = function(){
        const selectElement = document.getElementById('aberto_');
        const selectElementDias = document.getElementById('ab_dia_sem_');

        selectElement.addEventListener('change', (event) => {
            if (event.target.value == '1'){
                selectElementDias.disabled = false;
            } else {
                selectElementDias.disabled = true;
            }
            $('.diasSemana .selectpicker').selectpicker('refresh');
        });

        selectElementDias.addEventListener('change', (event) => {
            let todos = false;
            $(event.target.selectedOptions).each(function(e, i){
                if (i.value == '-1'){
                    $(selectElementDias.options).each(function(e, i){
                        if (i.value != '' && i.value != '-1') {
                            i.selected = false;
                        }
                    });
                    $('.diasSemana .selectpicker').selectpicker('refresh');
                    return;
                }
            });
        });
    }

    $('#frmSalvar').submit(function(event){
        let v = '';
        if (document.getElementById('aberto_').value == '1') {
            const el = $(document.getElementById('ab_dia_sem_').selectedOptions);

            if (el.length == 0){
                alert('Selecione o(s) dia(s) da semana');
                return false;
            }
            let vg = '';
            el.each(function(e, i){
                v += vg + i.value;
                vg = ',';
            });
        }
        
        document.getElementById('aberto_dia_semana').value = v;
    });

</script>