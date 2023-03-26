<?php
@$id_agrup = empty($_POST['fk_id_agrup']) ? @$_POST['id_agrup'] : @$_POST['fk_id_agrup'];
$hidden['id_agrup'] = @$id_agrup;
?>
<div class="fieldBody">
    <div class="fieldTop">
        Avaliações
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-6">
            <?php
            if ($_SESSION['userdata']['id_nivel'] == 8) {
                $where = ['ativo' => 1, '>' => 'n_agrup'];
            } else {
                $where = ['>' => 'n_agrup'];
            }
            $agrup = sql::idNome('global_agrupamento', @$where);
            formulario::select('id_agrup', $agrup, 'Agrupamento', @$id_agrup, 1);
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($id_agrup)) {
        ?>
        <input type="submit" onclick="document.getElementById('limpar').submit();" value="Nova Avaliação" />
        <br /><br />
        <?php
        $aval = sql::get('global_aval', '*', ['>' => 'n_gl', 'fk_id_agrup' => $id_agrup]);
        $sqlkey = DB::sqlKey('global_aval', 'delete');
        foreach ($aval as $k => $v) {
            $aval[$k]['calc'] = formulario::submit('calcular', NULL, ['calcular' => 'calcular', 'id_gl' => $v['id_gl'], 'id_agrup' => $id_agrup]);
            $v['novo'] = 2;
            $aval[$k]['perc'] = formulario::submit('Personalizar Questões', NULL, $v);
            $v['novo'] = 3;
            $aval[$k]['vall'] = formulario::submit('Valores', NULL, $v);
            $v['novo'] = 1;
            $aval[$k]['ac'] = formulario::submit('Editar', NULL, $v);
            $aval[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_gl]' => $v['id_gl'], 'id_agrup' => $id_agrup]);
            $aval[$k]['ativo'] = tool::simnao($v['ativo']);
        }
        $form['array'] = $aval;
        $form['fields'] = [
            'ID' => 'id_gl',
            'Avaliação' => 'n_gl',
            'Ativo' => 'ativo',
            'Ciclos' => 'ciclos',
            'Questões' => 'quest',
            'Valores' => 'val',
            "||1" => 'del',
            "||4" => 'vall',
            "||3" => 'perc',
            "||2" => 'ac',
            "||5" => 'calc'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>
<?php
if (@$_POST['novo'] == 1) {
    $modal = null;
} else {
    $modal = 1;
}
if (@$_POST['novo'] < 2) {
    tool::modalInicio('width: 95%', @$modal);
    ?>
    <br /><br />
    <form id="formulario" method="POST">
        <div class="row">
            <div class="col-md-12">
                <?php formulario::input('1[n_gl]', 'Avaliação') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-3">
                <?php formulario::input('1[ciclos]', 'Ciclos (separados por ",")') ?>
            </div>
            <div class="col-md-3">
                <?php formulario::selectDB('ge_disciplinas', '1[fk_id_disc]', 'Disciplina') ?>
            </div>
            <div class="col-md-3">
                <?php formulario::select('1[visualizar]', [1 => 'Sim', 0 => 'não'], 'Liberar Visualização') ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::selectNum('1[resposta_default]', 5, 'Resposta Padrão') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-3">
                N. Questões
                <select name="1[quest]">
                    <option></option>
                    <?php
                    for ($c = 1; $c <= 60; $c++) {
                        ?>
                        <option <?php echo $c == @$_POST['quest'] ? 'selected' : '' ?>><?php echo $c ?></option>
                        <?php
                    }
                    ?>

                </select>
            </div>
            <div class="col-md-3">
                <?php formulario::select('1[ativo]', [1 => 'Sim', 0 => 'não'], 'Ativo') ?>
            </div>
            <div class="col-md-3">
                <?php formulario::selectNum('1[colunas]', [1, 2], 'Colunas', @$_POST['colunas'], NULL, NULL, 'required') ?>
            </div>
            <div class="col-md-3">
                <?php formulario::select('1[numerar]', [1 => 'Sim', 0 => 'não'], 'numerar') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-3 text-center">
                <?php formulario::checkbox('1[naofez]', 1, 'Não Compareceu', @$_POST['naofez']) ?>
            </div>
            <div class="col-md-3 text-center">
                <?php formulario::checkbox('1[branco]', 1, 'Branco', @$_POST['branco']) ?>
            </div>
            <div class="col-md-3 text-center">
                <?php formulario::checkbox('1[nulo]', 1, 'Nulo', @$_POST['nulo']) ?>
            </div>
            <div class="col-md-3 text-center">
                <?php formulario::checkbox('1[escrita]', 1, 'Níveis de Escrita', @$_POST['escrita']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-12">
                <?php formulario::input('1[valores]', 'Valores (separados por ",")') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-6">
                <?php
                $esc = escolas::idInst();
                @$escSet = explode('|', $_POST['escolas']);
                foreach ($esc as $k => $v) {
                    ?>
                    <label>
                        <input <?php echo in_array($k, $escSet) ? 'checked' : '' ?> type="checkbox" name="esc[]" value="<?php echo $k ?>" />
                        <?php echo $v ?>
                    </label>
                    <br />
                    <?php
                    if (@$C++ == 50) {
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <br /><br />
        <div class="row">

            <div class="col-md-12 text-center">
                <?php echo DB::hiddenKey('global_aval') ?>
                <input type="hidden" name="global_aval" value="1" />
                <input type="hidden" name="id_agrup" value="<?php echo @$id_agrup ?>" />
                <input type="hidden" name="1[fk_id_agrup]" value="<?php echo @$id_agrup ?>" />
                <input type="hidden" name="1[id_gl]" value="<?php echo @$_POST['id_gl'] ?>" />
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </div>
    </form>
    <?php
    tool::modalFim();
} elseif (@$_POST['novo'] == 2) {
    tool::modalInicio('width: 95%');
    $aval = sql::get('global_aval', '*', ['id_gl' => @$_POST['id_gl']], 'fetch');
    $perc = unserialize(tool::serializeCorrector($aval['perc']));
    ?>
    <form method="POST">
        <br /><br />
        <textarea placeholder="Título" name="titulo" style="width: 100%; height: 250px"><?php echo $aval['titulo'] ?></textarea>
        <?php
        for ($c = 1; $c <= @$_POST['quest']; $c++) {
            ?>
            <br /><br />
            <div class="row">
                <div class="col-md-12">
                    <?php formulario::input('1[' . $c . ']', 'Questão ' . $c, NULL, $perc[$c]) ?>
                </div>
            </div>
            <?php
        }
        ?>
        <br /><br />
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo DB::hiddenKey('perc') ?>
                <input type="hidden" name="id_gl" value="<?php echo @$_POST['id_gl'] ?>" />
                <input type="text" name="id_agrup" value="<?php echo @$id_agrup ?>" />
                <button>
                    Salvar
                </button>
            </div>
        </div>
    </form>
    <?php
    tool::modalFim();
} else {
    tool::modalInicio('width: 95%');
    ?>
    <div class="fieldTop">
        Valores
    </div>
    <?php
    $aval = sql::get('global_aval', '*', ['id_gl' => @$_POST['id_gl']], 'fetch');
    $val = unserialize(tool::serializeCorrector($aval['val']));
    ?>
    <form method="POST">
        <?php
        foreach (explode(',', $aval['valores']) as $k => $v) {
            ?>
            <br /><br />
            <div class="row">
                <div class="col-md-12">
                    <?php formulario::input('1[' . ($k + 1) . ']', $v, NULL, $val[($k + 1)]) ?>
                </div>
            </div>
            <?php
        }
        ?>
        <br /><br />
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo DB::hiddenKey('val') ?>
                <input type="hidden" name="id_gl" value="<?php echo @$_POST['id_gl'] ?>" />
                <input type="hidden" name="id_agrup" value="<?php echo @$id_agrup ?>" />
                <button>
                    Salvar
                </button>
            </div>
        </div>
    </form>
    <?php
    tool::modalFim();
}
?>
<form id="limpar" method="POST">
    <input type="hidden" name="novo" value="1" />
    <input type="hidden" name="id_agrup" value="<?php echo @$id_agrup ?>" />
</form>