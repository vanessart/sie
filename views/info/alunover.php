<?php
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$aluno = new aluno($id_pessoa);
$aluno->endereco();
$aluno->vidaEscolar();
if (!empty($aluno->_escola)) {
    ?>
    <table style="width: 100%">
        <tr>
            <td style="width: 80%">
                <table class="table table-bordered table-hover table-responsive table-striped">
                    <tr>
                        <td>
                            Aluno
                        </td>
                        <td>
                            <?php echo $aluno->_nome ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            RSE
                        </td>
                        <td>
                            <?php echo $aluno->_rse ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            RA
                        </td>
                        <td>
                            <?php echo $aluno->_ra . ' - ' . $aluno->_ra_dig . ' ' . $aluno->_ra_uf ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Endereço
                        </td>
                        <td>
                            <?php echo $aluno->_logradouro_gdae . ', ' . $aluno->_num_gdae ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Complemento
                        </td>
                        <td>
                            <?php echo $aluno->_complemento ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Bairro
                        </td>
                        <td>
                            <?php echo $aluno->_bairro ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Cidade
                        </td>
                        <td>
                            <?php echo $aluno->_cidade . ' - ' . $aluno->_uf ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Escola
                        </td>
                        <td>
                            <?php echo $aluno->_escola ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Classe
                        </td>
                        <td>
                            <?php echo $aluno->_nome_classe ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Frenquente
                        </td>
                        <td>
                            <?php echo $aluno->_situacao ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <?php
                if (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".jpg")) {
                    ?>
                    <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $id_pessoa ?>.jpg?ass=<?php echo uniqid() ?>" width="199.2" alt="foto"/>
                    <?php
                } else {
                    ?>
                    <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="200" height="200" alt="anonimo"/>
                    <?php
                }
                ?>
            </td>
        </tr>
    </table>
    <?php
}
?>
