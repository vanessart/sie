<?php
if (!empty($_POST['id_gr']) || !empty($_POST[1]['id_gr'])) {
    @$id = !empty($_POST['id_gr']) ? $_POST['id_gr'] : $_POST[1]['id_gr'];
    $dados = grupo::get($id);
}
@$gr = $_POST['gr'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Grupos
    </div>
    <div id="search" class="row" style="display: <?php echo empty($dados) ? '' : 'none' ?>">

        <div class="col-lg-1 text-right" style="font-size: 20px">
            Pesquisa:
        </div>
        <div class="col-lg-5">
            <form method="POST">
                <div class="input-group">
                    <span class="input-group-addon">
                        Grupo
                    </span> 
                    <input class="form-control" type="text" name="gr" value="<?php echo @$gr ?>"  >
                    <span class="input-group-addon"  >
                        <button type="submit" class="btn btn-link btn-xs">
                            <span aria-hidden="true">
                                Buscar
                            </span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-lg-1">
            <form method="POST">
                <button type="submit" class="btn btn-default">
                    Limpar
                </button>
            </form>
        </div>
        <div class="col-lg-2">
            <button onclick="document.getElementById('novo').style.display = '';document.getElementById('search').style.display = 'none'" type="submit" name="novo" value="1" class="btn btn-default">
                Novo Cadastro
            </button>
        </div>

    </div>
    <div id="novo" class="row" style="display: <?php echo!empty($dados) ? '' : 'none' ?>">
        <form id="form" method="POST">
        <div class="col-lg-1 text-right" style="font-size: 20px">
                Inserir
            </div>
            <div class="col-lg-5">
                <?php echo formulario::input('1[n_gr]', 'Nome', NULL, @$dados['n_gr'], 'required')
                ?>
            </div>

            <div class="col-lg-1">
                <?php formulario::select('1[at_gr]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$dados['at_gr'])
                ?>
            </div>


            <div class="col-lg-1" style="text-align: center">
                <input type="hidden" name="1[id_gr]" value="<?php echo @$dados['id_gr'] ?>" />
                <?php
                echo DB::hiddenKey('grupo', 'replace');
                echo formulario::button();
                ?>
            </div>

        </form>
        <div class="col-lg-1" style="text-align: center" >
            <button onclick="document.getElementById('novo').style.display = 'none';document.getElementById('search').style.display = ''" type="submit" name="novo" value="1" class="btn btn-default">
                Fechar
            </button>
        </div>

    </div>
    <br />
    <?php
    grupo::relat(@$gr);
    ?>
</div>
