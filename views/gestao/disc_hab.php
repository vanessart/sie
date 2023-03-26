<div class="container">
    <div id="nvcur" class="row field" style="display: <?php echo (!empty(@$_POST['id_hab']) || !empty($_POST['limp'])) ? 'none' : '' ?>">
        <div class="col-lg-12">
            <div class="text-center">
                <button class="btn btn-info" onclick="document.getElementById('cur').style.display = '';document.getElementById('nvcur').style.display = 'none';">
                    Nova Habilidades
                </button>
            </div>
        </div>
    </div>
    <div id="cur" class="row field" style="display: <?php echo (!empty(@$_POST['id_hab']) || !empty($_POST['limp'])) ? '' : 'none' ?>">
        <form method="POST">
            <div class="col-lg-5">
                <?php echo formulario::input('1[n_hab]', 'Habilidade: ', NULL, @$hab['n_hab']) ?>
            </div>
            <div class="col-lg-2">
                <?php echo formulario::input('1[sg_hab]', 'Sigla: ', NULL, @$hab['sg_hab']) ?>
            </div>
            <div class="col-lg-5">
                <?php echo formulario::selectDB('ge_areas', '1[fk_id_area]', 'Área', @$hab['fk_id_area'] ,'required')?>
            </div>
            <div class="col-lg-12"></div>
            <div class="col-lg-4 text-center">
                <?php
                echo DB::hiddenKey('ge_habilidades', 'replace');
                ?>
                <input type="hidden" name="aba" value="hab" />
                <input type="hidden" name="1[id_hab]" value="<?php echo @$hab['id_hab'] ?>" />
                <button class="btn btn-success">
                    Salvar
                </button>
            </div>
        </form>
        <div class="col-lg-4 text-center">
            <form method="POST">
                <input type="hidden" name="aba" value="hab" />
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
            <?php $model->listHab() ?>
        </div>
    </div>
</div>