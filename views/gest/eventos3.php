<?php

@$id_eve = $_POST['id_eve'];
@$descEve = $_POST['evento'];
if(empty($_POST['id_grupo_e']) || !empty($_POST['excluir'])){
  $_POST[1]['onibus_g'] = NULL;  
  $_POST[1]['descricao_grupo'] = NULL;  
  $_POST['onibus_g'] = NULL;  
  $_POST['descricao_grupo'] = NULL;  
}

?>

<div>
    <div style="text-align: center; color: red; font-size: 25px">
        <br /><br />
        <b>Cadastrar Grupo para Evento: <?php echo @$descEve ?></b>
        <br /><br />
    </div>
    <div style="padding: 15px; border: solid" class="row">
        
        <form method="POST">

            <div class="col-lg-2">
                <?php echo formulario::input('1[onibus_g]', 'Nº. Ônibus.', null, @$_POST['onibus_g'], 'required') ?>
            </div>

            <div class="col-lg-6">
                
                <?php echo formulario::input('1[descricao_grupo]', 'Descrição Grupo', null, @$_POST['descricao_grupo'], 'required', 'Sugestão: Nome do Professor Responsável') ?>            
            </div>

            <div class="col-lg-4 text-center">
                <?php echo DB::hiddenKey('ge_grupo_evento', 'replace') ?>   
                <input type="hidden" name="1[fk_id_evento]" value="<?php echo $id_eve ?>" />
                <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
                <input type="hidden" name="1[id_grupo_e]" value="<?php echo @$_POST['id_grupo_e'] ?>" />
                <input type="hidden" name ="evento" value="<?php echo @$descEve ?>" />
                <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>"/> 
                <input type="submit" style="width: 45%" class ="art-button" value="Salvar" />
            </div>    

        </form>
       
    </div>
    <br /><br />
    <?php
    $ev = sql::get(['ge_eventos', 'ge_grupo_evento'], '*', ['id_evento' => @$id_eve, '>' => 'id_grupo_e']);

    $sqlkey = DB::sqlKey('ge_grupo_evento', 'delete');

    foreach ($ev as $key => $v) {
        $v['id_eve'] = $_POST['id_eve'];
        $v['tabClass'] = $_POST['tabClass'];
        $v['evento'] = $_POST['evento'];

        $ev[$key]['even'] = formulario::submit('Editar', null, $v);
        $v['1[id_grupo_e]'] = $v['id_grupo_e'];
        $v['excluir']=1;
        $ev[$key]['excluir'] = formulario::submit('Excluir', $sqlkey, $v);
        $ev[$key]['alocar'] = formulario::submit('Alocar');
    }
    $form['array'] = $ev;
    $form['fields'] = [
        'Grupo' => 'id_grupo_e',
        'Evento' => 'evento',
        'Data Evento' => 'dt_evento',
        'Descrição Grupo' => 'descricao_grupo',
        'Ônibus' => 'onibus_g',
        '||1' => 'even',
        '||2' => 'excluir'
    ];
    tool::relatSimples($form);
    ?>
</div>
