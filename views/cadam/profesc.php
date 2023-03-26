<?php
 $eg = sql::get(['cadam_esc_vaga'],'*', ['fk_id_inst'=>@$_POST['id_inst']] );
if(!empty($eg)){
     foreach ($eg as $v){
         $dados[$v['fk_id_cargo']]=$v;
     }

}
 ?>
<div class="fieldBody">
    <div class="fieldTop">
        Razão Professor/Escola
    </div>
    <?php
    if (empty($_POST['novo'])) {
        $modal = 1;
    }
    tool::modalInicio('width: 600px', @$modal);
    ?>
    <form method="POST">
        <div class="fieldTop">
            Escola: 
            <?php echo $_POST['n_inst'] ?>
        </div>
        <br /><br />
        <style>
            th{
                background-color: black;
                color: white;
            }
        </style>
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>
                        Disciplinas
                    </th>
                    <th>
                        Manhã
                    </th>
                    <th>
                        Tarde
                    </th>
                    <th>
                        Noite
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dis = sql::get('cadam_cargo', '*', ['>' => 'n_cargo']);
                $c = 1;
                foreach ($dis as $v) {
                    ?>
                    <tr>
                        <td>
                            <input type="hidden" name="<?php echo $c ?>[id_cev]" value="<?php echo @$dados[$v['id_cargo']]['id_cev'] ?>" />
                            <input type="hidden" name="<?php echo $c ?>[fk_id_cargo]" value="<?php echo $v['id_cargo'] ?>" />
                            <input type="hidden" name="<?php echo $c ?>[fk_id_inst]" value="<?php echo @$_POST['id_inst'] ?>" />
                            <?php echo $v['n_cargo'] ?>
                        </td>
                        <td>
                            <input type="number" name="<?php echo $c ?>[m]" value="<?php echo @$dados[$v['id_cargo']]['m'] ?>" placeholder="<?php echo $v['n_cargo'] ?>" />
                        </td>
                        <td>
                            <input type="number" name="<?php echo $c ?>[t]" value="<?php echo @$dados[$v['id_cargo']]['t'] ?>" placeholder="<?php echo $v['n_cargo'] ?>" />
                        </td>
                        <td>
                            <input type="number" name="<?php echo $c ?>[n]" value="<?php echo @$dados[$v['id_cargo']]['n'] ?>" placeholder="<?php echo $v['n_cargo'] ?>" />
                        </td>
                    </tr>
                    <?php
                    $c++;
                }
                ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="esc_vaga" value="1" />
                <input type="submit" value="Salvar" />
            </div>
        </div>
    </form>
    <?php
    tool::modalFim();

    $esc = escolas::liste();
    foreach ($esc as $k => $v) {
        $v['novo'] = 1;
        $esc[$k]['ac'] = formulario::submit('Editar', NULL, $v);
    }
    $form['array'] = $esc;
    $form['fields'] = [
        'Escola' => 'n_inst',
        '||' => 'ac'
    ];
    tool::relatSimples($form);
    ?>
</div>