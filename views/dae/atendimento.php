<?php

javaScript::cep();

if (empty($id_at)){
    $id_at = @$_POST['last_id'];
}else{
    @$id_at = $_POST['id_atendimento'];
}

$dep = sql::get('dae_departamento', '*', ['status' => True, '>' => 'n_departamento']);
$tip = sql::get('dae_tipo_contato', '*', ['status' => True, '>' => 'n_contato']);
$mot = sql::get('dae_motivo', '*', ['status' => True, '>' => 'n_motivo']);
$ens = sql::get('dae_tipo_ensino', '*', ['status' => True, '>' => 'id_tipo_ensino']);
$cic = sql::get('ge_ciclos', 'id_ciclo, n_ciclo', ['>' => 'n_ciclo']);
$esc = sql::get(['instancia', 'ge_escolas'], 'id_inst, n_inst', ['>' => 'n_inst']);
$sta = sql::get('dae_status', '*', ['>' => 'n_status']);
$est = sql::get('estados', 'sigla', ['>' => 'sigla']);
$dados = sql::get('dae_atendimento', '*', ['id_atendimento' => $id_at], 'fetch');

?>

<div style="font-size: 20px; padding: 20px">
    <form method="POST">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Atendimento Escolar
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo formulario::input('1[id_atendimento]', 'Nº Protocolo', null, @$dados['id_atendimento'], 'readonly', 'Não Digite Aqui') ?>
                        </div>                     
                        <div class="col-md-3">
                            <?php echo formulario::input('1[dt_inicio]', 'Data Inicio', NULL, data::converteBr(@$dados['dt_inicio']), formulario::dataConf(), 'Digite Dia, Mês e Ano 01/01/2017') ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo formulario::input('1[dt_fim]', 'Data Final', null, data::converteBr(@$dados['dt_fim']), formulario::dataConf(), 'Digite Dia, Mês e Ano 01/01/2017') ?>
                        </div>  
                        <div class="col-md-3">
                            <?php
                            foreach ($sta as $st) {
                                $stt[$st['id_status']] = $st['n_status'];
                            }
                            formulario::select('1[fk_id_status]', $stt, 'Status');
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
                            <?php echo formulario::input('1[oficio]', 'Ofício nº.', null, @$dados['oficio'], null, 'Digite xxxx/2017') ?>
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
                        <?php echo formulario::input('1[descricao]', 'Descrição', null, @$dados['descricao'], null, 'Digite aqui uma breve descrição do atendimento(limite de 255 caracteres)') ?>
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
                            <?php echo formulario::input('1[solicitante]', 'Solicitante', null, @$dados['solicitante'], null, 'Digite aqui Nome completo (limite de 100 caractere)') ?>
                        </div>
                        <div class="col-md-2">         
                            <?php formulario::select('1[sexo_solic]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$dados['sexo_solic']) ?>
                        </div>                
                        <div class="col-md-4">         
                            <?php echo formulario::input('1[rg_solic]', 'Nº RG - UF', null, @$dados['rg_solic']) ?>        
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo formulario::input('1[nome_aluno]', 'Nome da Criança', null, @$dados['nome_aluno']) ?>
                        </div>
                        <div class="col-md-2">
                            <?php formulario::select('1[sexo_aluno]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$dados['sexo_aluno']) ?>
                        </div>
                        <div class="col-md-4">         
                            <?php echo formulario::input('1[rse]', 'RSE', null, @$dados['rse'], null, 'Caso Aluno da Rede, Informe RSE') ?>        
                        </div>    
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-2">  
                            <?php echo formulario::input('1[cep]', 'CEP', null, @$dados['cep'], 'id="cep"', 'Digite somente números') ?>  
                        </div>
                        <div class="col-md-4">
                            <?php echo formulario::input('1[logradouro]', 'Endereço', null, @$dados['logradouro'], 'id="rua"') ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::input('1[num]', 'Número', null, @$dados['num']) ?>
                        </div>
                        <div class="col-md-4">   
                            <?php echo formulario::input('1[complemento]', 'Complemento', null, @$dados['complemento']) ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo formulario::input('1[telefone]', 'Telefone', null, @$dados['telefone'], null, 'Digite aqui os telefones para contato, p.ex 1234-4567 - 12345-6789') ?>
                        </div>

                        <div class="col-md-4">        
                            <?php echo formulario::input('1[cidade]', 'Cidade', null, @$dados['cidade'], 'id="cidade"') ?>
                        </div>
                        <div class="col-md-2">     
                            <?php formulario::input('1[uf]', 'Estado', NULL, @$dados['uf'], ' id="uf"') ?>
                        </div>
                    </div>
                    <br />
                    <?php echo formulario::input('1[procedimento]', 'Procedimento', null, @$dados['procedimento']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center">
                <a class="art-button" style="width: 45%" href="">
                    Limpar
                </a>  
            </div>

            <div class="col-md-4 text-center">
                 <?php echo formulario::hidden(['1[id_atendimento]' => @$dados['id_atendimento']]) ?>
                <?php echo DB::hiddenKey('dae_atendimento', 'replace') ?>
                <input type="hidden" name="1[id_atendimento]" value="<?php echo @$dados['id_atendimento'] ?>" />
                <input type="submit" style="width: 45%" class ="art-button" value="Salvar" />
            </div>
            <div class="col-md-4 text-center">  
                <?php
                if (!empty(@$dados['id_atendimento'])) {
                    ?>
                    <input onclick="$('#protocolo').submit();" type="button" style="width: 45%" class ="art-button" value="Protocolo" />
                    <?php
                } else {
                    ?>
                    <input type = "button" style = "width: 45%" class = "art-button" value = "Protocolo" />
                    <?php
                }
                ?>
            </div>       
        </div>
    </form>
    <form target="_blank" action="<?php echo HOME_URI; ?>/dae/protocolo" id="protocolo" method="POST">
        <input type="hidden" name="[id_atendimento]" value="<?php echo @$dados['id_atendimento'] ?>" />
    </form>
</div>



