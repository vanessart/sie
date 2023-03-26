<?php
ob_start();
$prof = $model->inscricoes(@$_POST['id_prof'], 'id_prof');
?>
<div class="fieldBody">
    <div class="fieldTop">
        Termo de Aceite de Inscrição -  Giz de Ouro <?php echo date("Y") ?>
    </div>
    <br /><br />
    <div>
        A equipe de gestão, abaixo assinada, formaliza o aceite da entrega da inscrição do projeto detalhado a seguir, considerando-o em conformidade com os requisitos e os critérios de aceitação definidos nos termos legais.
    </div>
    <br /><br />
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
                Disciplina:
            </td>
            <td>
                <?php echo $prof['n_disc'] ?>
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
                Justificativa:
            </td>
            <td>
                <div style="white-space: pre-wrap;"><?php echo $prof['justifica'] ?></div>
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
    <br /><br />
    A Direção atesta a inscrição do referido projeto.
    <br /><br /><br />
    <div style="text-align: right">
        Barueri, <?php echo date("d") ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
    </div>
    <br /><br /><br /><br /><br />
    <table style="width: 100%">
        <tr>
            <td style="text-align: center;">
                _______________________________________
                <br /><br />
                <?php echo $prof['n_pessoa'] ?>
                <br />
                Autor<?php echo ($prof['sexo'] == 'F' ? 'a' : '') ?>
            </td>
            <td style="text-align: center;">
                _______________________________________
                <br /><br />

                Equipe de Gestão
                <br />
                Carimbo e Assinatura
            </td>
        </tr>
    </table>
</div>

<?php
tool::pdfEscola();

