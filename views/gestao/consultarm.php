<?php
$siemon = sql::get('ge_aluno_rm_on', 'id_pessoa, pessoaid, cie, rm, ra, rg, dt_nasc, nome_aluno, nome_mae', ['CIE' => tool::cie(), '>' => 'nome_aluno']);
$siemof = sql::get('ge_aluno_rm_of', 'CIE, RM, RA, NOMEALUNO, DATANASC, NOMEMAE', ['CIE' => tool::cie(), '>' => 'NOMEALUNO']);
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="<?php echo HOME_URI; ?>/views/_js/jquery-ui.js"></script>

<script>
    function complete() {
        var buscar = [
<?php
foreach ($siemof as $value) {
    ?>
                "<?php
    echo strtoupper(str_replace('"', "'", $value['NOMEALUNO']))
    . '|' . $value['RM']
    . '|' . $value['RA']
    ?>",
    <?php
}
?>
        ];
        $("#busca").autocomplete({
            source: buscar,
            campo_adicional: ['#RM', '#RA']
        });
    }
    function completeon() {

        var buscar = [
<?php
foreach ($siemon as $value) {
    ?>
                "<?php
    echo strtoupper(str_replace('"', "'", $value['nome_aluno']))
    . '|' . $value['rm']
    . '|' . $value['ra']
    ?>",
    <?php
}
?>

        ];
        $("#buscaon").autocomplete({
            source: buscar,
            campo_adicional: ['#onrm', '#onra']
        });
    }
</script>

<head>
    <style type="text/css">
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding-left: 2px;
            padding-right: 2px;
        }
    </style>
</head>

<div class="fieldBody">

    <div class="rowField" style="min-height: 10vh; width: 98%">
        <br />
        <div style="text-align: center; font-size: 18px; color: red">
            <b>CONSULTA RM</b>
            <br />
        </div>  
        <div style="text-align: left; font-size: 12px; padding: 10px">
            <b>Dica:</b>
            <br />
            Para pesquisa avançada digite no Campo Nome a primeira letra do nome ou parte do nome p. ex. Maria: digite M Ma Mar Mari ou Maria.
            <br />
            No Campo Sobrenome digite o último nome p. ex. José de Oliveira Junior: digite Junior ou Oliveira Junior.
            <br />
        </div>          
    </div>
    <br />

    <div class="row">
        <div class="col-md-6">
            <div class="rowField" style="min-height: 45vh; width: 98%">
                <form method="POST">
                    <br />
                    <div class="col-md-8">
                        <input type="text" name="of" value="" id = "busca" onkeypress="complete()" placeholder="Digite aqui Nome Completo para Pesquisa"/>                  
                        <input type="hidden" name="rm" value="" id = "RM"/>
                    </div>                     
                    <div class="col-md-4">        
                        <button class="btn btn-success">
                            Pesquisar Siem Offline
                        </button>
                    </div>
                    <br />
                    <?php
                    if (!empty($_POST['rm'])) {
                        $id_rm = $_POST['rm'];
                        $dados = sql::get('ge_aluno_rm_of', 'CIE, RM, RA, NOMEALUNO, DATANASC, NOMEMAE', 'Where CIE = ' . tool::cie() . " AND RM = '" . @$id_rm . "' ORDER BY NOMEALUNO");
                    } else {
                        if ((!empty($_POST['pes_n'])) && (!empty($_POST['pes_s']))) {
                            $campo = $_POST['pes_n'] . '%' . $_POST['pes_s'];
                            $dados = sql::get('ge_aluno_rm_of', 'CIE, RM, RA, NOMEALUNO, DATANASC, NOMEMAE', 'Where CIE = ' . tool::cie() . " AND NOMEALUNO  LIKE '" . @$campo . "' ORDER BY NOMEALUNO");
                        }
                    }
                    ?>
                </form>
                <br /><br />
                <div class="row">
                    <div style="text-align: center; color: red" class="col-md-12">
                        Pesquisa Avançada
                    </div> 
                </div>
                <br />
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="pes_n" placeholder="Campo Nome" required /> 
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="pes_s" placeholder="Campo Sobrenome" required/> 
                        </div>          
                        <div class="col-md-4">        
                            <button class="btn btn-success">
                                Pesquisa Avançada
                            </button>
                        </div>
                    </div>
                </form>
                <br />
                <?php
                $form['array'] = @$dados;
                $form['fields'] = [
                    'RM' => 'RM',
                    'RA' => 'RA',
                    'Nome Aluno' => 'NOMEALUNO',
                    'Data Nasc.' => 'DATANASC',
                    'Nome Mãe' => 'NOMEMAE'
                ];
                tool::relatSimples($form);
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="rowField" style="min-height: 45vh; width: 98%">
                <form method="POST">
                    <br />
                    <div class="col-md-8">
                        <input type="text" name="on" value="" id = "buscaon" onkeypress="completeon()" placeholder="Digite aqui Nome Completo para Pesquisa"/>                  
                        <input type="hidden" name="onrm" value="" id = "onrm"/>
                    </div>                     
                    <div class="col-md-4">        
                        <button class="btn btn-success">
                            Pesquisar Siem Online
                        </button>
                    </div>
                    <br />
                    <?php
                    if (!empty($_POST['onrm'])) {
                        $id_rm = $_POST['onrm'];
                        $dados = sql::get('ge_aluno_rm_on', 'id_pessoa, pessoaid, cie, rm, ra, rg, dt_nasc, nome_aluno, nome_mae', 'WHERE CIE = ' . tool::cie() . " AND rm = '" . @$id_rm . "' ORDER BY nome_aluno");
                    } else {

                        if ((!empty($_POST['pes_non'])) && (!empty($_POST['pes_son']))) {
                            $campo = $_POST['pes_non'] . '%' . $_POST['pes_son'];
                            $dados = sql::get('ge_aluno_rm_on', 'id_pessoa, pessoaid, cie, rm, ra, rg, dt_nasc, nome_aluno, nome_mae', 'WHERE CIE = ' . tool::cie() . " AND nome_aluno  LIKE '" . @$campo . "' ORDER BY nome_aluno");
                        }
                    }
                    ?>
                </form>
                <br /><br />
                <div class="row">
                    <div style="text-align: center; color: red" class="col-md-12">
                        Pesquisa Avançada
                    </div> 
                </div>
                <br />
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="pes_non" placeholder="Campo Nome" required/> 
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="pes_son" placeholder="Campo Sobrenome" required/> 
                        </div>          
                        <div class="col-md-4">        
                            <button class="btn btn-success">
                                Pesquisa Avançada
                            </button>
                        </div>
                    </div>
                    <br />
                    <?php
                    $form['array'] = @$dados;
                    $form['fields'] = [
                        'RM' => 'rm',
                        'RA' => 'ra',
                        'ID' => 'id_pessoa',
                        'Nome Aluno' => 'nome_aluno',
                        'Data Nasc.' => 'dt_nasc',
                        'Nome Mãe' => 'nome_mae'
                    ];
                    tool::relatSimples($form);
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
