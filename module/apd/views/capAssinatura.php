<?php
if (!defined('ABSPATH'))
    exit;

$id_protocolo = filter_input(INPUT_POST, 'id_protocolo', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$d = filter_input(INPUT_GET, 'd', FILTER_SANITIZE_STRING);
$t = filter_input(INPUT_GET, 't', FILTER_SANITIZE_STRING);

if (empty($d) && empty($t)) {
    $externo = false;
} else { 
    if (empty($d) || empty($t)) {
        toolErp::alert("Dados inválidos");
        exit;
    }

    $id_pessoa = toolErp::decrypt($d);
    if ($id_pessoa === FALSE) {
        toolErp::alert("Dados inválidos");
        exit;
    }

    $id_protocolo = toolErp::decrypt($t);
    if ($id_protocolo === FALSE) {
        toolErp::alert("Dados inválidos");
        exit;
    }

    $externo = true;
}

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

$d = toolErp::encrypt($id_pessoa);
$t = toolErp::encrypt($id_protocolo);
$end = $_SERVER['HTTP_HOST'] . HOME_URI .'/apd/capAssinatura?d='. $d .'&t='. $t;

$sign = new assinaturaDigital();
$sign->bntClear(true, "Limpar", ['class'=>"btn btn-outline-warning"], 'limpa');
$sign->actChange(true, '__change');
$sign->setCSSBox([
    'width' => '70vw',
    'height' => '310px',
]);

$termo = $model->getTermoRecusa($id_protocolo);
if (empty($termo)){
    header("Location: ". HOME_URI ."/apd/alunoNovoList");
}

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

$ano = date('Y', strtotime($termo['dt_update']));
$dt = date('d / m / Y', strtotime($termo['dt_update']));
$eu = $termo['nome'];
$rg = $termo['rg'];
$motivo = $termo['motivo'];

$assinado = !empty($termo['assinatura']);
if ($assinado){
    $assinatura = '<img src="'. $termo['assinatura'] .'" style="width:45%" />';
    $assinatura_rod = "Documento assinado digitalmente por ".strtoupper($eu).". <br>Data: ". date('d/m/Y H:i:s', strtotime($termo['dt_update'])) ." | IP: ". $termo['IP'];
    $header = '';
    $texto = "Assinatura do Termo de Recusa - Protocolo $id_protocolo";
    $textoQr = 'Visualizar termo em dispositivo móvel';
} else {
    $assinatura = formErp::button("Assinar neste dispositivo", null, "modalCap()", "success",null,null,null,null,null,"id='imp'");
    $assinatura_rod = '';
    $header = toolErp::linha(['height'=>'3px']);
    $texto = "Aguardando Assinatura do Termo de Recusa - Protocolo $id_protocolo";
    $textoQr = 'Assinar o termo em dispositivo móvel';
}

$oa = concord::oa($aluno['id_pessoa']);
?>
<style type="text/css">
    .compl {
        border-bottom: 1px solid #000;
        margin: auto 10px;
    }
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
        .navbar, .big, .small, .medium, .btnImprime, #enviar, #imp{
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
    <form id="form" action="" method="POST">
    <br>
    <?= $header ?>
    <?= $texto ?>
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
    <br><br>
    <div class="card mb-2">
        <div class="fieldTop" style="text-align: left;font-size: 80%;margin-left:4em;">
            ALUN<?php echo strtoupper(toolErp::sexoArt($aluno['sexo'])) ?>: <span><?php echo $aluno['n_pessoa'] ?></span><br><br>
            <p>CID: <span><?php echo $cid ?></span></p>
            <p>EMEF: <span><?php echo toolErp::n_inst() ?></span> ANO: <span><?php echo $ano ?></span></p>
        </div>
        <div style="line-height:2em; margin-left:4em;">
            Eu, <span class="compl"><?php echo $eu ?></span> portador(a) do RG <span class="compl"><?php echo $rg ?></span>,
            informo que <?php echo concord::meu($aluno['id_pessoa']) ?> filh<?php echo $oa ?> <b><?php echo $aluno['n_pessoa'] ?></b> não frequentará o AEE - Atendimento Educacional Especializado, pelos motivos abaixo:
            <p><span class="compl"><?php echo $motivo ?></span></p>
        </div>

        <div style="font-weight: bold;margin-left:4em;">
            <p>Declaro também que estou ciente do termo do ECA abaixo descrito.</p>
            <p>Importante ressaltar que o ECA (Estatuto da Criança e do Adolescente) determina que é dever dos pais ou responsáveis legais, oferecer todas as oportunidades e desenvolvimento pleno de seus filhos, no que diz respeito à educação</p>
        </div>
        <div style="font-weight: bold;margin-right:4em;text-align: right;">
            <p>Data: <?php echo $dt ?></p>
        </div>
    </div>
    <div class="fieldTop" style="text-align: left;margin-left:4em;">
        Assinatura do Responsável:
    </div>
    <div style="text-align: center;">
        <div style="margin: 0 auto;display: inline-block;vertical-align: middle;<?php if (empty($externo)) { ?>width: 49%;<?php } ?>">
            
            <div style="text-align:center;margin-top: 30px;">
                <?php if (!empty($externo) && empty($assinado)) {
                    $sign->gerar();
                    echo formErp::button("Capturar Assinatura", null, "salvar()", "success", null, null, null, null, null, ' id="enviar" ');
                } else { ?>
                    <div><?php echo $assinatura ?></div>
                <?php } ?>
            </div>
            <!-- <p style="width: 350px;margin: 0 auto;border-top: 1px solid #000;">Ass. do responsável</p> -->
        </div>
        <?php if (empty($externo)) { ?>
        <div style="margin: 0 auto;display: inline-block;vertical-align: middle;width: 49%;" id="enviar">
            <?= $textoQr ?><br>
            <img src="<?= HOME_URI ?>/app/code/php/qr_img.php?d=<?= urlencode($end) ?>.PNG" width="240" height="240"/>
        </div>
        <?php } ?>
    </div>
    <div class="doc-assinat-dig">
        <?php echo $assinatura_rod ?>
    </div>

    <input type="hidden" name="id_protocolo" id="id_protocolo" value="<?php echo $id_protocolo ?>" />
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?php echo $id_pessoa ?>" />

<?php
echo formErp::hidden([
    '1[fk_id_protocolo]' => $id_protocolo,
    '1[fk_id_pessoa]' => !empty($externo) ? -1 : toolErp::id_pessoa(),
    '1[fk_id_pessoa_aluno]' => $id_pessoa,
    '1[tipo]' => 'R',
    'status' => 9,
])
. formErp::hiddenToken('protTermoAssinar');

if (empty($externo)) {
    toolErp::modalInicio();
    $sign->gerar();
    echo formErp::button("Capturar Assinatura", null, "salvar()", "success", null, null, null, null, null, ' id="enviar" ');
    toolErp::modalFim();
}
?>
</form>
</div>
<script>
    <?php if (empty($assinado)) { ?>
    setInterval(function () {
        fetch('<?= HOME_URI ?>/protocolo/termoAssinadoGet?recusa=1&id_protocolo=<?= $id_protocolo ?>')
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
    function limpa(e){
        e.preventDefault();
        $('#enviar').attr('disabled', true);
    }
    function __change(){
        $('#enviar').attr('disabled', false);
    }
    function voltar(){
        window.location.href = '<?= HOME_URI ?>/apd/alunoNovoList';
    }
    function salvar(){
        if (window.opener) {
            document.getElementById('form').submit();
            setTimeout(function(){
                window.opener.location.reload(true);
            }, 300);
        } else {
            document.getElementById('form').submit();
        }
    }
    function modalCap(){
        $('#myModal').modal('show');
    }
    jQuery(function(){
        $('#enviar').attr('disabled', true);
    })
</script>