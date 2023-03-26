<?php
if (!defined('ABSPATH'))
    exit;
?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <td>
            Nome   
        </td>
        <td>
            Matrícula
        </td>
        <td>
            CPF
        </td>
        <td>

        </td>
    </tr>
    <?php
    foreach ($busca as $v) {
        ?>
        <tr>
            <td>
                <?= $v['n_pessoa'] ?>   
            </td>
            <td>
                <?= !empty($v['rm']) ? $v['rm'] : 'Não é funcionário' ?> 
            </td>
            <td>
                <?= $v['cpf'] ?> 
            </td>
            <td>
                <form method="POST">
                    <?=
                    formErp::hidden([
                        'id_pessoa' => $v['id_pessoa'],
                        'activeNav' => 2
                    ])
                    ?>
                    <button class="btn btn-info">
                        Selecionar
                    </button>
                </form>
            </td>
        </tr>
        <?php
    }
    ?>
</table>