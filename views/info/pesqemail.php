<?php
if (!defined('ABSPATH'))
    exit;

$rse = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$email = filter_input(INPUT_POST, 'emailgoogle', FILTER_UNSAFE_RAW);
?>
<br />

<head>
    <style>
        .topo{
            font-size: 10pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>

<div class="body" style="padding: 30px">
    <br />
    <form method="POST">
        <div class="row">
            <div class="col-md-2">
                <?= formErp::input('id_pessoa', 'Digite RSE', $rse) ?>
            </div>
            <div class="col-md-6">
                <?= formErp::input('emailgoogle', 'Digite E-mail', $email, 'style="width:600px"' ) ?>
            </div>
            <div class="col-md-2">
                <input type="hidden" name="consulta" value="1" />
                <button class="btn btn-info">
                    Pesquisar
                </button>
            </div>
            <div class="col-md-2">
                <a class="btn btn-info" href="">
                    Limpar
                </a>
            </div>
        </div>
    </form>   

    <?php
    if (!empty($_POST['consulta'])) {
        if (!empty($rse) && (!empty($email))) {
            $criterio = "WHERE p.id_pessoa = $rse OR p.emailgoogle = '" . $email . "'";
        } else {
            if (!empty($rse)) {
                $criterio = "WHERE p.id_pessoa = " . $rse;
            } else if (!empty($email)) {
                $criterio = "WHERE p.emailgoogle = '" . $email . "'";
            } else {
                ?>
                <div class="alert alert-danger" role="alert" style="text-align: center">
                    Informe pelo menos uma das opções acima.
                </div>
                <?php
            }
        }
    }

    if (!empty($criterio)) {
        $dados = $model->pesquisaemail($criterio);

        if (!empty($dados)) {
            ?>
            <br />
            <div style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 1pt">
                Dados
            </div>    
            <br />
            <?php
           // $ciclo = [84, 86];
            foreach ($dados as $v) {
             //   if (empty($v['fk_id_pl']) || (in_array($v['fk_id_pl'], $ciclo))) {
                    ?>
                    <div>
                        <table style="width: 100%">
                            <tr>
                                <td colspan="2" class="topo" style="width: 50%">
                                    Nome 
                                </td>
                                <td colspan="2" class="topo" style="width: 50%">
                                    Escola
                                </td>
                            </tr>
                            <tr>      
                                <td colspan="2" class="topo" style="width: 50%">
                                    <?= $v['n_pessoa'] ?>                            
                                </td>
                                <td colspan="2" class="topo" style="width: 50%">
                                    <?= (empty($v['n_inst'])) ? '-' : $v['n_inst'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="topo" style="width: 25%">
                                    RSE
                                </td>
                                <td class="topo" style="width: 25%">
                                    RA
                                </td>
                                <td class="topo" style="width: 25%">
                                    Data Nascimento
                                </td>
                                <td class="topo" style="width: 25%">
                                    Turma
                                </td>
                            </tr>
                            <tr>
                                <td class="topo" style="width: 25%">
                                    <?= $v['id_pessoa'] ?>
                                </td>
                                <td class="topo" style="width: 25%">
                                    <?= $v['ra'] ?>
                                </td>
                                <td class="topo" style="width: 25%">
                                    <?= data::converteBr($v['dt_nasc']) ?>
                                </td>
                                <td class="topo" style="width: 25%">
                                    <?= $v['n_turma'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="topo" style="width: 50%">
                                    Email Google 
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="topo" style="width: 50%; color:red; border-color: black">
                                    <?= $v['emailgoogle'] ?> 
                                </td>
                            </tr>
                        </table>
                        <br /><br />
                    </div>
                    <?php
              //  }
            }
        } else {
            echo 'Não existem dados para os parâmetros de busca informados';
        }
    }
    ?>
</div>