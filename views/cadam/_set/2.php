<?php
if (empty($cargoId)) {
    tool::alert("Escolha ao menos um Cargo");
} else {
    foreach ($cargoId as $v) {
        $car[$v] = @$cg_[$v];
    }
    ?>
    <div style="text-align: center; width: 200px; margin: 0 auto">
    <br /><br /><br />
        <?php formulario::select('id_cargo', $cargo_e, 'Disciplina', @$_POST['id_cargo'], 1, ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class'], 'activeNav' => 2]); ?>
    </div>
    <br /><br />
    <?php
    if (!empty($_POST['id_cargo'])) {
        $dis = sql::get(['cadam_escola', 'instancia'], 'fk_id_inst, m, n, t', ['fk_id_cargo' => @$_POST['id_cargo']]);
        foreach ($dis as $v) {
            @$usado[$v['fk_id_inst']]['m'] += str_replace('X', 1, $v['m']);
            @$usado[$v['fk_id_inst']]['n'] += str_replace('X', 1, $v['n']);
            @$usado[$v['fk_id_inst']]['t'] += str_replace('X', 1, $v['t']);
        }


        $esc_e = sql::get(['cadam_escola', 'instancia'], '*', ['fk_id_cad' => @$dados['id_cad'], 'fk_id_cargo' => @$_POST['id_cargo']]);
        foreach ($esc_e as $k => $v) {
            $escolhidas[] = $v['fk_id_inst'];
            $esc_e[$k]['ch'] = '<input type="checkbox" name="id[' . $v['id_ce'] . ']" value="' . $v['id_ce'] . '" />';
        }

        $sql = " select n_inst, id_inst, bairro, m, t, n from instancia i "
                . " join ge_escolas e on e.fk_id_inst = i.id_inst "
                . " left join instancia_predio ip on ip.fk_id_inst = i.id_inst "
                . " left join predio p on p.id_predio = ip.fk_id_predio "
                . " left join cadam_esc_vaga cv on cv.fk_id_inst = i.id_inst and cv.fk_id_cargo = " . @$_POST['id_cargo'] . ' '
                . " order by n_inst";
        $query = $model->db->query($sql);
        $escolas = $query->fetchAll();
        $terceirizadas = [130, 121, 123, 105, 122, 128, 76, 129];
        foreach ($escolas as $k => $v) {
            $escolas[$k]['m'] = $v['m'] - @$usado[$v['id_inst']]['m'];
            $escolas[$k]['n'] = $v['n'] - @$usado[$v['id_inst']]['n'];
            $escolas[$k]['t'] = $v['t'] - @$usado[$v['id_inst']]['t'];
            $escolas[$k]['ch'] = '<input type="checkbox" name="id[' . $v['id_inst'] . ']" value="' . $v['id_inst'] . '" />';
            if (!empty($escolhidas)) {
                if (in_array($v['id_inst'], $escolhidas)) {
                    unset($escolas[$k]['ch']);
                    $escolas[$k]['n_inst'] = '<div style="color: red">' . $escolas[$k]['n_inst'] . '</div>';
                }
            }
            if (in_array($v['id_inst'], $terceirizadas)) {
                unset($escolas[$k]);
            }
        }
        foreach ($dados['dia'] as $v) {
            $diaOpt[substr($v, 1, 1)] = substr($v, 1, 1);
        }
        ?>
        <div style="color: red"></div>
        <table style="width: 100%;">
            <td class="fieldBorder2" style="width: 65%" valign="top">
                <form id="ini" method="POST">
                    <table style="width: 100%">
                        <tr>
                            <?php
                            if (in_array('M', $diaOpt)) {
                                ?>
                                <td>
                                    <label>
                                        <input <?php echo!empty($_POST['m']) ? 'checked' : '' ?>  type="checkbox" name="m" value="X" />
                                        Manhã
                                    </label>
                                </td>
                                <?php
                            }
                            if (in_array('T', $diaOpt)) {
                                ?>
                                <td>
                                    <label>
                                        <input <?php echo!empty($_POST['t']) ? 'checked' : '' ?>  type="checkbox" name="t" value="X" />
                                        Tarde
                                    </label>
                                </td>
                                <?php
                            }
                            if (in_array('N', $diaOpt)) {
                                ?>
                                <td>
                                    <label>
                                        <input <?php echo!empty($_POST['n']) ? 'checked' : '' ?>  type="checkbox" name="n" value="X" />
                                        Noite
                                    </label>
                                </td>
                                <?php
                            }
                            ?>
                            <td style="text-align: right; padding: 5px">
                                <input class="btn btn-success" type="submit" value=">>" />
                            </td>
                        </tr>
                    </table>
                    <br />
                    <?php
                    $form['array'] = $escolas;
                    $form['fields'] = [
                        'M' => 'm',
                        'T' => 't',
                        'N' => 'n',
                        'Escolas Disponíveis' => 'n_inst',
                        'Bairro' => 'bairro',
                        '||' => 'ch'
                    ];
                    tool::relatSimples($form);

                    echo formulario::hidden(['id_cad' => @$_POST['id_cad'], 'id_inscr' => @$_POST['id_inscr'], 'activeNav' => 2]);
                    ?>
                    <input type="hidden" name="atrib" value="1" />
                    <input type="hidden" name="id_cargo" value="<?php echo $_POST['id_cargo'] ?>" />

                </form>
            </td>
            <td style="width: 10%; text-align: center; padding: 10px" valign="top">
                &nbsp;
            </td>
            <td class="fieldBorder2"  valign="top">
                <form id="sai" method="POST">
                    <input type="hidden" name="id_cargo" value="<?php echo $_POST['id_cargo'] ?>" />
                    <input class="btn btn-danger" type="submit" value="<<" />
                    <br /><br />
                    <?php
                    $form['array'] = $esc_e;
                    $form['fields'] = [
                        '||' => 'ch',
                        'Escolas Escolhidas' => 'n_inst',
                        'M' => 'm',
                        'T' => 't',
                        'N' => 'n'
                    ];
                    tool::relatSimples1($form);
                    echo formulario::hidden(['id_cad' => @$_POST['id_cad'], 'id_inscr' => @$_POST['id_inscr'], 'activeNav' => 2]);
                    ?>
                    <input type="hidden" name="atribDel" value="1" />
                </form>
            </td>
        </table>
        <?php
    }
}
