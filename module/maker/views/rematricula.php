<?php
if (!defined('ABSPATH'))
    exit;
css::switchButton();
?>
<style>
    #label {
        background-color: #3498db;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        padding: 6px;
        width: 60px;
    }
</style>
<?php
$ciclos = sql::idNome('maker_ciclo');
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
    $n_inst = toolErp::n_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
    if ($id_inst) {
        $n_inst = $escolas[$id_inst];
    }
}
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], 'n_pl, id_pl', null, 'fetch');
$id_pl = $setup['id_pl'];
if ($id_inst) {
    $maker = $model->escolaPolo($id_inst);
}
if ($id_inst) {
    $freq = $model->frequeciaAluno();

    $al = $model->alunoListAutoriza($id_inst);
    foreach ($al as $y) {
        if ($ano == substr($y['Turma de Origem'], 0, 1) || !$ano) {
            $ids[$y['RSE']] = $y['RSE'];
            $alunos[] = [
                'id_pessoa' => $y['RSE'],
                'n_pessoa' => $y['Nome'],
                'n_turma' => $y['Turma de Origem'],
                'maker' => $y['Turma Maker'],
                'periodo_origem' => $y['periodo_origem'],
                'porc_frequencia_ant' => $y['porc_frequencia_ant']
            ];
        }
    }

    if (!empty($ids)) {
        $sql = "SELECT * FROM maker_autoriza where id_pessoa_aluno in(" . implode(', ', $ids) . ")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $autoriza[$v['id_pessoa_aluno']] = $v;
            }
        } else {
            $autoriza = [];
        }
    }
}
if (!empty($alunos)) {
    foreach ($alunos as $k => $v) {
        if ($v['porc_frequencia_ant'] >= 25) {
            $cicloAnt = $ciclos[(substr($v['maker'], 0, 1) - 1)];
        } else {
            $cicloAnt = $ciclos[substr($v['maker'], 0, 1)];
        }
        if (empty($freq[$v['id_pessoa']][1])) {
            $ciclo = $ciclos[substr($v['maker'], 0, 1)];
            $id_mc = substr($v['maker'], 0, 1);
        } else {
            $ciclo = $ciclos[substr($v['maker'], 0, 1) + 1];
            $id_mc = substr($v['maker'], 0, 1) + 1;
        }
        $alunos[$k]['periodo'] = $v['periodo_origem'] == 'M' ? 'Tarde' : 'Manhã';
        $alunos[$k]['print'] = formErp::checkbox('1[]', $v['id_pessoa'], 'Imprimir', null, ' class="printr" onclick="pt(this)" ');
        $sim = @$autoriza[$v['id_pessoa']]['sim'];
        $transp = @$autoriza[$v['id_pessoa']]['transporte'];
        // if (toolErp::id_nilvel() == 2) {
        $alunos[$k]['matri'] = '<input onchange="lanc(' . $v['id_pessoa'] . ', ' . $id_mc . ',  this)" ' . ($sim == 1 ? 'checked' : '') . ' id="switch-shadow-retirada' . $v['id_pessoa'] . '" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-retirada' . $v['id_pessoa'] . '"></label>';
        $alunos[$k]['transp'] = '<input onchange="lancTransp(' . $v['id_pessoa'] . ', ' . $id_mc . ',  this)" ' . ($transp == 1 ? 'checked' : '') . ' id="switch-shadow-transp' . $v['id_pessoa'] . '" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-transp' . $v['id_pessoa'] . '"></label>';
        //  } else {
        //      $alunos[$k]['matri'] = toolErp::simnao($sim);
        //  }
        if (empty($freq[$v['id_pessoa']][1])) {
            $alunos[$k]['ciclo'] = '<span style="color: blue">' . $ciclo . '</span>';
            if ($v['porc_frequencia_ant'] >= 25) {
                $alunos[$k]['cicloAnt'] = '<span style="color: green">' . $cicloAnt . '</span>';
            } else {
                $alunos[$k]['cicloAnt'] = '<span style="color: red">' . $cicloAnt . '</span>';
            }
            $alunos[$k]['porc'] = '0%';
        } else {
            $alunos[$k]['ciclo'] = $ciclo;
            if (empty($freq[$v['id_pessoa']][0])) {
                $faltas = 0;
            } else {
                $faltas = $freq[$v['id_pessoa']][0];
            }
            $alunos[$k]['porc'] = round(($freq[$v['id_pessoa']][1] / ($freq[$v['id_pessoa']][1] + $faltas)) * 100) . '%';
        }
        $alunos[$k]['frequencia_ant'] = $v['porc_frequencia_ant'] . '%';
    }
    $form['array'] = $alunos;
    $form['fields'] = [
        'RSE' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'Turma' => 'n_turma',
        //    'Turma Maker' => 'maker',
        'Fase Anterior' => 'cicloAnt',
        'Frequência no Período Anterior' => 'frequencia_ant',
        'Próxima Fase' => 'ciclo',
        'Período do Curso' => 'periodo',
        //  formErp::checkbox(null, 1, 'Todos', null, ' onclick="todos(this)" ') => 'print',
        'Autorizado' => 'matri',
        'Necessita de transporte' => 'transp'
    ];
}
/**
  //gerar porcentagem de frequencia
  $idPlAnt = 89;
  $f = $model->frequeciaAluno($idPlAnt);
  foreach ($f as $k => $v){
  if(!empty($v[0]) && !empty($v[1])){
  $porc = intval(($v[1]/($v[0]+$v[1]))*100);
  } else {
  $porc = 0;
  }

  echo '<br />'. $sql= "update maker_gt_turma_aluno ta "
  . " join maker_gt_turma t on t.id_turma = ta.fk_id_turma and t.fk_id_pl = $id_pl and fk_id_pessoa = $k "
  . " set porc_frequencia_ant = $porc ";
  $query = pdoSis::getInstance()->query($sql);

  }
 * 
 */
?>
<div class="body">
    <div class="fieldTop">
        Autorização para Rematriculas
    </div>
    <?php
    if (toolErp::id_nilvel() != 8) {
        ?>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
            </div>
        </div>
        <br />
        <?php
    }
    ?>
    <br /><br />
    <?php
    if (empty($maker)) {
        ?>
        <div class="alert alert-danger">
            Sua escola não está configurada para participar das Salas Maker
        </div>
        <?php
    } elseif ($id_inst) {
        ?>
        <div class="row">
            <div class="col-3">
                <?= formErp::selectNum('ano', [4, 9, 'º Ano'], 'Ano', $ano, 1, ['id_inst' => $id_inst]) ?>
            </div>
            <div class="col-3" style="font-weight: bold; font-size: 1,4em; padding-top: 10px">
                <?php
                if (!empty($form)) {
                    echo count($form['array']) . ' Alunos';
                }
                ?>
            </div>
            <div class="col-3" style="font-weight: bold; color: red; padding-top: 10px">

            </div>
            <div class = "col-3" style = "text-align: right; padding-right: 50px">
                <?php
                if (!empty($form)) {
                    ?>
                    <form target="_brank" action="<?= HOME_URI ?>/maker/pdf/remat" method="POST">
                        <button type="button" onclick="des()" class="btn btn-outline-dark">
                             Declaração de Confirmação
                        </button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <br />
        <?php
        if (!empty($form)) {
            if (false) {
                ?>
                <form target="_brank" action="<?= HOME_URI ?>/maker/pdf/remat" method="POST">
                    <div class="row">
                        <div class="col-4">
                            <button type="button" onclick="des()" class="btn btn-outline-dark">
                                 Declaração de Confirmação
                            </button>
                        </div>
                        <div class="col-4" style="font-weight: bold; font-size: 1.1em; text-align: center">
                            A quantidade máxima de impressão por PDF são <span id="mx"></span> Termos
                        </div>
                        <div class="col-4">
                            <button id="btnPrint" class="btn btn-primary" type="submit"  style="display: none">
                                Imprimir Termo de Requerimento de Inscrição
                            </button>
                            <button id="btnNoPrint" type="button" onclick="seleciona()" class="btn btn-secondary">
                                Imprimir Termo de Requerimento de Inscrição
                            </button>
                        </div>
                    </div>
                    <br />
                </form>
                <?php
            }
            report::simple($form);
            echo formErp::hidden([
                'id_inst' => $id_inst,
                'n_inst' => $n_inst,
                'n_polo' => $maker['sede']
            ]);
            ?>
            <?php
        }
    }
    ?>
</div>
<form target="_blank" id="dess" action="<?= HOME_URI ?>/maker/pdf/des" method="POST">
    <input type="hidden" name="n_inst" value="<?= $n_inst ?>" />
    <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
    <input type="hidden" name="id" value="<?= toolErp::id_pessoa() ?>" />
    <input type="hidden" name="id_pl" value="<?= $id_pl ?>" />
    <input type="hidden" name="data" value="<?= base64_encode(date("Y-m-d")) ?>" />
</form>
<script>
    conta = 0;
    max = 30;
    mx.innerHTML = max;
    function seleciona() {
        if (conta < 1) {
            alert('Selecione os Alunos para impressão');
        }
    }
    function pt(i) {
        if (i.checked === true) {
            conta++;
        } else {
            conta--;
        }
        if (conta > max) {
            if (i.checked === true) {
                alert("A quantidade máxima de impressão por PDF são " + max + " Termos");
            }
            sobrou = conta - max;
            if (sobrou > 1) {
                s = 's';
            } else {
                s = '';
            }
            qt = sobrou;
            btnPrint.style.display = 'none';
            btnNoPrint.innerHTML = ' Desmarque ' + qt + ' aluno' + s + ' para debloquear a impressão';
            btnNoPrint.style.display = '';
        } else if (conta < 1) {
            btnPrint.style.display = 'none';
            btnNoPrint.innerHTML = 'Imprimir Termo de Requerimento de Inscrição';
            btnNoPrint.style.display = '';
        } else {
            btnPrint.style.display = '';
            btnNoPrint.style.display = 'none';
        }
    }
    function todos(i) {
        $('.printr').not(i).prop('checked', i.checked);
        if (i.checked === true) {
            conta = <?= count($alunos) ?>;
            if (conta > max) {
                sobrou = conta - max;
                if (sobrou > 1) {
                    s = 's';
                } else {
                    s = '';
                }
                qt = sobrou;
                alert("A quantidade máxima de impressão por PDF são " + max + " Termos");
                btnPrint.style.display = 'none';
                btnNoPrint.innerHTML = ' Desmarque ' + qt + ' aluno' + s + ' para debloquear a impressão';
                btnNoPrint.style.display = '';

            }
        } else {
            conta = 0;
            btnPrint.style.display = 'none';
            btnNoPrint.innerHTML = 'Imprimir Termo de Requerimento de Inscrição';
            btnNoPrint.style.display = '';
        }
    }
    function lanc(id, id_mc, i) {
        chck = document.getElementById('switch-shadow-retirada' + id);
        chckTrsp = document.getElementById('switch-shadow-transp' + id);
        if (i.checked) {
            sit = 1;
        } else {
            if (chckTrsp.checked) {
                chckTrsp.checked = false;
                lancTransp(id, id_mc, chckTrsp);
            }
            sit = 0;
        }
        dados = 'sim=' + sit
                + '&id_pessoa=' + id
                + '&id_mc=' + id_mc
                + '&fk_id_pessoa_lanc=<?= toolErp::id_pessoa() ?>';
        fetch('<?= HOME_URI ?>/maker/autoriza', {
            method: "POST",
            body: dados,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
                .then(resp => resp.text())
                .then(resp => {
                    console.log(resp);
                    if (resp != 1) {
                        alert('Houve um erro na conexão. Por Favor, tente mais tarde')
                        if (sit == 1) {
                            chck.checked = false;
                        } else {
                            chck.checked = true;
                        }


                    }
                });
    }
    function lancTransp(id, id_mc, i) {
        chckAut = document.getElementById('switch-shadow-retirada' + id);
        chck = document.getElementById('switch-shadow-transp' + id);
        debugger;
        if (i.checked) {
            if (!chckAut.checked) {
                chckAut.checked = true;
                lanc(id, id_mc, chckAut);
            }
            sit = 1;
        } else {
            sit = 0;
        }
        dados = 'transporte=' + sit
                + '&id_pessoa=' + id
                + '&id_mc=' + id_mc
                + '&fk_id_pessoa_lanc=<?= toolErp::id_pessoa() ?>';
        fetch('<?= HOME_URI ?>/maker/transpote', {
            method: "POST",
            body: dados,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
                .then(resp => resp.text())
                .then(resp => {
                    console.log(resp);
                    if (resp != 1) {
                        alert('Houve um erro na conexão. Por Favor, tente mais tarde')
                        if (sit == 1) {
                            chck.checked = false;
                        } else {
                            chck.checked = true;
                        }


                    }
                });
    }
    function des() {
        //if (confirm("O documeto válido será o gerado no dia 1 de Fevereiro de 2023, mas você pode abrir para conferencia.")) {
            dess.submit();
        //}
    }
</script>