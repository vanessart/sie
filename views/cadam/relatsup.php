<?php
$id_cargo = @$_POST['id_cargo'];
$id_inst = @$_POST['id_inst'];
$cg = sql::get('cadam_cargo', 'id_cargo, n_cargo', ['>' => 'ordem']);
$options = tool::idName($cg);
$options['tea'] = 'TEA';
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$descr = sql::get(['dttie_suport_diag', 'pessoa'], 'n_pessoa, lado, descr, data', ['fk_id_sup' => @$id, '>' => 'data']);
$escola = @$_POST['escola'];
$prev = data::converteBr(@$_POST['prev']);

//echo '<pre>';
//$cargo = cadamp::carrega_cargos();
//exit;
//bloqueio de tela deve ser feito por protocolo e não por cadamp
$id_sup = $id;

if (empty($id_sup)) {
    echo '<div style="margin:50px auto; width:600px; text-align:center;"><p>';
    echo '<strong>AVISO:</strong> <br>';
    echo 'Utilize a opção "SUPORTE" do menu para pesquisar por protocolo ou <a href="/ge/dttie/suportepesq/"><strong>acesse por aqui.</strong></a>';
    echo '<br><br>Equipe de Desenvolvimento';
    echo '</p></div>';
    exit;
}

//clicou no botão salvar
if (!empty($_POST['novo2'])) {

    $dt_hora = date("Y-m-d H:i:s");

    $justifica = $_POST['1']['justifica'];
    $id_cad = $_POST['1']['fk_id_cad'];
    $rodizio = $_POST['rodizio'];
    $id_cargo = $_POST['id_cargo'];

    // correção calssificação lista cadamps
    $rodizio = $rodizio + 1;
    //$sql = "UPDATE cadam_classificacao_cargo_copy SET rodizio = $rodizio WHERE fk_id_cad = $id_cad and fk_id_cargo = $id_cargo ";
    //$query = $model->db->query($sql);
    //desbloqueia tela
    //cadamp::desbloqueia_tela($id_sup); // por protocolo
    // cadamp::desbloqueia_tela_cadamp($id_cad); // por cadamp
    //sql atualiza classificação
    //$sql = "UPDATE cadam_classificacao_cargo SET contato_esc = '$dt_hora' WHERE fk_id_cad = $id_cad ";
    //se selecionou justificativa 2 - Não coi possível contactar

    if ($justifica == 2) {

        //registra tentativa, independente da data
        cadamp::registra_tentativas($justifica, $id_cad);

        //consulta as tentativas ja feitas para o cadamp
        $tentativas = cadamp::tentativas_atendimento($id_cad);

        //se tentativas for maior a 2, atualiza a terceira e coloca no final da fila
        if ($tentativas > 2) {

            //atualiza classificação
            //$query = $model->db->query($sql);
            cadamp::atualiza_classificacao($rodizio, $id_cad, $id_cargo);

            //remove tentativas
            cadamp::remove_tentativas($id_cad);
        }
    } else {
        //atualiza classificação
        //$query = $model->db->query($sql);
        cadamp::atualiza_classificacao($rodizio, $id_cad, $id_cargo);
    }
}


$optJust = [
    1 => 'Contactado com sucesso e aceitou as aulas',
    2 => 'Não foi possível Contactar',
    3 => 'Já está alocado',
    4 => 'Contactado com sucesso, mas recusou as aulas'
];
?>
<br /><br />
<div class="fieldBody">
    <form method="POST">
        <div class="row">
            <div class="col-sm-12" style="font-weight: bold; font-size: 22px">
                Escola: <?php echo $escola ?>
                <br /><br />
                Para o dia/A partir do dia <?php echo $prev ?>
                <br /><br />
            </div>
            <br />
<?php
foreach ($descr as $d) {
    ?>
                <br />
                <div style="font-style: italic; color: black">
                    <pre style="border-radius: 10px"><span style="color: #0B94EF"><?php echo $d['lado'] ?> - <?php echo $d['n_pessoa'] ?> disse: (<?php echo data::converteBr(substr($d['data'], 0, 11)) ?>  <?php echo substr($d['data'], 11) ?>)</span><br /><?php echo $d['descr'] ?></pre>
                </div>
                <br />
                <?php
            }
            ?>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-5">
            <?php
            formulario::select('id_inst', escolas::idInst(), 'Escola', $id_inst, NULL, ['teaSecr' => 1]);
            ?>
            </div>       
            <div class="col-sm-5">
<?php
formulario::select('id_cargo', $options, 'Cargo', $id_cargo);
?>
            </div>
            <div class="col-sm-2">
                <input type="hidden" name="prev" value="<?php echo @$prev ?>" />
                <input type="hidden" name="id" value="<?php echo @$id ?>" />
                <input type="hidden" name="escola" value="<?php echo $escola ?>" />
                <input class="btn btn-info" type="submit" value="Buscar" />
            </div>
        </div>

    </form>

<?php
if (!empty($id_inst) && !empty($id_cargo)) {
    ?>
        <style>
            th{
                background: black;
                color: white;
            }
            td{
                padding: 5px;
            }
        </style>
        <div class="fieldBody">
            <br /><br />
            <div style="text-align: center; font-size: 16px">
                Listagem por Disciplina com dados do CADAMPE
                - 
    <?php echo sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'] ?>
            </div>
            <br />
            <div style="text-align: center; font-size: 16px">
                Departamento Técnico de Gestão de Pessoal
            </div>
            <br />
                <?php
                if ($_POST['id_cargo'] != 'tea') {
                    /*
                      $clas = sql::get('cadam_class');

                      foreach ($clas as $v) {
                      @$class[$v['fk_id_inscr']][$v['fk_id_cargo']] = $v['class'];
                      }

                     */
                    //$d = cadamp::cadampesPorEscola($id_inst);
                    $d = cadamp::cadampesPorCargo($id_cargo);

                    foreach ($d as $v) {
                        $dados[$v['fk_id_cargo']][] = $v;
                    }
                    ?>
                <br /><br />
                <div style="text-align: left; font-size: 12px">
                    M = Manhã; T = Tarde; N = Noite
                </div>

                <table class="table table-bordered table-hover table-striped" >

                    <tr>
                        <th colspan="5" style="text-align: left">
        <?php echo $options[$id_cargo] ?>
                        </th>
                        <th colspan="9" style="text-align: center">
                            Período
                        </th>
                    </tr>
                    <tr style="background-color: #E0E0E0; border-color: #000000">
                        <td>
                            Rodízio
                        </td>
                        <td>
                            Class.
                        </td>
                        <td>
                            Tipo Processo
                        </td>
                        <td>
                            Nome CADAMPE
                        </td>
                        <td>TEA</td>
                        <td>
                            Telefones
                        </td>
                        <td>
                            E-mail
                        </td>
                        <!--
                        <td>
                            Seg
                        </td>
                        <td>
                            Ter
                        </td>
                        <td>
                            Qua
                        </td>
                        <td>
                            Qui
                        </td>
                        <td>
                            Sex
                        </td>
                        <td>
                            Último Contato
                        </td>
                        <td>
                            Hora
                        </td>
                        -->
                        <td>
                            Status
                        </td>
                        <!-- botao contactar -->
                        <td>
                        </td>

                        <!-- botao histórico -->
                        <td>
                        </td>

                        <!-- situação do atendimento -->
                        <td>
                        </td>
                    </tr>
        <?php
        if (!empty(@$dados[$id_cargo])) {
            //   ksort($dados[$v['id_cargo']]);
            foreach ($dados[$id_cargo] as $i) {
                //var_dump($i['id_cad']);
                $arr_historico = cadamp::historico_atendimento($i['id_cad']);
                ?>
                            <tr>
                                <td>
                            <?php echo @$i['rodizio'] ?>
                                </td>
                                <td>
                            <?php echo @$i['class'] . 'º' ?>
                                </td>
                                <td nowrap="nowrap">
                                    <?php echo @$i['n_sel'] ?>
                                </td>
                                <td>
                                    <?php echo @$i['n_pessoa'] ?>
                                </td>
                                <td>
                                    <?php echo ($i['tea'] == 0) ? 'NÃO' : 'SIM'; ?>
                                </td>
                                <td>
                                    <?php echo (!empty($i['tel1']) ? $i['tel1'] : '') . (!empty($i['tel2']) ? '<br />' . $i['tel2'] : '') . (!empty($i['tel3']) ? '<br />' . $i['tel3'] : '') ?>
                                </td>
                                <td>
                                    <?php echo @$i['email'] ?>
                                </td>
                                <!--
                                <td style="text-align: center; white-space: nowrap">
                                    <?php echo @$i['dias'][1] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                    <?php echo @$i['dias'][2] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                <?php echo @$i['dias'][3] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                <?php echo @$i['dias'][4] ?>
                                </td>
                                <td style="text-align: center; white-space: nowrap">
                                <?php echo @$i['dias'][5] ?>
                                </td>
                                <td>
                                <?php echo data::converteBr(substr(@$i['contato_esc'], 0, 10)) ?>
                                </td>
                                <td>
                                <?php echo substr(@$i['contato_esc'], 10) ?>
                                </td>
                                -->
                                <!-- situação do atendimento -->                                
                                <?php
                                $dt_atendimento = '';
                                if (isset($arr_historico[0]['dt_hora'])) {
                                    $timezone = new DateTimeZone('America/Sao_Paulo');
                                    $now = new DateTime($arr_historico[0]['dt_hora'], $timezone);
                                    $dt_atendimento = $now->format('d/m/Y H:i');
                                }
                                ?>
                                <td nowrap="nowrap">
                                    <ul>
                                        <li><strong>Data Atendimento:</strong> <?php echo $dt_atendimento ?></li>
                                        <li><strong>Atendente:</strong> <?php echo (isset($arr_historico[0]['atendente'])) ? $arr_historico[0]['atendente'] : '' ?></li>
                                        <li><strong>Situação:</strong> <?php echo (isset($arr_historico[0]['justifica'])) ? $optJust[$arr_historico[0]['justifica']] : '' ?></li>
                                    </ul>
                                </td>
                                <td>
                                    <form method="POST">
                <?php
                echo formulario::hidden($i);
                ?>
                                        <input type="hidden" name="prev" value="<?php echo @$prev ?>" />
                                        <input type="hidden" name="id" value="<?php echo @$id ?>" />
                                        <input type="hidden" name="escola" value="<?php echo $escola ?>" />
                                        <input type="hidden" name="novo" value="1" />
                                        <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                                        <input type="hidden" name="id_cargo" value="<?php echo $id_cargo ?>" />
                                        <input type="hidden" name="rodizio" value="<?php echo $i['rodizio'] ?>" />
                                        <input class="btn btn-success" type="submit" value="Contactar" />
                                    </form>
                                </td>
                                <td>
                <?php
                //var_dump($arr_historico);
                ?>

                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal<?php echo $i['id_cad'] ?>">
                                        Abrir histórico
                                    </button>


                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal<?php echo $i['id_cad'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-lg" role="document">

                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Histórico de Atendimento: <?php echo ucfirst(strtoupper($i['n_pessoa'])) ?></h4>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- lista histórico-->
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Data Atendimento</th>
                                                                <th>Obs</th>
                                                                <th>Justificativa</th>
                                                                <th>Atendente</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                <?php
                foreach ($arr_historico as $c => $v) {

                    $now = new DateTime($v['dt_hora']);
                    $dt_hora_saida = $now->format('d/m/Y H:i:s');
                    ?>
                                                                <tr>
                                                                    <td nowrap="nowrap"><?php echo $dt_hora_saida ?></td>
                                                                    <td><?php echo $v['obs_cs'] ?></td>
                                                                    <td nowrap="nowrap"><?php echo $optJust[$v['justifica']] ?></td>
                                                                    <td nowrap="nowrap"><?php echo $v['atendente'] ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>

                                                    </table>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end modal -->

                                </td>




                            </tr>

                <?php
            }
        }
        ?>
                </table>
        <?php
    } else {
        $sql = "select distinct p.n_pessoa, pc.n_pessoa as cadampe, p.tel1, p.tel2, p.tel3, p.ddd1, p.ddd2, p.ddd3 from cadam_tea tea "
                . " join cadam_cadastro c on c.id_cad = tea.fk_id_cad "
                . " join pessoa p on p.id_pessoa = tea.fk_id_pessoa "
                . " join pessoa pc on pc.id_pessoa = c.fk_id_pessoa "
                . " where fk_id_inst = " . $id_inst . " "
                . " and c.antigo = 1 "
                . " and c.ativo = 1 "
                . " order by p.n_pessoa ";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $kk => $vv) {
            $array[$kk]['t1'] = !empty($vv['tel1']) ? '(' . $vv['ddd1'] . ') ' . $vv['tel1'] : NULL;
            $array[$kk]['t2'] = !empty($vv['tel2']) ? '(' . $vv['ddd2'] . ') ' . $vv['tel2'] : NULL;
            $array[$kk]['t3'] = !empty($vv['tel3']) ? '(' . $vv['ddd3'] . ') ' . $vv['tel3'] : NULL;
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Aluno' => 'n_pessoa',
            'Professor' => 'cadampe',
            'tel1' => 't1',
            'tel2' => 't2',
            'tel3' => 't3'
        ];
        tool::relatSimples($form);
    }
}
?>
    </div>
        <?php
        if (!empty($_POST['novo'])) {

            tool::modalInicio();

            $i = $_POST;
            $dias = explode('|', @$i['dia']);
            foreach ($dias as $d) {
                @$dp[substr($d, 0, 1)] .= substr($d, 1, 1) . ' ';
            }
            $contatoOld = @$_POST['contato_esc'];

            //$timezone = new DateTimeZone('America/Sao_Paulo');
            //$now = new DateTime('now', $timezone);
            //$dt_hora = $now->getTimestamp();
            $dt_hora = date('Y-m-d H:i:s');

            $id_cad = filter_input(INPUT_POST, 'id_cad', FILTER_SANITIZE_NUMBER_INT);
            $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
            $rodizio = filter_input(INPUT_POST, 'rodizio', FILTER_SANITIZE_NUMBER_INT);

            //verifica se a tela esta em uso
            //$arr_tela = cadamp::verifica_tela_bloqueada($id_sup);
            //$arr_tela_cadamp = cadamp::verifica_tela_bloqueada_cadamp($id_cad);
            //var_dump($arr_tela);
            //print_r($_SESSION['userdata']['n_pessoa']);
            /*
              echo   ' sup ' . $id_sup;
              echo '<br>';
              echo   ' cad ' . $id_cad;
              echo '<br>';
              echo $arr_tela['tela_bloqueada'];
              echo '<br>';
              echo $arr_tela_cadamp['tela_bloqueada'];
              echo '<br>';
              echo $arr_tela['atendente'];
              echo '<br>';
              echo $arr_tela_cadamp['atendente'];
              echo '<br>';
             */

            //cadamp

            /*
              if($arr_tela_cadamp['tela_bloqueada'] == 'S') {
              if($arr_tela_cadamp['atendente'] != $_SESSION['userdata']['n_pessoa'] ){
              echo 'Já existe um atendimento em andamento!';
              exit;
              }
              } else {
              //cadamp::bloqueia_tela($id_sup, $_SESSION['userdata']['n_pessoa']);
              //cadamp::bloqueia_tela_cadamp($id_cad, $_SESSION['userdata']['n_pessoa']);
              }

             */

            /*
              if (($arr_tela['tela_bloqueada'] == 'S' || $arr_tela_cadamp['tela_bloqueada'] == 'S') && ($arr_tela['atendente'] != $_SESSION['userdata']['n_pessoa'] || $arr_tela_cadamp['atendente'] != $_SESSION['userdata']['n_pessoa']) ) {
              echo 'Já existe um atendimento em andamento!';
              exit;
              } else {
              cadamp::bloqueia_tela($id_sup, $_SESSION['userdata']['n_pessoa']);
              cadamp::bloqueia_tela_cadamp($id_cad, $_SESSION['userdata']['n_pessoa']);
              }
             */

            //realiza o bloqueio de tela
            //$sql = "UPDATE cadam_convoca_sup SET tela_bloqueada = 'S',  data_tela_bloqueada = '$dt_hora' WHERE fk_id_cad = $id_cad ";
            //$query = $model->db->query($sql);
            ?>
        <br /><br />
        <table class="table table-bordered table-hover table-striped">
            <tr style="background-color: #E0E0E0; border-color: #000000; font-size: 22px">
                <td>
                    Nome CADAMPE
                </td>
                <td>
                    Telefones
                </td>
                <td>
                    E-mail
                </td>
            </tr>
            <tr style="font-size: 22px">
                <td>
    <?php echo @$i['n_pessoa'] ?>
                </td>
                <td>
    <?php echo (!empty($i['tel1']) ? $i['tel1'] : '') . (!empty($i['tel2']) ? '<br />' . $i['tel2'] : '') . (!empty($i['tel3']) ? '<br />' . $i['tel3'] : '') ?>
                </td>
                <td>
    <?php echo @$i['email'] ?>
                </td>
            </tr>
        </table>
        <table class="table table-bordered table-hover table-striped">
            <tr style="background-color: #E0E0E0; border-color: #000000; font-size: 22px">
                <td>
                    Seg
                </td>
                <td>
                    Ter
                </td>
                <td>
                    Qua
                </td>
                <td>
                    Qui
                </td>
                <td>
                    Sex
                </td>     
            </tr>

            <tr style="font-size: 22px">
                <td style="text-align: center; white-space: nowrap">
    <?php echo @$dp[1] ?>
                </td>
                <td style="text-align: center; white-space: nowrap">
    <?php echo @$dp[2] ?>
                </td>
                <td style="text-align: center; white-space: nowrap">
    <?php echo @$dp[3] ?>
                </td>
                <td style="text-align: center; white-space: nowrap">
    <?php echo @$dp[4] ?>
                </td>
                <td style="text-align: center; white-space: nowrap">
                    <?php echo @$dp[5] ?>
                </td>  
            </tr>
        </table>
        <br /><br />

        <form method="POST" id="frmSalvar">
            <div class="row">
                <div class="col-sm-3">
                    <?php echo formulario::input('1[dt_cs]', 'Data da Escola', NULL, data::converteBr(date("Y-m-d"))) ?>
                </div>
                <div class="col-sm-6">

                    Resposta a Solicitação 
                    <select id="justifica" name="1[justifica]">
    <?php
    foreach ($optJust as $k => $v) {
        ?>
                            <option value="<?php echo $k ?>"><?php echo $v ?></option>
        <?php
    }
    ?>
                    </select>
                </div>
            </div>
            <br /><br />
            <div class="row">

                <br />
                <div class="col-sm-12">
                    <div style="font-weight: bold">
                        Observação do Atendimento:
                    </div>
                    <textarea id="obs_cs" name="1[obs_cs]" style="width: 100%"></textarea>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-6 text-center">
    <?php
    echo formulario::hidden(['dt_hora' => date("Y-m-d H:i:s")]);
    echo DB::hiddenKey('cadam_convoca_sup', 'replace');
    ?>
                    <input type="hidden" name="novo2" value="1" />
                    <input type="hidden" name="id" value="<?php echo @$id ?>" />
                    <input type="hidden" name="escola" value="<?php echo $escola ?>" />
                    <input type="hidden" name="1[dt_hora]" value="<?php echo $dt_hora ?>" />
                    <input type="hidden" name="1[fk_id_cad]" value="<?php echo $id_cad ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="1[fk_id_inst]" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="id_cargo" value="<?php echo $id_cargo ?>" />
                    <input type="hidden" name="rodizio" value="<?php echo $rodizio ?>" />
                    <input type="hidden" name="1[atendente]" value="<?php echo $_SESSION['userdata']['n_pessoa'] ?>" />
                    <input id="btnSalvar" class="btn btn-success" type="button" value="Salvar" />

                </div>
                <div class="col-sm-6 text-center">
                    <input onclick="document.getElementById('desfazer').submit()" class="btn btn-danger" type="button" value="Desfazer" id="btnDesfazer" />
                </div>
            </div>
            <br /><br />
        </form>
        <form id="desfazer" method="POST">
            <input type="hidden" name="id" value="<?php echo @$id ?>" />
            <input type="hidden" name="escola" value="<?php echo $escola ?>" />
            <input type="hidden" name="1[contato_esc]" value="<?php echo $contatoOld ?>" />
            <input type="hidden" name="1[id]" value="<?php echo $i['id'] ?>" />
            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
            <input type="hidden" name="id_cargo" value="<?php echo $id_cargo ?>" />
    <?php echo DB::hiddenKey('cadam_classificacao_cargo_copy', 'replace') ?>
        </form>
    <?php
    tool::modalFim();
}
?>
</div>

<!-- Modal -->
<div class="modal fade" id="myModalAlerta" tabindex="-1" role="dialog" aria-labelledby="myModalAlerta">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Aviso!</h4>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning alert-dismissible" role="alert">
                    O campo <strong>"Observação do Atendimento"</strong> é obrigatório <br> 
                    quando Resposta a Solicitação for <strong>"<?php echo $optJust[2] ?>"</strong>.
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div><!-- end modal -->

<div class="modal fade" id="myModalTelaBloqueada" tabindex="-1" role="dialog" aria-labelledby="myModalTelaBloqueada">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Aviso!</h4>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning alert-dismissible" role="alert">
                    Já existe um atendimento em andamento para o Suporte n <strong>"<?php echo $id_sup ?>"</strong>.
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div><!-- end modal -->

<script>

    $('#btnSalvar').on('click', function () {

        var justifica = $("#justifica").val();
        var obs_cs = $("#obs_cs").val();

        //console.log(justifica);

        if (justifica == 2) {
            //obs obrigatório
            if (obs_cs == '') {
                $("#myModalAlerta").modal('show');
            } else {
                $('#frmSalvar').submit();
            }

        } else {
            $('#frmSalvar').submit();
        }

        return false;
    });

    $(document).ready(function () {

        $("#myModal").modal("show");

        $("#btnDesfazer").click(function () {
            //alert('desfazer');
            $("#myModal").modal("hide");
        });

        $("#myModal").on('hide.bs.modal', function () {
            //alert('The modal is about to be hidden.');
            $.ajax({
                type: "POST",
                url: "<?php echo HOME_URI . '/cadam/desbloqueiatela' ?>",
                data: {id_sup: <?php echo $id_sup ?>, id_cad: <?php echo $id_cad ?>},
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                }
            });
        });
    });

</script>
