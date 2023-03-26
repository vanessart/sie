
<?php
  $p = sql::get('ge_periodo_letivo', '*',['at_pl'=>1]);
  foreach ($p as $v){
      @$letivos .= "'".$v['n_pl']."', ";
  }
  @$letivos = substr($letivos, 0, -2);
  
if (empty($_POST['finalizado'])) {
    $finalizado = " and (status_transf != 'Finalizado' "
            . " AND status_transf != 'Cancelado') ";
}
if (@$_POST['rectrasnf'] == "Matricular") {

    $cod = $model->pegacodclasse($_POST['cod_inst_d'], $_POST['turmaid']);
    tool::modalInicio();
    ?> 
    <form name="codturma" method="POST">
        <div class="input-group">
            <div class="input-group-addon">
                Selecione Código da Classe:
            </div>
            <div>
                <select required name="opt">
                    <option></option>
                    <?php
                    foreach ($cod as $k => $a) {
                        ?>                                         
                        <option <?php echo $k == @$_POST['opt'] ? 'selected' : '' ?> value ="<?php echo $k ?>">
                            <?php echo $a ?>
                        </option>                                        
                        <?php
                    }
                    ?>
                </select>

            </div>

        </div>
        <br /><br />
        <div>
            <?php formulario::input('motivo', 'Motivo da Transferência', NULL, empty($_POST['motivo']) ? 'Mudança de endereço' : '', ' required ') ?>
        </div>
        <br /><br /><br />
        <div class="text-center">
            <?php echo DB::hiddenKey('transfereEscola'); ?>
            <input type="hidden" name="transfereEscola" value="1" />
            <input type="hidden" name="id_transf" value="<?php echo $_POST['id_transf'] ?>" />
            <input style="width: 22%; font-weight: 900" class="art-button" type="submit" name="confirmar" value="Confirmar" />
        </div>
    </form>
    <?php
    tool::modalFim();
}
?>
<div class="fieldBody">
    <div class="row">
        <div class="col-lg-8 text-center">
            Solicitações de Transferências Enviadas e Recebidas
        </div>
        <div class="col-lg-4 text-center">
            <form method="POST">
                <?php
                if (empty($_POST['finalizado'])) {
                    ?>
                    <input class="btn btn-primary" type="submit" name="finalizado" value="Visualizar Finalizados e Cancelados" />
                    <?php
                } else {
                    ?>
                    <input class="btn btn-danger" type="submit"  value="Não Visualizar Finalizados e Cancelados" />
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            Transferências Enviadas
            <div style="width: 100%; overflow: auto; height: 180px">
                <?php
                $sql = "select * from ge_transf_aluno "
                        . " where cod_inst_o = " . tool::id_inst()
                      //  . " and periodo_letivo in  (".$letivos.") "
                        . @$finalizado
                        . " order by id_transf";
                $query = $model->db->query($sql);
                $emit = $query->fetchAll();
                foreach ($emit as $k => $v) {
                    if ($v['status_transf'] == 'Aprovado') {
                        $emit[$k]['libera'] = formulario::submit('Liberar Matrícula', NULL, ['idtra' => $v['id_transf'], 'liberar' => 'Liberar Matricula']);
                    }
                    if ($v['status_transf'] <> 'Cancelado' && $v['status_transf'] <> 'Finalizado') {
                        $emit[$k]['cancela'] = formulario::submit('Cancelar', NULL, ['idtra' => $v['id_transf'], 'del' => 'Cancelar']);
                    }
                }
                $form['array'] = $emit;
                $form['fields'] = [
                    'Protocolo' => 'id_transf',
                    'RSE' => 'fk_id_pessoa',
                    'Cód. Classe' => 'codigo_classe_o',
                    'Nome Aluno' => 'n_pessoa',
                    'Nome Escola' => 'n_escola_destino',
                    'Data Solicitação' => 'dt_solicitacao',
                    'Status Transferência' => 'status_transf',
                    '||2' => 'cancela',
                    '||1' => 'libera'
                ];
                tool::relatSimples($form)
                ?>
            </div>
        </div>

        <div class="col-lg-12">
            Transferências Recebidas
            <div style="width: 100%; overflow: auto; height: 180px">
                <?php
                $ano = date('Y');
                $sql = "select * from ge_transf_aluno "
                        . " where cod_inst_d = " . tool::id_inst()
                       //  . " and periodo_letivo in  (".$letivos.") "
                        . @$finalizado
                        . " order by id_transf";
                $query = $model->db->query($sql);
                $transf = $query->fetchAll();
                foreach ($transf as $k => $v) {
                    if ($v['status_transf'] == 'Ag. Aprovação') {
                        $ap = $v;
                        $ap['aprova'] = 'Aprovar';
                        $transf[$k]['aprova'] = formulario::submit('Aprovar', NULL, $ap);
                    } elseif ($v['status_transf'] == 'Matricula Liberada') {
                        $ap = $v;
                        $ap['rectrasnf'] = 'Matricular';
                        $transf[$k]['matri'] = formulario::submit('Matricular', NULL, $ap);
                    }
                    if ($v['status_transf'] <> 'Cancelado' && $v['status_transf'] <> 'Finalizado') {
                        $transf[$k]['cancela'] = formulario::submit('Cancelar', NULL, ['idtra' => $v['id_transf'], 'del' => 'Cancelar']);
                    }
                    //$transf[$k]['te'] = '<input type="radio" name="idtransf" value="' . $v['id_transf'] . '|' . $v['turmaid'] . '|' . $v['chamada'] . '|' . $v['cod_inst_d'] . '|' . $v['codigo_classe_o'] . '|' . $v['cod_inst_o'] . '|' . $v['fk_id_pessoa'] . '|' . $v['n_escola_destino'] . '|' . $v['n_escola_origem'] . '|' . $v['turmaid'] . '" />';
                }

                $form['array'] = $transf;
                $form['fields'] = [
                    'Protocolo' => 'id_transf',
                    'RSE' => 'fk_id_pessoa',
                    'Cód. Classe' => 'codigo_classe_o',
                    'Nome Aluno' => 'n_pessoa',
                    'Nome Escola' => 'n_escola_origem',
                    'Data Solicitação' => 'dt_solicitacao',
                    'Status Transferência' => 'status_transf',
                    '||2' => 'cancela',
                    '||1' => 'aprova',
                    '||3' => 'matri'
                ];
                tool::relatSimples($form)
                ?>
            </div>

        </div>         

    </div>       

</div>



