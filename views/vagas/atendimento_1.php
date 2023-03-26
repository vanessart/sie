<?php
$idvaga = $_POST['id_vaga'];

if (!empty($_POST['salva'])) {
    @$descricao = $_POST['descricao'];
    @$tentativas = $_POST['tentativas'];
    @$quantidade = $_POST['quantidade'];
}

$dados = $model->pegadadoscrianca($idvaga);
$dados_hist = $model->pegahistoricocrianca($idvaga);

$wsituacao = [
    1 => "Confirmado - Aguardando Matrícula",
    2 => "1ª Tentativa - Responsável Não Localizado",
    3 => "2ª Tentativa - Responsável Não Localizado",
    4 => "3ª Tentativa - Responsável Não Localizado",
    5 => "Não Tem Interesse na Vaga",
    5 => "Endereço Atual em Outro Município"
];

/*
  Controle direto no código
  $wquantidade = [
  1 => "1ª Vez",
  2 => "2ª Vezes",
  3 => "3ª Vezes"
  ];
 */
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
                            Nome do Pai:
                        </td> 
                        <td class="topo3" style="width: 750px">
                            <?php echo $dados['pai'] ?>
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
        <form method="POST">
            Observação:
            <input id = "descricao" type="text" name='descricao' value="<?php echo @$descricao ?>" required placeholder= "Digite aqui"/>
            <div class="row">
                <div class="col-md-6">
                    <?php echo formulario::select('tentativas', $wsituacao, 'Situação', @$tentativas); ?>
                </div>
                <div class="col-md-6">
                    <!--
                    <?php echo formulario::select('quantidade', $wquantidade, 'Quantidade de Vezes', @$quantidade) ?>
                    -->
                </div>

            </div>
            <div class="row">  
                <div class="col-md-3 text-center">
                    <button name="salva" value="1" type="submit" class="btn btn-success">
                        Salvar
                    </button>
                    <input type="text" name="id_vaga" value="<?php echo $idvaga ?>" />
                    <input type="text" name="grava_hist" value="<?php echo 'gravar' ?>" />
                </div>
                <div class="col-md-3 text-center">
                    <a onclick="retira()" class="btn btn-warning" >
                        Retirar da Lista
                    </a>
                </div> 
                <div class="col-md-3">
                   
                                 
                    <a href="<?php echo HOME_URI; ?>/vagas/pesq/" >
                        <input  style="width: 150px" class="btn btn-danger" type="submit" value="Voltar" />
                    </a>
                </div>
            </div>
        </form>
    </div>
    <?php
    if (!empty($dados_hist)) {
        ?>
        <div class="fieldWhite">
            <br />
            <div style="font-weight:bold; font-size:14pt; background-color: #000000; color:#ffffff; text-align: center">
                HISTÓRICO DAS LIGAÇÕES       
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="topo" style="width: 200px">
                            Data
                        </th>
                        <th class="topo" style="width: 200px">
                            Tentativa
                        </th>
                        <th class="topo" style="width: 200px">
                            Quantidade
                        </th>
                        <th class="topo" style="width: 800px">
                            Histórico
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dados_hist as $v) {
                        ?>
                        <tr>
                            <td class="topo" style="width: 100px">
                                <?php echo data::converteBr($v['update_at']) ?>
                            </td>
                            <td class="topo" style="width: 100px">
                                <?php echo $v['tentativas'] ?>
                            </td>
                            <td class="topo" style="width: 100px">
                                <?php echo $v['quantidade'] ?>
                            </td>
                            <td class="topo" style="width: 500px">
                                <?php echo $v['descricao'] ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    ?>
</div>
<?php
if (!empty($_POST['salva'])) {
    $model->gravahistorico($idvaga, $wsituacao[$tentativas], $descricao);
}
?>

