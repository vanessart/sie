<?php
$id_cate = $_POST['id_cate'];

if (!empty($id_cate)) {
    $id_pessoa = tool::id_pessoa(@$_POST['id_pessoa']);
    $proj = sql::get('giz_prof', '*', ['fk_id_pessoa' => $id_pessoa, 'fk_id_cate' => $id_cate], 'fetch');
 if(!empty($proj)){
     $id_mod = @$proj['fk_id_mod'];
 } else {
    $id_mod = @$_POST['id_mod']; 
 }
    $giz = sql::get('giz', '*', NULL, 'fetch');
    $cat = sql::get('giz_categoria', '*', ['id_cate' => $id_cate], 'fetch');
    if ($giz['fase'] == 1) {
        $disabled = NULL;
    } else {
        $disabled = 'disabled';
    }
    $id_inst = empty($_POST['id_inst']) ? @$proj['fk_id_inst'] : $_POST['id_inst'];
    $escolas = professores::profEscola($id_pessoa);
    $hidden = ['id_pessoa' => $id_pessoa, 'id_inst' => $id_inst, 'id_cate' => $id_cate, 'id_mod'=>$id_mod];

    $falta = 1;
    $verifica = ['objgeral', 'objespec', 'justifica', 'metodo', 'cronograma'];
    foreach ($verifica as $v) {
        if (empty($proj[$v])) {
            unset($falta);
        }
    }
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            GIZ DE OURO
            <br />
            Autor<?php echo user::session('sexo') == 'F' ? 'a' : '' ?>: <?php echo user::session('n_pessoa') ?>
            <br />
            Categoria: 
            <?php echo $cat['n_cate'] ?>
        </div>
        <br /><br />
        <?php
        $abas[1] = ['nome' => "Participantes", 'ativo' => 1, 'hidden' => $hidden, 'link' => "",];
        $abas[2] = ['nome' => "Projeto", 'ativo' => !empty($proj['id_prof']), 'hidden' => $hidden, 'link' => "",];
        $abas[3] = ['nome' => "Projeto (Continuação)", 'ativo' => !empty($proj['titulo']), 'hidden' => $hidden, 'link' => "",];
       // $abas[4] = ['nome' => "Coautores", 'ativo' => !empty($proj['titulo']), 'hidden' => $hidden, 'link' => "",];
        $abas[4] = ['nome' => "Validação", 'ativo' => @$falta, 'hidden' => $hidden, 'link' => "",];
        $abas[5] = ['nome' => "Sair", 'ativo' => 1, 'hidden' => $hidden, 'link' => HOME_URI . "/giz/",];
        $activeNav = tool::abas($abas);
        include ABSPATH . '/views/giz/_inscricao/' . $activeNav . '.php';
    }
    ?>
</div>                              

