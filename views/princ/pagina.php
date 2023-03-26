<br />
<div class="field">
    <div class="fieldTop">
        Configuração das Páginas 
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php formulario::selectDB('sistema', 'fk_id_sistema', 'Selecione um Sistema:', @$_REQUEST['fk_id_sistema'], NULL, 1, NULL, NULL, ['ativo' => 1, 'fk_id_fr' => 1]) ?>
        </div>
        <div class="col-lg-6">
            <span class="label label-info">
                menu|| = Menu DROPDOWN            menu|>submenu = Submenu do Dropdown 
            </span>
        </div>
    </div>
</div>
<?php
if (!empty($_REQUEST['fk_id_sistema'])) {
    ?>
    <br />
    <div class="field">
        <form name="form" method="POST">
            <div class="row">
                <div class="col-lg-4 text-left">
                    <div class="input-group">
                        <div class="input-group-addon" >
                            Arquivo:
                        </div>
                        <?php
                        if (empty(@$_POST['control'])) {
                            @$pagina = explode('/', $_POST['pagina']);
                            @$_POST['page'] = $pagina[1];
                            @$_POST['control'] = $pagina[0];
                        }
                        $options = $model->selectControles();
                        if (@$_POST['control'] == "geral/pdf") {
                            @$_POST['control'] = 'PDF';
                        }
                        ?>
                        <div class="input-group-addon">
                            <select name="control" onchange="document.form.submit()">
                                <option></option>
                                <?php
                                if ($_POST['control'] == 'geral' && substr($_POST['page'], 0, 3) == "pdf") {
                                    $_POST['control'] = "PDF";
                                }
                                foreach ($options as $v) {
                                    ?>
                                    <option <?php echo $v == @$_POST['control'] ? 'selected' : '' ?> value="<?php echo $v ?>"><?php echo $v ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                            /
                        </div>
                        <?php
                        if (!empty(@$_POST['control']) && @$_POST['control'] != 'Dropdown' && @$_POST['control'] != 'Link' && @$_POST['control'] != 'form' && @$_POST['control'] != 'PDF') {
                            ?>
                            <div class="input-group-addon">
                                <?php
                                echo @$model->selectPage(@$_POST['control']);
                                ?>
                            </div>
                            <?php
                        } elseif (@$_POST['control'] == 'Dropdown') {
                            ?>
                            <input type = "hidden" name = "page" value = "<?php echo @$_POST['page'] ?>" />
                            <?php
                        } elseif (@$_POST['control'] == 'PDF') {
                            @$pdf = tool::dirList(ABSPATH . '/views/geral/pdf');
                            @$pm = explode('?', $_POST['pagina']);
                            @$pm = explode('&', $pm[1]);
                            ?>
                            <div class="input-group-addon">
                                Model:
                            </div>
                            <div class="input-group-addon">
                                <select name="page[model]">
                                    <option></option>
                                    <?php
                                    $c = 1;
                                    foreach ($options as $v) {
                                        ?>
                                    <option <?php echo substr(@$pm[0], 4)==@str_replace('+', '|||', openssl_encrypt($v, 'aes128', 150936))?'selected':'' ?> value="<?php echo @str_replace('+', '|||', openssl_encrypt($v, 'aes128', 150936)) ?>"><?php echo $v ?></option>
                                        <?php
                                        if ($c > (count($options) - 4)) {
                                            break;
                                        }
                                        $c++;
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group-addon">
                                Página:
                            </div>
                            <div class="input-group-addon">
                                <select name="page[page]">
                                    <option></option>
                                    <?php
                                    foreach ($pdf as $pd) {
                                        $item = explode('.', $pd)[0];
                                        ?>
                                        <option  <?php echo substr(@$pm[1], 3)==@str_replace('+', '|||', openssl_encrypt($item, 'aes128', 150936))?'selected':'' ?> value="<?php echo @str_replace('+', '|||', openssl_encrypt($item, 'aes128', 150936)) ?>"><?php echo $item ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php
                        } elseif (!empty($_POST['control'])) {
                            ?>
                            <div class="input-group">
                                <div class="input-group-addon" >
                                    <input width="200px" type = "text" name = "page" value = "<?php echo @$_POST['page'] ?>" />
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                if (!empty(@$_POST['control']) || @$_POST['id_pag']) {
                    ?>
                    <div class="col-lg-4">
                        <?php formulario::input('n_pag', 'Menu:', NULL, @$_POST['n_pag']) ?>
                    </div>
                    <div class="col-lg-2">
                        <?php formulario::selectNum('ord_pag', 100, 'Ordem:', (empty(@$_POST['ord_pag']) ? 0 : @$_POST['ord_pag'])) ?>
                    </div>
                    <div class="col-lg-2">
                        <?php formulario::select('ativo', [1 => 'Sim', 2 => 'Não'], 'Ativo:', NULL, NULL, NULL, 'required') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <?php formulario::input('descr_page', 'Descrição:', NULL, @$_POST['descr_page']) ?>
                    </div>   
                    <div class="col-lg-2">
                        <input class="btn btn-default" name="lacarPagina" type="submit" value="Salvar" />
                    </div>
                    <div class="col-lg-2">
                        <a class="btn btn-default" href="<?php echo HOME_URI ?>/princ/paginas?fk_id_sistema=<?php echo @$_REQUEST['fk_id_sistema'] ?>">
                            Limpar
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>
            <input type="hidden" name="fk_id_sistema" value="<?php echo @$_REQUEST['fk_id_sistema'] ?>" />
            <input type="hidden" name="id_pag" value="<?php echo @$_POST['id_pag'] ?>" />
        </form>
    </div>
    <br />
    <?php
    $form = $model->formPagina(@$_REQUEST['fk_id_sistema']);
}
?>