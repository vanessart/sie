<?php
ob_start();

$dados = sql::get('vagas', '*', ['id_vaga' => $_POST['id_vaga']], 'fetch');
$code = '005' . $dados['cd_acesso'] . str_pad($dados['id_vaga'], 6, "0", STR_PAD_LEFT);

$esc = new escola();
for ($c = 1; $c <= 2; $c++) {
    ?>
    <div style="border: 1px solid">
        <?php
        echo $esc->cabecalho();
        ?>
    </div> 
    <table border=1 cellspacing=0 cellpadding=0 style="width: 100%">
        <tr>
            <td>
                Protocolo de Inscrição para Matrícula Nº <?php echo $dados['id_vaga'] ?>
            </td>
            <td rowspan="6" style="width: 100px; text-align: center">
                <?php
                $url = HOME_URI . '/app/code/php/qr_img.php?d=https://portal.educ.net.br/ge/pub/rastro?rastro=' . $code . '&.PNG';
                ?>
                <img src = "<?php echo $url . 'width="90" height="90"' ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                Aluno: <?php echo $dados['n_aluno'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Responsável: <?php echo $dados['responsavel'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Data de Nascimento (aluno(a)): <?php echo data::converteBr(substr($dados['dt_aluno'], 0, 10)) ?>
            </td>
        </tr>
        <tr>
            <td>
                Data Inscr.: <?php echo data::converteBr(substr($dados['dt_vagas'], 0, 10)) ?>
            </td>
        </tr>
        <tr>
            <td>
                Seriação: <?php echo $dados['seriacao'] ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo'End: ' . $dados['logradouro'] . ', ' . $dados['num'] . ' - ' . $dados['bairro'] . ' - CEP: ' . $dados['cep']; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo 'Telefones: ' . $dados['tel1'] . (!empty(@$dados['tel2']) ? '; ' . @$dados['tel2'] : '') . (!empty(@$dados['tel3']) ? '; ' . @$dados['tel3'] : '') ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 5px; text-align: justify">
                <span style="font-weight:bold; font-size:8pt; background-color: #000000; color:#ffffff; text-align: center">
                    Sr. Responsável, solicitamos que tome ciência:
                </span>
                <br />
                <span style=" font-size: 8pt">
                    1- Manter telefones atualizados, pois após 3 tentativas de ligações em dias e horários diferentes, sem êxito, o nome da criança será retirado da lista de espera:
                    <br />
                    2- Quando surgir a vaga, o prazo máximo para comparecer com os documentos e efetuar a matrícula será de 10 dias úteis a contar a data de chamada, não comparecimento dentro do prazo, a vaga será ofertada para o próximo da lista, conforme classificação.
                    <br />
                    3 - Havendo vaga em outra U.E. e sendo ofertada,  o responsável fazendo ou não a matrícula, será realizado o cancelamento da lista de espera. Devendo o responsável retornar a U.E. no mês de Setembro para nova inscrição de solicitação de vaga. Estando matriculado na U.E que foi ofertada a vaga, terá prioridade de transferência para a escola de abrangência, onde fez a inscrição, no próximo  ano letivo.
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 5px; text-align: justify; font-size: 8pt">
                Para saber a sua pontuação acesse o sítio http://educacao.barueri.sp.gov.br, clique em "Rastreamento de Serviços" e insira o seguinte código:
                &nbsp;&nbsp;&nbsp;
                <strong>
                    <?php echo '005' . $dados['cd_acesso'] . str_pad($dados['id_vaga'], 6, "0", STR_PAD_LEFT) ?>
                </strong>
                &nbsp;&nbsp;&nbsp;
                ou utilize o QR Code acima com seu smartphone
            </td>
        </tr>
        <tr>
            <td colspan="2" >
                <table style="width: 100%">
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <?php echo CLI_CIDADE.", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") ?>
                            <br /><br /><br /><br /><br />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">
                            ______________________________
                        </td>
                        <td style="text-align: center">
                            ______________________________
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">
                            Responsável pelo Aluno
                        </td>
                        <td style="text-align: center">
                            Responsável U.E.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php
        if ($c == 1) {
            ?>
            <tr>
                <td colspan="2" style="text-align: center">
                    -------------------------------------------------------------------------------------------------------------------------------------------------
                </td>
            </tr>
            <?php
        }
        ?>

    </table>
    <?php
}
tool::pdf();
?>

