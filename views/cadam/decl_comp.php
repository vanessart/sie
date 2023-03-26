<?php
ob_start();

$id_cad = $_POST['id_cad'];

$dados = sql::get(['pessoa', 'cadam_cadastro'], 'n_pessoa, rg, rg_dig, rg_oe, rg_uf, cpf, sexo', 'WHERE id_cad = ' .  $id_cad . " AND check_update = '1'", 'fetch');

if (!empty($dados)) {
    ?>

    <br /><br /><br /><br /><br /><br /><br />
    <div style="font-weight:bold; font-size:12pt; text-align: center">
        DECLARAÇÃO DE COMPARECIMENTO
    </div>
    <br /><br /><br /><br /><br />
    <div style="text-align:justify"> Declaro que <?php echo $a = ($dados['sexo'] == 'M') ? 'o' : 'a' ?> docente 
        <b><?php echo $dados['n_pessoa'] ?></b>, RG nº <?php echo $dados['rg'] . ' - ' . $dados['rg_oe'] ?>,
        CPF nº <?php echo substr($dados['cpf'], 0, 9) . '-' . substr($dados['cpf'], 9, 2) ?>, compareceu ao Departamento Técnico de Gestão de Pessoal da
        Secretaria de Educação para fins de atualização de dados no Cadastro Municipal de Professores 
        Eventuais - CADAMPE - conforme convocação no Jornal Oficial de Barueri, Edição 1.184, de 06/11/2019, página 11.
    </div>

    <br /><br />
    <br />
    <br /><br /><br /><br />

    <div style="text-align: right">Barueri, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
    <br /><br /><br /><br /><br /><br />
    <div style="text-align: center">_____________________________________</div>
    <div style="text-align:center">Assinatura</div>
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

    <?php
    
} else {
    echo "Recadastramento não efetuado";
}

tool::pdfsecretaria3('P');

?>