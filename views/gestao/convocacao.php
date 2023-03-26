<?php
@$id_ev = $_POST['id_evento'];
?>
<div style="font-size: 20px; padding: 20px">

    <form method="POST">
        <div style="text-align: center; color: red">
            <b>Cadastro - Convocações, Comunicados e Eventos</b>
        </div>
        <div class="col-lg-12">
            <?php echo formulario::input('1[evento]', 'Evento ou Assunto',null, @$_POST['evento'],'required') ?>
        </div>
        <div class="col-lg-4">
            <?php echo formulario::input('1[dt_evento]', 'Data (dd/mm/aaaa)', null, data::converteBr(@$_POST['dt_evento']), formulario::dataConf()) ?>
        </div > 
        <div class="col-lg-4">
            <?php echo formulario::input('1[h_inicio]', 'Hora Início (hh:mm)', null, @$_POST['h_inicio'], 'id="hi" onkeypress="mascaraHora(\'hi\')"') ?> 
        </div>

        <div class="col-lg-4">
            <?php echo formulario::input('1[h_final]', 'Hora Final (hh:mm)', null, @$_POST['h_final'], 'id="hf" onkeypress="mascaraHora(\'hf\')"') ?>
        </div>
        <div class="col-lg-12">
            <?php echo formulario::input('1[local_evento]', 'Local Evento',null, @$_POST['local_evento']) ?>
        </div>
        <div class="col-lg-12">
            <?php echo formulario::input('1[ev_resp]', 'Responsável',null, @$_POST['ev_resp']) ?>
        </div>  
        <div class="col-lg-6 text-center">
            <a class="art-button" style="width: 45%" href="">
                Limpar
            </a>  
        </div>
            
        <div class="col-lg-6 text-center">
            <?php echo DB::hiddenKey('ge_eventos', 'replace') ?>
            <input type="hidden" name="1[id_evento]" value="<?php echo @$_POST['id_evento'] ?>" />
            <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst()?>" />
            <input type="submit" style="width: 45%" class ="art-button" value="Salvar" />
        </div>
        
    </form>
    <br />&nbsp;
    <?php
    
    $ev = sql::get('ge_eventos', '*', ['fk_id_inst' => tool::id_inst(), '<' => 'dt_evento']);
    $sqlkey=DB::sqlKey('ge_eventos', 'delete');
    foreach ($ev as $key => $v) {
        $ev[$key]['even'] = formulario::submit('Editar', null, $v);
        $ev[$key]['acessar'] = formulario::submit('Acessar', null, ['id_eve'=>$v['id_evento']],HOME_URI.'/gestao/convocacao_lista');
        $ev[$key]['excluir'] = formulario::submit('Excluir', $sqlkey,['1[id_evento]'=>$v['id_evento']]);
    }
    $form['array'] = $ev;
    $form['fields'] = [
        'Evento' => 'evento',
        'Data Evento' => 'dt_evento',
        'Local' => 'local_evento',
        '||1' => 'even',
        '||2' => 'excluir',
        '||3' => 'acessar'
        
    ];
    tool::relatSimples($form);
    ?>

</div>

