<?php
if (!defined('ABSPATH'))
    exit;

$cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_STRING);
if (!empty($cie)) {
    $esc = $model->plEscolas($cie);
    
    $id_passelivre = filter_input(INPUT_POST, 'id_passelivre', FILTER_SANITIZE_NUMBER_INT);
    if(empty($id_passelivre)){
        $id_passelivre = $model->db->last_id;
    }
    if ($id_passelivre) {
        $req = sql::get('pl_passelivre', '*', ['id_passelivre' => $id_passelivre], 'fetch');
        if (empty($req['distancia_escola']) && !empty($req['latitude']) && !empty($req['longitude']) && !empty($esc['maps'])) {
            $maps = tool::distancia((trim($req['latitude']) . ',' . trim($req['longitude'])), $esc['maps']);
           $req['distancia_escola'] = str_replace(',', '.', explode(' ', $maps[0])[0]);
        }
    }
    if ($id_passelivre) {
        $abaAtiva = 1;
    } else {
        $abaAtiva = null;
    }
    ?>
    <div class="body">
        <div class="fieldTop">
            Requerente<?= !empty($req['nome']) ? ': ' . $req['nome'] : '' ?>
        </div>
        <br />
        <?php
        $abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => ['id_passelivre' => $id_passelivre, 'cie' => $cie]];
        $abas[2] = ['nome' => "Uploads", 'ativo' => $abaAtiva, 'hidden' => ['id_passelivre' => $id_passelivre, 'cie' => $cie]];
        $abas[3] = ['nome' => "Deferimento", 'ativo' => $abaAtiva, 'hidden' => ['id_passelivre' => $id_passelivre, 'cie' => $cie]];
        $abas[4] = ['nome' => "Geolocalização", 'ativo' => $abaAtiva, 'hidden' => ['id_passelivre' => $id_passelivre, 'cie' => $cie]];
        $aba = report::abas($abas);
        include ABSPATH . "/module/passelivre/views/_requer/$aba.php";
        ?>
    </div>
    <?php
}