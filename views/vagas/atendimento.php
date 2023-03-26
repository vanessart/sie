<?php
$wsituacao = [
    1 => "Confirmado - Aguardando Matrícula",
    2 => "1ª Tentativa - Responsável Não Localizado",
    3 => "2ª Tentativa - Responsável Não Localizado",
    4 => "3ª Tentativa - Responsável Não Localizado",
    5 => "Não Tem Interesse na Vaga",
    6 => "Endereço Atual em Outro Município"
];

$wsituacao2 = [
    "Confirmado - Aguardando Matrícula" => 1,
    "1ª Tentativa" => 2,
    "2ª Tentativa" => 3,
    "3ª Tentativa" => 4,
    "Não Tem Interesse na Vaga" => 5,
    "Endereço Atual em Outro Município" => 6
];
/*
  Controle direto no código
  $wquantidade = [
  1 => "1ª Vez",
  2 => "2ª Vezes",
  3 => "3ª Vezes"
  ];
 */

$idvaga = $_POST['id_vaga'];
$dados = $model->pegadadoscrianca($idvaga);
$dados_hist = $model->pegahistoricocrianca($idvaga);
$sit = $model->verificasituacao($idvaga);

if (empty($_POST['ultima_tentativa'])) {
    if (empty($tentativas)) {
        $tentativas = 2;
    }
} else {
    if (empty($tentativas)) {
        $tentativas = $wsituacao2[$_POST['ultima_tentativa']];
    }
}

if (!empty($_POST['salva'])) {
    @$descricao = $_POST['descricao'];
    @$tentativas = $_POST['tentativas'];
    @$quantidade = $_POST['quantidade'];
}
?>
<head>
    <style>
        .topo{
            font-size: 12pt;
            font-weight:bold;
            border-style: solid;
            border-width: 1px;
            padding-left: 5px;
            padding-right: 1px;
            padding-top: 1px;
            padding-bottom: 1px;
            text-align: center;
        }
        .topo2{
            padding-left: 50px;
            padding-right: 50px;
            padding-top: 2px;
            padding-bottom: 2px;
        }
        .topo3{
            font-size: 12pt;
            font-weight:bold;
            border-style: solid;
            border-width: 1px;
            padding-left: 5px;
            padding-right: 1px;
            padding-top: 1px;
            padding-bottom: 1px;
            text-align: left;
        }
    </style>
</head>

<div class="fieldBody" style="width: 80%">
    <div class="fieldWhite">
        <div style="font-weight:bold; font-size:14pt; background-color: #000000; color:#ffffff; text-align: center">
            ATENDIMENTO       
        </div>
        <div class="row topo2">
            <div class="col-md-3 topo">
                Protocolo: <?php echo $dados['id_vaga'] ?>
            </div>
            <div class="col-md-3 topo">
                Data Inscrição: <?php echo data::converteBr($dados['dt_vagas']) ?>
            </div>
            <div class="col-md-3 topo">
                Seriação: <?php echo $dados['seriacao'] ?>
            </div>
            <div class="col-md-3 topo">
                Data Nasc.: <?php echo data::converteBr($dados['dt_aluno']) ?>
            </div>
        </div> 
        <div class="row topo2">
            <div class="col-mod-12">
                <table>
                    <tr>
                        <td class="topo3" style="width: 200px">
                            Nome da Criança:
                        </td> 
                        <td class="topo3" style="width: 750px">
                            <?php echo $dados['n_aluno'] ?>
                        </td> 
                    </tr>
                    <tr>
                        <td class="topo3" style="width: 200px">
                            Nome da Mãe:
                        </td> 
                        <td class="topo3" style="width: 750px">
                            <?php echo $dados['mae'] ?>
                        </td> 
                    </tr>
                    <tr>
                        <td class="topo3" style="width: 200px">
                            Nome do Responsável:
                        </td> 
                        <td class="topo3" style="width: 750px">
                            <?php echo $dados['responsavel'] ?>
                        </td> 
                    </tr>
                    <tr>
                        <td class="topo3" style="width: 200px">
                            Telefones:
                        </td> 
                        <td class="topo3" style="width: 750px">
                            <?php echo $dados['tel1'] . ' - ' . $dados['tel2'] . ' - ' . $dados['tel3'] ?>
                        </td> 
                    </tr>
                </table> 
            </div>
        </div>
    </div>
    <div class="fieldWhite">
        <div class="row">
            <form method="POST">
                <div style="padding-left:15px; padding-right: 15px">
                    Observação:
                    <input id = "descricao" type="text" name='descricao' value="<?php echo @$descricao ?>" required placeholder= "Digite aqui"/>
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo formulario::select('tentativas', $wsituacao, 'Situação', @$tentativas) ?>
                        </div>
                        <div class="col-md-6">
                            <!--
                            <?php echo formulario::select('quantidade', $wquantidade, 'Quantidade de Vezes', @$quantidade) ?>
                            -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <button name="salva" value="1" type="submit" class="btn btn-success" style="width:150px">
                        Salvar
                    </button>
                    <input type="hidden" name="id_vaga" value="<?php echo $idvaga ?>" />
                    <input type="hidden" name="grava_hist" value="<?php echo 'gravar' ?>" />
                </div>
            </form>
            <form method="POST">
                <div class="col-md-4 text-center">
                    <?php
                    if (!empty($sit)) {
                        ?>
                        <button name="retira" value="1" type="submit" class="btn btn-success" style="width:150px">
                            Retirar da Lista
                        </button>
                        <input type="hidden" name="id_vaga" value="<?php echo $idvaga ?>" />
                        <?php
                    } else {
                        ?>
                        <button class="btn btn-default" disabled = "disabled" style="width:150px">
                            Retirar da Lista
                        </button>
                        <?php
                    }
                    ?>
                </div> 
            </form>
            <form action="<?php echo HOME_URI; ?>/vagas/pesq/" method="POST">
                <div class="col-md-4 text-center">
                    <input  style="width: 150px" class="btn btn-danger" type="submit" value="Voltar" />
                </div>
            </form>
        </div>
    </div>
    <?php
    if (!empty($dados_hist)) {
        ?>
        <div class="fieldWhite">
            <div style="font-weight:bold; font-size:14pt; background-color: #000000; color:#ffffff; text-align: center">
                HISTÓRICO DAS LIGAÇÕES       
            </div>
            <?php
            $form['array'] = $dados_hist;
            $form['fields'] = [
                'Código' => 'id_vagas_hist',
                'Tentativa' => 'tentativas',
                'Descrição' => 'descricao',
                'Data' => 'update_at'
            ];

            tool::relatSimples($form);
        }
        ?>
    </div>
</div>

<?php
if (!empty($_POST['retira'])) {
    $model->gravahistorico($idvaga, 'Retirar da Lista', '3 tentativas sem sucesso');
}

if (!empty($_POST['salva'])) {
    $model->gravahistorico($idvaga, $wsituacao[$tentativas], $descricao);
}
?>


