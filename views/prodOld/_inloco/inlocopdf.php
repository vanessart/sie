<?php
ob_start();
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
?>
<div style="text-align: center; font-size: 22px">
    <?php echo sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'] ?>
</div>
<br /><br />

<?php
if (!empty($id_inst)) {
    $prof = $model->prof12($id_inst);
    if (!empty($prof)) {
        ?>
        <table style="width: 100%" border="1" cellspacing="1" cellpadding="0" bgcolor="#CCCCCC">
            <tr>
                <td colspan="5" style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Professores - AEE, EJA, Infantil, Polivalente, Inglês e E. Física 
                </td>
            </tr>
            <tr>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Funcionário
                </td>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Matrícula
                </td>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Classe
                </td>
                <td>
                    Período
                </td>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">   
                    Função
                </td>
            </tr>
            <?php
            foreach ($prof as $k => $v) {
                ?>
                <tr>
                    <td>
                        <?php echo $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?php echo $v['rm'] ?>
                    </td>
                    <td>
                        <?php echo $v['n_turma'] ?>
                    </td>
                    <td>
                        <?php echo $v['periodo'] ?>
                    </td>
                    <td>
                        <?php echo $v['n_disc'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br /><br />
        <?php
    }
    ?>
    <?php
    $prof = $model->informatica($id_inst);
    if (!empty($prof)) {
        ?>
        <table style="width: 100%" border="1" cellspacing="1" cellpadding="0" bgcolor="#CCCCCC">
            <tr>
                <td colspan="3" style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Professores - Informática
                </td>
            </tr>
            <tr>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Funcionário
                </td>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Matrícula
                </td>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">   
                    Função
                </td>
            </tr>
            <?php
            foreach ($prof as $k => $v) {
                ?>
                <tr>
                    <td>
                        <?php echo $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?php echo $v['rm'] ?>
                    </td>
                    <td>
                        <?php echo $v['n_disc'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br /><br />
        <?php
    }
    $prof = $model->adi($id_inst);
    if (!empty($prof)) {
        ?>
        <table style="width: 100%" border="1" cellspacing="1" cellpadding="0" bgcolor="#CCCCCC">
            <tr>
                <td colspan="2" style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    ADI - Agente de Desenvolvimento Infantil 
                </td>
            </tr>
            <tr>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Funcionário
                </td>
                <td style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                    Matrícula
                </td>
            </tr>
            <?php
            foreach ($prof as $k => $v) {
                ?>
                <tr>
                    <td>
                        <?php echo $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?php echo $v['rm'] ?>
                    </td>
                </tr>
                <?php
            }
        }
    }
    ?>
</table>
<?php
tool::pdf();

