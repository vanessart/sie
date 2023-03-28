<?php
ob_start();
$sel = sql::get('dtpg_seletivas', '*', ['id_sel' => @$_POST['fk_id_sel']], 'fetch')['n_sel'];
$dados = sql::get('dtgp_cadampe', '*', ['id_cad' => @$_POST['id_cad']], 'fetch');
$classif = sql::get('dtpg_class', '*', ['fk_id_inscr' => @$dados['fk_id_inscr'], 'fk_id_cargo' => @$_POST['id_cargo']], 'fetch')['class'];
$cargo = sql::get('dtgp_cadampe_cargo', 'n_cargo', ['id_cargo' => @$_POST['id_cargo']], 'fetch')['n_cargo'];
?>
<br /><br />
<style>
    td{
        padding: 5px;
    }
</style>
<div style="text-align: center; font-size: 20px; ">
    Escolas Cadastradas
</div>
<br /><br />
<table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
    <tr>
        <td>
            Seletiva
        </td>
        <td>
            <?php echo $sel ?>
        </td>
    </tr>
    <tr>
        <td>
            Classificação
        </td>
        <td>
            <?php echo  empty($dados['classifica'])?@$classif:$dados['classifica'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Nome
        </td>
        <td>
            <?php echo $dados['n_insc'] ?>
        </td>
    </tr>
    <tr>
        <td>
            CPF
        </td>
        <td>
            <?php echo $dados['cpf'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Telefone
        </td>
        <td>
            <?php echo (!empty($dados['tel1']) ? $dados['tel1'] : '') . (!empty($dados['tel2']) ? ' - ' . $dados['tel2'] : '') . (!empty($dados['tel3']) ? ' - ' . $dados['tel3'] : '') ?>
        </td>
    </tr>
    <tr>
        <td>
            Cargo
        </td>
        <td>
            <?php echo $cargo ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 15px">
            <table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
                <tr>
                    <td>
                        SEQ
                    </td>
                    <td>
                        Nome das Unidades Escolares
                    </td>
                    <td>
                        M
                    </td>
                    <td>
                        T
                    </td>
                    <td>
                        N  
                    </td>
                </tr>
                <?php
                $esc_e = sql::get(['dtgp_cadampe_esc', 'instancia'], '*', ['fk_id_cad' => @$dados['id_cad'], 'fk_id_cargo' => @$_POST['id_cargo']]);
                $cont = 1;
                foreach ($esc_e as $v) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $cont++ ?>
                        </td>
                        <td>
                            <?php echo $v['n_inst'] ?>
                        </td>
                        <td style="width: 40px; text-align: center">
                            <?php echo $v['m'] ?>
                        </td>
                        <td style="width: 40px; text-align: center">
                            <?php echo $v['t'] ?>
                        </td>
                        <td style="width: 40px; text-align: center">
                            <?php echo $v['n'] ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </td>
    </tr>
</table>
<br /><br />
<div style="text-align: right; padding: 10px">
    <?= CLI_CIDADE ?>, <?php echo date("d") ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
</div>
<br /><br /><br />
<div style="text-align: center; width: 100%">
    _________________________________________
    <br />
    Assinatura DTGP / <?php echo user::session('n_pessoa') ?>
</div>
<?php
tool::pdfSecretaria();
?>
