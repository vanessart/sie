<div class="container">
    <div id="nvcur" class="row field" style="display: <?php echo (!empty(@$_POST['id_grade']) || !empty($_POST['limp'])) ? 'none' : '' ?>">
        <div class="col-lg-12">
            <div class="text-center">
                <button class="btn btn-info" onclick="document.getElementById('cur').style.display = '';document.getElementById('nvcur').style.display = 'none';">
                    Nova Grade Curricular
                </button>
            </div>
        </div>
    </div>
    <div id="cur" class="row field" style="display: <?php echo (!empty(@$_POST['id_grade']) || !empty($_POST['limp'])) ? '' : 'none' ?>">
        <form method="POST">
            <div class="col-lg-7">
                <?php echo formulario::input('1[n_grade]', 'Grade Curricular :', NULL, @$grade['n_grade']) ?>
            </div>
            <div class="col-lg-3">
                <?php echo formulario::selectDB('ge_tp_aval', '1[fk_id_ta]', 'Avaliar: ', @$grade['fk_id_ta']) ?>
            </div>
            <div class="col-lg-2">
                <?php
                $options = [0 => 'Não', 1 => 'Sim'];
                formulario::select('1[ativo]', $options, 'Ativo', @$grade['ativo']);
                ?>
            </div>
            <div class="col-lg-12"></div>
            <div class="col-lg-4 text-center">
                <?php
                echo DB::hiddenKey('ge_grades', 'replace');
                ?>
                <input type="hidden" name="aba" value="grade" />
                <input type="hidden" name="1[id_grade]" value="<?php echo @$grade['id_grade'] ?>" />
                <button class="btn btn-success">
                    Salvar
                </button>
            </div>
        </form>
        <div class="col-lg-4 text-center">
            <form method="POST">
                <input type="hidden" name="aba" value="grade" />
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
            <?php $model->listGrades() ?>
        </div>
    </div>
</div>