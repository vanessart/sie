<?php
@$id_vaga_c = $_POST['id_vaga_c'];
$wn_ciclo = $model->peganomeciclo();
alunos::AlunosAutocomplete();
?>
<div style="font-size: 20px; padding: 20px">

    <form method="POST">
        <div style="text-align: center; color: red">
            <b>Cadastro - Declaração de Vaga/Comparecimento</b>
            <br />
        </div>
        <div style="padding: 15px" class="row">
            <div class="col-lg-6">
                <?php echo formulario::input('1[nome_solicitante]', 'Nome Solicitante', null, @$_POST['nome_solicitante'], 'required') ?>
            </div>
            <div class="col-lg-4">
                <?php echo formulario::input('1[rg]', 'R.G.', null, @$_POST['rg'], 'required') ?>
            </div>    
            <div class="col-lg-2">  
                <?php formulario::select('1[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$_POST['sexo'], NULL, NULL, 'required') ?>
            </div> 
        </div>
        <div style="padding: 15px" class="row">
            <div class="col-lg-6">
                <?php echo formulario::input('1[nome_aluno]', 'Nome Aluno', null, @$_POST['nome_aluno'], 'id="busca" onkeypress="complete()"') ?>                 
            </div>
            <div class="col-lg-2">  
                <?php formulario::select('1[sexo_aluno]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$_POST['sexo'], NULL, NULL) ?>
            </div> 
            <div class="col-lg-4">
                <?php
                $n_cic = $model->peganomeciclo();
                formulario::select('1[n_ciclo]', $n_cic, 'Ciclo/Ensino', @$_POST['n_ciclo'], NULL, NULL, 'required')
                ?>    
                <br />
            </div> 

            <div style="text-align: left" class="col-lg-12">
                Tipo Declaração
                <select name ="tipodec" onchange="fcadd(this.options[this.selectedIndex].text)">
                    <option value="">Escolha Tipo de Declaração</option>
                    <option value="Vaga">Declaração de Vaga</option>
                    <option value="Comparecimento">Declaração de Comparecimento</option>
                </select>
            </div>
        </div>
        <div id = "habcad" style="visibility: hidden; padding: 15px" class="row">
            <div class="col-lg-6">
                <?php echo formulario::input('1[dt_comp]', 'Data Comparecimento (dd/mm/aaaa)', null, data::converteBr(@$_POST['dt_comp']), formulario::dataConf()) ?>
            </div>     
            <div class="col-lg-3">
                <?php echo formulario::input('1[h_inicio]', 'Hora Início (hh:mm)', null, @$_POST['h_inicio'], 'id="hi" onkeypress="mascaraHora(\'hi\')"') ?> 
            </div>
            <div class="col-lg-3">
                <?php echo formulario::input('1[h_final]', 'Hora Final (hh:mm)', null, @$_POST['h_final'], 'id="hf" onkeypress="mascaraHora(\'hf\')"') ?>
            </div>
        </div>

        <div class="col-lg-6 text-center">
            <a class="art-button" style="width: 45%" href="">
                Limpar
            </a>  
        </div>
        <div class="col-lg-6 text-center">

            <?php echo DB::hiddenKey('ge_decl_vaga_comp', 'replace') ?>
            <input type="hidden" name="1[id_vaga_c]" value="<?php echo @$_POST['id_vaga_c'] ?>" />
            <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
            <input type="hidden" name="1[dt_emissao]" value="<?php echo date('Y-m-d') ?>" />
            <input id="tipo" type="hidden" name="1[tipo]" value="<?php echo @$_POST['tipodec'] ?>" />
            <input type="hidden" name="1[rse]" id="rse" value="" />
            <input type="hidden" name="1[codigo]" id="codigo" value="" />

            <input type="submit" style="width: 45%" class ="art-button" value="Salvar" />
        </div>    

    </form>
    <br />&nbsp;
    <?php
    $dec = sql::get('ge_decl_vaga_comp', '*', ['fk_id_inst' => tool::id_inst(), '<' => 'id_vaga_c']);

    foreach ($dec as $key => $v) {

        if ($v['tipo'] == "Declaração de Vaga") {
            $caminho = '/gestao/decl_vaga';
        } else {
            $caminho = '/gestao/decl_comp';
        }

        $dec[$key]['decl'] = formulario::submit('Editar', null, $v);
        $dec[$key]['acessar'] = formulario::submit('Imprimir', null, ['id_vaga_c' => $v['id_vaga_c']], HOME_URI . $caminho, 1);
    }
    $form['array'] = $dec;
    $form['fields'] = [
        'Nome Solicitante' => 'nome_solicitante',
        'R.G.' => 'rg',
        'Sexo' => 'sexo',
        'Tipo Declaração' => 'tipo',
        'Data Comp' => 'dt_comp',
        'Nome Aluno' => 'nome_aluno',
        '||1' => 'decl',
        '||2' => 'acessar'
    ];
    tool::relatSimples($form);
    ?>

    <script>

        function fcadd(texto) {
            document.getElementById("tipo").value = texto;

            if (texto == "Declaração de Comparecimento") {
                document.getElementById("habcad").style.visibility = "";
            } else {
                document.getElementById("habcad").style.visibility = "hidden";
            }
        }

    </script>

</div>

