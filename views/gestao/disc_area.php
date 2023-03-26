<div class="container">
    <div id="nvcur" class="row field" style="display: <?php echo (!empty(@$_POST['id_area']) || !empty($_POST['limp'])) ? 'none' : '' ?>">
        <div class="col-lg-12">
            <div class="text-center">
                <button class="btn btn-info" onclick="document.getElementById('cur').style.display = '';document.getElementById('nvcur').style.display = 'none';">
                    Nova Área do Conhecimento
                </button>
            </div>
        </div>
    </div>
    <div id="cur" class="row field" style="display: <?php echo (!empty(@$_POST['id_area']) || !empty($_POST['limp'])) ? '' : 'none' ?>">
        <form method="POST">
            <div class="col-lg-10">
                <?php echo formulario::input('1[n_area]', 'Área:', NULL, @$area['n_area']) ?>
            </div>
            <div class="col-lg-2">
                <?php echo formulario::input('1[sg_area]', 'Sigla:', NULL, @$area['sg_area']) ?>
            </div>
         
            <div class="col-lg-4 text-center">
                <?php
                echo DB::hiddenKey('ge_areas', 'replace');
                ?>
                <input type="hidden" name="aba" value="area" />
                <input type="hidden" name="1[id_area]" value="<?php echo @$area['id_area'] ?>" />
                <button class="btn btn-success">
                    Salvar
                </button>
            </div>
        </form>
        <div class="col-lg-4 text-center">
            <form method="POST">
                <input type="hidden" name="aba" value="area" />
                <input type="hidden" name="limp" value="1" />
                <button class="btn btn-primary">
                    Limpar
                </button>
            </form>
        </div>
        <div class="col-lg-4 text-center">
            <button class="btn btn-danger" onclick="document.getElementById('cur').style.display = 'none';document.getElementById('nvcur').style.display = '';">
                Fechar
            </button>               
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php $model->listAreas() ?>
        </div>
    </div>
</div>