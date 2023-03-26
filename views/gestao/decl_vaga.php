<?php
ob_start();

$escola = new escola();

@$vag = $_POST['id_vaga_c'];

$vaga = sql::get('ge_decl_vaga_comp', 'nome_solicitante, rg, sexo, n_ciclo, nome_aluno, sexo_aluno', ['id_vaga_c' => $vag]);
?>

<head>
    <style>
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 5px;
        }
        .dc{
            font-size: 6pt;
            text-align: right;

        }
    </style> 
</head>

<?php
foreach ($vaga as $v) {
    // echo $escola->cabecalho();
    ?>
    <br /><br /><br /><br /><br />
    <div style="font-weight:bold; font-size:12pt; text-align: center">
        DECLARAÇÃO DE VAGA
    </div>
    <br /><br /><br /><br />
    <div style="text-align:justify"> A direção desta escola declara, para devidos fins, que <b><?php echo addslashes($v['nome_solicitante']) ?></b>
        , RG nº. <?php echo $v['rg'] ?>, solicitou uma vaga no <?php echo $model->peganomeciclo($v['n_ciclo']) ?>, para <?php echo ($v['sexo_aluno'] == "M" ? 'o aluno ' : 'a aluna ') ?> <b><?php echo addslashes($v['nome_aluno']) ?></b> nesta Unidade Escolar.
    </div>
    <br /><br />
    <div>Este documento tem validade de 05 dias a partir da data de sua emissão.</div>
    <br /><br /><br /><br />
    <table style="border:1px solid">
        <tr>
            <td style="color:red; font-size: 7pt; padding: 5px; text-decoration: underline">
                Documentos para Matrícula
                <ul style="text-align: left; padding: 5px; color:black; text-decoration: none" >
                    <li>3 fotos 3x4 atualizadas do(a) aluno(a);</li>
                    <li>Histórico Escolar;</li>
                    <li>Declaração de Transferência;</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="color:red; font-size: 7pt; padding: 5px; text-decoration: underline">
                Apresentação Obrigatória no Ato da Matrícula
                <ul style="text-align: left; padding: 5px; color:black; text-decoration: none">
                    <li>xerocópia do documento de identidade do(a) aluno(a) e da certidão de nascimento;</li>
                    <li>xerocópia da carteira de vacina atualizada, com a validação da Unidade Básica de Saúde – UBS;</li>
                    <li>xerocópia do documento de identidade e do CPF dos pais e/ou responsáveis legais;</li>
                    <li>cartão do SUS referente ao aluno;</li>
                    <li>xerocópia do(s) RG (s) das pessoas maiores de idade autorizadas a retirarem o(a) aluno(a) da escola;</li>
                    <li>número de telefones dos responsáveis pela retirada do(a) aluno(a) da escola;</li>
                    <li>apresentação de comprovante de residência.</li>
                </ul>
            </td>
        </tr>
    </table>
    <br /><br />
    <div style="text-align: right">Barueri, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
    <br /><br /><br /><br />
    <div style="text-align: center">_____________________________________</div>
    <div style="text-align:center">Carimbo e Assinatura</div>
    <br /><br />
    <div style="font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
        Obs. Este documento não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
    </div>
    <?php
}
tool::pdfSemRodape();
?>