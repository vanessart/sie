<?php
if (!defined('ABSPATH'))
    exit;

$id_protocolo = filter_input(INPUT_POST, 'id_protocolo', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);

if (empty($id_protocolo)) {
    toolErp::alertModal('Lamento! Algo de errado :( ');
    die();
}

$protocolo = $model->getProtocolo($id_protocolo);
if (empty($protocolo)) {
    toolErp::alertModal('Lamento! Algo de errado :( ');
    die();
}

$aluno = $model->getPessoa($id_pessoa);
if (empty($aluno)) {
    toolErp::alertModal('Lamento! Algo de errado :( ');
    die();
}

$termo = $model->getTermoRecusa($id_protocolo);
$cidPessoa = $model->getCIDpessoa($id_pessoa);
$cid = $virg = '';
if (!empty($cidPessoa)) {
    foreach ($cidPessoa as $key => $value) {
        $cid .= $virg . $value['descricao'];
        $virg = ',';
    }
} else {
    $cid = $model->getCidProto($id_protocolo);
    $cid = !empty($cid) ? $cid : '';
}

if (empty($termo)) {
    $ano = date('Y');
    $eu = '<input type="text" name="1[nome]" class="obrig txt" placeholder="INFORME SEU NOME" style="width:40%" required />';
    $rg = '<input type="text" name="1[rg]" class="obrig txt" placeholder="INFORME O NÚMERO DO SEU RG" style="width:20%" required />';
    $motivo = '<textarea rows="3" cols="150" name="1[motivo]" class="obrig txtArea" placeholder="INFORME O(S) MOTIVO(S)" style="width:100%" required ></textarea>';

    $assinatura = formErp::button('Assinar o Termo', null, 'termo()', 'danger');
    $assinatura_rod = "";
} else {
    $ano = date('Y', strtotime($termo['dt_update']));
    $eu = $termo['nome'];
    $rg = $termo['rg'];
    $motivo = $termo['motivo'];

    $assinatura = '<img src="'. $termo['assinatura'] .'" style="width:45%" />';
    $assinatura_rod = "Documento assinado digitalmente por ".strtoupper($eu).". <br>Data: ". date('d/m/Y H:i:s', strtotime($termo['dt_update'])) ." | IP: ". $termo['IP'];
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
    <form id="form" action="<?= HOME_URI ?>/apd/capAssinatura" method="POST">
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
            <button class="btn btn-warning" onclick="impr()">IMPRIMIR</button>
        </div>
    </div>
    <div class="fieldTop">
        1.3 - TERMO DE RECUSA – Atendimento Educacional Especializado - Protocolo <?php echo $id_protocolo ?><br>
    </div>

    <div class="fieldTop" style="text-align: left;font-size: 80%;">
        ALUN<?php echo strtoupper(toolErp::sexoArt($aluno['sexo'])) ?>: <span><?php echo $aluno['n_pessoa'] ?></span><br><br>
        <p>CID: <span><?php echo $cid ?></span></p>
        <p>EMEF: <span><?php echo toolErp::n_inst() ?></span> ANO: <span><?php echo $ano ?></span></p>
    </div>
    <div style="line-height:2em">
        Eu, <span class="compl"><?php echo $eu ?></span> portador(a) do RG <span class="compl"><?php echo $rg ?></span>,
        informo que <?php echo concord::meu($aluno['id_pessoa']) ?> filh<?php echo $oa ?> <b><?php echo $aluno['n_pessoa'] ?></b> não frequentará o AEE - Atendimento Educacional Especializado, pelos motivos abaixo:
        <p><span class="compl"><?php echo $motivo ?></span></p>
    </div>

    <div style="font-weight: bold;margin: 40px 0;">
        <p>Declaro também que estou ciente do termo do ECA abaixo descrito.</p>
        <p>Importante ressaltar que o ECA (Estatuto da Criança e do Adolescente) determina que é dever dos pais ou responsáveis legais, oferecer todas as oportunidades e desenvolvimento pleno de seus filhos, no que diz respeito à educação</p>
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
    '1[tipo]' => 'R',
    'status' => 9
])
. formErp::hiddenToken('protTermo');

?>
</form>
</div>
<script>
    function impr(){
        this.print();
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

        $('#myModal').modal('show');
        return true;
    }
    function salvar(){
        <?php if (empty($termo)) { ?>
        if (!termo()){
            return false;
        }
        <?php } ?>
        document.getElementById('form').submit();
    }
    function voltar(){
        window.location.href = '<?= HOME_URI ?>/apd/alunoNovoList';
    }
    jQuery(function(){
        $('#enviar').attr('disabled', true);
    })
</script>