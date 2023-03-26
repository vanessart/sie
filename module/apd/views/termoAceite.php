<?php
if (!defined('ABSPATH'))
    exit;

$id_protocolo = filter_input(INPUT_POST, 'id_protocolo', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);

$protocolo = $model->getProtocolo($id_protocolo);
if (empty($protocolo)) {
    toolErp::alertModal('Lamento! Algo de errado :( ');
    die();
}

$id_pessoa = $protocolo['fk_id_pessoa'];
$aluno = $model->getPessoa($id_pessoa);
if (empty($aluno)) {
    toolErp::alertModal('Lamento! Algo de errado :( ');
    die();
}

$termo = $model->getTermoAdesao($id_protocolo);
if (empty($termo)) {
    $ano = date('Y');
    $prof = toolErp::n_pessoa();
    $aluno['idade'] = $model->idade($aluno['dt_nasc']);
    $eu = '<input type="text" name="1[nome]" class="obrig txt" placeholder="INFORME SEU NOME" style="width:40%" />';
    $rg = '<input type="text" name="1[rg]" class="obrig txt" placeholder="INFORME O NÚMERO DO SEU RG" style="width:20%" />';
    $dias = '<input type="text" name="1[dias]" class="obrig txt" placeholder="INFORME O(S) DIA(S)" style="width:40%" />';
    $horarios = '<input type="text" name="1[horarios]" class="obrig txt" placeholder="INFORME O(S) HORÁRIO(S)" style="width:40%" />';
    $assinatura = formErp::button('Assinar o Termo', null, 'termo()', 'success');
    $assinatura_rod = "";
    $escondeRadio = false;
} else {
    $ano = date('Y', strtotime($termo['dt_update']));
    $aluno['idade'] = $model->idade($aluno['dt_nasc'], date('Y-m-d', strtotime($termo['dt_update'])));
    $prof = $termo['n_pessoa'];
    $eu = $termo['nome'];
    $rg = $termo['rg'];
    $dias = $termo['dias'];
    $horarios = $termo['horarios'];
    $conduzido_por = $termo['conduzido_por'];
    $assinatura = '<img src="'. $termo['assinatura'] .'" style="width:45%" />';
    $assinatura_rod = "Documento assinado digitalmente por ".strtoupper($eu).". <br>Data: ". date('d/m/Y H:i:s', strtotime($termo['dt_update'])) ." | IP: ". $termo['IP'];
    $escondeRadio = true;
}

$oa = concord::oa($aluno['id_pessoa']);
?>
<style type="text/css">
    <?php if (!empty($termo)) { ?>
    .compl {
        border-bottom: 1px solid #000;
        margin: auto 10px;
    }
    <?php } ?>
    ::-webkit-input-placeholder { /* Edge */
      color: #abaeb0;
      font-size: 12px;
      vertical-align: middle;
    }
    :-ms-input-placeholder { /* Internet Explorer 10-11 */
      color: #abaeb0;
      font-size: 12px;
      vertical-align: middle;
    }
    ::placeholder {
      color: #abaeb0;
      font-size: 12px;
      vertical-align: middle;
    }
    input[type="text"] {
        border: 1px solid #cbced0;
        font-size: 14px;
        height: 30px;
        margin: 2px;
    }
    .fieldTop span {
        font-weight: normal;
    }
    @media print {
        .navbar, .big, .small, .medium, .btnImprime{
            display: none;
        }
        .doc-assinat-dig {
            position: absolute;
            bottom: 2px;
            color: #a1a1a1;
        }
    }
</style>
<div class="body">
    <form id="form" action="<?= HOME_URI ?>/apd/capAssinaturaAceite" method="POST">
    <br>
    <div class="row btnImprime">
        <div class="col-9">
        </div>
        <?php 
        if (toolErp::id_nilvel()==24) {?>
            <div class="col" style="text-align: right;">
                <div class="btn btn-warning" onclick="voltar()">VOLTAR</div>
            </div>
            <?php
        } ?>
        <div class="col" style="text-align:right;">
            <div class="btn btn-warning" onclick="impr()">IMPRIMIR</div>
        </div>
    </div>
    <div class="fieldTop">
        1.4 -TERMO DE ADESÃO- AEE-<?php echo $ano ?><br>
    </div>

    <div class="fieldTop" style="text-align: left;font-size: 80%;">
        EMEIEF-EMEF: <span><?php echo $protocolo['n_inst'] ?></span><br><br>
        <p>Pólo: <span><?php echo $protocolo['n_inst_aee'] ?></span></p>
        <p>Turma: <span><?php echo $protocolo['n_turma_aee'] ?></span></p>
        <p>Prof: <span><?php echo $prof ?></span></p>
    </div>
    <div style="line-height:2em">
        Eu, <span class="compl"><?php echo $eu ?></span> portador(a) do RG <span class="compl"><?php echo $rg ?></span>,
        informo que <?php echo concord::meu($aluno['id_pessoa']) ?> filh<?php echo $oa ?> <b><?php echo $aluno['n_pessoa'] ?></b> DN: <b><?php echo dataErp::converteBr($aluno['dt_nasc']) ?></b> idade <b><?php echo $aluno['idade'] ?></b> anos frequentará o AEE no(s) dia(s) e horário(s) discriminados abaixo:
        <p>
            Dia (s): &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="compl"><?php echo $dias ?></span><br>
            Horários (s): <span class="compl"><?php echo $horarios ?></span><br><br>
            Em relação ao transporte, <?php echo $oa ?> alun<?php echo $oa ?> :<br>
        <div for="check1" style="margin-left: 80px;">
            <?php if (empty($termo) || $termo['autorizado'] == 1) { ?>
                <?php if (!$escondeRadio) { ?><input type="radio" id="check1" name="1[autorizado]" class="obrig rd" value="1"><?php } ?>
                está autorizad<?php echo $oa ?> a ir e vir sozinh<?php echo $oa ?>.
                </div>
            <?php }

            if (empty($termo) || $termo['autorizado'] == 2) {
                if (!$escondeRadio) { ?>
                    <div style="margin-left: 80px;" for="check2">
                        <input type="radio" id="check2" name="1[autorizado]" class="obrig rd" value="2">
                <?php } ?>
                não está autorizad<?php echo $oa ?> a ir e vir sozinh<?php echo $oa ?>, sendo conduzido por 

                <?php
                if (!empty($termo)) { 
                    if ($termo['autorizado'] == 2) { ?>
                        <span class="compl"><?php echo $conduzido_por ?></span> 
                    <?php 
                    } else {
                        echo "______________________"; 
                    }
                } else { ?>
                    <input type="text" name="1[conduzido_por]" class="obrig txt obRd" data-dep="check2" placeholder="INFORME O NOME DO RESPONSÁVEL" style="width:20%" />
                <?php } ?>
                    </div>
                <?php
            }

            if (empty($termo) || $termo['autorizado_img'] == 1) {
                if (!$escondeRadio) { ?>
                    <br>
                    Em relação ao uso de imagem, o responsável :<br>
                <div style="margin-left: 80px;margin-top: 10px;" for="check3">
                    <input type="radio" id="check3" name="1[autorizado_img]" class="obrig rdImg" value="1">
                <?php } ?>
                autoriza o uso da imagem d<?php echo $oa ?> alun<?php echo $oa ?> para divulgação.
                </div>
            <?php }
            
            if (empty($termo) || $termo['autorizado_img'] == 2) {
                if (!$escondeRadio) { ?>
                <div style="margin-left: 80px;" for="check4">
                    <input type="radio" id="check4" name="1[autorizado_img]" class="obrig rdImg" value="2">
                <?php } ?>
                não autoriza o uso da imagem d<?php echo $oa ?> alun<?php echo $oa ?>.

                </div>
            <?php } ?>

    </div>

    <div style="font-weight: bold;margin: 40px 0;">
        “Importante ressaltar que o ECA (Estatuto da Criança e do Adolescente) determina que é dever dos pais ou responsáveis legais, oferecer todas as oportunidades de desenvolvimento pleno de seus filhos, no que diz respeito à Educação.”
    </div>

    <div style="text-align:center;margin-top: 30px;">
        <?= formErp::button("Ir para Assinatura Digital", null, "salvar()", "success"); ?>
    </div>

    <input type="hidden" name="id_protocolo" id="id_protocolo" value="<?php echo $id_protocolo ?>" />
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?php echo $id_pessoa ?>" />

<?php
echo formErp::hidden([
    '1[fk_id_protocolo]' => $id_protocolo,
    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
    '1[fk_id_pessoa_aluno]' => $id_pessoa,
    '1[tipo]' => 'A',
    'status' => 8
])
. formErp::hiddenToken('protTermo');

?>
</form>
</div>
<script>
    <?php if (empty($termo)) { ?>
    setInterval(function () {
        fetch('<?= HOME_URI ?>/protocolo/termoAssinadoGet?id_protocolo=<?= $id_protocolo ?>')
            .then(resp => resp.text())
            .then(resp => {
                if (resp == '1') {
                    window.location.reload(true);
                }
            })
    }, 2000);
    <?php } ?>

    function impr(){
        this.print();
    }
    function voltar(){
        window.location.href = '<?= HOME_URI ?>/apd/alunoNovoList';
    }
    function termo(){
        error = false;
        $('.obrig').each(function(){
            if ($(this).hasClass('txt') && $(this).val().length == 0 ) {
                if ($(this).hasClass('obRd')) {
                    if ($('#'+ $(this).data('dep') + ':checked').length > 0) {
                        alert($(this).attr('placeholder'));
                        $(this).focus();
                        error = true;
                        return false;
                    }
                } else {
                    alert($(this).attr('placeholder'));
                    $(this).focus();
                    error = true;
                    return false;
                }
            }
        });

        if (error){
            return false;
        }

        if ($('.obrig.rd:checked').length ==0 ){
            alert('Informe uma das opções de autorização do aluno');
            error = true;
            return false;
        }

        if ($('.obrig.rdImg:checked').length ==0 ){
            alert('Informe uma das opções de autorização de uso da imagem do aluno');
            error = true;
            return false;
        }

        $('#myModal').modal('show');
        return true;
    }

    function limpa(e){
        e.preventDefault();
        $('#enviar').attr('disabled', true);
    }

    function __change(){
        $('#enviar').attr('disabled', false);
    }

    function salvar(){
        <?php if (empty($termo)) { ?>
        if (!termo()){
            return false;
        }
        <?php } ?>
        document.getElementById('form').submit();
    }
    jQuery(function(){
        $('#enviar').attr('disabled', true);
    })
</script>