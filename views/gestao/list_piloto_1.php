<div class="fieldBody">
################
<table style="width: 100%">
    <tr>
        <td>
            <img style="width: 80px" src="'. HOME_URI .'/views/_images/brasao.png"/>
            
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
            <div>
                <div style="font-size: 15px; font-weight: bold">Prefeitura Municipal de Barueri<br>SE - Secretaria de Educação</div>
                <div style="font-size: 11px">'. $this->_nome .'<br>'. $this->_email .'<br>'. $this->enderecoEstruturado(1) .'</div>
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width: 200px>
            <img style="width: 200px" src="'. HOME_URI .'/views/_images/logo.png"/>
        </td>
    </tr>
</table>
##################
    <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapiloto" name="codclasse" method="POST">

        <?php
        $ano = date('Y');
        $wcodclasse = sql::get('ge_turmas', 'codigo,id_turma', ['fk_id_inst' => tool::id_inst(), 'periodo_letivo' => $ano, '>' => 'codigo']);
        ?>
        <br /><br /><br /><br />
        <div class="">
            <div class="row" style="padding-left: 145px">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-addon">
                            Selecione Código da Classe:
                        </div>
                        <div>
                            <select name="twj">
                                <option></option>
                                <?php
                                foreach ($wcodclasse as $a) {
                                    ?>
                                    <option <?php echo @$_POST['turma'] == $a['id_turma'] ? 'selected' : '' ?> value="<?php echo $a['id_turma'] ?>">
                                        <?php echo $a ['codigo'] ?>
                                    </option>
                                    <?php
                                }
                                ?>

                            </select>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <button style="width: 41%; font-weight: 900" type="submit" class="art-button">
                        Lista Piloto
                    </button>
                </div>
            </div>
        </div>

    </form>


</div>
