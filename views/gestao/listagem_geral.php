<?php
ob_start();
$escola = new escola();
echo $escola->cabecalho();
?>
<head>
    <style>
        td{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 5px;
        }
    </style> 
</head>

<div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 100%; color:#ffffff; text-align: center">
    Listagem Geral de Alunos
</div>

<table class="table tabs-stacked table-bordered">
    <?php
    $lista = "Select "
            . " id_pessoa, chamada, pessoa.n_pessoa, codigo_classe, rg, logradouro, "
            . " num, complemento, cep, bairro, cidade, tel1, tel2, email, "
            . "mae, pai, cidade_nasc, sexo,dt_nasc "
            . "from pessoa"
            . " Join ge_turma_aluno on ge_turma_aluno.fk_id_pessoa = pessoa.id_pessoa"
            . " Join endereco on endereco.fk_id_pessoa = pessoa.id_pessoa"
            . " Where fk_id_inst=" . tool::id_inst() . " order by codigo_classe, chamada";

    $query = $model->db->query($lista);
    $array = $query->fetchAll();
    
    ?>
    <thead>
        <tr>
            <td>RSE</td>
            <td>Ch</td>
            <td>Nome Aluno</td>
            <td>Cod.Classe</td>
            <td>R.G.</td>
            <td>Cartão SUS</td>
            <td>Cartão <?= CLI_CIDADE ?></td>
            <td>Endereço</td>
            <td>Nr.</td>
            <td>Complemento</td>
            <td>CEP</td>
            <td>Bairro</td>
            <td>Cidade</td>
            <td>Fone</td>
            <td>Celular</td>
            <td>Email</td>
            <td>Nome Mãe</td>
            <td>Nome Pai</td>
            <td>Local Nasc.</td>
            <td>Sexo</td>
            <td>Data Nasc</td>
            <td>Idade</td>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach ($array as $v) {
            ?>

            <tr>
                <td>
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td>
                    <?php echo $v['chamada'] ?>
                </td>
                <td style="text-align: left">
                    <?php echo $v['n_pessoa'] ?>
                </td>
                <td>
                    <?php echo $v['codigo_classe'] ?>
                </td>
                <td>
                    <?php echo $v['rg'] ?>
                <td>
                    <?php echo ' ' ?>
                </td>
                <td>
                    <?php echo ' ' ?>
                </td>
                <td>
                    <?php echo $v['logradouro'] ?>
                </td>
                <td>
                    <?php echo $v['num'] ?>
                </td>
                <td>
                    <?php echo $v['complemento'] ?>
                </td>
                <td>
                    <?php echo $v['cep'] ?>
                </td>
                <td>
                    <?php echo $v['bairro'] ?>
                </td>
                <td>
                    <?php echo $v['cidade'] ?>
                </td>
                <td>
                    <?php echo $v['tel1'] ?>
                </td>
                <td>
                    <?php echo $v['tel2'] ?>
                </td>
                <td>
                    <?php echo $v['email'] ?>
                </td>      
                <td>
                    <?php echo $v['mae'] ?>
                </td>
                <td>
                    <?php echo $v['pai'] ?>
                </td>
                <td>
                    <?php echo $v['cidade_nasc'] ?>
                </td>
                <td>
                    <?php echo $v['sexo'] ?>
                </td>
                <td> 
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td>     
                    <?php echo ' ' ?>
                </td>
            </tr>
        </tbody>
        <?php
    }
    ?>

</table>

<?php
tool::pdf('L');
?>