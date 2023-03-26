<?php
$valida = 1;

?>
<table  class="table table-bordered table-condensed table-responsive table-striped" style="font-weight: bold">
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold">
            Validação dos Dados
        </td>
    </tr>
    <tr>
        <td>
            CPF:
            <?php
            echo $dados['cpf_resp'];

            
            if (empty(validar::Cpf($dados['cpf_resp']))) {
                echo '(CPF inválido)';
                $img = 'errado';
                $valida = 0;
            } else {
                $img = 'ok';
            }
          

            /*
            if(!$dados['cpf_resp']) {
                echo '(CPF inválido)';
                $img = 'errado';
                $valida = 0;
            } else {
                $img = 'ok';
            }*/
            ?>

        </td>
        <td style="width: 22px">
            <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>

        </td>
    </tr>
    <tr>
        <td >
            Certidão de Nascimento:
            <?php 
            if (strlen($dados['cn_matricula']) < 32) {
                echo '(Matrícula Inválida)';
                $img = 'errado';
                $valida = 0;
            } else {
                $img = 'ok';
            } 

            /*
            $cn = $dados['cn_matricula'];

            if(!validaCertidao($cn)) {
                echo '(Certidão de Nascimento Inválida)';
                $img = 'errado';
                $valida = 0;
            } else {
                $img = 'ok';
            }
            */

            ?>
        </td>
        <td style="width: 22px">
            <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
        </td>
    </tr>
    <tr>
        <td >
            Registro Anterior:
            <?php
                $vi = $model->maeFilho($dados['mae'], $dados['n_aluno'], $dados['cn_matricula'], $dados['id_vaga']);
                if (!empty($vi)) {
                    $img = 'errado';
                    if ($dados['cn_matricula'] == $vi['cn_matricula']) {
                        echo " <br />A criança " . $dados['n_aluno'] . " da " . $vi['n_inst'] . " possui o mesmo número de certidão de nascimento que você digitou.";
                    } else {
                        echo " <br />Está criança já está na lista de espera na  " . $vi['n_inst'] . " com o Status de inscricão \"".$vi['status']."\". ";
                    }

                    $valida = 0;
                } else {
                    $img = 'ok';
                }
            ?>
        </td>
        <td style="width: 22px">
            <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
        </td>
    </tr>
    <tr>
        <td >
            <!--Pelo Menos um Telefone-->
            Telefone:
            <?php
            /*$tel = $dados['tel1'] . $dados['tel2'] . $dados['tel3'];
            if ((str_replace(' ', '', $tel)) == '') {
                $img = 'errado';
                $valida = 0;
            } else {
                $img = 'ok';
            }*/
            
            $tel = $dados['tel1'] . $dados['tel2'] . $dados['tel3'];
            if(empty($tel)) {
                echo "Pelo menos um telefone deve ser cadastrado";
                $img = 'errado';
                $valid = 0;
            } else {
                $img = "ok";
                print_r($tel);
            }

            
            ?>
        </td>
        <td style="width: 22px">
            <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
        </td>
    </tr>
    <?php
    //$end = escolas::getAbrangencia('logradouro', trim($dados['logradouro']))[0];
    @$end = escolas::getAbrangencia('logradouro', trim($dados['logradouro']));
        if (@$end['id_inst'] != tool::id_inst() || IS_NULL($end)) {
        ?>
        <tr>
            <td colspan="2" style="color: red;text-align: center">
                <?php
                if (empty($end['n_inst'])) {
                    ?>
                    Este Logradouro não esta relacionado a esta escola.
                    <br />
                    Se este logradouro pertencer a sua área de abrangência, favor cadastrá-lo logo que possível.
                    <?php
                } else {
                    ?>
                    Este Logradouro Não pertence à Escola
                <?php } ?>
                <br />
                (Isso não impede o cadastro desta reserva de vaga)
            </td>
        </tr>
    <?php
    }
    if (!empty($dados['criterio1'])) {
        ?>
        <tr>
            <td >
                Critério 1
                <?php
                if (empty($dados['nis'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta Preencher o NIS)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if ($seriacao == 'Acima da Idade' || $seriacao == 'Ainda Não Nasceu') {
        $img = 'errado';
        $valida = 0;
        ?>
        <tr>
            <td >
                Aluno Fora da Idade
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio2'])) {
        ?>
        <tr>
            <td >
                Critério 2
                <?php
                if (empty($dados['estuda'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 2)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio3'])) {
        ?>
        <tr>
            <td >
                Critério 3
                <?php
                if (empty($dados['estagio'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 3)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio4'])) {
        ?>
        <tr>
            <td >
                Critério 4
                <?php
                if (empty($dados['gdea']) || empty($dados['irmao'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 4)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio5'])) {
        ?>
        <tr>
            <td >
                Critério 5
                <?php
                if (empty($dados['comp_trab'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 5)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio6'])) {
        ?>
        <tr>
            <td >
                Critério 6
                <?php
                if (empty($dados['incapacitante'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 6)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio7'])) {
        ?>
        <tr>
            <td >
                Critério 7
                <?php
                if (empty($dados['obito'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 7)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio8'])) {
        ?>
        <tr>
            <td >
                Critério 8
                <?php
                if (empty($dados['violacao'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 8)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    if (!empty($dados['criterio9'])) {
        ?>
        <tr>
            <td >
                Critério 9
                <?php
                if (empty($dados['deficiencia'])) {
                    $img = 'errado';
                    $valida = 0;
                    echo ' (Falta ticar a documentação do critério 9)';
                } else {
                    $img = 'ok';
                }
                ?>
            </td>
            <td style="width: 22px">
                <img src="<?php echo HOME_URI ?>/views/_images/<?php echo $img ?>.jpg" style="width: 20px"/>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
