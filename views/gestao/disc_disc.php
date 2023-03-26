<div class="container">
    <div id="nvcur" class="row field" style="display: <?php echo (!empty(@$_POST['id_disc']) || !empty($_POST['limp'])) ? 'none' : '' ?>">
        <div class="col-lg-12">
            <div class="text-center">
                <button class="btn btn-info" onclick="document.getElementById('cur').style.display = '';document.getElementById('nvcur').style.display = 'none';">
                    Nova Disciplina
                </button>
            </div>
        </div>
    </div>
    <div id="cur" class="row field" style="display: <?php echo (!empty(@$_POST['id_disc']) || !empty($_POST['limp'])) ? '' : 'none' ?>">
        <form method="POST">
            <div class="col-lg-5">
                <?php echo formulario::input('1[n_disc]', 'Disciplina: ', NULL, @$disc['n_disc']) ?>
            </div>
            <div class="col-lg-2">
                <?php echo formulario::input('1[sg_disc]', 'Sigla: ', NULL, @$disc['sg_disc']) ?>
            </div>
            <div class="col-lg-5">
                <?php echo formulario::selectDB('ge_areas', '1[fk_id_area]', 'Área', @$disc['fk_id_area'], 'required') ?>
            </div>
            <div class="col-lg-12"></div>
            <div class="col-lg-4 text-center">
                <?php
                echo DB::hiddenKey('ge_disciplinas', 'replace');
                ?>
                <input type="hidden" name="aba" value="disc" />
                <input type="hidden" name="1[id_disc]" value="<?php echo @$disc['id_disc'] ?>" />
                <button class="btn btn-success">
                    Salvar
                </button>
            </div>
        </form>
        <div class="col-lg-4 text-center">
            <form method="POST">
                <input type="hidden" name="aba" value="disc" />
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
            <?php $model->listDisc() ?>
        </div>
    </div>
</div>