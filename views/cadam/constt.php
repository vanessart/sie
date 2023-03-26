<div  style="text-align: center; font-size: 16px">
    QUADRO POR ESCOLA CONSOLIDADO
</div>
<div style="float: right; text-align: center; padding-right: 100px">
    <form action="<?php echo HOME_URI ?>/cadam/constt" method="POST">
        <div style="text-align: center; margin: 0 auto">
            <input class="btn btn-success" type="submit" value="Atualizar" />
        </div>
    </form>  
</div>

<br /><br />
<?php
$cc = sql::get('cadam_esc_vaga');
$uso = sql::get('cadam_escola');

foreach ($cc as $v) {
    @$disc[$v['fk_id_cargo']]['m'] += $v['m'];
    @$disc[$v['fk_id_cargo']]['t'] += $v['t'];
    @$disc[$v['fk_id_cargo']]['n'] += $v['n'];
    @$disc[$v['fk_id_cargo']]['tt'] += ($v['t'] + $v['n'] + $v['m']);
}

foreach ($uso as $v) {
    @$discUso[$v['fk_id_cargo']]['m'] += str_replace('X', 1, $v['m']);
    @$discUso[$v['fk_id_cargo']]['t'] += str_replace('X', 1, $v['t']);
    @$discUso[$v['fk_id_cargo']]['n'] += str_replace('X', 1, $v['n']);
    @$discUso[$v['fk_id_cargo']]['tt'] += (str_replace('X', 1, $v['n']) + str_replace('X', 1, $v['t']) + str_replace('X', 1, $v['m']));
}
?>
<style>
    td{
        padding: 8px;
    }
</style>
<table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
    <tr>
        <td rowspan="2">
            Cargo
        </td>
        <td colspan="3">
            Total
        </td>
        <td colspan="3">
            Manhã
        </td>
        <td colspan="3">
            Tarde
        </td>
        <td colspan="3">
            Noite
        </td>
    </tr>
    <tr>
        <td>
            Previstas
        </td>
        <td>
            Usadas
        </td>
        <td>
            Disponível
        </td>
        <td>
            Previstas
        </td>
        <td>
            Usadas
        </td>
        <td>
            Disponível
        </td>
        <td>
            Previstas
        </td>
        <td>
            Usadas
        </td>
        <td>
            Disponível
        </td>
        <td>
            Previstas
        </td>
        <td>
            Usadas
        </td>
        <td>
            Disponível
        </td>
    </tr>
    <?php
    $discList = sql::get('cadam_cargo');
    foreach ($discList as $v) {
        
        
        ?>
        <tr>
            <td>
                <?php echo $v['n_cargo'] ?>
            </td>
            <td>
                <?php
                echo @$disc[$v['id_cargo']]['tt'];
                @$totalPrevisto += @$disc[$v['id_cargo']]['tt']
                ?>
            </td>
            <td>
                <?php
                echo @$discUso[$v['id_cargo']]['tt'];
                @$totalUsado += @$discUso[$v['id_cargo']]['tt']
                ?>
            </td>
            <td>
                <?php echo @$disc[$v['id_cargo']]['tt'] - @$discUso[$v['id_cargo']]['tt'] ?>
            </td>
            <td>
                <?php
                echo @$disc[$v['id_cargo']]['m'];
                @$manhaPrevisto += @$disc[$v['id_cargo']]['m']
                ?>
            </td>
            <td>
                <?php
                echo @$discUso[$v['id_cargo']]['m'];
                @$manhaUso += @$discUso[$v['id_cargo']]['m']
                ?>
            </td>
            <td>
                <?php echo @$disc[$v['id_cargo']]['m'] - @$discUso[$v['id_cargo']]['m'] ?>
            </td>
            <td>
                <?php
                echo @$disc[$v['id_cargo']]['t'];
                @$tardePrevisto += @$disc[$v['id_cargo']]['t']
                ?>
            </td>
            <td>
                <?php
                echo @$discUso[$v['id_cargo']]['t'];
                @$tardeUso += @$discUso[$v['id_cargo']]['t']
                ?>
            </td>
            <td>
                <?php echo @$disc[$v['id_cargo']]['t'] - @$discUso[$v['id_cargo']]['t'] ?>
            </td>
            <td>
                <?php
                echo @$disc[$v['id_cargo']]['n'];
                @$noitePrevisto += @$disc[$v['id_cargo']]['n']
                ?>
            </td>
            <td>
                <?php
                echo @$discUso[$v['id_cargo']]['n'];
                @$noiteUso += @$discUso[$v['id_cargo']]['n']
                ?>
            </td>
            <td>
                <?php echo @$disc[$v['id_cargo']]['n'] - @$discUso[$v['id_cargo']]['n'] ?>
            </td>

        </tr>
        <?php
    }
    ?>
    <tr>
        <td>
            Total
        </td>
        <td>
            <?php echo @$totalPrevisto ?>
        </td>
        <td>
            <?php echo @$totalUsado ?>
        </td>
        <td>
            <?php echo @$totalPrevisto - @$totalUsado ?>
        </td>
        <td>
            <?php echo @$manhaPrevisto ?>
        </td>
        <td>
            <?php echo @$manhaUso ?>
        </td>
        <td>
            <?php echo @$manhaPrevisto - @$manhaUso ?>
        </td>
        <td>
            <?php echo @$tardePrevisto ?>
        </td>
        <td>
            <?php echo @$tardeUso ?>
        </td>
        <td>
            <?php echo @$tardePrevisto - @$tardeUso ?>
        </td>
        <td>
            <?php echo @$noitePrevisto ?>
        </td>
        <td>
            <?php echo @$noiteUso ?>
        </td>
        <td>
            <?php echo @$noitePrevisto - $noiteUso ?>
        </td>
    </tr>
</table>



