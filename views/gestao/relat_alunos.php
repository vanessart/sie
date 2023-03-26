<?php
@$id_turma = $_POST['id_turma'];
$ano = date('Y');
$wcodclasse = sql::get('ge_turmas', 'codigo, id_turma', 'where fk_id_inst = ' . tool::id_inst() . " and periodo_letivo like '%" . $ano . "%' order by codigo");
if (empty($_POST['periodoLetivo'])) {
    if (empty($_SESSION['tmp']['periodoLetivo'])) {
        $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
    } else {
        $periodoLetivo = $_SESSION['tmp']['periodoLetivo'];
    }
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
    @$_SESSION['tmp']['periodoLetivo'] = $_POST['periodoLetivo'];
}
$turmaOptions = turma::option(tool::id_inst(), $periodoLetivo);
?>
<div class="fieldBody" style="width: 80%;margin: 0 auto">
    <br /><br />
    <div class="row">
        <div class="col-md-4">
            <?php
            $per = gtMain::periodosPorSituacao();
            formulario::select('periodoLetivo', $per, 'Período Letivo', @$periodoLetivo, 1);
            ?>
        </div>
        <div class="col-md-5">
            <?php
            if (!empty($periodoLetivo)) {
                formulario::select('id_turma', $turmaOptions, 'Selecione Código da Classe:', @$id_turma, 1, ['periodoLetivo' => $periodoLetivo]);
            }
            ?>
        </div>
    </div>
    <br />
    <div class="row">
        <?php
        if (!empty($id_turma)) {
            $turma = turmas::classe($id_turma);
            ?>
        <div class="col-md-2">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/fichacadastralbranco" name="alunos" method="POST">
                    <button type="submit" class="btn btn-default btn-xs">
                        Ficha Cadastral em Branco
                    </button>
                </form> 
            </div>
            <div class="col-md-2">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/perfildoalunopdfbranco" name="alunos" method="POST">
                    <button type="submit" class="btn btn-default btn-xs">
                        Perfil do Aluno em Branco
                    </button>
                </form> 
            </div>

            <div class="col-md-4" style="text-align: center">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/fichacadastral" name="alunos" method="POST">
                    <?php
                    foreach ($turma as $k => $v) {
                        ?>
                        <input id="fc<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="<?php echo $v['id_pessoa'] ?>" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />                   
                    <button  type="submit" class="btn btn-default btn-xs">
                        Ficha Cadastral - classe <?php echo $turmaOptions[$_POST['id_turma']] ?> 
                    </button>
                </form>  
            </div>
            <div class="col-md-4" style="text-alig:center">
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/perfildoalunopdf" name="perfil" method="POST">
                    <?php
                    foreach ($turma as $k => $v) {
                        ?>
                        <input id="fc<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="<?php echo $v['id_pessoa'] ?>" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />                   
                    <button  type="submit" class="btn btn-default btn-xs">
                        Perfil do Aluno - classe <?php echo $turmaOptions[$_POST['id_turma']] ?> 
                    </button>
                </form>  
            </div>
            <br /><br />

        </div>
        <div class="row">
            <div class ="col-md-12">

                <div>  
                    <?php
                    foreach ($turma as $k => $v) {
                        $dropdown = '
                        <div class = "btn-group">
                        <button class = "btn btn-default btn-xs dropdown-toggle" type = "button" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">
                        Ação <span class = "caret"></span>
                        </button>
                        <ul class = "dropdown-menu">
                        <li onclick = "formulario(\'pAlu\', \'pa\', \'' . $v['id_pessoa'] . '\')"><a href="#">Perfil do Aluno</a></li>
                        <li onclick = "formulario(\'fCad\', \'fc\', \'' . $v['id_pessoa'] . '\')"><a href="#"> Ficha Cadastral</a></li>
                        <li onclick = "formulario(\'dTrans\', \'dt\', \'' . $v['id_pessoa'] . '\')"><a href="#">Declaração de Transferência</a></li>
                        </ul>
                        </div>
                        ';
                        $turma[$k]['tur'] = $dropdown;
                    }
                    $form['array'] = $turma;
                    $form['fields'] = [
                        'Chamada' => 'chamada',
                        'RSE' => 'id_pessoa',
                        'Nome Aluno' => 'n_pessoa',
                        'Data Nas.' => 'dt_nasc',
                        'Situação' => 'situacao',
                        '||' => 'tur'
                    ];
                    tool::relatSimples($form);
                    ?>
                </div>
            </div>  
            <form id="fCad" target="_blank" action="<?php echo HOME_URI ?>/gestao/fichacadastral" name="alunos" method="POST">
                <input id="fc" type="hidden" name="sel[]" value="" />
                <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />                   
            </form>                  
            <form  id="dTrans" target="_blank" action="<?php echo HOME_URI ?>/gestao/decl_transf" name="transfd" method="POST">
                <input id="dt" type="hidden" name="sel[]" value="" />
                <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />             
            </form>  
            <form id="pAlu" target="_blank" action="<?php echo HOME_URI ?>/gestao/perfildoalunopdf" name="perfil" method="POST">
                <input id="pa" type="hidden" name="sel[]" value="" />
                <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />                   
            </form>  
        </div>
    </div>
    <br /><br /><br /><br /><br /><br />
    <?php
}
?>
</div>
<script>
    function formulario(formu, inp, idAluno) {
        document.getElementById(inp).value = idAluno;
        document.getElementById(formu).submit();
    }
</script>