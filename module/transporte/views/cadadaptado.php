<?php
if (($model->_setup['aberto'] == 1) || user::session('id_nivel') == 10) {
    $destino_ = unserialize(base64_decode($model->_setup['destino']));
    foreach ($destino_ as $k => $v) {
        if ($v['ativo'] == 1) {
            $destino[$k] = $v;
        }
    }
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    if (!empty($id_pessoa)) {
        $aluno = new aluno($id_pessoa);
    }
    $id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
    if (!empty($id_li)) {
        $linhaDados = transporteErp::linhaGet($id_li);
    }
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    if (user::session('id_nivel') != 10) {
        $id_inst = toolErp::id_inst();
    } else {
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    }
    $listaBranco = array_column(sqlErp::get('transporte_lita_branca'), 'id_pessoa');
    ?>
    <div class="fieldTop">
        Alocação de Alunos - Transporte Adaptado
    </div>
    <br /><br />
    <div class="row">
        <?php
        if (user::session('id_nivel') == 10) {
            ?>
            <div class="col-sm-6">
                <?php echo formErp::select('id_inst', escolas::idInst(), 'Escola', $id_inst, 1) ?>
                <br /><br />
            </div>
            <?php
        }
        ?>
    </div>

    <?php
    if (!empty($id_inst)) {
        $escola = new escola($id_inst);
        $escola = $escola->endereco()[0];

        alunos::AlunosAutocomplete($id_inst, "p.id_pessoa, p.n_pessoa");
        ?>
        <br />
        <div class="fieldWhite">

            <input type="hidden" name="novo" value="1" />
            <input type="hidden" name="aba" value="esc" />
            <div class="row">
                <div class="col-md-6">
                    <?php echo formErp::input('n_pessoa', 'Nome do Aluno:', @$_POST['n_pessoa'], ' id="busca" onkeypress="complete()" ') ?>
                </div>
                <div class="col-md-4">
                    <?php echo formErp::input('id_pessoa', 'RSE:', $id_pessoa, " id=\"rse\" required onkeypress='return SomenteNumero(event)' ") ?>
                </div>
                <div class="col-md-2">
                    <button id="formSubmit" class="btn btn-success" type="button">
                        Acessar
                    </button>
                </div>
            </div>
            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
            <input type="hidden" name="pesq" value="1" />
        </div>
        <br /><br />
        <?php
        $model->relatAluAdaptado($id_inst);
        toolErp::modalInicio(0);
        include ABSPATH . '/views/transporte/_cadadaptado/form.php';
        toolErp::modalFim();
    }
}
javaScript::somenteNumero();
?>
<script>
    function acessar(id) {
        carregar(id);
    }
    document.querySelector('#formSubmit').addEventListener('click', function () {
        rse = document.querySelector('#rse').value;
        carregar(rse);
    })
    function carregar(rse) {
        if (rse != false) {
            var dados = {
                idAluno: rse
            };
            form = ['n_pessoa', 'id_pessoa', 'idade', 'responsavel', 'cpf_respons', 'tp_def', 'logradouro', 'num', 'cep', 'dt_nasc', 'tel1', 'tel2', 'bairro', 'dt_nasc', 'cadeirante'];
            for (var item in form) {
                document.querySelector("#" + form[item]).value = '';
            }
            $.post('<?php echo HOME_URI ?>/transporte/cadadaptado', dados, function (retorna) {
                dd = JSON.parse(retorna);
                aluno = dd.aluno;
                if (aluno.erro == 1) {
                    alert('Aluno não Encontrado');
                } else {
                    for (var item in aluno) {
                        if (aluno[item] != null && aluno[item] != '') {
                            document.querySelector("#" + item).value = aluno[item];
                        }
                    }
                    $('#myModal').modal('show');
                }
<?php
if (!empty($destino)) {
    foreach ($destino as $k => $v) {
        ?>
                        document.querySelector('#spamStatus' + <?php echo $k ?>).style.display = 'none';
                        document.querySelector('#ch' + <?php echo $k ?>).checked = false;
                        destA = document.querySelector('#destOcultoA' + <?php echo $k ?>).style.display = "none";
                        destB = document.querySelector('#destOcultoB' + <?php echo $k ?>).style.display = "none";
                        destC = document.querySelector('#destOcultoC' + <?php echo $k ?>).style.display = "none";

                        document.getElementById('status<?php echo $k ?>').value = '0';
                        document.getElementById('statushidden<?php echo $k ?>').value = '0';
                        document.getElementById(<?php echo $k ?> + '_e2').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_s2').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_e3').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_s3').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_e4').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_s4').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_e5').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_s5').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_e6').value = '00:00';
                        document.getElementById(<?php echo $k ?> + '_s6').value = '00:00';
                        document.getElementById('ch' + <?php echo $k ?>).value = '';
        <?php
    }
}
?>
                if (dd.destino) {
                    destino = dd.destino;
                    for (var item in destino) {
                        destMostra(item)
                        document.getElementById(item + '_e2').value = destino[item].e2;
                        document.getElementById(item + '_s2').value = destino[item].s2;
                        document.getElementById(item + '_e3').value = destino[item].e3;
                        document.getElementById(item + '_s3').value = destino[item].s3;
                        document.getElementById(item + '_e4').value = destino[item].e4;
                        document.getElementById(item + '_s4').value = destino[item].s4;
                        document.getElementById(item + '_e5').value = destino[item].e5;
                        document.getElementById(item + '_s5').value = destino[item].s5;
                        document.getElementById(item + '_e6').value = destino[item].e6;
                        document.getElementById(item + '_s6').value = destino[item].s6;
                        document.getElementById('ch' + item).value = destino[item].id_at;
                        document.getElementById('status' + item).value = destino[item].fk_id_sa;
                        document.getElementById('statushidden' + item).value = destino[item].fk_id_sa;
                        if (destino[item].fk_id_sa > 0) {
                            document.getElementById('ch' + item).readonly = true;
                        }

                    }
                }
            });
        } else {
            alert('Preencha o RSE');
        }
    }

    function destMostra(i) {
        document.querySelector('#spamStatus' + i).style = '';
        document.querySelector('#ch' + i).checked = true;
        destA = document.querySelector('#destOcultoA' + i);
        destB = document.querySelector('#destOcultoB' + i);
        destC = document.querySelector('#destOcultoC' + i);
        destA.style.display = "";
        destB.style.display = "";
        destC.style.display = "";
    }
    function destOculta(i) {
        ch = document.querySelector('#ch' + i);
        stat = document.querySelector('#status' + i).value;
        if (stat > 0) {
            ch.checked = true;
        }
        destA = document.querySelector('#destOcultoA' + i);
        destB = document.querySelector('#destOcultoB' + i);
        destC = document.querySelector('#destOcultoC' + i);
        if (ch.checked) {
            destA.style.display = "";
            destB.style.display = "";
            destC.style.display = "";
        } else {
            destA.style.display = "none";
            destB.style.display = "none";
            destC.style.display = "none";
        }

    }
</script>