<?php
$grupo = sql::get('hab_grupo2', 'n_grupo');
$compt = sql::get('hab_competencia', 'n_compt');
$hab = sql::get('hab_habilidade', 'n_hab');


foreach ($grupo as $k => $v) {
    $n_grupo = $v['n_grupo'];
}
foreach ($compt as $k1 => $v1) {
    $n_compt = $v1['n_compt'];
}
foreach ($hab as $k2 => $v2) {
    $n_hab = $v2['n_hab'];
}
?>

<div class="fieldBody">
    <div class="fieldTop">
        Nomenclaturas Gerais
    </div>
    <br />
    <div class="container">

        <form method="POST">
            <div id="ci" class="row field">
                <div class="row">




                    <div class="col-lg-3">
                        <?php echo formulario::input('1[n_grupo]', 'Grupo', NULL, "$n_grupo" ) ?>
                        
                        
                    </div>
                    <div class="col-lg-3">
                        <?php echo formulario::input('2[n_compt]', 'Competência', NULL, "$n_compt") ?>
                    </div>
                    <div class="col-lg-3">
                        <?php echo formulario::input('3[n_hab]', 'Habilidades', NULL, "$n_hab") ?>
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-success">Salvar</button>  
                    </div>

                    <input type="hidden" name="1[id_grupo]" value="1[id_grupo]" />
                    <input type="hidden" name="2[id_compt]" value="1[id_grupo]" />
                    <input type="hidden" name="3[id_hab]" value="1[id_grupo]" />
                    <?php echo DB::hiddenKey('cadnomenclatura') ?>
                </div>
        </form>
    </div>
</div>
