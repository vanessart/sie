<?php
$id_setor = filter_input(INPUT_POST, 'id_setor', FILTER_SANITIZE_NUMBER_INT);
$sql = " SELECT id_setor, n_setor FROM avs_setor_pessoa sp "
        . " JOIN setor s on s.id_setor = sp.fk_id_setor "
        . " WHERE sp.fk_id_pessoa =  " . tool::id_pessoa();
$query = $model->db->query($sql);
$acesso = $query->fetchAll();
$dados = NULL;
?>
<div class="fieldBody">

    <?php
    if (empty($acesso)) {
        ?>
        <div class=" alert alert-danger text-center">
            Você não está alocado
        </div>
        <?php
    } elseif (count($acesso) > 1) {
        $acesso = tool::idName($acesso);
        ?>
        <div class="row">
            <div class="col-sm-6">
                <?php echo formulario::select('id_setor', $acesso, 'Setor', $id_setor, 1); ?>
            </div>
        </div>
        <?php
    } else {
        $id_setor = current($acesso)['id_setor'];
    }
    if (!empty($id_setor)) {
        ?>
        <br /><br />
        <?php
        echo formulario::submit('Novo Aviso', NULL, ['id_setor' => $id_setor, 'modal' => 1]);
        if (empty($_POST['modal'])) {
            $modal = 1;
        } else {
            $modal = NULL;
        }
        tool::modalInicio('width: 95%', $modal);
        include ABSPATH . '/views/avs/_aviso/cadastro.php';
        tool::modalFim();

        $sql = 'SELECT `id_avs`,`dt_avs`,`dt_avs_prazo`,`n_avs` FROM avs_main '
                . " WHERE fk_id_setor = $id_setor "
                . ' ORDER by `dt_avs_prazo` ';
        $query = $model->db->query($sql);
        $av = $query->fetchAll();
        $form['array'] = $av;
        $form['fields'] = [
            'ID' => 'id_avs',
            'Aviso' => 'n_avs',
            'Data' => 'dt_avs',
            'Prazo' => 'dt_avs_prazo',
            '||1' => 'anexo',
            '||2' => 'escolas',
            '||3' => 'edit',
            '||4' => 'consulta'
        ];
        tool::relatSimples($form);
    }
    ?>

</div>
