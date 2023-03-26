<?php
  if(!empty($_POST['id_item'])){
      $dados = $_POST;
  }
  $itens = sql::get(['biro_item','biro_contrato']);
  $sqlkey = DB::sqlKey('biro_item', 'delete');
  foreach ($itens as $k => $v){
      $itens[$k]['total']= 'R$ '.number_format(str_replace(',', '.', $v['preco'])*$v['quant'], 2);
  $itens[$k]['precoR'] = 'R$ '.$v['preco'];
   $itens[$k]['at'] = tool::simnao($v['ativo']);
   $v['novo']=1;
   $itens[$k]['ac'] = formulario::submit('Editar', NULL, $v);
   $itens[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_item]'=>$v['id_item']]);
  }
  $form['array']=$itens;
  $form['fields']=[
      'Contrato'=>'n_con',
      'Lote'=> 'lote',
      'Item'=> 'num',
      'Quant.'=>'quant',
      'V. Unit.'=>'precoR',
      'V. Total'=> 'total',
      'Ativo'=> 'at',
      '||2'=> 'del',
      '||1'=>'ac'
  ];
                                    ?>
<div class="fieldBody">
    <div class="fieldTop">
        Contratos
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-6">
            <?php echo formulario::submit('Novo Item', NULL, ['novo' => 1]) ?> 
        </div>
        <br /><br />
        
        <?php
                                            tool::relatSimples($form);                                   
        if (!empty($_POST['novo'])) {
            tool::modalInicio();
            ?>
            <br /><br />
            <form method="POST">
                <div class="row">
                    <div class="col-sm-3">
                        <?php echo formulario::selectDB('biro_contrato', '1[fk_id_con]', 'Contrato', @$dados['fk_id_con']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo formulario::selectNum('1[lote]', [1, 20], 'Lote', @$dados['lote']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo formulario::selectNum('1[num]', [1, 20], 'Num. Item', @$dados['num']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo formulario::input('1[quant]', 'Quant.', NULL, @$dados['quant']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?php echo formulario::input('1[unidade]', 'Unid.', NULL, @$dados['unidade']) ?>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo formulario::input('1[n_item]', 'Descrição', NULL, @$dados['n_item']) ?>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-sm-4">
                        <?php echo formulario::select('1[ativo]', [1=>'Sim',0=>'Não'], 'Ativo', @$dados['ativo']) ?>
                    </div>
                    <div class="col-sm-4">
                        <?php echo formulario::input('1[preco]', 'Valor Unitário', NULL, @$dados['preco']) ?>
                    </div>
                    <div class="col-sm-4 text-center">
                        <?php
                        echo DB::hiddenKey('biro_item', 'replace');
                        echo formulario::hidden(['1[id_item]' => @$dados['id_item']]);
                        ?>
                        <input type="submit" value="Salvar" class="btn btn-success" />
                    </div>
                </div>
                <br /><br />
            </form>
            <?php
            tool::modalFim();
        }
        ?>
    </div>
</div>