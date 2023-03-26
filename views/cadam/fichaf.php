<?php
$id_cargo = @$_POST['fk_id_cargo'];
$rm = @$_POST['rm'];
$periodo = @$_POST['periodo'];
$mes = @$_POST['mes'];
$id_fr = @$_POST['id_fr'];
if (user::session('id_nivel') == 14) {
    $id_inst = @$_POST['id_inst'];
} else {
    $id_inst = tool::id_inst();
}
?>
<div class="fieldBody">
    <?php
    if (user::session('id_nivel') == 14) {
        ?>
        <div class="row">
            <div class="col-sm-12">
                <?php
                formulario::select('id_inst', escolas::idInst(), 'Escola', null, 1);
                ?>
            </div>
        </div>
        <?php
    }
    ?>
    <br /><br />
    <div class="row">
        <div class="col-sm-12 text-center">
            <div class="fieldTop">
                Relatório de Frequência
            </div>
        </div>
    </div>

    <?php
    if (!empty($id_inst)) {
        if (empty($_POST['mesSet'])) {
            $mesSet = date("m");
        } else {
            $mesSet = $_POST['mesSet'];
        }
        ?>
        <br /><br />
        <div class="row">
            <div class="col-sm-6 text-center">
                <?php formulario::select('mesSet', [(date("m") + 1) => data::mes((date("m") + 1)), date("m") => data::mes(date("m")), (date("m") - 1) => data::mes((date("m") - 1))], 'Mês: ', $mesSet, 1, ['id_inst' => $id_inst]) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-6 text-center">
                <?php
                $freq = $model->cadampesEscola($id_inst);

                $sqlkey = DB::sqlKey('cancela');
                $location = HOME_URI . '/cadam/fichapdf';
                $location1 = HOME_URI . '/cadam/extrato';
                foreach ($freq as $k => $v) {
                    $v['mes'] = $mesSet;
                    $freq[$k]['tea'] = empty($v['id_tea']) ? 'Não' : 'Sim';
                    $freq[$k]['edit'] = formulario::submit('Ficha', NULL, $v, $location, 1, NULL, 'btn btn-success');
                    $freq[$k]['ac'] = formulario::submit('Extrato', NULL, $v, $location1, 1, NULL, 'btn btn-primary');
                }
                $form['array'] = $freq;
                if (user::session('id_nivel') == 14) {

                    $form['fields'] = [
                        'Cadastro' => 'cad_pmb',
                        'Cadampe' => 'n_pessoa',
                        'TEA' => 'tea',
                        '||1' => 'edit',
                        '||2' => 'ac'
                    ];
                } else {
                    $form['fields'] = [
                        'Cadastro' => 'cad_pmb',
                        'Cadampe' => 'n_pessoa',
                        'TEA' => 'tea',
                        '||1' => 'edit',
                    ];
                }
                tool::relatSimples($form);
            }
            ?>
        </div>

    </div>
</div>

