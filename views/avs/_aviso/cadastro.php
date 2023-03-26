<div class="fieldTop">
    Cadastro de Aviso
</div>
<br />
<form method="POST">
    <?php echo formulario::input('1[n_avs]', 'Título', NULL, $dados['n_avs']) ?>
    <br />
    <?php echo formulario::textarea('1[msg_rem] ', $dados['msg_rem '], NULL, 1) ?>
    <br /><br />
    <div class="row">
        <div class="col-sm-4">
            <?php echo formulario::selectDB('avs_tipoconf', '1[fk_id_tipoconf]', 'Confirmação', $dados['fk_id_tipoconf'], NULL, NULL, NULL, NULL, ['ativo' => 1, '>' => 'n_tipoconf']) ?>
        </div>
        <div class="col-sm-4">
            <?php echo formulario::input('1[botao]', 'Botão (personalizar)', NULL, $dados['botao']) ?>
        </div>
        <div class="col-sm-4">
            <?php echo formulario::input('1[dt_avs_prazo]', 'Prazo de Entrega', NULL, $dados['dt_avs_prazo'], formulario::dataConf()) ?>
        </div>
    </div>
    <br /><br />
    <div style="text-align: center">
        <?php
        $hidden = [
            '1[dt_avs]' => date('Y-m-d'),
            '1[fk_id_pessoa_rem]' => tool::id_pessoa(),
            '1[fk_id_setor]' => $id_setor,
            'id_setor' => $id_setor
        ];
        echo formulario::hidden($hidden);
        echo DB::hiddenKey('avs_main', 'replace');
        ?>
        <input class="btn btn-success" type="submit" value="Salvar" />
    </div>
</form>