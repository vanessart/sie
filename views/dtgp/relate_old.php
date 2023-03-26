<?php
if (empty($_POST['teaSecr'])) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = @$_POST['id_inst'];
    formulario::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1, ['teaSecr' => 1]);
}
    if (!empty($id_inst)) {
?>
<style>
    th{
        background: black;
        color: white;
    }
    td{
        padding: 5px;
    }
</style>
<div class="fieldBody">
    <br /><br />
    <div style="text-align: center; font-size: 16px">
        Listagem por Disciplina com dados do CADAMPE
        - 
        <?php echo sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'] ?>
    </div>
    <br />
    <div style="text-align: center; font-size: 16px">
        Departamento Técnico de Gestão de Pessoal
    </div>
    <br /><br />
    <table style="width: 100%">
        <tr>
            <td style="width: 33%">
                <?php
                if (empty($_POST['tea'])) {
                    ?>
                    <input style="width: 100%" class="btn btn-primary" type = "submit" value = "Todos" />
                    <?php
                } else {
                    ?>
                    <form method="POST">
                        <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                        <input type="hidden" name="teaSecr" value="<?php echo @$_POST['teaSecr'] ?>" />
                        <input style="width: 100%" class="btn btn-warning" type="submit" value="Todos" />
                    </form>
                    <?php
                }
                ?> 
            </td>
            <td style="width: 33%">
                <?php
                if (@$_POST['tea'] == 2) {
                    ?>
                    <input style="width: 100%" class="btn btn-primary" type = "submit" value = "TEA com Aluno" />
                    <?php
                } else {
                    ?>
                    <form method="POST">
                        <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                        <input type="hidden" name="teaSecr" value="<?php echo @$_POST['teaSecr'] ?>" />
                        <input type="hidden" name="tea" value="2" />
                        <input style="width: 100%" class="btn btn-warning" type="submit" value="TEA com Aluno" />
                    </form>
                    <?php
                }
                ?> 
            </td>
        </tr>
    </table>
    <?php
    if (empty($_POST['tea'])) {

        $clas = sql::get('dtpg_class');

        foreach ($clas as $v) {
            @$class[$v['fk_id_inscr']][$v['fk_id_cargo']] = $v['class'];
        }

    
            $sql = "SELECT "
                    . " c.id_cad, c.dia, c.classifica, s.n_sel, s.id_sel, c.n_insc, c.tel1, c.tel2, c.tel3, c.email, "
                    . " ce.m, ce.t, ce.n, c.cargos_e, c.fk_id_sel, c.fk_id_inscr, ce.fk_id_cargo, "
                    . "  c.tea, ce.fk_id_inst as ce "
                    . " FROM dtgp_cadampe c "
                    . " left join dtgp_cadampe_esc ce on c.id_cad = ce.fk_id_cad "
                    . " left join dtpg_seletivas s on s.id_sel = c.fk_id_sel "
                    . " WHERE ce.fk_id_inst = " . $id_inst . " ";
            $query = $model->db->query($sql);
            $da = $query->fetchAll();

            foreach ($da as $v) {
                unset($pe);
                if (!empty($v['m'])) {
                    $pe[] = 'M';
                }
                if (!empty($v['n'])) {
                    $pe[] = 'N';
                }
                if (!empty($v['t'])) {
                    $pe[] = 'T';
                }
                if (!empty($v['cargos_e'])) {
                    $carg = explode('|', $v['cargos_e']);
                    foreach ($carg as $cc) {
                        if (!empty($cc)) {
                            if ($v['id_sel'] == 5) {
                                $pos = 1000000000000 + $v['classifica'];
                            } elseif ($v['id_sel'] == 3) {
                                @$pos = 2000000000000 + $class[$v['fk_id_inscr']][$cc];
                                @$v['classifica'] = $class[$v['fk_id_inscr']][$cc];
                            } elseif ($v['id_sel'] == 10) {
                                @$pos = 3000000000000 + $class[$v['fk_id_inscr']][$cc];
                                @$v['classifica'] = $class[$v['fk_id_inscr']][$cc];
                            }
                            unset($dia);
                            $dia_ = explode('|', $v['dia']);
                            foreach ($dia_ as $di) {
                                if (!empty($pe)) {
                                    if (in_array(substr($di, 1), $pe)) {
                                        @$dia[substr($di, 0, 1)] .= substr($di, 1) . ' ';
                                    }
                                }
                            }
                            @$v['dias'] = $dia;
                            if ($v['fk_id_cargo'] == $cc)
                                $dados[$cc][$pos] = $v;
                        }
                    }
                }
            }
            ?>
            <br /><br />
            <div style="text-align: left; font-size: 12px">
                M = Manhã; T = Tarde; N = Noite
            </div>

            <table class="table table-bordered table-hover table-striped" >
                <?php
                $cg = sql::get('dtgp_cadampe_cargo', 'id_cargo, n_cargo', ['>' => 'ordem']);
                foreach ($cg as $v) {
                    ?>
                    <tr>
                        <th colspan="6" style="text-align: left">
                            <?php echo $v['n_cargo'] ?>
                        </th>
                        <th colspan="5" style="text-align: center">
                            Período
                        </th>
                    </tr>
                    <tr style="background-color: #E0E0E0; border-color: #000000">
                        <td>
                            Class.
                        </td>
                        <td>
                            Tipo Processo
                        </td>
                        <td>
                            Nome CADAMPE
                        </td>
                        <td>
                            Telefones
                        </td>
                        <td>
                            E-mail
                        </td>
                        <td>
                            TEA (interessado)
                        </td>
                        <td>
                            Seg
                        </td>
                        <td>
                            Ter
                        </td>
                        <td>
                            Qua
                        </td>
                        <td>
                            Qui
                        </td>
                        <td>
                            Sex
                        </td>
                    </tr>
                    <?php
                    if (!empty(@$dados[$v['id_cargo']])) {
                        ksort($dados[$v['id_cargo']]);
                        foreach ($dados[$v['id_cargo']] as $i) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo @$i['classifica'] ?>
                                </td>
                                <td>
                                    <?php echo @$i['n_sel'] ?>
                                </td>
                                <td>
                                    <?php echo @$i['n_insc'] ?>
                                </td>
                                <td>
                                    <?php echo (!empty($i['tel1']) ? $i['tel1'] : '') . (!empty($i['tel2']) ? '<br />' . $i['tel2'] : '') . (!empty($i['tel3']) ? '<br />' . $i['tel3'] : '') ?>
                                </td>
                                <td>
                                    <?php echo @$i['email'] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                    <?php
                                    if (!empty(@$i['tea'])) {
                                        ?>
                                        Sim
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                    <?php echo @$i['dias'][1] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                    <?php echo @$i['dias'][2] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                    <?php echo @$i['dias'][3] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                    <?php echo @$i['dias'][4] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                    <?php echo @$i['dias'][5] ?>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                    ?>
                    <?php
                }
                ?> 
            </table>
            <?php
        } else {
            $sql = "select n_insc, n_pessoa from dtgp_tea tea "
                    . " join dtgp_cadampe c on c.id_cad = tea.fk_id_cad "
                    . "join pessoa p on p.id_pessoa = tea.fk_id_pessoa "
                    . " where fk_id_inst = " . $id_inst . " "
                    . " and c.antigo = 1 "
                    . " order by n_pessoa ";
            $query = $model->db->query($sql);
            $form['array'] = $query->fetchAll();
            $form['fields']=[
                'Aluno' =>'n_pessoa',
                'Professor'=>'n_insc'
            ];
            tool::relatSimples($form);
            
        }
    }
    ?>
</div>