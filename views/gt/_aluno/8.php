<?php
$sql = "SELECT id_pessoa, trabalho_pai, end_trab_pai, trabalho_mae, end_trab_mae"
        . " FROM pessoa where id_pessoa = " . @$id_pessoa;

$query = $model->db->query($sql);
$trab = $query->fetch();
?>

<br /><br /><br />
<div class="panel panel-default">
    <div class="panel panel-heading" style="text-align: center; font-size: 14pt">
        Inscrição da Família - Situação de Trabalho
    </div>
    <div class="panel-body">
        <form method="POST">
            <table class="table table-bordered table-striped table-hover" style="font-size: 16px" >
                <tr>
                    <td style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
                        Dados do Pai
                    </td>
                    <td style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
                        Dados da Mãe
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: red">
                        Empregador
                    </td>
                    <td style="font-weight: bold; color: red">
                        Empregador
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="t_pai" type="text" name="12[trabalho_pai]" value="<?php echo $trab['trabalho_pai'] ?>" placeholder="Nome da Empresa(Pai)" />
                    </td>
                    <td>
                        <input id = "t_mae" type="text" name="12[trabalho_mae]" value="<?php echo $trab['trabalho_mae'] ?>" placeholder="Nome da Empresa(Mãe)"/>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: red">
                        Endereço de Trabalho
                    </td>
                    <td style="font-weight: bold; color: red">
                        Endereço de Trabalho
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id= "end_Pai" type="text" name="12[end_trab_pai]" value="<?php echo $trab['end_trab_pai'] ?>" placeholder="Endereço: Digite Rua, Nome da Rua, Bairro, Cidade e Sigla do Estado"/>
                    </td>
                    <td>
                        <input id = "end_mae" type="text" name="12[end_trab_mae]" value="<?php echo $trab['end_trab_mae'] ?>" placeholder="Endereço: Digite Rua, Nome da Rua, Bairro, Cidade e Sigla do Estado" />
                    </td>
                </tr>
            </table>

            <div style="text-align: center; width: 100%">
                <input type="hidden" name="novo" value="1" />
                <input type="hidden" name="aba" value="trab" />
                <input type="hidden" name="activeNav" value="8" />

                <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
                <input type="hidden" name="12[id_pessoa]" value="<?php echo @$id_pessoa ?>" />

                <?php echo DB::hiddenKey('pessoa', 'replace') ?>
                <button type="submit" class="btn btn-success">
                    Salvar
                </button>
            </div>     
        </form>  
    </div>
</div>