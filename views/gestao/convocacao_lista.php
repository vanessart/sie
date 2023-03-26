<?php
@$id_eve = $_POST['id_eve'];
$ano = date('Y');
@$id_turma = $_POST['id_turma'];

$wcodclasse = sql::get('ge_turmas', 'codigo, id_turma', 'where fk_id_inst = ' . tool::id_inst() . " and periodo_letivo like '%" . $ano . "%' order by codigo");
if (empty($_POST['periodoLetivo'])) {
    if (empty($_SESSION['tmp']['periodoLetivo'])) {
        echo $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
    } else {
        $periodoLetivo = $_SESSION['tmp']['periodoLetivo'];
    }
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
    $_SESSION['tmp']['periodoLetivo'] = $_POST['periodoLetivo'];
}
$turmaOptions = turma::option(tool::id_inst(), $periodoLetivo);

$turma = sql::get('ge_turmas', 'codigo, id_turma', 'where fk_id_inst = ' . tool::id_inst() . " and periodo_letivo like '%" . $ano . "%' order by codigo");
$ev = sql::get('ge_eventos', 'id_evento, evento, dt_evento, h_inicio, h_final, local_evento', ['fk_id_inst' => tool::id_inst(), 'ano_letivo' => date('Y'), '<' => 'id_evento']);
?>
<div>
    <div>
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
    </div>

    <div class="fieldBody">
        <?php
        if (!empty($_POST['id_turma'])) {
            if (!empty($id_turma)) {
                $cod = sql::get(['pessoa', 'ge_turma_aluno'], '*', 'where fk_id_inst =' . tool::id_inst() . " and fk_id_turma ='" . $id_turma . "' And situacao = 'Frequente' order by chamada");
            }
            ?>
            <div class="col-md-7" style="font-size: 18px">
                <div style="width: 100%; font-size: 18; overflow: auto; height: 350px">  
                    <?php
                    foreach ($cod as $k => $v) {
                        $cod[$k]['tur'] = formulario::checkboxSimples('sel[]', $v['id_pessoa'], NULL, NULL, 'id="' . $v['id_pessoa'] . '"');
                    }
                    $form['array'] = $cod;
                    $form['fields'] = [
                        'Chamada' => 'chamada',
                        'RSE' => 'id_pessoa',
                        'Nome Aluno' => 'n_pessoa',
                        'Situação' => 'situacao',
                        'todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />' => 'tur'
                    ];
                    tool::relatSimples($form);
                }
                ?>
            </div>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-3">

            <b>Visualizar</b>
            <?php
            if (!empty(@$id_eve)) {
                ?>
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/convocacaopdf" name="conv" method="POST">

                    <?php
                    foreach ($cod as $k => $v) {
                        ?>
                        <input id="cd<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" /> 
                    <input type="hidden" name="id_eve" value="<?php echo @$_POST['id_eve'] ?>" />
                    <button onmouseover="cd()" style="width: 95%" type="submit" class="art-button" id="btncd">
                        Convocação
                    </button>
                </form>
                <br />
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/autorizacaopdf" name="aut" method="POST">

                    <?php
                    foreach ($cod as $k => $v) {
                        ?>
                        <input id="au<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" /> 
                    <input type="hidden" name="id_eve" value="<?php echo @$_POST['id_eve'] ?>" />
                    <button onmouseover="au()" style="width: 95%" type="submit" class="art-button" id="btnau">
                        Autorização
                    </button>
                </form>

                <?php
            }
            ?>
            <br />
            <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/declaracaopdf" name="decl" method="POST">
                    <?php
                    foreach ($cod as $k => $v) {
                        ?>
                        <input id="de<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" /> 
                    <button onmouseover="de()" style="width: 95%" type="submit" class="art-button" id="btnde">
                        Declaração Escolaridade
                    </button>
                </form>  
            </div>
            <br />
            <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/listapersonalizadapdf" name="pers" method="POST">
                    <?php
                    foreach ($cod as $k => $v) {
                        ?>
                        <input id="pp<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" /> 
                    <input type="hidden" name="id_eve" value="<?php echo @$_POST['id_eve'] ?>" />
                    <button onmouseover="pp()" style="width: 95%" type="submit" class="art-button" id="btnpp">
                        Lista Piloto Personalizada
                    </button>
                </form>  
            </div> 
            <br />

            <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/decl_conclusao" name="conc" method="POST">
                    <?php
                    foreach ($cod as $k => $v) {
                        ?>
                        <input id="dc<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" /> 
                    <button onmouseover="dc()" style="width: 95%" type="submit" class="art-button" id="btndc">
                        Declaração Conclusão 9º Ano
                    </button>
                </form>  
            </div>
            <br />
            <div>
                <form target="_blank" action="<?php echo HOME_URI ?>/gestao/decl_conclusaoeja" name="conceja" method="POST">
                    <?php
                    foreach ($cod as $k => $v) {
                        ?>
                        <input id="ej<?php echo $v['id_pessoa'] ?>" type="hidden" name="sel[]" value="" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" /> 
                    <button onmouseover="ej()" style="width: 95%" type="submit" class="art-button" id="btn">
                        Declaração Conclusão Eja 2º Segmento Termo IV
                    </button>
                </form>  
            </div>
            <br />
            <div>
                <a style="width:95%" class="art-button" href = "<?php echo HOME_URI ?>/gest/convocacao">Selecionar Convocação/Evento</a>
            </div>
        </div> 
    </div>

    <script>
<?php
$funcao = ['cd', 'de', 'au', 'pp', 'dc', 'ej'];

foreach ($funcao as $f) {
    ?>
            function <?php echo $f ?>() {
                teste = 0;
    <?php
    foreach ($cod as $k => $v) {
        ?>
                    if (document.getElementById("<?php echo @$v['id_pessoa'] ?>").checked) {
                        teste = 1;
                        document.getElementById("<?php echo @$f . @$v['id_pessoa'] ?>").value = '<?php echo @$v['id_pessoa'] ?>';
                    } else {
                        document.getElementById("<?php echo @$f . @$v['id_pessoa'] ?>").value = '';
                    }
        <?php
    }
    ?>
                if (teste == 0) {
                    document.getElementById('btn<?php echo $f ?>').disabled = true;
                    //   alert('Favor selecionar pelo menos 1 aluno')
                } else {
                    document.getElementById('btn<?php echo $f ?>').disabled = false;
                }
            }
    <?php
}
?>
        function checkAll(o) {

            var boxes = document.getElementsByTagName("input");
            for (var x = 0; x < boxes.length; x++) {
                var obj = boxes[x];
                if (obj.type == "checkbox") {
                    if (obj.name != "chkAll")
                        obj.checked = o.checked;
                }
            }
        }
    </script>
</div>
