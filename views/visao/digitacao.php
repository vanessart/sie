<?php
$id_pessoa = $_POST['id_pessoa'];
$codClasse = $_POST['codigo'];
$visao = $_POST['id_visao'];
@$tit = $_POST['titulo'];
@$Nav = $_POST['activeNav'];

if (empty($tit)) {
    switch ($Nav) {
        case 1:
            @$tit = 'Registro dos Exames - Teste';
            break;
        case 2:
            @$tit = 'Registro dos Exames - Reteste';
            break;
        case 3:
            @$tit = 'Acompanhamento do Aluno';
            break;
        default :
            @$tit = 'Registrar';
    }
}
$def = sql::get('cv_deficiencia', '*', ['>' => 'id_deficiencia']);
$sin = sql::get('cv_sinais', '*', ['>' => 'id_sinais']);
$com = sql::get('cv_teste_computador', '*', ['>' => 'id_teste_comp']);
$pap = sql::get('cv_teste_papel', '*', ['>' => 'id_teste_papel']);

$dados = sql::get('pessoa', '*', ['id_pessoa' => @$id_pessoa], 'fetch');
extract($dados);

$exame = sql::get('cv_visao_aluno', '*', ['id_visao' => $visao], 'fetch');
?>

<div style="padding-left: 20px; padding-right: 20px; padding-top: 10px">
    <div class="row">
        <table class="table" style="width: 100%; border: #000000 thin solid; height: 80px" > 
            <thead>
                <tr>
                    <td style="color: red; font-size: 20px" colspan="8">
                        <b>Dados do Aluno</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        RSE
                    </td>
                    <td>
                        Nome Aluno
                    </td>
                    <td>
                        Cód.Classe
                    </td>
                    <td>
                        RA
                    </td> 
                    <td>
                        Data Nasc.
                    </td>
                    <td>
                        Sexo
                    </td>
                    <td>
                        SUS
                    </td>
                    <td>
                        Telefone
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo $id_pessoa ?>
                    </td>
                    <td>
                        <?php echo addslashes($n_pessoa) ?>
                    </td>
                    <td>
                        <?php echo $codClasse ?>
                    </td>
                    <td>
                        <?php echo $ra . '-' . $ra_dig ?>
                    </td>
                    <td>
                        <?php echo data::converteBr($dt_nasc) ?>
                    </td>
                    <td>
                        <?php echo $sexo ?>
                    </td>
                    <td>
                        <?php echo $sus ?>
                    </td>
                    <td>
                        <?php echo $tel1 ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="panel panel-default">
            <div style="font-size: 20px; color: red" class="panel panel-heading">
                <?php echo @$tit ?>
            </div>

            <?php
            if (@$exame['reteste'] == 'Sim') {
                $ativoReTeste = 1;
            }
            if (@$exame['reteste_sit'] == 'FALHA') {
                $ativaAcomp = 1;
            }

            $hidden = ['id_pessoa' => $id_pessoa, 'id_turma' => $exame['fk_id_turma'], 'id_visao' => $visao, 'codigo' => $codClasse];
            $abas[1] = [ 'nome' => "Teste", 'ativo' => 1, 'hidden' => $hidden, 'link' => "",];
            $abas[2] = [ 'nome' => "Reteste", 'ativo' => @$ativoReTeste, 'hidden' => $hidden, 'link' => "",];
            $abas[3] = [ 'nome' => "Acompanhamento", 'ativo' => @$ativaAcomp, 'hidden' => $hidden, 'link' => "",];
            $abas[4] = [ 'nome' => "Retornar", 'ativo' => 1, 'hidden' => $hidden, 'link' => HOME_URI . "/visao/selecionaaluno",];
            $activeNav = tool::abas($abas);

            echo '<br /><br /><br />';
            include ABSPATH . '/views/visao/_digitacao/' . $activeNav . '.php';
            ?>
        </div>
    </div>
</div>
