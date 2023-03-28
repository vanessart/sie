<?php
ob_start();
$escola = new escola();

if (!empty($_POST['imprimir'])) {
    $destino = $_POST['imprimir'];
    $idturma = $_POST['idturma'];
    $dados = $model->encaminhamento($destino, $idturma);
} else {
    $dados = $model->encaminhamentocarta($_POST['as2']);
}
?>

<head>
    <style>
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 5px;
        }
        .quebra {
            page-break-before: always;
        }
    </style> 
</head>

<?php
foreach ($dados as $v) {
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    switch ($v['ciclo_futuro']) {
        case "1º Ano do Ensino Fundamental":
        case "2º Ano do Ensino Fundamental":
        case "3º Ano do Ensino Fundamental":
        case "4º Ano do Ensino Fundamental":
        case "5º Ano do Ensino Fundamental":
        case "6º Ano do Ensino Fundamental":
        case "7º Ano do Ensino Fundamental":
        case "8º Ano do Ensino Fundamental":
        case "9º Ano do Ensino Fundamental":
            $data_i = "02/01/2023";
            $data_f = "06/01/2023";
            $artigo = 'no';
            break;
        default :
            //Infantil
            $data_i = "10/10/2022";
            $data_f = "27/10/2022";
            $artigo = 'na';
            break;
    }
    ?>
    <br /><br />
    <div style="font-weight:bold; font-size:10pt; text-align: center">
        <b>CARTA DE ENCAMINHAMENTO</b>
    </div>
    <br /><br />
    <div style="font-weight:bold; font-size:10pt; text-align: left">
        <b>Senhores Pais ou Responsável,</b>
    </div>      
    <br /><br />              
    <div style="text-align: justify">
        Encaminhamos <?php echo $sexo = ($v['sexo'] == "M") ? ' o aluno ' : ' a aluna ' ?>  
        <b><?php echo addslashes($v['n_pessoa']) ?> </b>, <?php echo $sexo = ($v['sexo'] == "M") ? ' nascido ' : ' nascida ' ?><?php echo data::converteBr($v['dt_nasc']) ?> 
        <?php echo $sexo = ($v['sexo'] == "M") ? ' portador ' : ' portadora ' ?> do RG: Nº <?php echo $v['rg'] ?>, RSE: Nº
        <?php echo $v['id_pessoa'] ?>, RA: Nº <?php echo $v['ra'] . '-' . $v['ra_dig'] ?> para <?php echo $v['n_inst'] ?> <?php echo $artigo ?> <?php echo $v['ciclo_futuro'] ?> em 2023.
    </div>  
    <br />
    <div style="text-align: justify">
        Solicitamos que compareçam à Escola para qual <?php echo $sexo = ($v['sexo'] == "M") ? ' seu filho foi encaminhado ' : ' sua filha foi encaminhada ' ?> 
        no período de <?php echo $data_i ?> à <?php echo $data_f ?> portando a seguinte documentação:           
    </div>
    <br />
    <div style="font-weight: bold; font-size: 8pt; background-color: #000000; color:#ffffff; text-align: center; width: 300px; padding: 3px">
        DOCUMENTOS PARA MATRÍCULA
    </div>
    <div style="text-align: left; font-size: 8pt">
        <br />
        a. Xerox da Certidão de Nascimento e RG <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
        b. 03 fotos 3x4 recentes <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
        <!--c. Xerox do RG e CPF dos pais <?php echo $sexo = ($v['sexo'] == "M") ? ' pelo aluno; ' : ' pela aluna; ' ?><br /> -->
        c. Xerox do RG e CPF dos pais;<br />
        d. Xerox do comprovante de endereço atualizado, em nome do pai, mãe ou responsável legal <?php echo $sexo = ($v['sexo'] == "M") ? ' pelo aluno, ' : ' pela aluna, ' ?> morador de Barueri;<br />
        e. Xerox da carteira de vacina atualizada <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno ' : ' da aluna ' ?> com validação da Unidade Básica de Saúde - UBS; <br />


        <?php
        switch ($v['ciclo_futuro']) {
            case "1ª Fase Maternal do Ensino Infantil":
            case "2ª Fase Maternal do Ensino Infantil":
            case "3ª Fase Maternal do Ensino Infantil":
                ?>
                f. Xerox dos documentos de identidade das pessoas autorizadas a retirar a criança da escola;<br />
                g. Comprovante de trabalho da mãe ou do(a) responsável legal pela criança, podendo ser
                cópia da Carteira de Trabalho, devidamente registrada, e último holerite ou
                Declaração de Trabalho com firma reconhecida e recibo de pagamento que comprove atividade laboral fora do lar.<br />
                h. Cartão do SUS <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno. ' : ' da aluna. ' ?><br />
                <?php
                break;

            case "1ª Fase Pré do Ensino Infantil":
            case "2ª Fase Pré do Ensino Infantil":
                ?>
                f. Xerox do CPF <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
                g. Cartão do SUS <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
                <?php
                break;
            case "1º Ano do Ensino Fundamental":
                ?>
                f. Xerox do CPF <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
                g. Cartão do SUS <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
                <?php
                break;
            case "2º Ano do Ensino Fundamental":
            case "3º Ano do Ensino Fundamental":
            case "4º Ano do Ensino Fundamental":
            case "5º Ano do Ensino Fundamental":
            case "6º Ano do Ensino Fundamental":
            case "7º Ano do Ensino Fundamental":
            case "8º Ano do Ensino Fundamental":
            case "9º Ano do Ensino Fundamental":
            case "1º Ano do Ensino Médio":
                ?>
                f. Xerox do CPF <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
                g. Cartão do SUS <?php echo $sexo = ($v['sexo'] == "M") ? ' do aluno; ' : ' da aluna; ' ?><br />
                h. Histórico Escolar e ou Boletim.<br />
                <?php
                break;
            default:
        }
        ?>
        <br />
    </div>
    <div style="text-align: left; font-size: 8pt; color: red; font-weight:bold ">    
        Obs. A cópia do comprovante de residência deve ser apresentada com o original para a devida conferência.
    </div>
    <br />
    <div style="text-align: right">
        <?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?>
    </div>
    <br /><br />
    <div style="text-align: center; padding-left: 350px">
        <?php echo '_____________________________________<br /> Assinatura' ?>
    </div>
    <br />
    <div style="text-align:center; padding-right: 250px">
        <?php echo 'Ciente:<br /><br />_____________________________________<br />Assinatura do Responsável ' ?>
    </div>
    <br />
    <?php
    $end = $model->pegaenderecoescola($v['escola_destino']);
    ?>
    <br />
    <div style="font-size: 8pt; text-align: left; width: 500px; border: 1px solid">
        <b>Escola Encaminhada</b><br />
        <?php echo $v['n_inst'] ?> <br />
        <?php echo $end['end'] ?><br />
        <?php echo 'Telefone: ' . $end['tel'] ?><br />
    </div>

    <?php
}

tool::pdfSemRodape();
