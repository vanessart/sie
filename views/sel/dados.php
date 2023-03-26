<?php
$i = $_POST;
?>

<br /><br />
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover table-striped" style="font-size: 20px">
            <tr>
                <td>
                    Inscrição
                </td>
                <td>
                    <?php echo str_pad($i['id_inscr'], 6, "0", STR_PAD_LEFT) ?> 
                </td>

            </tr>
            <tr>
                <td style="text-align: center" colspan="2">

                    <?php
                  //  $folha = sql::get('ps_respostas', 'original', ['fk_id_tb' => @$i['id_inscr']], 'fetch')['original'];
                   // $folha = str_replace('/var/www/html', 'https://portal.educ.net.br/', $folha);
                    ?>
                    <!--
                    <form style="text-align: center" target="_blank" action="<?php echo $folha ?>" method="POST">
                        <input class="btn btn-danger" type="submit" value="FOLHA ÓTICA" />
                    </form>
                    -->
                </td>
            </tr>
            <tr>
                <td>
                    Data da Inscrição
                </td>
                <td>
                    <?php echo data::converte(@$i['dt_inscr']) ?> 
                </td>
            </tr>
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    <?php echo @$i['n_insc'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    CPF
                </td>
                <td>
                    <?php echo @$i['cpf'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    D. Nascimento
                </td>
                <td>
                    <?php echo data::converteBr(@$i['dt_nasc']) ?> 
                </td>
            </tr>
            <tr>
                <td>
                    RG
                </td>
                <td>
                    <?php echo @$i['rg'] . ' - ' . @$i['rg_oe'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    Endereço
                </td>
                <td>
                    <?php echo @$i['logradouro'] . ', ' . @$i['num'] ?> 
                    <br />
                    <?php
                    if (!empty($i['compl'])) {
                        $i['compl'] . '<br />';
                    }
                    echo @$i['bairro'] . '<br />' . @$i['cidade'] . ' - ' . @$i['uf'] . ' - CEP: ' . @$i['cep']
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Telefone
                </td>
                <td>
                   <?php echo $i['tel1'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    E-mail
                </td>
                <td>
                    <?php echo @$i['email'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    Q. Filhos
                </td>
                <td>
                    <?php echo @$i['filhos'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    Cargo
                </td>
                <td>
                    <?php echo$i['n_cargo'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    Código de Acesso
                </td>
                <td>
                    <?php echo "006" . $i['chave'] . str_pad($i['id_inscr'], 6, "0", STR_PAD_LEFT); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Prédio
                </td>
                <td>
                    <?php echo @$i['n_predio'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    Sala
                </td>
                <td>
                    <?php echo @$i['sala'] ?> 
                </td>
            </tr>

        </table>
    </div>
</div>