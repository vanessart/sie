<?php
$id_cargo = @$_POST['id_cargo'];
$id_inst = @$_POST['id_inst'];
var_dump($id_inst);
$cg = sql::get('cadam_cargo', 'id_cargo, n_cargo', ['>' => 'ordem']);
$options = tool::idName($cg);
$options['tea'] = 'TEA';
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$descr = sql::get(['dttie_suport_diag', 'pessoa'], 'n_pessoa, lado, descr, data', ['fk_id_sup' => @$id, '>' => 'data']);
$escola = @$_POST['escola'];
$prev = data::converteBr(@$_POST['prev']);
?>
<br /><br />
<div class="fieldBody">
    <form method="POST">
        <div class="row">
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
    if (!empty($id_cargo)) {
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
            <!--<br /><br />
            <div style="text-align: center; font-size: 16px">
                Listagem por Disciplina com dados do CADAMPE
                - 
                <?php //echo sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'] ?>
            </div>
            <br />-->
            <div style="text-align: center; font-size: 16px">
                Departamento Técnico de Gestão de Pessoal
            </div>
            <br /><br />
            <?php
            if ($_POST['id_cargo'] != 'tea') {

                $clas = sql::get('cadam_class');

                foreach ($clas as $v) {
                    @$class[$v['fk_id_inscr']][$v['fk_id_cargo']] = $v['class'];
                }
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
                        <th colspan="8" style="text-align: center">
                            Período
                        </th>
                    </tr>
                    <tr style="background-color: #E0E0E0; border-color: #000000">
                        <td>
                            Class Única
                        </td>   
                        <td>
                            Class
                        </td>                          
                        <td>
                            Tipo Processo
                        </td>
                        <td>
                            Nome CADAMPE
                        </td>
                        <td>
                            Telefones
                        </td>
                        <td>
                            E-mail
                        </td>
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
                        <td>

                        </td>
                    </tr>
                    <?php
                    if (!empty(@$dados[$id_cargo])) {
                        //   ksort($dados[$v['id_cargo']]);
                        $count_class = 1;
                        foreach ($dados[$id_cargo] as $i) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $count_class . 'º'; ?>
                                </td>                            
                                <td>
                                    <?php echo @$i['class'] . 'º' ?>
                                </td>
                                <td>
                                    <?php echo @$i['n_sel'] ?>
                                </td>
                                <td>
                                    <?php echo @$i['n_pessoa'] ?>
                                </td>
                                <td>
                                    <?php echo (!empty($i['tel1']) ? $i['tel1'] : '') . (!empty($i['tel2']) ? '<br />' . $i['tel2'] : '') . (!empty($i['tel3']) ? '<br />' . $i['tel3'] : '') ?>
                                </td>
                                <td>
                                    <?php echo @$i['email'] ?>
                                </td>
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
                                <td>
                                    <form method="POST">
                                        <?php
                                        echo formulario::hidden($i);
                                        ?>
                                        <input type="hidden" name="prev" value="<?php echo @$prev ?>" />
                                        <input type="hidden" name="id" value="<?php echo @$id ?>" />
                                        <input type="hidden" name="id_inst" value="<?php echo @$id_inst ?>" />
                                        <input type="hidden" name="escola" value="<?php echo $escola ?>" />
                                        <input type="hidden" name="novo" value="1" />
                                        <input type="hidden" name="id_cargo" value="<?php echo $id_cargo ?>" />
                                        <input class="btn btn-success" type="submit" value="Contactar" />
                                    </form>
                                </td>
                            </tr>

                            <?php
                            $count_class++;
                        }
                    }
                    ?>
                </table>
                <?php
            } else {
            echo    $sql = "select distinct p.n_pessoa, pc.n_pessoa as cadampe, p.tel1, p.tel2, p.tel3, p.ddd1, p.ddd2, p.ddd3 from cadam_tea tea "
                        . " join cadam_cadastro c on c.id_cad = tea.fk_id_cad "
                        . " join pessoa p on p.id_pessoa = tea.fk_id_pessoa "
                        . " join pessoa pc on pc.id_pessoa = c.fk_id_pessoa "
                        . " where fk_id_inst = " . $id_inst . " "
                        . " and c.antigo = 1 "
                        . " and ativo = 1 "
                        . " order by n_pessoa ";
                $query = $model->db->query($sql);
                $array = $query->fetchAll();
                foreach ($array as $kk => $vv){
                    $array[$kk]['t1']= !empty($vv['tel1'])?'('.$vv['ddd1'].') '.$vv['tel1']:NULL;
                    $array[$kk]['t2']= !empty($vv['tel2'])?'('.$vv['ddd2'].') '.$vv['tel2']:NULL;
                    $array[$kk]['t3']= !empty($vv['tel3'])?'('.$vv['ddd3'].') '.$vv['tel3']:NULL;
                }
                $form['array'] = $array;
                $form['fields'] = [
                    'Aluno' => 'n_pessoa',
                    'Professor' => 'cadampe',
                    'tel1'=>'t1',
                    'tel2'=>'t2',
                    'tel3'=>'t3'
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
        $dt_hora = date("Y-m-d H:i:s");
        $id_cad = filter_input(INPUT_POST, 'id_cad', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        //var_dump($id_inst);
        //echo $sql = "update cadam_classificacao SET contato_esc = '$dt_hora' WHERE fk_id_cad = $id_cad ";
        $query = $model->db->query($sql);

        $sql2 = "update cadam_escola SET contato_esc = '$dt_hora' WHERE fk_id_cad = $id_cad ";
        $query = $model->db->query($sql2);
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

            <form method="POST">
            <div class="row">
                <div class="col-sm-3">
                    <?php echo formulario::input('1[dt_cs]', 'Data da Escola', NULL, data::converteBr(date("Y-m-d"))) ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    $opt = [
                        1 => 'Contactado com sucesso e aceitou as aulas',
                        2 => 'Não foi possível Contactar',
                        3 => 'Já está alocado',
                        4 => 'Contactado com sucesso, mas recusou as aulas'
                    ];

                    formulario::select('1[justifica]', $opt, 'Justificativa', 1);
                    ?>
                    
                    <!--<select name="1[justifica]">
                        <?php
                        foreach ($opt as $k => $v) {
                            ?>
                            <option value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>-->
                </div>
            </div>
            <br /><br />
            <div class="row">

                <br />
                <div class="col-sm-12">
                    <div style="font-weight: bold">
                        Observação do Atendimento:
                    </div>
                    <textarea name="1[obs_cs]" style="width: 100%"></textarea>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-6 text-center">
                    <?php
                    echo formulario::hidden(['dt_hora' => date("Y-m-d  H:i:s")]);
                    echo DB::hiddenKey('cadam_convoca_sup', 'replace')
                    ?>
                    <input type="hidden" name="id" value="<?php echo @$id ?>" />
                    <input type="hidden" name="escola" value="<?php echo $escola ?>" />
                    <input type="hidden" name="1[dt_hora]" value="<?php echo $dt_hora ?>" />
                    <input type="hidden" name="1[fk_id_cad]" value="<?php echo $id_cad ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="1[fk_id_inst]" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="id_cargo" value="<?php echo $id_cargo ?>" />
                    <input class="btn btn-success" type="submit" value="Salvar" />
                </div>
                <div class="col-sm-6 text-center">
                    <input onclick="document.getElementById('desfazer').submit()" class="btn btn-danger" type="button" value="Desfazer" />
                </div>
            </div>
            <br /><br />
        </form>
        <form id="desfazer" method="POST">
            <input type="hidden" name="id" value="<?php echo @$id ?>" />
            <input type="hidden" name="escola" value="<?php echo $escola ?>" />
            <input type="hidden" name="1[contato_esc]" value="<?php echo $contatoOld ?>" />
            <input type="hidden" name="1[id_ce]" value="<?php echo $i['id'] ?>" />
            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
            <input type="hidden" name="id_cargo" value="<?php echo $id_cargo ?>" />
            <?php echo DB::hiddenKey('cadam_escola', 'replace') ?>
        </form>
        <?php
        tool::modalFim();
    }
    ?>
</div>
