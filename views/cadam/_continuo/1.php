<?php
if (!empty($_REQUEST['uniqid'])) {
    $uniqid = $_REQUEST['uniqid'];
} else {
    $uniqid = uniqid();
}
$id_inst = @$_REQUEST['id_inst'];
$fk_id_mot = @$_POST['fk_id_mot'];
$mesSet = @$_REQUEST['mesSet'];
$dia_ini = @$_POST['dia_ini'];
$dia_fim = @$_POST['dia_fim'];
$rm = @$_POST['rm'];
$m = @$_POST['manha'];
$t = @$_POST['tarde'];
$n = @$_POST['noite'];
$id_cargo = @$_POST['id_cargo'];
$iddisc = sql::get('cadam_cargo', 'fk_id_disc', ['id_cargo' => $id_cargo], 'fetch')['fk_id_disc'];
$hidden = ['id_inst' => $id_inst, 'mesSet' => $mesSet, 'uniqid' => $uniqid];
$du = data::diasUteis($mesSet, date('Y'));
foreach ($du as $k => $v) {
    $diasUteis[$v] = str_pad($v, 2, "0", STR_PAD_LEFT) . '/' . str_pad($mesSet, 2, "0", STR_PAD_LEFT) . '/' . date("Y");
}
?>
<br />
<div style="height: 80vh; width: 95%; margin: 0 auto">
    <div class="row">
        <div class="col-sm-12">
            <?php
            formulario::selectDB('cadam_cargo', 'id_cargo', 'Cargo', @$id_cargo, ' required ', 1, $hidden);
            $hidden['id_cargo'] = @$id_cargo;
            ?>
        </div>
    </div>
    <?php
    if (!empty($id_cargo)) {
        $prof = funcionarios::profEscola($id_inst, $iddisc);
        foreach ($prof as $v) {
            $proff[$v['rm']] = $v['n_pessoa'];
        }
        if (!empty($dia_fim)) {
            $disabled = "disabled";
        }
        ?>

        <form method="POST">
            <br /><br />
            <div class="row">
                <div class="col-sm-6">
                    <?php echo formulario::select('rm', @$proff, 'professor', NULL, NULL, $hidden, ' required ' . @$disabled) ?>
                </div>
                <div class="col-sm-3">  
                    <?php
                    echo formulario::select('dia_ini', $diasUteis, 'Data Inicial', @$_POST['dia_ini'], NULL, NULL, @$disabled . ' required');
                    ?>
                </div>
                <div class="col-sm-3">  
                    <?php
                    echo formulario::select('dia_fim', $diasUteis, 'Dia Final', @$_POST['dia_fim'], NULL, NULL, @$disabled . ' required');
                    ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-4">
                    <label>
                        <input <?php echo $m == 'M' ? 'checked' : '' ?> type="checkbox" name="manha" value="M" />
                        Manhã
                    </label>
                </div>
                <div class="col-sm-4">
                    <label>
                        <input <?php echo $t == 'T' ? 'checked' : '' ?> type="checkbox" name="tarde" value="T" />
                        Tarde
                    </label>
                </div>
                <div class="col-sm-4">
                    <label>
                        <input <?php echo $n == 'N' ? 'checked' : '' ?> type="checkbox" name="noite" value="N" />
                        Noite
                    </label>
                </div>
            </div>
            <br /><br />
            <div style="text-align: center">
                <?php
                if (empty($m) && empty($t) && empty($n)) {
                    echo formulario::hidden($hidden);
                    ?>
                    <input class = "btn btn-success" type = "submit" value = "Continuar" />
                    <?php
                }
                ?>

            </div>
        </form>
        <?php
        if (!empty($dia_fim) && (!empty($m) || !empty($t) || !empty($n))) {

            $confere = cadamp::confereareservado($id_cargo, $dia_ini, $dia_fim, $mesSet, @$m . @$t . @$n, $id_inst, $rm);
            if (!empty($confere)) {
                ?>
                <div class="alert alert-danger" style="text-align: center; font-size: 18px">
                    Este Professor Já tem reserva neste período.  
                </div>
                <?php
            } else {
                $hidden['id_inst'] = $id_inst;
                $hidden['mesSet'] = $mesSet;
                $hidden['dia_ini'] = $dia_ini;
                $hidden['dia_fim'] = $dia_fim;
                $hidden['manha'] = $m;
                $hidden['tarde'] = $t;
                $hidden['noite'] = $n;
                $hidden['rm'] = $rm;
                $hidden['uniqid'] = $uniqid;
                $hidden['id_cargo'] = $id_cargo;

                $jaConvocado = cadamp::jaContato($id_inst, $id_cargo);

                if (empty($jaConvocado) || !empty($_POST['editar']) || !empty($_POST['novo'])) {
                    include ABSPATH . '/views/cadam/_continuo/1_1.php';
                } else {
                    foreach ($jaConvocado as $k => $v) {
                        $v['id_inst'] = $id_inst;
                        $v['mesSet'] = $mesSet;
                        $v['dia_ini'] = $dia_ini;
                        $v['dia_fim'] = $dia_fim;
                        $v['rm'] = $rm;
                        $v['manha'] = $m;
                        $v['tarde'] = $t;
                        $v['noite'] = $n;
                        $v['id_cargo'] = $id_cargo;
                        $v['editar'] = 1;
                        if ($v['contato'] == 1 && $uniqid == $v['uniqid']) {
                            $v['uniqid'] = $uniqid;
                            $jaConvocado[$k]['sit'] = 'Aceitou';
                            $jaConvocado[$k]['edit'] = formulario::submit('Alterar', NULL, $v);
                            $aceitou[] = $jaConvocado[$k];
                            unset($jaConvocado[$k]);
                        } elseif ($v['contato'] == 2 || $v['contato'] == 1) {
                            $v['uniqid'] = $uniqid;
                            $jaConvocado[$k]['sit'] = 'Não encontrado';
                            $jaConvocado[$k]['edit'] = formulario::submit('Tentar Novamente', NULL, $v);
                        } elseif ($v['contato'] == 3) {
                            $jaConvocado[$k]['sit'] = 'Recusou';
                        }
                    }
                    if (empty($aceitou)) {
                        $form['array'] = $jaConvocado;
                    } else {
                        $form['array'] = $aceitou;
                    }
                    $form['fields'] = [
                        'CAD PMB' => 'cad_pmb',
                        'Nome' => 'n_pessoa',
                        'Tel1' => 'tel1',
                        'Tel2' => 'tel2',
                        'Tel3' => 'tel3',
                        'email' => 'email',
                        'Situação' => 'sit',
                        '||' => 'edit'
                    ];

                    tool::relatSimples($form);
                    if (empty($aceitou)) {
                        ?>
                        <form style="text-align: center" method="POST">
                            <?php
                            echo formulario::hidden($hidden);
                            ?>
                            <input type="hidden" name="novo" value="1" />
                            <input class="btn btn-primary" type="submit" value="Tentar outro Cadampe" />
                        </form>
                        <?php
                    } else {
                        ?>
                        <br /><br />
                        <form target="_parent" action="<?php echo HOME_URI ?>/cadam/continuo" method="POST">
                            <?php
                            $hidden['id_cad'] = current($aceitou)['id_cad'];
                            echo formulario::hidden($hidden);
                            echo DB::hiddenKey('finalizarReserva')
                            ?>
                            <div style="text-align: center">
                                <input name="finalizado" class="btn btn-success" type="submit" value="Finalizar Reserva" />
                            </div>
                        </form>
                        <br /><br />
                        <?php
                    }
                }
            }
        }
    }
    ?>
</div>
