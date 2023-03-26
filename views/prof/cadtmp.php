<?php
@$edit = $_POST['edit'];
@$fun = sql::get(['ge_funcionario','pessoa'], 'rm, n_pessoa', " where funcao like '%prof%' order by n_pessoa ");
foreach ($fun as $v){
    $funcionarios[$v['rm']]= $v['rm']. ' - ' . $v['n_pessoa'];
}
$disc = sql::get('ge_disciplinas');
foreach ($disc as $v) {
    $disci[$v['id_disc']] = $v['n_disc'];
}
$disci['nc'] = "Professor Polivalente";
if (!empty($_POST['disciplinas'])) {
    $disciplinas = explode('|', $_POST['disciplinas']);
}
if (!empty($_POST['n_pessoa'])) {
    $_POST['n_pe'] = $_POST['n_pessoa'];
    $edit = 1;
}
?>
<div class="fieldBody" style="background-color: window">
    <div class="fieldTop">
        Cadastro de Professores 2019
    </div>
    <br />
    <div>
        <div id="ab" style="display: <?php echo!empty($edit) ? "none" : '' ?>">
            <div class="col-md-12">
                <!--
                <a href="#" onclick="document.getElementById('fm').style.display = '';document.getElementById('ab').style.display = 'none'">
                    <button type="button" class=" btn btn-info">
                        Novo Cadastro
                    </button>
                </a>
                -->
                <form method="POST">
                    <input name="edit" class="btn btn-info" type="submit" value="Novo Cadastro" />
                </form>
            </div>          
        </div>

        <form method="POST">
            <div class="fieldWhite"  id="fm" style="display:  <?php echo!empty($edit) ? "" : 'none' ?>; width: 95%">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo formulario::select('rm', @$funcionarios, 'Matrícula e Nome', @$_POST['rm'], NULL, NULL, NULL, ' required ') ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-9">
                        <input type="hidden" id="emailOld" value="" />
                        <?php echo formulario::input('email', 'E-mail (necessário para o acesso ao Portal)', NULL, @$_POST['email'], ' id="emailPessoa" ') ?>
                    </div>
                    <div class="col-md-3 input-group">
                        <label  style="width: 100%">
                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                <input onclick="sem()" type="checkbox"   />
                            </span>
                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                O(A) professor(a) não tem e-mail
                            </span>
                        </label>
                        <label>


                        </label>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-3">
                        <input type="hidden" name="nao_hac" value="" />
                        <label  style="width: 100%">
                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                <input id="hcc" onclick="hac()" <?php echo @$_POST['nao_hac'] == 1 ? 'checked' : '' ?> type="checkbox" aria-label="..." name="nao_hac" value="1">
                            </span>
                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                Não faz HAC nesta escola
                            </span>
                        </label>

                    </div>
                    <div class="col-md-3"  id="hc2" style="display: <?php echo @$_POST['nao_hac'] == 1 ? 'none' : '' ?>">
                        HAC (<span style="color: red">De acordo com documento de atribuição</span>)
                    </div>
                    <div class="col-md-3"  id="hc1" style="display: <?php echo @$_POST['nao_hac'] == 1 ? 'none' : '' ?>">
                        <?php formulario::select('hac_dia', [2 => '2ª Feira', 3 => '3ª Feira', 4 => '4ª Feira', 5 => '5ª Feira'], 'Dia da Semana', @$_POST['hac_dia']) ?>
                    </div>
                    <div class="col-md-3" id="hc" style="display: <?php echo @$_POST['nao_hac'] == 1 ? 'none' : '' ?>">
                        <?php formulario::select('hac_periodo', ['Tarde' => 'Tarde', 'Noite' => 'Noite'], 'Período', @$_POST['hac_periodo']) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                            foreach ($disci as $k => $v) {
                                if (!empty($disciplinas)) {
                                    if (in_array($k, $disciplinas)) {
                                        $checked = "checked";
                                    } else {
                                        $checked = NULL;
                                    }
                                }
                                ?>
                                <div class="col-md-3">
                                    <div class="input-group" style="width: 100%">
                                        <label  style="width: 100%">
                                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                                <input id="<?php echo $k ?>" <?php echo @$checked ?> type="checkbox" aria-label="..." name="disc[]" value="<?php echo $k ?>">
                                            </span>
                                            <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                                <?php echo $v ?>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <input type="hidden" name="id_pe" value="<?php echo @$_POST['id_pe'] ?>" />

                </div>
                <br /><br />
                <div class="row">
                    <div class="col-md-4 text-center">
                        <button onmousemove="verif()" name="cadproftmp" value="1" type="submit" class=" btn btn-success">
                            Incluir
                        </button>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="#" onclick="document.lp.submit()">
                            <button type="button" class=" btn btn-info">
                                Limpar
                            </button>
                        </a>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="#" onclick="document.getElementById('fm').style.display = 'none';document.getElementById('ab').style.display = ''">
                            <button type="button" class=" btn btn-danger">
                                Fechar
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <form name="lp" method="POST">
            <input type="hidden" name="edit" value="1" />
        </form>
        <br /><br /><br />
        <div class="row">
            <div class="col-md-12">
                <?php
                $sql = "select distinct nao_hac, p.n_pessoa, p.email, pe.rm, pe.id_pe, pe.disciplinas, hac_periodo, hac_dia from ge_prof_esc_tmp pe "
                        . " join ge_funcionario f on f.rm = pe.rm "
                        . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                        . " where pe.fk_id_inst = " . tool::id_inst()
                        . " order by n_pessoa ";

                $query = $model->db->query($sql);
                $pf = $query->fetchAll();

                $sqlkey = DB::sqlKey('ge_prof_esc_tmp', 'delete');
                foreach ($pf as $k => $v) {
                    $nd = NULL;
                    $discip = explode('|', $v['disciplinas']);
                    $cont = 4;

                    foreach ($discip as $d) {
                        if ($d != '') {
                            if ($cont < count($discip)) {
                                @$nd .= $disci[$d] . ', ';
                            } else {
                                $nd .= $disci[$d] . ' e ';
                            }
                            $cont++;
                        }
                    }
                    $pf[$k]['disc'] = substr($nd, 0, -3);
                    $pf[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_pe]' => $v['id_pe']]);
                    $pf[$k]['edit'] = formulario::submit('Editar', Null, $v);
                    $pf[$k]['hac_dia'] = $v['hac_dia'] . 'ª Feira';
                }
                $form['array'] = $pf;
                $form['fields'] = [
                    'Professor' => 'n_pessoa',
                    'E-mali' => 'email',
                    'Matrícula' => 'rm',
                    'Disciplinas' => 'disc',
                    'Dia' => 'hac_dia',
                    'Período' => 'hac_periodo',
                    '||1' => 'del',
                    '||2' => 'edit'
                ];
                tool::relatSimples($form);
                ?>
            </div>
        </div>
    </div>



</div>
<script>
    function verif() {
        if (document.getElementById('9').checked == true && document.getElementById('12').checked == true && document.getElementById('6').checked == true) {
            alert('Tem certeza? Você não está querendo cadastrar um "Professor Polivalente" ');
        }
    }

    function sem() {
        if (document.getElementById("emailPessoa").value == 'Professor(a) sem e-mail') {
            document.getElementById("emailPessoa").checked = true;
            document.getElementById("emailPessoa").value = document.getElementById("emailOld").value;
            document.getElementById("emailPessoa").readOnly = false;
        } else {
            document.getElementById("emailPessoa").checked = false;
            document.getElementById("emailOld").value = document.getElementById("email").value;
            document.getElementById("emailPessoa").value = 'Professor(a) sem e-mail';
            document.getElementById("emailPessoa").readOnly = true;
        }
    }

    function hac() {
        if (document.getElementById("hcc").checked == true) {
            document.getElementById('hc').style.display = 'none'
            document.getElementById('hc1').style.display = 'none'
            document.getElementById('hc2').style.display = 'none'
        } else {
            document.getElementById('hc').style.display = ''
            document.getElementById('hc1').style.display = ''
            document.getElementById('hc2').style.display = ''
        }

    }
</script>
