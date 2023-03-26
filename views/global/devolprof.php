<style>
    td{
        -webkit-print-color-adjust: exact !important; 
        color-adjust: exact !important;
    }
    div{
        -webkit-print-color-adjust: exact !important; 
        color-adjust: exact !important;
    }
</style>
<?php
ini_set('memory_limit', '2000M');

@$id_agrup = @$_POST['id_agrup'];
@$id_inst = @$_POST['id_inst'];
$id_gl = @$_POST['id_gl'];
$id_turma = @$_POST['id_turma'];
@$classeDisc = @$_POST['classeDisc'];
@$escola = @$_POST['escola'];
$dados = aval::getAval($id_gl);
$tipoProva = sql::get('global_agrupamento', 'tipo', ['id_agrup'=>@$id_agrup], 'fetch')['tipo'];
$hidden = [
    'id_gl' => $id_gl,
    'id_agrup' => @$id_agrup,
    'id_inst' => $id_inst,
    'id_turma' => $id_turma,
    'classeDisc' => @$classeDisc,
    'escola' => $escola
];
?>
<div class="fieldBody">
    <div class="noprint">
        <div class="fieldTop">
            Devolutivas
        </div>
                <?php
  if (!empty($_POST['professor'])) {
            ?>
            <div style="text-align: center; font-size: 18px; font-weight: bold">
                <?php echo @$_POST['escola'] ?> - <?php echo @$_POST['classeDisc'] ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="btn btn-info" href="<?php echo HOME_URI ?>/global/classeprof">Voltar</a>
            </div>
            <br /><br />
            <?php
        }
?>
        <br /><br />
        <?php
        $aba1 = 1;
        $hidden['acessarGrafico'] = 1;
        $abas[1] = ['nome' => "Gráficos por Habilidades", 'ativo' => 1, 'hidden' => $hidden, 'link' => "",];
        $abas[2] = ['nome' => "Proficiência por Aluno", 'ativo' => $aba1, 'hidden' => $hidden, 'link' => "",];
        $abasSet = tool::abas($abas);
        ?>
    </div>
    <?php
    if (@$_POST['activeNav']) {
        include ABSPATH . '/views/global/_projeto/' . $abasSet . '.php';
    }
    ?>
</div>
