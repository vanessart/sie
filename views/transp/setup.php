<?php
if (!defined('ABSPATH'))
    exit;
$destino = unserialize(base64_decode ($model->_setup['destino']));

?>
<div class="fieldBody">
    <div class="fieldTop">
        Configurações
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-4">
                <?php echo form::select('1[aberto]', [1 => 'Sim', 0 => 'Não'], 'Permitir inclusão de Alunos', $model->_setup['aberto']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo form::select('1[troca]', [1 => 'Sim', 0 => 'Não'], 'Permitir Troca de Ônibus', $model->_setup['troca']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo form::selectNum('1[distancia]', [1, 10], 'Distâcia Máxima', $model->_setup['distancia']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row fieldBorder2">
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
                                <input <?php echo $destino[$v]['ativo']==1?'checked':'' ?> type="checkbox" name="1[destinoAtivo][<?php echo $v ?>]" value="1" />
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
                <?php echo DB::hiddenKey('transp_setup', 'replace') ?>
                <button class="btn btn-success">
                    Salvar
                </button>  
            </div>
        </div>
        <br /><br />
    </form>
</div>