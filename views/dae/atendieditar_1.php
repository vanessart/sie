<?php
javaScript::cep();

@$id_aten = $_POST['id_atendimento'];

$dep = sql::get('dae_departamento', '*', ['>' => 'n_departamento']);
$tip = sql::get('dae_tipo_contato', '*', ['>' => 'n_contato']);
$mot = sql::get('dae_motivo', '*', ['>' => 'n_motivo']);
$ens = sql::get('dae_tipo_ensino', '*', ['>' => 'id_tipo_ensino']);
$cic = sql::get('ge_ciclos', 'id_ciclo, n_ciclo', ['>' => 'n_ciclo']);
$esc = sql::get(['instancia', 'ge_escolas'], 'id_inst, n_inst', ['>' => 'n_inst']);
$sta = sql::get('dae_status', 'd_status', ['>' => 'd_status']);
$est = sql::get('estados', 'sigla', ['>' => 'sigla']);
?>
<div style="font-size: 20px; padding: 20px">

    <?php
    $modalAct = empty($_REQUEST['l']) ? 1 : null;
    tool::modalInicio('width: 100%', $modalAct);
    ?>
    <form method="POST">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Atendimento Escolar
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo formulario::input('1[id_atendimento]', 'Nº Protocolo', null, @$_POST['id_atenedimento'], 'readonly', 'Não Digite Aqui') ?>
                        </div>   
                        <div class="col-md-3">
                            <?php echo formulario::input('1[dt_inicio]', 'Data Inicio', NULL, data::converteBr(@$_POST['dt_inicio']), formulario::dataConf(), 'Digite Dia, Mês e Ano') ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo formulario::input('1[dt_fim]', 'Data Final', null, data::converteBr(@$_POST['dt_fim']), formulario::dataConf(), 'Digite Dia, Mês e Ano') ?>
                        </div>  
                        <div class="col-md-3">
                            <?php
                            foreach ($sta as $st) {
                                $stt[$st['d_status']] = $st['d_status'];
                            }
                            formulario::select('1[status]', $stt, 'Status');
                            ?>
                        </div>

                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            foreach ($tip as $c) {
                                $cc[$c['id_contato']] = $c['n_contato'];
                            }
                            formulario::select('1[fk_id_contato]', $cc, 'Tipo Contato')
                            ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[oficio]', 'Ofício nº.', null, @$_POST['oficio'], null, 'Digite xxxx/2017') ?>
                        </div>  
                        <div class="col-md-4">
                            <?php
                            foreach ($mot as $m) {
                                $mm[$m['id_motivo']] = $m['n_motivo'];
                            }
                            formulario::select('1[fk_id_motivo]', $mm, 'Motivo do Contato')
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            foreach ($dep as $d) {
                                $dd[$d['id_departamento']] = $d['n_departamento'];
                            }
                            formulario::select('1[fk_id_departamento]', $dd, 'Departamento')
                            ?>
                        </div>
                    </div>
                    <br />
                    <div>
                        <?php echo formulario::input('1[descricao]', 'Descrição', null, @$_POST['descricao']) ?>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            foreach ($ens as $t) {
                                $tt[$t['id_tipo_ensino']] = $t['n_tipo_ensino'];
                            }
                            formulario::select('1[fk_id_tipo_ensino]', $tt, 'Tipo Ensino');
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            foreach ($cic as $ci) {
                                $cci[$ci['id_ciclo']] = $ci['n_ciclo'];
                            }
                            formulario::select('1[fk_id_ciclo]', $cci, 'Ciclo');
                            ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            foreach ($esc as $eo) {
                                $eoo[$eo['id_inst']] = $eo['n_inst'];
                            }
                            formulario::select('1[fk_escola_origem]', $eoo, 'Escola Origem');
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            foreach ($esc as $ed) {
                                $edd[$ed['id_inst']] = $ed['n_inst'];
                            }
                            formulario::select('1[fk_escola_destino]', $edd, 'Escola Destino');
                            ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6"> 
                            <?php echo formulario::input('1[solicitante]', 'Solicitante', null, @$_POST['solicitante']) ?>
                        </div>
                        <div class="col-md-2">         
                            <?php formulario::select('1[sexo_solic]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$_POST['sexo_solic']) ?>
                        </div>                
                        <div class="col-md-4">         
                            <?php echo formulario::input('1[rg_solic]', 'Nº RG - UF', null, @$_POST['rg_solic']) ?>        
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo formulario::input('1[nome_aluno]', 'Nome da Criança', null, @$_POST['nome_aluno']) ?>
                        </div>
                        <div class="col-md-2">
                            <?php formulario::select('1[sexo_aluno]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$_POST['sexo_aluno']) ?>
                        </div>
                        <div class="col-md-4">         
                            <?php echo formulario::input('1[rse]', 'RSE', null, @$_POST['rse'], null, 'Caso Aluno da Rede, Informe RSE') ?>        
                        </div>    
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-2">  
                            <?php echo formulario::input('1[cep]', 'CEP', null, @$_POST['cep'], 'id="cep"') ?>  
                        </div>
                        <div class="col-md-4">
                            <?php echo formulario::input('1[logradouro]', 'Endereço', null, @$_POST['logradouro'], 'id="rua"') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[num]', 'Número', null, @$_POST['num']) ?>
                        </div>
                        <div class="col-md-4">   
                            <?php echo formulario::input('1[complemento]', 'Complemento', null, @$_POST['complemento']) ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo formulario::input('1[telefone]', 'Telefone', null, @$_POST['telefone'], null, 'Digite aqui os telefones para contato, p.ex 1234-4567 - 12345-6789') ?>
                        </div>

                        <div class="col-md-4">        
                            <?php echo formulario::input('1[cidade]', 'Cidade', null, @$_POST['cidade'], 'id="cidade"') ?>
                        </div>
                        <div class="col-md-2">     
                            <?php formulario::input('1[uf]', 'Estado', NULL, @$_POST['uf'], ' id="uf"  ') ?>
                        </div>
                    </div>
                    <br />
                    <?php echo formulario::input('1[procedimento]', 'Procedimento', null, @$_POST['procedimento']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 text-center">
                <a class="art-button" style="width: 45%" href="#?l=1">
                    Limpar
                </a>  
            </div>

            <div class="col-lg-6 text-center">
                <?php echo DB::hiddenKey('dae_atendimento', 'replace') ?>
                <input type="hidden" name="1[id_atendimento]" value="<?php echo @$_POST['id_atendimento'] ?>" />
                <input type="submit" style="width: 45%" class ="art-button" value="Salvar" />
            </div>
        </div>
    </form>
</div>
<?php
tool::modalFim();
?>

<br />&nbsp;
<?php
$at = sql::get('dae_atendimento', '*', ['<' => 'id_atendimento']);
$sqlkey = DB::sqlKey('dae_atendimento', 'delete');

foreach ($at as $key => $v) {
    $v['id_aten'] = $v['id_atendimento'];
    $v['l'] = 1;
    $at[$key]['excluir'] = formulario::submit('Excluir', $sqlkey, ['1[id_atendimento]' => $v['id_atendimento']]);
    $at[$key]['aten'] = formulario::submit('Editar', null, $v, HOME_URI . '/dae/atendimento');
    $at[$key]['imp'] = formulario::submit('Imprimir', null, $v, HOME_URI . '/dae/protocolo');
}
$form['array'] = $at;
$form['fields'] = [
    'Código Atendimento' => 'id_atendimento',
    'Solicitante' => 'solicitante',
    'Data' => 'dt_inicio',
    'Departamento' => 'fk_id_departamento',
    'Contato' => 'fk_id_contato',
    'Motivo Contato' => 'fk_id_motivo',
    '||1' => 'excluir',
    '||2' => 'aten',
    '||3' => 'imp'
];
tool::relatSimples($form);
?>


