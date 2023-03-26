<style>
    .descricao{
        display: none;
    }

    .item:hover .descricao{
        display: block;
    }
</style>
<?php
$disable = NULL;
$color = 'black';
javaScript::cpf();
javaScript::somenteNumero();
javaScript::dataMascara();
if (!empty($_POST['pessoaGedae'])) {
    $ins = $model->pessoaGedae(@$_POST['ra'], @$_POST['ra_dig'], @$_POST['ra_uf']);
    @$alunoOld = (array) @$aluno;

    if (!empty($ins)) {

        $color = 'red';
        if (empty($ins['id_pessoa'])) {
            @$aluno->_nome = $ins['n_pessoa'];
            @$aluno->_ra = $ins['ra'];
            @$aluno->_ra_dig = $ins['ra_dig'];
            @$aluno->_ra_uf = $ins['ra_uf'];
            @$aluno->_sexo = $ins['sexo'];
            @$aluno->_corPele = $ins['cor_pele'];
            @$aluno->_nasc = $ins['dt_nasc'];
            @$aluno->_rg = @$ins['rg'];
            @$aluno->_rg_dig = @$ins['rg_dig'];
            @$aluno->_rg_uf = @$ins['rg_uf'];
            @$aluno->_dt_rg = @$ins['dt_rg'];
            @$aluno->_certidao = $ins['certidao'];
            @$aluno->_nascionalidade = $ins['nascionalidade'];
            @$aluno->_nascUf = $ins['uf_nasc'];
            @$aluno->_nascCidade = $ins['cidade_nasc'];
            @$aluno->_mae = $ins['mae'];
            @$aluno->_pai = $ins['pai'];
            @$aluno->_tel2 = $ins['tel2'];
            @$aluno->_tel3 = $ins['tel3'];
            @$aluno->_novacert_cartorio = $ins['novacert_cartorio'];
            @$aluno->_novacert_acervo = $ins['novacert_acervo'];
            @$aluno->_novacert_regcivil = $ins['novacert_regcivil'];
            @$aluno->_novacert_ano = $ins['novacert_ano'];
            @$aluno->_novacert_tipolivro = $ins['novacert_tipolivro'];
            @$aluno->_novacert_numlivro = $ins['novacert_numlivro'];
            @$aluno->_novacert_folha = $ins['novacert_folha'];
            @$aluno->_novacert_termo = $ins['novacert_termo'];
            @$aluno->_novacert_controle = $ins['novacert_controle'];
        } else {
            @$aluno = (object) $ins;
        }
    }
}
?>
<style>
    input[type=text]{
        width: 100%;
        color: <?php echo $color ?> !important;;
    }

</style>
<br /><br /><br />
<div>
    <?php
    if ($color == 'red' && !empty(@$aluno->_nome)) {
        ?>
        <form style="text-align: center" method="POST">
            <?php
            if (!empty($alunoOld['_nome'])) {
                $nomeOld = explode(' ', $alunoOld['_nome'])[0];
                $nome = explode(' ', $aluno->_nome)[0];
                $maeOld = explode(' ', $alunoOld['_mae'])[0];
                $mae = explode(' ', $aluno->_mae)[0];
                /**
                  if ($nome != $nomeOld) {
                  ?>
                  <div class="alert alert-danger" style="font-weight: bold; font-size: 22px">
                  Estranho... <img src="<?php echo HOME_URI ?>/views/_images/pen.png"/>
                  O nome não era "<?php echo $alunoOld['_nome'] ?>"?
                  <br /><br />
                  Se você sabe o que está fazendo escreva no campo abaixo exatamente com as mesmas palavras:
                  <br /><br />
                  <div style="text-decoration: underline">
                  Eu sei o que estou fazendo
                  </div>
                  <br /><br />
                  <input type="hidden" name="veri" value="1" />
                  <input autocomplete="off" onclick="onpaste = function () {
                  return false;
                  }"  required style="width: 100%" type="text" name="verifica" value="" />
                  <br /><br />
                  <div style="text-align: left">
                  Obs: Na dúvida consulte o prontuário impresso do aluno
                  </div>
                  <br /><br />
                  </div>
                  <?php
                  } elseif ($mae != $maeOld) {
                  ?>
                  <div class="alert alert-danger" style="font-weight: bold; font-size: 22px">
                  Estranho... O nome da mãe não era "<?php echo $alunoOld['_mae'] ?>"?
                  <br /><br />
                  Se você sabe o que está fazendo escreva no campo abaixo exatamente com as mesmas palavras:
                  <br /><br />
                  <div style="text-decoration: underline">
                  Eu sei o que estou fazendo
                  </div>
                  <br /><br />
                  <input type="hidden" name="veri" value="1" />
                  <input autocomplete="off" onclick="onpaste = function () {
                  return false;
                  }"  required style="width: 100%" type="text" name="verifica" value="" />
                  <br /><br />
                  <div style="text-align: left">
                  Obs: Na dúvida consulte o prontuário impresso do aluno
                  </div>
                  <br /><br />
                  </div>
                  <?php
                  } elseif ($alunoOld['_nasc'] != $aluno->_nasc) {
                  ?>
                  <div class="alert alert-danger" style="font-weight: bold; font-size: 22px">
                  Estranho... A data de nascimento não era "<?php echo data::converteBr($alunoOld['_nasc']) ?>"?
                  <br /><br />
                  Se você sabe o que está fazendo escreva no campo abaixo exatamente com as mesmas palavras:
                  <br /><br />
                  <div style="text-decoration: underline">
                  Eu sei o que estou fazendo
                  </div>
                  <br /><br />
                  <input type="hidden" name="veri" value="1" />
                  <input autocomplete="off" onclick="onpaste = function () {
                  return false;
                  }" required style="width: 100%" type="text" name="verifica" value="" />
                  <br /><br />
                  <div style="text-align: left">
                  Obs: Na dúvida consulte o prontuário impresso do aluno
                  </div>
                  <br /><br />
                  </div>
                  <?php
                  }
                 * 
                 */
            }
            ?>
            <input type="hidden" name="ra" value="<?php echo @$_POST['ra'] ?>" />
            <input type="hidden" name="ra_dig" value="<?php echo @$_POST['ra_dig'] ?>" />
            <input type="hidden" name="ra_uf" value="<?php echo @$_POST['ra_uf'] ?>" />
            <input type="hidden" name="id_pessoa" value="<?php echo @$aluno->_rse ?>" />
            <input type="hidden" name="activeNav" value="1" />
            <?php
            if (!empty($aluno->_ra) && !empty($aluno->_ra) && empty($id_pessoa)) {
                $sql = "select id_pessoa from pessoa "
                        . " where ra = " . $aluno->_ra
                        . " and ra_dig = " . $aluno->_ra_dig;
                $query = $model->db->query($sql);
                if ($query) {
                    $idPessoaVerif = $query->fetch()['id_pessoa'];
                }
            }
            if (empty($idPessoaVerif)) {
                ?>
                <input class="btn btn-primary" type="submit" value="Finalizar Importação" name="sincronizarPessoa" />
                <?php
            } else {
                ?>
                <div class="alert alert-danger" style="font-weight: bold; font-size: 22px">
                    <form method="POST">
                    </form>
                    Este Aluno já está cadastrado em nosso sistema com o RSE:
                    <input class="btn btn-default" type="submit" value="<?php echo $idPessoaVerif ?>" />
                    <input type="hidden" name="id_pessoa" value="<?php echo $idPessoaVerif ?>" />

                </div>
                <?php
                unset($aluno);
            }
            ?>
        </form>
        <br /><br />
        <?php
    }
    ?>

</div>
<form method="POST">
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-bordered table-hover table-striped" style="font-size: 18px">
                <tr>
                    <th colspan="2" style="background-color: lightgrey">
                        <div class="row">
                            <div class="col-sm-6">
                                Identificação do Aluno
                            </div>

                            <div class="col-sm-6">
                                <?php
                                if ($color != 'red' && false) {
                                    ?>
                                    <input type="button" onclick=" $('#myModal').modal('show');
                                                $('.form-class').val('')" value="Importar do SED" />
                                           <?php
                                       }
                                       ?>
                            </div>
                        </div>

                    </th>
                </tr>
                <tr>
                    <td>
                        RSE:
                    </td>
                    <td>
                        <?php echo @$aluno->_rse ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome:
                    </td>
                    <td>
                        <?php echo @$aluno->_nome ?>
                        <!--
                        <input <?php echo $disable ?> type="text" name="1[n_pessoa]" value="<?php echo @$aluno->_nome ?>" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        Sexo:
                    </td>
                    <td>
                        <?php echo @$aluno->_sexo == 'F' ? 'Feminino' : 'Masculino' ?>
                        <!--
                        <select name="1[sexo]">
                            <option <?php echo @$aluno->_sexo == 'F' ? ' selected' : '' ?> value="F">Feminino</option>
                            <option <?php echo @$aluno->_sexo == 'M' ? ' selected' : '' ?> value="M">Masculino</option>
                        </select>
                        -->
                    </td>
                </tr>

                <tr>
                    <td>
                        Nome Social:
                    </td>
                    <td>
                        <input type="text" name="1[n_social]" value="<?php echo @$aluno->_nomeSocial ?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        RA:
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-6">
                                <?php echo @$aluno->_ra ?>
                                <!--
                               <input type="text" name="1[ra]" value="<?php echo @$aluno->_ra ?>" />
                                -->
                            </div>
                            <div class="col-sm-3">
                                <?php echo @$aluno->_ra_dig ?>
                                <!--
                                                        <input type="text" name="1[ra_dig]" value="<?php echo @$aluno->_ra_dig ?>" />
                                -->
                            </div>
                            <div class="col-sm-3">
                                <?php echo @$aluno->_ra_uf ?>
                                <!--
                                <input type="text" name="1[ra_uf]" value="<?php echo @$aluno->_ra_uf ?>" />
                                -->
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cor/Raça:
                    </td>
                    <td>
                        <?php echo @$aluno->_corPele ?>
                        <!--
                       <input <?php echo $disable ?> type="text" name="1[cor_pele]" value="<?php echo @$aluno->_corPele ?>" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        D.Nasc:
                    </td>
                    <td>
                        <?php echo data::converteBr(@$aluno->_nasc) ?>
                        <!--
                       <input <?php echo $disable ?> type="text" name="1[dt_nasc]" value="<?php echo data::converteBr(@$aluno->_nasc) ?>" <?php echo formulario::dataFormat() ?> />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        Local Nasc.:
                    </td>
                    <td>
                        <?php echo @$aluno->_nascCidade ?> - <?php echo @$aluno->_nascUf ?>
                        <!--
                        <input style="width: 75%" <?php echo $disable ?> type="text" name="1[cidade_nasc]" value="<?php echo @$aluno->_nascCidade ?>" placeholder="Cidade"/>
                        - 
                        <input style="width: 20%" <?php echo $disable ?> type="text" name="1[uf_nasc]" value="<?php echo @$aluno->_nascUf ?>" placeholder="Estado" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        Nacionalidade:
                    </td>
                    <td>
                        <?php echo empty(@$aluno->_nascionalidade) ? 'Brasileira' : @$aluno->_nascionalidade ?>
                        <!--
                        <input <?php echo $disable ?> type="text" name="1[nacionalidade]" value="<?php echo empty(@$aluno->_nascionalidade) ? 'Brasileira' : @$aluno->_nascionalidade ?>" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        Certidão de Nasc Antiga:
                    </td>
                    <td>
                        <?php echo @$aluno->_certidao ?>
                        <!--
                        <input <?php echo $disable ?> type="text" name="1[certidao]" value="<?php echo @$aluno->_certidao ?>" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        Certidão de Nasc Nova:
                    </td>
                    <td>


                        <?php echo @$aluno->_novacert_cartorio ?> - 
                        <?php echo @$aluno->_novacert_acervo ?> - 
                        <?php echo @$aluno->_novacert_regcivil ?> - 
                        <?php echo @$aluno->_novacert_ano ?> - 
                        <?php echo @$aluno->_novacert_tipolivro ?> - 
                        <?php echo @$aluno->_novacert_numlivro ?> - 
                        <?php echo @$aluno->_novacert_folha ?> - 
                        <?php echo @$aluno->_novacert_termo ?> - 
                        <?php echo @$aluno->_novacert_controle ?>

                        <!--
                                                <input style="width: 30%" <?php echo $disable ?> type="text" name="1[novacert_cartorio]" value="<?php echo @$aluno->_novacert_cartorio ?>" placeholder="Cartorio" />
                                                <input style="width: 13%" <?php echo $disable ?> type="text" name="1[novacert_acervo]" value="<?php echo @$aluno->_novacert_acervo ?>" placeholder="Acervo" />
                                                <input style="width: 13%" <?php echo $disable ?> type="text" name="1[novacert_regcivil]" value="<?php echo @$aluno->_novacert_regcivil ?>" placeholder="R. Civil" />
                                                <input style="width: 28%" <?php echo $disable ?> type="text" name="1[novacert_ano]" value="<?php echo @$aluno->_novacert_ano ?>" placeholder="Ano" />
                                                <input style="width: 10%" <?php echo $disable ?> type="text" name="1[novacert_tipolivro]" value="<?php echo @$aluno->_novacert_tipolivro ?>" placeholder="Tipo" />
                                                <br /><br />
                                                <input style="width: 30%" <?php echo $disable ?> type="text" name="1[novacert_numlivro]" value="<?php echo @$aluno->_novacert_numlivro ?>" placeholder="Nº Livro" />
                                                <input style="width: 15%" <?php echo $disable ?> type="text" name="1[novacert_folha]" value="<?php echo @$aluno->_novacert_folha ?>" placeholder="Fl. Livro" />
                                                <input style="width: 40%" <?php echo $disable ?> type="text" name="1[novacert_termo]" value="<?php echo @$aluno->_novacert_termo ?>" placeholder="Termo" />
                                                <input style="width: 8%" <?php echo $disable ?> type="text" name="1[novacert_controle]" value="<?php echo @$aluno->_novacert_controle ?>" placeholder="Dígito" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        RG:
                    </td>
                    <td>
                        <?php echo @$aluno->_rg ?> - 
                        <?php echo @$aluno->_rgDig ?> - 
                        <?php echo @$aluno->_rgOe ?> - 
                        <?php echo @$aluno->_rgUf ?> - 
                        <?php echo data::converteBr(@$aluno->_rgDt) ?>
                        <!--
                                                <input placeholder="RG" style="width: 40%" <?php echo $disable ?> type="text" name="1[rg]" value="<?php echo @$aluno->_rg ?> " />
                                                <input placeholder="Dig." style="width: 10%" <?php echo $disable ?> type="text" name="1[rg_dig]" value="<?php echo @$aluno->_rgDig ?>" />
                                                <input placeholder="O.E." style="width: 12%" <?php echo $disable ?> type="text" name="1[rg_oe]" value="<?php echo @$aluno->_rgOe ?>" />
                                                <input placeholder="UF" style="width: 12%" <?php echo $disable ?> type="text" name="1[rg_uf]" value="<?php echo @$aluno->_rgUf ?>" />
                                                <input placeholder="Data" style="width: 20%" <?php echo $disable ?> type="text" name="1[dt_rg]" value="<?php echo data::converteBr(@$aluno->_rgDt) ?>" <?php echo formulario::dataFormat() ?>/>
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        E-mail Pessoal:
                    </td>
                    <td>
                        <input type="text" name="1[email]" value="<?php echo @$aluno->_email ?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        E-mail Google:
                    </td>
                    <td>
                        <?php
                        $equipe = $model->equipedttie($_SESSION['userdata']['id_pessoa']);
                        if ($equipe) {
                            ?>
                            <input type="text" name="1[emailgoogle]" value="<?php echo @$aluno->_emailGoogle ?>" />
                            <?php
                        } else {
                            echo @$aluno->_emailGoogle;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        CPF:
                    </td>
                    <td>
                        <?php echo @$aluno->_cpf ?>
                        <!--
                        <input placeholder="Somente números" type="text" name="1[cpf]" value="<?php echo @$aluno->_cpf ?>" onblur="validarCPF(this.value);" maxlength="14" onkeypress="return SomenteNumero(event)" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td>
                        Cartão SUS:
                    </td>
                    <td>
                        <input type="text" name="1[sus]" value="<?php echo @$aluno->_sus ?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        NIS:
                    </td>
                    <td>
                        <?php echo @$aluno->_nis ?>
                        <!--
                        <input type="text" name="1[nis]" value="<?php echo @$aluno->_nis ?>" />
                        -->
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        APD - Avaliação Parecer Descritivo(Deficiência Cognitiva)
                        <select name="1[deficiencia]" onchange="alert('Atenção! Aluno com APD bloqueia o lançamento de nota')">
                            <option>Não</option>
                            <option <?php echo (@$aluno->_deficiencia == 'Sim' || @$aluno->_deficiencia == '1') ? 'selected' : '' ?>>Sim</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <div class="col-sm-6">
            <table class="table table-bordered table-hover table-striped" style="font-size: 18px">
                <tr>
                    <th colspan="2" style="background-color: lightgrey">
                        Filiação
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="alert alert-danger">
                            Os dados dos responsáveis deverão ser incluídos no “Gestão Educacional (Novo)
                        </div>
                    </td>
                </tr>
                <?php
                if (false) {
                    ?>
                    <tr>
                        <td>
                            Mãe:
                        </td>
                        <td>
                            <input id="mae" style="border: none" disabled type="text" name="1[mae]" value="<?php echo @$aluno->_mae ?>" />
                            <!--
                            <input id="mae" <?php echo $disable ?> type="text" name="1[mae]" value="<?php echo @$aluno->_mae ?>" />
                            -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            CPF da Mãe:
                        </td>
                        <td>
                            <input type="text" name="1[cpf_mae]" value="<?php echo @$aluno->_maeCpf ?>" id="cpf_mae" onblur="validarCPF(this.value);" maxlength="14"  onkeypress="return SomenteNumero(event)"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        Pai: 
                                    </td>
                                    <td>
                                        <div class="item">
                                            <label >
                                                <input type="checkbox"  onclick="paii()" />
                                                <a>SPE</a>
                                                <div style="position: absolute; color: red; margin-top: -50px; margin-left: -95px; z-index: 9999; background-color: yellow" class="descricao">Sem Paternidade Estabelecida</div>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td>
                            <input style="border: none" readonly required id="pai" <?php echo $disable ?> type="text" name="1[pai]" value="<?php echo @$aluno->_pai ?>" />
                            <input id="paiold" type="hidden" name="" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            CPF do Pai:
                        </td>

                        <td>
                            <input type="text" name="1[cpf_pai]" value="<?php echo @$aluno->_paiCpf ?>" id="cpf_pai" onblur="validarCPF(this.value);" maxlength="14"  onkeypress="return SomenteNumero(event)"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Responsável legal:
                        </td>
                        <td>
                            <span class="input-group-addon" style="text-align: left; background-color: white; width: 10%">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php
                                if (!empty(@$aluno->_responsavel)) {
                                    if (@$aluno->_mae == @$aluno->_responsavel) {
                                        $cmae = 'checked';
                                    } elseif (@$aluno->_pai == @$aluno->_responsavel) {
                                        $cpai = 'checked';
                                    } else {
                                        $cresp = 'checked';
                                    }
                                }
                                ?>
                                <label>
                                    <input <?php echo @$cmae ?> onclick="document.getElementById('responsavel').value = document.getElementById('mae').value;
                                                document.getElementById('cpf_respons').value = document.getElementById('cpf_mae').value;" type="radio" name="resp" value="ON" />
                                    Mãe 
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input <?php echo @$cpai ?> onclick="document.getElementById('responsavel').value = document.getElementById('pai').value;document.getElementById('cpf_respons').value = document.getElementById('cpf_pai').value;" type="radio" name="resp" value="ON" />
                                    Pai 
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input <?php echo @$cresp ?> onclick="document.getElementById('responsavel').value = '';
                                                document.getElementById('cpf_respons').value = ''" type="radio" name="resp" value="ON" />
                                    Outro 
                                </label>

                            </span>  
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Nome do Responsável:
                        </td>
                        <td>
                            <input type="text" name="1[responsavel]" value="<?php echo str_replace("'", '', @$aluno->_responsavel) ?>" id="responsavel" <?php echo @$requiredCad ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            CPF do Responsável:
                        </td>
                        <td>
                            <input type="text" name="1[cpf_respons]" value="<?php echo @$aluno->_responsCpf ?>" id="cpf_respons"  onblur="validarCPF(this.value);" maxlength="14"  onkeypress="return SomenteNumero(event)" <?php echo @$requiredCad ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            E-mail do Responsável:
                        </td>
                        <td>
                            <input type="text" name="1[email_respons]" value="<?php echo @$aluno->_responsEmail ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Telefone Residencial
                        </td>
                        <td>
                            <?php echo @$aluno->_tel3 ?>
                            <!--
                            <input type="text" name="1[tel3]" value="<?php echo @$aluno->_tel3 ?>" />
                            -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Telefone Recado
                        </td>
                        <td>
                            <?php echo @$aluno->_tel2 ?>
                            <!--
                            <input type="text" name="1[tel2]" value="<?php echo @$aluno->_tel2 ?>" />
                            -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Telefone Celular
                        </td>
                        <td>
                            <?php echo @$aluno->_tel1 ?>
                            <!--
                            <input type="text" name="1[tel1]" value="<?php echo @$aluno->_tel1 ?>" />
                            -->
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 offset4" style="text-align: center">
            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />

            <?php
            echo formulario::hidden(['1[id_pessoa]' => $id_pessoa, '1[ativo]' => 1]);
            echo DB::hiddenKey('pessoa', 'replace');
            ?>            
            <input type="hidden" name="activeNav" value="1" />
            <?php
            if ($color != 'red' && !empty($id_pessoa)) {
                ?>
                <input class="btn btn-success" type="submit" value="Salvar" id="btn" />
                <?php
            }
            ?>
        </div>
    </div>
</form>
<br /><br /> 
<?php
tool::modalInicio('width: 95%', 1);
if (!empty($id_pessoa)) {
    $readonly = 'readonly';
} else {
    $readonly = NULL;
}
?>
<form style="text-align: center" method="POST">
    <div class="row">
        <div class="col-sm-3">
            <?php echo formulario::input('ra', 'RA', NULL, @$aluno->_ra, "required $readonly") ?>
        </div>
        <div class="col-sm-3">
            <?php echo formulario::input('ra_dig', 'Digito (RA)', NULL, @$aluno->_ra_dig, $readonly) ?>
        </div>
        <div class="col-sm-3">
            <?php echo formulario::input('ra_uf', 'UF (RA)', NULL, empty(@$aluno->_ra_uf) || str_replace(' ', '', @$aluno->_ra_uf) == '' ? 'SP' : @$aluno->_ra_uf, "required $readonly") ?>
        </div>
        <div class="col-sm-3">
            <input type="hidden" name="id_pessoa" value="<?php echo @$aluno->_rse ?>" />
            <input type="hidden" name="activeNav" value="1" />
            <input class="btn btn-info" type="submit" value="Importar do SED" name="pessoaGedae" />


        </div>
    </div>
</form>
<br /><br />
<script>
    function paii() {
        if (document.getElementById('pai').value == 'SPE') {
            document.getElementById('pai').checked = true;
            document.getElementById('pai').value = document.getElementById('paiold').value;
            document.getElementById('pai').readOnly = false;
        } else {
            document.getElementById('pai').checked = false;
            document.getElementById('paiold').value = document.getElementById('pai').value;
            document.getElementById('pai').value = 'SPE';
            document.getElementById('pai').readOnly = true;
        }
    }
</script>
<?php
tool::modalFim();
?>