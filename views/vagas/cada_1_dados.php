<table style="width: 100%">
    <tr>
        <td style="font-size: 18px;font-weight: bold;text-align: center;padding: 20px">
            Dados do(a) Aluno(a)
        </td>
    </tr>
    <tr>
        <td>
            <table style="font-weight: bold"  class="table table-bordered table-condensed table-responsive table-striped">
                <tr>
                    <td style="width: 40%" >
                        Protocolo
                    </td>
                    <td>
                        <?php echo @$dados['id_vaga']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 40%" >
                        Status
                    </td>
                    <td>
                        <?php echo @$dados['status']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 40%" >
                        Escola
                    </td>
                    <td>
                        <?php echo current(escolas::idInst(@$dados['fk_id_inst'], 'fk_id_inst')); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 40%" >
                        Nome da Mãe
                    </td>
                    <td>
                        <?php echo @$dados['mae']; ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Nome do(a) Aluno(a)
                    </td>
                    <td>
                        <?php echo @$dados['n_aluno']; ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Data de nascimento (Aluno(a)) 
                    </td>
                    <td>
                        <?php
                        echo data::converteBr(@$dados['dt_aluno']);
                        ?>
                        <?php ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Sexo 
                    </td>
                    <td>
                        <?php echo @$dados['sx_aluno'] ?> 
                    </td>
                </tr>
                <tr>
                    <td >
                        RG (Aluno(a) - somente números) 
                    </td>
                    <td>
                        <?php echo @$dados['rg_aluno']; ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        O.E.:
                        <?php echo @$dados['oe_rg_aluno']; ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Certidão de Nascimento (matrícula) 
                    </td>
                    <td>
                        <?php echo @$dados['cn_matricula']; ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Seriação 
                    </td>
                    <td>
                        <?php
                        $de = explode('-', @$dados['dt_aluno']);
                        $seriacao = $model->setSerie($de[0] . $de[1] . $de[1]);
                        echo $seriacao;
                        ?> 
                    </td>
                </tr>
                <tr>
                    <td >
                        Data da Inscrição 
                    </td>
                    <td>
                        <?php
                        echo data::converteBr(substr(@$dados['dt_vagas'], 0, 10));
                        ?> 
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php if (!empty($prox)) { ?>            
        <tr>
            <td style="font-size: 18px;font-weight: bold;text-align: center;padding: 20px">
                Matrícula 2017
            </td>
        </tr>
        <tr>
            <td>
                <table style="font-weight: bold" class="table table-bordered table-condensed table-responsive table-striped">
                    <tr>
                        <td>
                            Escola em 2017
                        </td>
                        <td>
                            <?php echo!empty(@$dados['prox_ano']) ? escolas::dados(@$dados['prox_ano'])['n_school'] : (@$dados['prox_status'] == 'Disponível' ? '' : $_SESSION['userdata']['n_inst']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            Status da Matrícula
                        </td>
                        <td>
                            <?php echo empty(@$dados['prox_status']) ? 'Indefinido' : @$dados['prox_status']; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td style="font-size: 18px;font-weight: bold;text-align: center;padding: 20px">
            Dados do Responsável 
        </td>
    </tr>
    <tr>
        <td>
            <table style="font-weight: bold" class="table table-bordered table-condensed table-responsive table-striped">
                <tr>
                    <td style="width: 40%" >
                        Nome do Responsável
                    </td>
                    <td>
                        <?php echo @$dados['responsavel']; ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Grau de Parentesco
                    </td>
                    <td>
                        <?php echo @$dados['parente'] ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        Data de Nasc. (responsável) 
                    </td>
                    <td>
                        <?php
                        echo data::converteBr(@$dados['dt_resp']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Sexo 
                    </td>
                    <td>
                        <?php echo @$dados['sx_resp'] ?> 
                    </td>
                </tr>
                <tr>
                    <td >
                        Estado Civil 
                    </td>


                    <td>
                        <?php echo @$dados['civil'] ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        CPF
                    </td>
                    <td>
                        <?php echo @$dados['cpf_resp']; ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Está empregado(a)? 

                    </td>
                    <td>
                        <?php
                        if (@$dados['trabalha'] == '0') {
                            echo 'Não';
                        } else {
                            echo 'Sim';
                            if (!empty(@$dados['atividade'])) {
                                echo ', ' . @$dados['atividade'];
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Número de Filhos
                    </td>
                    <td>
                        <?php echo @$dados['filhos'] ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        <label>CEP</label>
                    </td>
                    <td>
                        <?php echo str_pad(@$dados['cep'], 8, "0", STR_PAD_LEFT); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Logradouro</label>
                    </td>
                    <td>
                        <?php echo @$dados['logradouro'] . ', ' . @$dados['num']; ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Complemento
                    </td>
                    <td>
                        <?php echo @$dados['compl'] ?>
                    </td>
                </tr>                    
                <tr>
                    <td >
                        <label>Bairro</label>
                    </td>
                    <td>
                        <?php echo @$dados['bairro'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Cidade</label>
                    </td>
                    <td>
                        <?php echo @$dados['cidade'] ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label> - UF:</label>
                        &nbsp;&nbsp;
                        <?php echo @$dados['uf'] ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        Telefones 
                    </td>
                    <td> 
                        <?php echo @$dados['tel1'] ?> 
                        <?php echo!empty($dados['tel2']) ? ' - ' . $dados['tel2'] : '' ?>
                        <?php echo!empty($dados['tel3']) ? ' - ' . $dados['tel3'] : '' ?>
                    </td>
                </tr>           
            </table>
        </td>
    </tr>
    <tr>
        <td style="font-size: 18px;font-weight: bold;text-align: center;padding: 20px">
            Vulnerabilidade Social
        </td>
    </tr>
    <tr>
        <td>
            <table style="font-weight: bold" class="table table-bordered table-condensed table-responsive table-striped">
                <?php
                if (!empty(@$dados['criterio1'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 1: Recebimento da Bolsa Família, Lei Federal n° 10.836, de 9 de janeiro de 2004.
                        </td>
                        <td valign="top">
                            <ul style="margin-left: 15px">
                                <li>
                                    Cartão do NIS:
                                    <?php echo @$dados['nis'] ?>
                                </li>
                            </ul>

                        </td>
                    </tr>
                    <?php
                }
                if (!empty(@$dados['criterio2'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 2: A genitora ou responsável legal que possui a guarda judicial da criança é regularmente matriculada na educação básica ou educação superior.
                        </td>
                        <td valign="top">
                            <?php
                            if (@$dados['estuda'] == 1) {
                                ?>
                                <ul style="margin-left: 15px">
                                    <li>
                                        Declaração de frequência, devidamente assinada pela instituição de ensino reconhecida pelo MEC.
                                    </li>
                                </ul>

                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                if (!empty(@$dados['criterio3'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 3: A genitora ou responsável legal que possui a guarda judicial da criança, devido aos estudos na Educação Superior, frequenta estágios laborais obrigatórios.
                        </td>
                        <td valign="top">
                            <?php
                            if (@$dados['estagio'] == 1) {
                                ?>
                                <ul style="margin-left: 15px">
                                    <li>
                                        Declaração de frequência, devidamente assinada pela instituição de ensino reconhecida pelo MEC e declaração da referida universidade comprovando a frequência em estágios laborais obrigatórios.
                                    </li>
                                </ul>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }

                if (!empty(@$dados['criterio4'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 4: A genitora ou responsável legal que possui a guarda judicial da criança, já possua um(a) filho(a) estudando na escola;
                        </td>
                        <td valign="top">
                            <ul style="margin-left: 15px">


                                <?php
                                if (@$dados['gdea'] == 1) {
                                    ?>
                                    <li>
                                        Ficha de aluno(a) no GDAE (irmão(a))
                                    </li>
                                    <?php
                                }
                                if (@$dados['irmao'] == 1) {
                                    ?>
                                    <li>
                                        Comprovando o parentesco de irmãos
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                    <?php
                }

                if (!empty(@$dados['criterio5'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 5: Atividade laboral desempenhada fora do lar pela genitora  Ou responsável legal que possui a guarda judicial da criança;  
                        </td>
                        <td valign="top" >
                            <?php
                            if (@$dados['comp_trab'] == 1) {
                                ?>
                                <ul style="margin-left: 15px">
                                    <li >
                                        Carteira de trabalho da genitora ou responsável legal pelo(a) aluno (a), devidamente registrada e/ou o último holerite ou Declaração de trabalho com firma reconhecida, constando função, horário de trabalho, tempo de serviço, data de admissão, endereço do trabalho e número de telefone, acompanhado do último recibo de pagamento. Em se tratando de trabalho autônomo ou informal desempenhado pela genitora, declaração expedida pelos receptores dos mesmos, bem como a apresentação de comprovantes de materiais necessários ao serviço prestado.
                                    </li>
                                </ul>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }

                if (!empty(@$dados['criterio6'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 6: Genitora ou responsável legal esteja com condição física, mental ou psíquica incapacitante, causada por doença ou uso abusivo de substâncias químicas.
                        </td>
                        <td valign="top">
                            <?php
                            if (@$dados['incapacitante'] == 1) {
                                ?>
                                <ul style="margin-left: 15px">
                                    <li>
                                        Carteira de trabalho da genitora ou responsável legal pelo(a) aluno (a), devidamente registrada e/ou o último holerite ou Declaração de trabalho com firma reconhecida, constando função, horário de trabalho, tempo de serviço, data de admissão, endereço do trabalho e número de telefone, acompanhado do último recibo de pagamento. Em se tratando de trabalho autônomo ou informal desempenhado pela genitora, que se comprove a prestação de serviços com a declaração expedida pelos receptores dos mesmos, bem como a apresentação de comprovantes de materiais necessários ao serviço prestado;
                                    </li>
                                </ul>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }

                if (!empty(@$dados['criterio7'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 7: Óbito de um dos genitores ou do responsável legal, ou em situação  de abandono por um dos responsáveis legais.
                        </td>
                        <td valign="top">
                            <?php
                            if (@$dados['obito'] == 1) {
                                ?>
                                <ul style="margin-left: 15px">
                                    <li>
                                        Certidão de óbito de um dos genitores ou do responsável legal, ou declaração de situação  de abandono por um dos responsáveis legais
                                    </li>
                                </ul>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }

                if (!empty(@$dados['criterio8'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 8: Crianças que vivenciam violação de direitos, dentre elas a violência física, psicológica, sexual, situação de rua e cumprimento de medidas socioeducativas em meio aberto, acompanhadas por serviço de referência da assistência social.
                        </td>
                        <td valign="top">
                            <?php
                            if (@$dados['violacao'] == 1) {
                                ?>
                                <ul style="margin-left: 15px">
                                    <li>
                                        Declaração que comprove a violação de direitos, dentre elas a violência física, psicológica, sexual, situação de rua e cumprimento de medidas socioeducativas em meio aberto, acompanhadas por serviço de referência da assistência social
                                    </li>
                                </ul>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }

                if (!empty(@$dados['criterio9'])) {
                    ?>
                    <tr>
                        <td valign="top">
                            Critério 9: Criança com deficiência. Parágrafo Único: Considerar-se-á, de acordo com LEI Nº 13.146, DE 6 DE JULHO DE 2015, que Institui a Lei Brasileira de Inclusão da Pessoa com Deficiência (Estatuto da Pessoa com Deficiência), estabelece no Artigo  2o  que pessoa com deficiência é aquela que tem impedimento de longo prazo de natureza física, mental, intelectual ou sensorial, o qual, em interação com uma ou mais barreiras, pode obstruir sua participação plena e efetiva na sociedade em igualdade de condições com as demais pessoas.
                        </td>
                        <td valign="top">
                            <?php
                            if (@$dados['deficiencia'] == 1) {
                                ?>
                                <ul style="margin-left: 15px">
                                    <li>
                                        Comprovante de criança com deficiência pelo:
                                        <ul style="margin-left: 15px">
                                            <li>
                                                Recebimento do LOAS - Lei Orgânica de Assistência Social, Lei n° 8.742 de 7 de dezembro de 1993 ou;
                                            </li>
                                            <li>
                                                Declaração médica comprovando deficiência, com menção à nomenclatura do Anexo I do Decreto  Nº 7.345, DE 15 DE MAIO DE 2012 que menciona Relação de Patologias que Podem Caracterizar Deficiências ou;
                                            </li>
                                            <li>
                                                Laudo médico comprovando deficiência.
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>


            </table>
        </td>
    </tr>
</table>