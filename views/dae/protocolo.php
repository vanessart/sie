<?php
ob_start();
?>
<head>
    <style>
        .topo{
            font-size: 10pt;
            font-weight:bolder;
            text-align: left;
            padding: 1px 5px 1px 5px;
            border: 1px solid;
            color: red;
            text-align: center;
        }
        .topo2{
            font-size: 10pt;
            font-weight: bolder;
            text-align: left;
            padding: 1px;
            border: 1px solid;        
        }      
    </style>
</head>

<?php

$id_at = $_POST['id_atendimento'];

$dae_departamento = sql::idNome('dae_departamento');
$dae_motivo = sql::idNome('dae_motivo');
$dae_tipo = sql::idNome('dae_tipo_contato');
$dae_ensino = sql::idNome('dae_tipo_ensino');
$dae_status = sql::idNome('dae_status');
$dae_ciclo = sql::idNome('ge_ciclos');

//estou aqui
//$esc = sql::get(['instancia', 'ge_escolas'], 'id_inst, n_inst', ['>' => 'n_inst']);

$dados = sql::get('dae_atendimento', '*', ['id_atendimento' => $id_at], 'fetchall');

foreach ($dados as $key => $v) {

    $dados[$key]['dep'] = $dae_departamento[$v['fk_id_departamento']];
    $dados[$key]['mot'] = @$dae_motivo[$v['fk_id_motivo']];
    $dados[$key]['tipo'] = @$dae_tipo[$v['fk_id_contato']];
    $dados[$key]['ensino'] = @$dae_ensino[$v['fk_id_tipo_ensino']];
    $dados[$key]['sta'] = @$dae_status[$v['fk_id_status']];
    $dados[$key]['esc'] = @$dae_escola[$v['fk_id_inst']];
    $dados[$key]['ci'] = @$dae_ciclo[$v['fk_id_ciclo']];
    
}

?>

<div style="border: 1px solid">
    <table>
        <tr>
            <td style="width: 50px; padding: 5px">
                <img src="<?php echo HOME_URI ?>/views/_images/brasao.png" width="90" height="80"/>                
            </td>
            <td style="padding-top: 5px; width: 579px; text-align: center">
                <div style="font-size: 20px; font-weight: bold">
                    Prefeitura Municipal de Barueri
                    <br />
                    SE - Secretaria de Educação
                    <br />
                    Departamento de Atendimento Escolar
                    <br />
                    E-mail: educ.atendimentoescolar@barueri.sp.gov.br
                </div>
            </td>
            <td style="width: 50px; padding: 5px">
                <img src="<?php echo HOME_URI ?>/views/_images/logo.png"  width="230" height="60"/>                
            </td>
        </tr>
    </table>
</div>

<?php
foreach ($dados as $v) {
    ?>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
        Protocolo de Atendimento
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <td style="width: 120px; text-align: center" class="topo2">
                    Nº Protocolo
                </td>        
                <td style="width: 120px; text-align: center" class="topo2">
                    Data Início
                </td>
                <td style="width: 120px; text-align: center" class="topo2">
                    Data Final
                </td>
                <td style="width: 120px; text-align: center" class="topo2">
                    Status
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 120px; text-align: center" class="topo2">
                    <?php echo $v['id_atendimento'] ?>               
                </td>        
                <td style="width: 120px; text-align: center" class="topo2">
                    <?php echo data::converteBr($v['dt_inicio']) ?>
                </td>
                <td style="width: 120px; text-align: center" class="topo2">
                    <?php echo data::converteBr($v['dt_fim']) ?>
                </td>
                <td style="width: 120px; text-align: center" class="topo2">
                    <?php echo $v['sta'] ?>
                </td>
            </tr>
        </tbody>
       
        <tr>
            <td style="width: 120px" class="topo2">
                Tipo Contato
            </td>    
            <td style="width: 120px" class="topo2">
                <?php echo $v['tipo'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Motivo Contato
            </td>
            <td style="width: 120px" class="topo2">
                <?php echo $v['mot'] ?>
            </td>  
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Departamento
            </td>
            <td style="width: 120px" class="topo2">
                <?php echo $v['dep'] ?>
            </td>
            
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Descrição
            </td>
            <td style="width: 120px" class="topo2">
                <?php echo  $v['descricao']?>
            </td>
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Tipo Ensino
            </td>
            <td style="width: 120px" class="topo2">
                <?php echo $v['ensino'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Ciclo
            </td>
            <td style="width: 120px" class="topo2">
                <?php echo $v['ci'] ?>
            </td>
        </tr> 
        <tr>
            <td style="width: 120px" class="topo2">
                Escola Origem
            </td>        
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Escola Destino
            </td>        
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Solicitante
            </td>        
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Endereço
            </td>
        </tr>
        <tr>
            <td style="width: 120px" class="topo2">
                Procedimento
            </td>
        </tr>

        <?php
    }
    ?>
</table>
<div class="topo">
    Barueri, <?php echo date("d") ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
</div>

<?php
tool::pdf();
?>
