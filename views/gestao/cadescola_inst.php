<?php
@$instBusc = $_POST['n_inst'];
?>
<br />
<div class="field row" id="busca" style="display: <?php echo (empty($_POST['novo']) && empty($inst)) ? '' : 'none' ?>">
    <form method="POST">
        <div class="col-lg-5">
            <div class="input-group">
                <span class="input-group-addon">
                    Filtrar Instância:
                </span> 
                <input class="form-control" type="text" name="n_inst" value="<?php echo @$instBusc ?>"  >
                <span class="input-group-addon"  >
                    <button type="submit" class="btn btn-link btn-xs">
                        Buscar
                    </button>
                </span>
            </div>
        </div>
    </form>
    <div class="col-lg-2">
        <form method="POST">
            <button type="submit" class="btn btn-success">
                Limpar
            </button>
        </form>
    </div>
    <div class="col-lg-2"></div>
    <div class="col-lg-3">
        <button onclick="document.getElementById('form').style.display = '';document.getElementById('busca').style.display = 'none'" type="submit" name="novo" value="1" class="btn btn-info">
            Cadastrar uma nova escola
        </button>
    </div>

</div>
<form id="form" method="POST" style="display: <?php echo (!empty($_POST['novo']) || !empty($inst)) ? '' : 'none' ?>">
    <div class="field">
        <div class="row">
            <div class="col-lg-12"></div>
        </div>
        <div class="row">
            <div class="col-lg-10">
                <?php echo formulario::input('1[n_inst]', 'Nome da Escola:', NULL, @$inst['n_inst'], 'required')
                ?>
            </div>
            <div class="col-lg-2">
                <?php formulario::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$inst['ativo'], 'required')
                ?>
            </div>  
            <div class="col-lg-12">
                <?php echo formulario::input('1[email]', 'E-mail: Institucional', NULL, @$inst['email'], 'required')
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 offset4" style="text-align: center" >
                <?php
                formulario::button('Fechar', NULL, 'button', NULL, 'onclick="document.getElementById(\'form\').style.display=\'none\';document.getElementById(\'busca\').style.display=\'\'"');
                ?>
            </div>
            <div class="col-lg-6 offset4" style="text-align: center">
                <input type="hidden" name="1[fk_id_tp]" value="1" />
                <input type="hidden" name="1[id_inst]" value="<?php echo @$inst['id_inst'] ?>" />
                <?php
                echo DB::hiddenKey('editEscola');
                echo formulario::button();
                ?>
            </div>

        </div>
    </div>
</form>
<br />
<?php
$model->relat(@$instBusc, 'Escola');
?>
