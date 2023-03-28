<?php
ob_start();
$banco_ = sql::get('banco');
foreach ($banco_ as $v) {
    $banco[$v['id_ban']] = $v['n_ban'];
}
$sel = sql::get('cadam_seletivas', '*', ['id_sel' => @$_POST['fk_id_sel']], 'fetch')['n_sel'];
$dados = $model->getCadampe($_POST['id_cad']); 
$sql = "select class, n_cargo, id_cargo from cadam_cargo c "
        . "join cadam_class cl on cl.fk_id_cargo = c.id_cargo "
        . " where fk_id_inscr = " . @$dados['fk_id_inscr'];
$query = $model->db->query($sql);
$classi = $query->fetchAll();
?>
<style>
    td{
        padding: 5px;
    }
</style>
<div class="fieldTop">
    CADASTRO MUNICIPAL DE PROFESSOR EVENTUAL- CADAMPE
</div>
<div style="text-align: center; font-size: 14px">
    <?php echo $sel ?>
</div>

<br />
<table style="width: 100%" border=1 cellspacing=0 cellpadding=1>
    <tr>
        <td>
            Nome
        </td>
        <td>
            <?php echo strtoupper(@$dados['n_pessoa']) ?>
        </td>
    </tr>
    <tr>
        <td>
            Classificação
        </td>
        <td colspan="2">
            <?php
            $cargoApov = explode('|', $dados['cargos_e']);
            if (!empty($dados['classifica'])) {
                echo $dados['classifica'];
            } else {
                foreach ($classi as $v) {
                    if (in_array($v['id_cargo'], $cargoApov)) {
                        @$cl[] = $v['class'] . ' (' . $v['n_cargo'] . ')';
                    }
                }
                if (!empty($cl)) {
                    echo implode(';', @$cl);
                }
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>
            TIPO DE CADASTRO
        </td>
        <td>
            <?php
            $cargos_ = sql::get('cadam_cargo');
            foreach ($cargos_ as $v) {
                $cargos[$v['id_cargo']] = $v['n_cargo'];
            }
            if (!empty($dados['cargos_e'])) {
                foreach (explode("|", substr($dados['cargos_e'], 1, -1)) as $v) {
                    echo $cargos[$v] . '<br />';
                }
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>
            DISPONIBILIDADE
        </td>
        <td>
            <?php
            $per = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite'];
            $semana = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta'];
            if (!empty($dados['dia'])) {
                foreach (explode("|", substr($dados['dia'], 1, -1)) as $v) {
                    $dispo[substr($v, 0, 1)][] = substr($v, 1, 1);
                }
                asort($dispo);
                foreach ($dispo as $k => $v) {
                    echo @$semana[$k] . ' (';
                    if (count($v) == 2) {
                        echo @$per[$v[0]] . ' e ' . $per[$v[1]];
                    } elseif (count($v) == 1) {
                        echo @$per[$v[0]];
                    } elseif (count($v) == 3) {
                        echo @$per[$v[0]] . ', ' . $per[$v[1]] . ' e ' . $per[$v[2]];
                    }
                    echo ')<br />';
                }
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            Observações:
            <br />
            <?php echo @$dados['obs'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style=" background-color: black; color: white;text-align: center;">
            DADOS PESSOAIS
        </td>
    </tr>
    <tr>
        <td>
            Endereço
        </td>
        <td>
            <?php echo @$dados['logradouro'] . ', ' . @$dados['num'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Complemento
        </td>
        <td>
            <?php echo @$dados['compl'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Bairro
        </td>
        <td>
            <?php echo @$dados['bairro'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Cidade
        </td>
        <td>
            <?php echo @$dados['cidade'] . ' - ' . @$dados['uf'] ?>
        </td>
    </tr>
    <tr>
        <td>
            CEP
        </td>
        <td>
            <?php echo @$dados['cep'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Telefones
        </td>
        <td>
            <?php
            echo
            (!empty(@$dados['tel1']) ? @$dados['tel1'] . ' (Cel) &nbsp;&nbsp;&nbsp;&nbsp;' : '')
            . (!empty(@$dados['tel2']) ? @$dados['tel2'] . ' (Res)&nbsp;&nbsp;&nbsp;&nbsp;' : '')
            . (!empty(@$dados['tel1']) ? @$dados['tel3'] . ' (Outro)&nbsp;&nbsp;&nbsp;&nbsp;' : '');
            ?>
        </td>
    </tr>
    <tr>
        <td>
            E-mail
        </td>
        <td>
            <?php echo @$dados['email'] ?>
        </td>
    </tr>
    <tr>
        <td>
            RG
        </td>
        <td>
            <?php echo @$dados['rg'] . ' - ' . @$dados['rg_oe'] ?>
        </td>
    </tr>
    <tr>
        <td>
            CPF
        </td>
        <td>
            <?php echo @$dados['cpf'] ?>
        </td>
    </tr>
    <tr>
        <td>
            PIS/PASEP
        </td>
        <td>
            <?php echo @$dados['pis'] ?>
        </td>
    </tr>    
    <tr>
        <td colspan="2" style=" background-color: black; color: white;text-align: center;">
            CONTA BANCÁRIA
        </td>
    </tr> 
    <tr>
        <td colspan="2">
            <table style="width: 100%" border=1 cellspacing=0 cellpadding=1>
                <tr>
                    <td>
                        Banco: 
                        <?php echo @$banco[$dados['banco']] ?>
                    </td>
                    <td>
                        Agência: 
                        <?php echo @$dados['agencia'] ?>
                    </td>
                    <td>
                        C/C: 
                        <?php echo @$dados['cc'] ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style=" background-color: black; color: white;text-align: center;">
            CÓPIAS
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php
            $doc = explode('|', @$dados['doc'])
            ?>
            (<?php echo in_array('rg', @$doc) ? 'X' : '' ?> ) RG   
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            (<?php echo in_array('cpf', @$doc) ? 'X' : '' ?> ) CPF   
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            (<?php echo in_array('pis', @$doc) ? 'X' : '' ?> ) PIS/PASEP 
            <br />
            (<?php echo in_array('banco', @$doc) ? 'X' : '' ?> )   DADOS BANCÁRIOS   
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            (<?php echo in_array('diploma', @$doc) ? 'X' : '' ?> ) DIPLOMA/ CERTIFICADO
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            (<?php echo in_array('historico', @$doc) ? 'X' : '' ?> ) HISTÓRICO
        </td>
    </tr>

</table>
<div style="text-align: left; padding: 10px">
    Declaração: li e confirmo as informações.
</div>
<div style="text-align: right; padding: 10px">
    <?= CLI_CIDADE ?>, <?php echo date("d") ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
</div>
<br /><br />
<div style="text-align: center; width: 100%">
    _________________________________________
    <br />
    <?php echo strtoupper(@$dados['n_pessoa']) ?>
</div>
<?php
tool::pdfSecretaria();
?>