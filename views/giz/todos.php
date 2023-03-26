<?php
ob_start();
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('memory_limit', '2000M');
$id_cate=$_POST['id_cate'];
$professores = $model->inscricoes($id_cate, 'fk_id_cate', 1);
foreach ($professores as $prof){
?>
<div class="fieldBody">
 
    <style>
        td{
            padding: 4px;
        }
    </style>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=1>
        <tr>
            <td style="width: 15%">
                Autor:
            </td>
            <td>
                <?php echo $prof['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Nº de Inscrição:
            </td>
            <td>
                <?php echo $prof['id_prof'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Situação:
            </td>
            <td>
                <?php
                if ($prof['gestor'] == 1) {
                    echo 'Aguardando efetivação pela Equipe de Gestão';
                } elseif ($prof['gestor'] == 2) {
                    echo 'Efetivado pela Equipe de Gestão';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Categoria:
            </td>
            <td>
                <?php echo $prof['n_cate'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Título:
            </td>
            <td>
                <?php echo $prof['titulo'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Tema:
            </td>
            <td>
                <?php echo $prof['tema'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Escola:
            </td>
            <td>
                <?php echo $prof['n_inst'] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                turmas:
            </td>
            <td>
                <?php echo $model->turmas($prof['turmas']) ?>
            </td>
        </tr>
        <?php
        /**
        $coautor = $model->coautor($prof['id_prof']);
        if (!empty($coautor)) {
            ?>
            <tr>
                <td style="width: 15%">
                    Coautor<?php echo (count($coautor) > 1 ? 'es' : '') ?>:
                </td>
                <td>
                    <?php
                    foreach ($coautor as $v) {
                        echo $v['n_pessoa'] . ' (RM: ' . $v['rm'] . ')<br />';
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
         * 
         */
        ?>
        <tr>
            <td style="width: 15%">
                Período:
            </td>
            <td>
                de 
                <?php echo data::converteBr($prof['dt_inicio']) ?> 
                a 
                <?php echo data::converteBr($prof['dt_fim']) ?> 
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Objetivo Geral:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['objgeral'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Objetivo Específico:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['objespec'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Justificativa:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['justifica'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Método:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['metodo'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Cronograma:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['cronograma'] ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%">
                Recursos:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['recurso'] ?></div>
            </td>
        </tr>
    </table>
<br /><br /><br />
</div>
<div style="page-break-after: always"></div>
<?php
}
tool::pdfEscola();

