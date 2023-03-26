<?php
ob_start();
$prof = $model->inscricoes(@$_POST['id_prof'], 'id_prof');
?>
<div class="fieldBody">
    <div class="fieldTop" style="font-size: 18px">
        Giz de Ouro <?php echo date("Y") ?>
        <br /><br />
        Projeto - <?php echo $prof['titulo'] ?>
    </div>

    <br /><br /><br /><br />
    <style>
        td{
            font-size: 13px;
            padding: 4px;
        }
    </style>
    <table style="width: 100%; font-size: 14px" border=1 cellspacing=0 cellpadding=1>
        <tr>
            <td>
                Autor:
            </td>
            <td>
                <?php echo $prof['n_pessoa'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Nº de Inscrição:
            </td>
            <td>
                <?php echo $prof['id_prof'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Categoria:
            </td>
            <td>
                <?php echo $prof['n_cate'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Escola:
            </td>
            <td>
                <?php echo $prof['n_inst'] ?>
            </td>
        </tr>
        <?php
        if (!empty($prof['n_disc'])) {
            ?>
            <tr>
                <td>
                    Disciplina:
                </td>
                <td>
                    <?php echo $prof['n_disc'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>
                turmas:
            </td>
            <td>
                <?php echo $model->turmas($prof['turmas']) ?>
            </td>
        </tr>
        <?php
        $coautor = $model->coautor($prof['id_prof']);
        if (!empty($coautor)) {
            ?>
            <tr>
                <td>
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
        ?>
        <tr>
            <td>
                Tema:
            </td>
            <td>
                <?php echo $prof['tema'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Justificativa:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['justifica'] ?></div>
            </td>
        </tr>
        <tr>
            <td>
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
            <td>
                Objetivo Geral:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['objgeral'] ?></div>
            </td>
        </tr>
        <tr>
            <td>
                Objetivo Específico:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['objespec'] ?></div>
            </td>
        </tr>
        <tr>
            <td>
                Método:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['metodo'] ?></div>
            </td>
        </tr>
        <tr>
            <td>
                Cronograma:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['cronograma'] ?></div>
            </td>
        </tr>
        <tr>
            <td>
                Recursos:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['recurso'] ?></div>
            </td>
        </tr>
    </table>

</div>

<?php
tool::pdfSecretaria();

