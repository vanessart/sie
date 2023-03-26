<?php
$id_pessoa = tool::id_pessoa(@$_POST['id_pessoa']);
$proj = sql::get(['giz_prof', 'giz_categoria', 'giz_modalidade'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');

$giz = sql::get('giz', '*', NULL, 'fetch');
?>
<div>
    <div class="fieldBody" >

        <div class="fieldTop">
            Giz de Ouro <?php echo date("Y") ?>
        </div>
        <br /><br />
        <?php
        if (empty($proj)) {
            foreach (sql::get('giz_categoria') as $v) {
                ?>
        <div class="fieldBorder2 alert-<?php echo $alert ?>" style="width: 60%">
                    <table style="width: 100%">
                        <td>
                            <div style="font-weight: bold; font-size: 20px">
                                <?php echo $v['n_cate'] ?>
                            </div>
                            <br />
                           
                            <?php //echo $v['descr']  ?>
                        </td>
                        <td style="width: 20%; text-align: center">
                            <form action="<?php echo HOME_URI ?>/giz/inscricao" method="POST">
                                <input type="hidden" name="id_cate" value="<?php echo $v['id_cate'] ?>" />
                                <input class="btn btn-primary" type="submit" value="Inscrição" />
                            </form>
                        </td>
                    </table>

                </div>
                <br /><br />
                <?php
            }
        } else {
            if ($giz['fase'] == 1 || !empty($proj['id_cate'])) {
                if (!empty($proj['id_cate'])) {
                    if (@$proj['gestor'] == 1) {
                        $alert = "info";
                        $situa = "O seu projeto está esperando a avaliação da Equipe de Gestão da sua escola";
                    } elseif (@$proj['gestor'] == 2) {
                        $alert = "success";
                        $situa = "O seu projeto foi aprovado pela Equipe de Gestão da sua escola e você está participando do Prêmio Giz de Ouro ". date("Y");
                    } else {
                        $alert = "info";
                        $situa = "Você ainda não enviou o Projeto à Equipe de Gestão da sua escola";
                    }
                    $texto = "Entrar";
                } else {
                    $alert = "warning";
                    $situa = "Você não está inscrito nesta Categoria";
                    $texto = "Inscrever-se";
                }
                ?>
                <div class="fieldBorder2 alert-<?php echo $alert ?>" style="width: 60%">
                    <table style="width: 100%">
                        <td>
                            <div style="font-weight: bold; font-size:  20px">
                                Categoria:   <?php echo $proj['n_cate'] ?>
                            </div>
                            <br />
                            <div style="font-weight: bold; font-size:  18px">
                                Modalidade: 
                                <?php echo $proj['n_mod'] ?>
                            </div>
                            <br />
                            <div style="font-weight: bold">
                                Situação: <?php echo $situa ?>
                            </div>
                        </td>
                        <td style="width: 20%; text-align: center">
                            <form action="<?php echo HOME_URI ?>/giz/inscricao" method="POST">
                                <input type="hidden" name="id_cate" value="<?php echo $proj['id_cate'] ?>" />
                                <input class="btn btn-primary" type="submit" value="<?php echo $texto ?>" />
                            </form>
                        </td>
                    </table>

                </div>
                <br /><br />
                <?php
            }
        }
        ?>

    </div>
</div>
