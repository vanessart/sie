<?php
    $id_inst = $_SESSION['userdata']['id_inst'];

    $sql = "SELECT latitude, longitude FROM ge_escolas WHERE fk_id_inst = $id_inst";
    $query = autenticador::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $key => $value) {
        $lat = $value['latitude'];
        $long = $value['longitude'];
    }
?>
<input type="hidden" id="lat" value="<?php echo $lat; ?>  ">
<input type="hidden" id="long" value="<?php echo $long; ?>  ">


<div class="field">
    <form method="POST">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Dados do(a) Aluno(a)
            </div>
            <div class="panel panel-body">
            <div class="row">
                    <div class="col-md-10">
                        <?php echo formulario::input('1[mae]', 'Nome da Mãe <span style="color: red">*</span>', NULL, @$dados['mae'], ' required id="mae" ' . @$readonly) ?>
                    </div>
                    <div class="col-md-2">
                        <label>
                            <input <?php echo @$readonly ?> id="chk" onclick="maResp()" type="radio" value="ON" name="exampleRadios" />
                            Responsável Legal
                        </label>
                    </div>
                </div>
                 <br>
                <div class="row ">
                    <div class="col-md-10">
                        <?php echo formulario::input('1[pai]', 'Nome do Pai ', NULL, @$dados['pai'], ' id="pai" ' . @$readonly) ?>
                    </div>
                    <div class="col-md-2">
                        <label>
                            <input <?php echo @$readonly ?> id="chk2" onclick="maResp2()" type="radio" value="ON" name="exampleRadios" />
                            Responsável Legal
                        </label>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-8">
                        <?php echo formulario::input('1[n_aluno]', 'Nome do(a) Aluno(a)<span style="color: red">*</span>', NULL, @$dados['n_aluno'], ' required ' . @$readonly) ?>
                    </div>
                    <div class="col-md-3">

                    <?php
                    if(@$dados['dt_aluno'] != null) {
                        @$dados['dt_aluno'] = date("d/m/Y", strtotime(@$dados['dt_aluno']));
                    }
                    ?>
                        <?php echo formulario::input('1[dt_aluno]', 'Data Nasc. (Aluno(a))<span style="color: red">*</span>', NULL, @$dados['dt_aluno'], @$readonly . 'maxlength="10" required id=data1 readonly' . formulario::dataConf(1)) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                        <?php echo formulario::select('1[sx_aluno]', ['Feminino' => 'Feminino', 'Masculino' => 'Masculino'], 'Sexo', @$dados['sx_aluno'], NULL, NULL, @$readonly . ' required ') ?>
                    </div>
                    <div class="col-md-5">
                        <?php echo formulario::input('1[cn_matricula]', 'Certidão de Nasc.<span style="color: red">*</span>', NULL, @$dados['cn_matricula'], @$readonly . ' required maxlenght="32"') ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-3">
                        <?php echo formulario::input('1[rg_aluno]', 'RG' , NULL, @$dados['rg_aluno'], @$readonly . 'id=rg' ) ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo formulario::input('1[oe_rg_aluno]', 'Orgão Emissor', NULL, @$dados['oe_rg_aluno'], @$readonly . 'id="oe_rg_aluno" onkeyup="somenteLetras(this);"') ?>
                    </div>
                    <div class="col-md-2">
                        <?php formulario::selectDB('estados', '1[uf_rg_aluno]', 'RG-UF:', @$dados['uf_rg_aluno'], @$readonly, NULL, NULL, ['sigla' => 'sigla']) ?>
                    </div>
                    <div class="col-md-3">

                    <?php
                    if(@$dados['dt_rg_aluno'] != null) {
                        @$dados['dt_rg_aluno'] = date("d/m/Y", strtotime(@$dados['dt_rg_aluno']));
                    }
                    ?>

                        <?php echo formulario::input('1[dt_rg_aluno]', 'Data de Emissão RG:', NULL, @$dados['dt_rg_aluno'], @$readonly . 'maxlength="10" id=data2 readonly' . formulario::dataConf(2)) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Dados do Responsável
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-7">
                        <?php echo formulario::input('1[responsavel]', 'Nome do Responsável Legal <span style="color: red">*</span>', NULL, @$dados['responsavel'], ' required id="mae1" ' . @$readonly) ?>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4  bg-success">
                        <label>
                            <input <?php echo @$readonly ?> id="mares" required onclick="maResp()"  <?php echo ((@$dados['parente'] == 'MÃE') ? 'checked' : '') ?> type="radio" name="1[parente]" value="MÃE" />
                            Mãe
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input <?php echo @$readonly ?> id="paires" required onclick="maResp2()"  <?php echo (@$dados['parente'] == 'PAI' ? 'checked' : '') ?> type="radio" name="1[parente]" value="PAI" />
                            Pai
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input <?php echo @$readonly ?> id="resp" required onclick="outroresp()" <?php echo (@$dados['parente'] == 'Responsável Legal' ? 'checked' : '') ?> type="radio" name="1[parente]" value="Responsável Legal" />
                            Responsável Legal
                        </label>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-6 col-md-3 col-lg-3 ">

                    <?php
                    if(@$dados['dt_resp'] != null) {
                        @$dados['dt_resp'] = date("d/m/Y", strtotime(@$dados['dt_resp']));
                    }
                    ?>

                        <?php echo formulario::input('1[dt_resp]', 'Data Nasc .Resp.<span style="color: red">*</span>', NULL, @$dados['dt_resp'], @$readonly . 'maxlength="10" required id=data3 readonly' . formulario::dataConf(3)) ?>
                    </div>
                    <div class="col-sm-1 col-md-1 col-lg-1 col-0"></div>
                    <div class="col-sm-7 col-md-4 col-lg-4 bg-success">
                        Sexo <span style="color: red">*</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input  <?php echo @$readonly ?> id="pai" required onclick="no1()"  <?php echo (@$dados['sx_resp'] == 'Feminino' ? 'checked' : '') ?> type="radio" name="1[sx_resp]" value="Feminino" />
                            Feminino
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input  <?php echo @$readonly ?> id="resp" required onclick="no5()" <?php echo (@$dados['sx_resp'] == 'Masculino' ? 'checked' : '') ?> type="radio" name="1[sx_resp]" value="Masculino" />
                            Masculino
                        </label>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3">

                        <?php

                        if(@$dados['dt_vagas']) {
                            //$dateView = dateView($dados['dt_vagas']);
                            $dateView = date("d/m/Y", strtotime(@$dados['dt_vagas']));
                        } else {
                            $dateView = NULL;
                        }


                        ?>
                        <?php @$dados['dt_vagas'] = date("d/m/Y", strtotime(@$dados['dt_vagas'])); ?>
                        <?php echo formulario::input('1[dt_vagas]', 'Data de Inscrição  <span style="color: red">*</span>', NULL, $dateView, @$readonly . 'maxlength="10" required id=data4 readonly' . formulario::dataConf(4)) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 ">
                        <?php formulario::input('1[cpf_resp]', 'CPF (somente números) <span style="color: red">*</span>', NULL, @$dados['cpf_resp'], @$readonly . ' required id="cpf"') ?>
                    </div>
                    <div class="col-6 col-sm-12 col-md-12 col-lg-7 bg-success">
                        Estado Civil <span style="color: red">*</span>
                        <?php
                        foreach (pessoa::civil() as $v) {
                            ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label>
                                <input <?php echo @$readonly ?> required <?php echo (@$dados['civil'] == $v ? 'checked' : '') ?> type="radio" name="1[civil]" value="<?php echo $v ?>" />
                                <?php echo $v ?>
                            </label>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <?php echo formulario::selectNum('1[filhos]', 15, 'Número de Filhos', @$dados['filhos'], NULL, NULL, @$readonly . ' required ') ?>
                    </div>
                    <div class="col-6 col-sm-6 col-md-5 col-lg-4  bg-success">
                        Está empregado(a)? <span style="color: red">*</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input  <?php echo @$readonly ?>  onclick="ats()" required <?php echo (@$dados['trabalha'] == '1' ? 'checked' : '') ?> type="radio" name="1[trabalha]" value="1" />
                            Sim
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input  <?php echo @$readonly ?>  onclick="atn()" required <?php echo (@$dados['trabalha'] == '0' ? 'checked' : '') ?> type="radio" name="1[trabalha]" value="0" />
                            Não
                        </label>
                    </div>
                    <div class="col-md-5" id="atv" style="display: <?php echo empty($dados['atividade']) ? 'none' : '' ?>">
                        <div class="fieldrow3">
                            <?php formulario::input('1[atividade]', ' Atividade Laborativa: <span style="color: red">***</span>', NULL, @$dados['atividade'], @$readonly) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Endereço
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <?php formulario::input('1[cep]', 'CEP: <span style="color: red">*</span>', NULL, empty(@$dados['cep']) ? '' : str_pad(str_replace(' ', '', str_replace('-', '', @$dados['cep'])), 8, "0", STR_PAD_LEFT), ' required id="cep" maxlength="8" ') ?>
                        <!-- <input type="hidden" name="1[latitude]" id="latitude_pessoa" value="" >
                        <input type="hidden" name="1[longitude]" id="longitude_pessoa" value=""> -->
                    </div>
                    <div class="col-md-4">
                        <?php formulario::input('1[logradouro]', 'Logradouro: ', NULL, @$dados['logradouro'], ' id="rua"  required readonly') ?>
                    </div>
                    <div class="col-md-2">
                    <?php formulario::input('1[num]', 'Nº: <span style="color: red">*</span>', NULL, @$dados['num'], ' required maxlength="8"') ?>
                    </div>
                    <div class="col-md-4">
                        <?php formulario::input('1[compl]', 'Complemento: ', NULL, @$dados['compl']) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-3">
                        <?php formulario::input('1[bairro]', 'Bairro: ', NULL, @$dados['bairro'], ' id="bairro" required readonly') ?>
                        <input type="hidden" name="1[latitude]" id="latitude_pessoa" value="">
                        <input type="hidden" name="1[longitude]" id="longitude_pessoa" value="">
                    </div>
                    <div class="col-md-3">
                        <?php formulario::input('1[cidade]', 'Cidade: ', NULL, @$dados['cidade'], ' id="cidade" required readonly') ?>
                    </div>
                    <div class="col-md-1">
                        <?php formulario::input('1[uf]', 'UF: ', NULL, @$dados['uf'], ' id="uf" required readonly') ?>
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" id="ibge" name="" value="" />

                    <?php
                    if(@$dados['dt_barueri'] != null) {
                        @$dados['dt_barueri'] = date("d/m/Y", strtotime(@$dados['dt_barueri']));
                    }
                    ?>

                    <?php echo formulario::input('1[dt_barueri]', 'Mora em Barueri desde', NULL, data::converteBr(@$dados['dt_barueri']), formulario::dataConf(8) . 'maxlength="10" id="data8" ' . @$readonly) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <p>Rota:</p>
                        <div id="map_content" class="mapa_invisible"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Telefones
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                        <?php formulario::input('1[tel1]', 'Celular : ', NULL, @$dados['tel1'], ' id="telefone" ' . @$readonly) ?>
                        <!-- <input  <?php echo @$readonly ?> type="text" name="1[tel1]" value="<?php echo @$dados['tel1'] ?>"   size="20" /> -->
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                        <?php formulario::input('1[tel2]', 'Celular : ', NULL, @$dados['tel2'],' id="telefone2"  ' . @$readonly) ?>
                        <!-- <input <?php echo @$readonly ?> type="text" name="1[tel2]" value="<?php echo @$dados['tel2'] ?>"   size="20" /> -->
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                        <?php formulario::input('1[tel3]', 'Telefone Fixo: ', NULL, @$dados['tel3'], ' id="telefone3" ' . @$readonly) ?>
                        <!-- <input <?php echo @$readonly ?> type="text" name="1[tel3]" value="<?php echo @$dados['tel3'] ?>"   size="20" /> -->
                    </div>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <table style="width: 100%">
                    <td>
                       Decreto Vulnerabilidade social (opcional)
                    </td>
                    <td>
                        <input <?php echo @$dados['criterio10'] == 1000 ? 'disabled' : '' ?>   id="de" onclick="mudaLei('decreto')" class="btn btn-<?php echo @$dados['criterio10'] == 1000 ? 'default' : 'info' ?>" type="button" value="Decreto 8.691" />
                    </td>
                    <td>
                       Lei Municipal - Mulheres vítimas de Violência (opcional)
                    </td>
                    <td>
                        <input id="le" onclick="mudaLei('lei')" class="btn btn-<?php echo @$dados['criterio10'] == 1000 ? 'info' : 'default' ?>" type="button" value="Lei 2.682" />
                    </td>
                </table>

            </div>
            <div class="panel panel-body">
                <div id="decreto" style="display: <?php echo @$dados['criterio10'] == 1000 ? 'none' : '' ?> ">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>
                                Critério 1: Recebimento da Bolsa Família, Lei Federal n° 10.836, de 9 de janeiro de 2004 (peso 1).
                            </td>
                            <td style="width: 90px">
                                <label>
                                    <input id="cc1" <?php echo @$readonly ?> onclick="document.getElementById('c1').style.display = 'none';leiAbre()" <?php echo @$dados['criterio1'] == 0 || empty(@$dados['criterio1']) ? 'checked' : '' ?> type="radio" name="1[criterio1]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c1').style.display = '';document.getElementById('le').disabled = true;" <?php echo @$dados['criterio1'] == 1 ? 'checked' : '' ?> type="radio" name="1[criterio1]" value="1" />
                                    Sim
                                </label>
                            </td>
                            <td style="width: 45%">
                                <div id="c1" style="display: <?php echo!empty($dados['criterio1']) ? '' : 'none' ?>">
                                    Cartão do NIS (Nº de Identificação Social):<span style="color: red">****</span>
                                    <br />
                                    <input <?php echo @$readonly ?> type="text" name="1[nis]" value="<?php echo @$dados['nis'] ?>" style="width: 100%" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 2: A genitora ou responsável legal que possui a guarda judicial da criança é regularmente matriculada na educação básica ou educação superior (peso 1).
                            </td>
                            <td>
                                <label>
                                    <input id="cc2" <?php echo @$readonly ?> onclick="document.getElementById('c2').style.display = 'none';leiAbre()" <?php echo @$dados['criterio2'] == 0 || empty($dados['criterio2']) ? 'checked' : '' ?> type="radio" name="1[criterio2]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c2').style.display = '';document.getElementById('le').disabled = true;" <?php echo @$dados['criterio2'] == 1 ? 'checked' : '' ?>  type="radio" name="1[criterio2]" value="1" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c2" style="display: <?php echo!empty($dados['criterio2']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['estuda'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[estuda]" value="1" />
                                        Declaração de frequência, devidamente assinada pela instituição de ensino reconhecida pelo MEC.<span style="color: red">****</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 3: A genitora ou responsável legal que possui a guarda judicial da criança, devido aos estudos na Educação Superior, frequenta estágios laborais obrigatórios (peso 1).
                            </td>
                            <td>
                                <label>
                                    <input id="cc3" <?php echo @$readonly ?> onclick="document.getElementById('c3').style.display = 'none';leiAbre()" <?php echo @$dados['criterio3'] == 0 || empty($dados['criterio3']) ? 'checked' : '' ?> type="radio" name="1[criterio3]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c3').style.display = '';document.getElementById('le').disabled = true;" <?php echo @$dados['criterio3'] == 1 ? 'checked' : '' ?>  type="radio" name="1[criterio3]" value="1" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c3" style="display: <?php echo!empty($dados['criterio3']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['estagio'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[estagio]" value="1" />
                                        Declaração de frequência, devidamente assinada pela instituição de ensino reconhecida pelo MEC e declaração da referida universidade comprovando a frequência em estágios laborais obrigatórios.
                                        <span style="color: red">****</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 4: A genitora ou responsável legal que possui a guarda judicial da criança, já possua um(a) filho(a) estudando na escola (peso 1).
                            </td>
                            <td>
                                <label>
                                    <input id="cc4" <?php echo @$readonly ?> onclick="document.getElementById('c4').style.display = 'none';leiAbre()" <?php echo @$dados['criterio4'] == 0 || empty($dados['criterio4']) ? 'checked' : '' ?> type="radio" name="1[criterio4]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c4').style.display = '';document.getElementById('le').disabled = true;" <?php echo @$dados['criterio4'] == 1 ? 'checked' : '' ?>  type="radio" name="1[criterio4]" value="1" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c4" style="display: <?php echo!empty($dados['criterio4']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['gdea'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[gdea]" value="1" />
                                        Ficha de aluno(a) no GDAE (irmão(a))
                                        <span style="color: red">****</span>
                                    </label>
                                    <br />
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['irmao'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[irmao]" value="1" />
                                        Comprovando o parentesco de irmãos
                                        <span style="color: red">****</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 5: Atividade laboral desempenhada fora do lar pela genitora  Ou responsável legal que possui a guarda judicial da criança (peso 4).
                            </td>
                            <td>
                                <label>
                                    <input id="cc5" <?php echo @$readonly ?> onclick="document.getElementById('c5').style.display = 'none';leiAbre()" <?php echo @$dados['criterio5'] == 0 || empty($dados['criterio5']) ? 'checked' : '' ?> type="radio" name="1[criterio5]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c5').style.display = '';document.getElementById('le').disabled = true;" <?php echo @$dados['criterio5'] == 4 ? 'checked' : '' ?>  type="radio" name="1[criterio5]" value="4" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c5" style="display: <?php echo!empty($dados['criterio5']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['comp_trab'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[comp_trab]" value="1" />
                                        Carteira de trabalho da genitora ou responsável legal pelo(a) aluno (a), devidamente registrada e/ou o último holerite ou Declaração de trabalho com firma reconhecida, constando função, horário de trabalho, tempo de serviço, data de admissão, endereço do trabalho e número de telefone, acompanhado do último recibo de pagamento. Em se tratando de trabalho autônomo ou informal desempenhado pela genitora, declaração expedida pelos receptores dos mesmos, bem como a apresentação de comprovantes de materiais necessários ao serviço prestado.
                                        <span style="color: red">****</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 6: Genitora ou responsável legal esteja com condição física, mental ou psíquica incapacitante, causada por doença ou uso abusivo de substâncias químicas (peso 2).
                            </td>
                            <td>
                                <label>
                                    <input id="cc6" <?php echo @$readonly ?> onclick="document.getElementById('c6').style.display = 'none';leiAbre()" <?php echo @$dados['criterio6'] == 0 || empty($dados['criterio6']) ? 'checked' : '' ?> type="radio" name="1[criterio6]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c6').style.display = '';document.getElementById('le').disabled = true;" <?php echo @$dados['criterio6'] == 2 ? 'checked' : '' ?>  type="radio" name="1[criterio6]" value="2" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c6" style="display: <?php echo!empty($dados['criterio6']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['incapacitante'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[incapacitante]" value="1" />
                                        Declaração médica, que indique que a genitora ou responsável legal esteja com condição física, mental ou psíquica incapacitante, causada por doença ou uso abusivo de substâncias químicas;
                                        <span style="color: red">****</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 7: Óbito de um dos genitores ou do responsável legal, ou em situação  de abandono por um dos responsáveis legais (peso 2).
                            </td>
                            <td>
                                <label>
                                    <input id="cc7" <?php echo @$readonly ?> onclick="document.getElementById('c7').style.display = 'none';leiAbre()"  <?php echo @$dados['criterio7'] == 0 || empty($dados['criterio7']) ? 'checked' : '' ?> type="radio" name="1[criterio7]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c7').style.display = '';document.getElementById('le').disabled = true;"  <?php echo @$dados['criterio7'] == 3 ? 'checked' : '' ?>  type="radio" name="1[criterio7]" value="2" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c7" style="display: <?php echo!empty($dados['criterio7']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['obito'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[obito]" value="1" />
                                        Certidão de óbito de um dos genitores ou do responsável legal, ou declaração de situação  de abandono por um dos responsáveis legais
                                        <span style="color: red">****</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 8: Crianças que vivenciam violação de direitos, dentre elas a violência física, psicológica, sexual, situação de rua e cumprimento de medidas socioeducativas em meio aberto, acompanhadas por serviço de referência da assistência social (peso 3).
                            </td>
                            <td>
                                <label>
                                    <input id="cc8" <?php echo @$readonly ?> onclick="document.getElementById('c8').style.display = 'none';leiAbre()"  <?php echo @$dados['criterio8'] == 0 || empty($dados['criterio8']) ? 'checked' : '' ?> type="radio" name="1[criterio8]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c8').style.display = '';document.getElementById('le').disabled = true;"  <?php echo @$dados['criterio8'] == 3 ? 'checked' : '' ?>  type="radio" name="1[criterio8]" value="3" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c8" style="display: <?php echo!empty($dados['criterio8']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['violacao'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[violacao]" value="1" />
                                        Declaração que comprove a violação de direitos, dentre elas a violência física, psicológica, sexual, situação de rua e cumprimento de medidas socioeducativas em meio aberto, acompanhadas por serviço de referência da assistência social
                                        <span style="color: red">****</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Critério 9: Criança com deficiência. Parágrafo Único: Considerar-se-á, de acordo com LEI Nº 13.146, DE 6 DE JULHO DE 2015, que Institui a Lei Brasileira de Inclusão da Pessoa com Deficiência (Estatuto da Pessoa com Deficiência), estabelece no Artigo  2o  que pessoa com deficiência é aquela que tem impedimento de longo prazo de natureza física, mental, intelectual ou sensorial, o qual, em interação com uma ou mais barreiras, pode obstruir sua participação plena e efetiva na sociedade em igualdade de condições com as demais pessoas (peso 3).
                            </td>
                            <td>
                                <label>
                                    <input id="cc9" <?php echo @$readonly ?> onclick="document.getElementById('c9').style.display = 'none';leiAbre()"  <?php echo @$dados['criterio9'] == 0 || empty($dados['criterio9']) ? 'checked' : '' ?> type="radio" name="1[criterio9]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c9').style.display = '';document.getElementById('le').disabled = true;"  <?php echo @$dados['criterio9'] == 3 ? 'checked' : '' ?>  type="radio" name="1[criterio9]" value="3" />
                                    Sim
                                </label>
                            </td>
                            <td>
                                <div id="c9" style="display: <?php echo!empty($dados['criterio9']) ? '' : 'none' ?>">
                                    <label>
                                        <input <?php echo @$readonly ?> <?php echo @$dados['deficiencia'] == 1 ? 'checked' : '' ?> type="checkbox" name="1[deficiencia]" value="1" />
                                        Comprovante de criança com deficiência pelo:
                                        <div style="padding-left: 15px">
                                            a. Recebimento do LOAS - Lei Orgânica de Assistência Social, Lei n° 8.742 de 7 de dezembro de 1993 ou;
                                        </div>
                                        <div <?php echo @$readonly ?> style="padding-left: 15px">
                                            b. Declaração médica comprovando deficiência, com menção à nomenclatura do Anexo I do Decreto  Nº 7.345, DE 15 DE MAIO DE 2012 que menciona Relação de Patologias que Podem Caracterizar Deficiências ou;
                                        </div>
                                        <div <?php echo @$readonly ?> style="padding-left: 15px">
                                            c. Laudo médico comprovando deficiência.
                                            <span style="color: red">****</span>
                                        </div>

                                    </label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="lei" style="display: <?php echo @$dados['criterio10'] == 1000 ? '' : 'none' ?> ">
                    <table class="table table-bordered table-hover table-responsive table-striped">
                        <tr>
                            <td>
                                Critério: Mãe ou responsável foi vítima de violência doméstica ou familiar definida pela Lei Federal nº 11.340, de 7 de Agosto de 2006 - Lei Maria da Penha.
                            </td>
                            <td>
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c10').style.display = 'none';document.getElementById('boletim').required = false;document.getElementById('de').disabled = false;"  <?php echo @$dados['criterio10'] == 0 || empty($dados['criterio10']) ? 'checked' : '' ?> type="radio" name="1[criterio10]" value="0" />
                                    Não
                                </label>
                                <br />
                                <label>
                                    <input <?php echo @$readonly ?> onclick="document.getElementById('c10').style.display = '';document.getElementById('boletim').required = true;document.getElementById('de').disabled = true;"  <?php echo @$dados['criterio10'] == 1000 ? 'checked' : '' ?>  type="radio" name="1[criterio10]" value="1000" />
                                    Sim
                                </label>
                            </td>
                            <td style="width: 45%">
                                <div id="c10" style="display: <?php echo!empty(@$dados['criterio10']) ? '' : 'none' ?>">
                                    <br />
                                    Número do Boletim de Ocorrência
                                    <br />
                                    <input id="boletim" type="text" name="1[num_ocorrencia]" value="<?php echo @$dados['num_ocorrencia'] ?>" />
                                    Ao encerrar a matrícula, contacte o Depto de Informática para atualizar a classificação                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div style="text-align: center">
            <?php echo formulario::hidden($sqlkey) ?>
            <input type="hidden" name="aba" value="1" />
            <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() <> 13 ? tool::id_inst() : $dados['fk_id_inst'] ?>" />
            <input type="hidden" name="1[status]" value="<?php echo empty($dados['status']) ? 'Edição' : $dados['status'] ?>" />
            <input type="hidden" name="1[cd_acesso]" value="<?php echo empty($dados['cd_acesso']) ? substr(uniqid(), 0, 6) : $dados['cd_acesso'] ?>" />
            <input type="hidden" name="1[id_vaga]" value="<?php echo @$dados['status'] ?>" />
            <input type="hidden" name="1[id_vaga]" value="<?php echo @$id_vaga ?>" />
            <input <?php echo @$readonly ?> class="btn btn-success" name="cadastro" type="submit" value="Salvar" />
        </div>
    </form>
</div>
<span style="color: red; font-size: 12px;">* &nbsp;Campos obrigatórios</span>
<br />
<span style="color: red; font-size: 12px;">** &nbsp;Pelo menos um dos telefones é obrigatório</span>
<br />
<span style="color: red; font-size: 12px;">*** &nbsp;Obrigatório apenas para questões de vulnerabilidade social</span>
<br /><br />
<script>
    function ma() {
        document.getElementById("mae").value = document.getElementById("mae1").value;
        // document.getElementById("mae").readOnly = true;
        document.getElementById("mae2").checked = true;
        document.getElementById("chk").checked = true;
        document.getElementById("mae3").disabled = true;
    }
    function maResp() {
        if (document.getElementById("chk").checked === true || document.getElementById("mares").checked === true) {
            document.getElementById("mae1").value = document.getElementById("mae").value;
            document.getElementById("mae1").readOnly = true;
            document.getElementById("mares").checked = true;
            document.getElementById("mae2").checked = true;
            document.getElementById("mae3").disabled = true;
            document.getElementById("pai").disabled = true;
            document.getElementById("resp").disabled = true;
        } else {
            document.getElementById("mae1").value = "";
            document.getElementById("mae1").readOnly = false;
            document.getElementById("mares").checked = false;
            document.getElementById("mae2").checked = false;
            document.getElementById("mae3").disabled = false;
            document.getElementById("pai").disabled = false;
            document.getElementById("resp").disabled = false;
        }

    }

    //Nova função
    function maResp2() {
        if (document.getElementById("chk2").checked === true || document.getElementById("paires").checked === true) {
            document.getElementById("mae1").value = document.getElementById("pai").value;
            document.getElementById("mae1").readOnly = true;
            document.getElementById("paires").checked = true;
            document.getElementById("pai2").checked = true;
            document.getElementById("pai3").disabled = true;
            document.getElementById("pai").disabled = true;
            document.getElementById("resp").disabled = true;
        } else {
            document.getElementById("mae1").value = "";
            document.getElementById("mae1").value = document.getElementById("pai").value;
            document.getElementById("mae1").readOnly = false;
            document.getElementById("paires").checked = false;
            document.getElementById("pai2").checked = false;
            document.getElementById("pai3").disabled = false;
            document.getElementById("pai").disabled = false;
            document.getElementById("resp").disabled = false;
        }
    }

    // Desabilita Responsavel legal mae e pai
    function outroresp() {
        if (document.getElementById("resp").checked === true) {
            // alert('teste');
            document.getElementById("chk").checked = false;
            document.getElementById("chk2").checked = false;
            document.getElementById("mae1").value = "";
            document.getElementById("mae1").readOnly = false;
            document.getElementById("paires").checked = false;
            document.getElementById("pai2").checked = false;
            document.getElementById("pai3").disabled = false;
            document.getElementById("pai").disabled = false;
            document.getElementById("resp").disabled = false;
        }
    }

    function no() {
        // document.getElementById("mae").value = " ";
        document.getElementById("mae").readOnly = false;
        document.getElementById("mae3").disabled = false;
        document.getElementById("mae2").disabled = false;
    }
    function no5() {
        // document.getElementById("mae").value = " ";
        document.getElementById("mae").readOnly = false;
        document.getElementById("mae3").disabled = false;
        document.getElementById("mae2").disabled = false;
    }
    function no1() {
        // document.getElementById("mae").value = " ";
        document.getElementById("mae").readOnly = false;
        document.getElementById("mae2").disabled = true;
        document.getElementById("mae3").checked = true;
    }


    function ats() {
        document.getElementById("atv").style.display = "";
    }

    function atn() {
        document.getElementById("atv").style.display = "none";
    }

    function mudaLei(tipo) {
        if (tipo === 'decreto') {
            document.getElementById('decreto').style.display = '';
            document.getElementById('lei').style.display = 'none';
            document.getElementById('de').className = "btn btn-info";
            document.getElementById('le').className = "btn btn-default";
        }
        if (tipo === 'lei') {
            document.getElementById('lei').style.display = '';
            document.getElementById('decreto').style.display = 'none';
            document.getElementById('le').className = "btn btn-info";
            document.getElementById('de').className = "btn btn-default";
        }

    }
    function leiAbre() {
        if (
                document.getElementById("cc1").checked === true
                &&
                document.getElementById("cc2").checked === true
                &&
                document.getElementById("cc3").checked === true
                &&
                document.getElementById("cc4").checked === true
                &&
                document.getElementById("cc5").checked === true
                &&
                document.getElementById("cc6").checked === true
                &&
                document.getElementById("cc7").checked === true
                &&
                document.getElementById("cc8").checked === true
                &&
                document.getElementById("cc9").checked === true
                ) {
            document.getElementById("le").disabled = false;

        }
    }
</script>


<!-- MAPA -->
<script>
    var map;
    function initrota(){

        const cep = document.getElementById('location-input').value;
        const lat = document.getElementById('lat').value;
        const long = document.getElementById('long').value;

        // console.log(cep);
        // console.log(lat);
        // console.log(long);

        if (cep.length == 8) {

            var directionsService = new google.maps.DirectionsService();
            var info = new google.maps.InfoWindow({maxWidth: 200});

            var marker = new google.maps.Marker({
                title: 'Escola',
                icon: 'marker.png',
                position: new google.maps.LatLng(lat, long)
            });


            info.close();
            marker.setMap(map);

            var directionsDisplay = new google.maps.DirectionsRenderer();

            const cep = document.getElementById('location-input').value;

            var request = {
                origin: cep,
                destination: marker.position,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };

            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                    directionsDisplay.setMap(map);
                }
            });

        }
    return false;
}



</script>

<?php
javaScript::cep();

