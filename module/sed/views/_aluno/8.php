<?php
if (!defined('ABSPATH'))
    exit;
$telAluno = ng_aluno::telefone($id_pessoa);
$resp = $aluno->responsaveis();
$geral = $aluno->dadosPessoais;
$readonly = 'readonly';
unset($resp['tel']);
foreach ($resp as $key) {
    $newResp = $key;
}
if ($geral['sexo'] == 'F') {
    $filho = 'FILHA';
} else {
    $filho = "FILHO";
}
?>
<form method="POST">
    <div class="border">
        <div class="row">
            <div class="col" align="center">
                <p>
                    <?=
                    $geral['n_pessoa']
                    . '  -  '
                    . $filho
                    ?></p>

            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <?= formErp::input('1[email]', "Email", $geral['email'], $readonly) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[emailgoogle]', "Email Institucional", $geral['emailgoogle'], $readonly) ?>   
            </div>
        </div><br>
        <?php for ($i = 0; $i <= 2; $i++): ?>
            <div class="row">

                <div class="col-3">
                    <?= formErp::select("aluno[$i][n_tt]", ['', 'Celular', 'Residencial', 'Comercial', 'Recados'], "Tipo") ?>
                </div>

                <div class="col-3">    
                    <?= formErp::input("aluno[$i][tel]", "Número " . ($i + 1), '', "onkeyup='mascara(this, mtel)'") ?> <br>
                </div>

                <div class="col-6">
                    <?= formErp::input("aluno[$i][obs]", 'Obs', $geral['obs'], null) ?>
                </div>

            </div>
        <?php endfor; ?>

        <?= "<pre>" ?>
        <?php print_r($_POST) ?>
        <?= "</pre>" ?>


    </div><br>


    <?php //=====================================//  ?>

    <?php for ($i = 0; $i <= 1; $i++): ?>
        <?php if (!empty($resp[$i])): ?>
            <div class="border">
                <div class="col" align="center">
                    <p> <?= $resp[$i]['n_pessoa'] . ' - Responsavel' ?> </p>
                </div>
                <div class="row">
                    <div class="col-6">
                        <?= formErp::input('1[cpf]', "CPF", $resp[$i]['cpf'], $readonly) ?><br>
                    </div>
                    <div class="col-5">
                        <?= formErp::input('1[email]', "Email", $resp[$i]['email'], $readonly) ?>
                    </div><br>
                </div>

                <div class="row">
                    <div class="col-3">
                        <?= formErp::select('1[n_tt]', ['', 'Celular', 'Residencial', 'Comercial', 'Recados'], "Tipo") ?>
                    </div>

                    <div class="col-4">
                        <?= formErp::input('1[tel]', "Número", '', "onkeyup='mascara(this, mtel)'") ?>
                    </div>
                </div> <br>  
            </div><br>
        <?php else: ?>
            <div class="border" align="center">
                <b><p>Não há responsável cadastrado no sistema referente a esse aluno</p></b>

            </div><br>

        <?php endif; ?>
    <?php endfor; ?>


    <div style="text-align: center">
        <?=
        formErp::hiddenToken('contatoSet')
        . formErp::hidden(['id_pessoa' => $id_pessoa,
            '1[id_pessoa]' => $id_pessoa,
            'activeNav' => 8
        ])
        . formErp::button('Enviar')
        ?>
    </div>

    <br />
</form>
<?php
javaScript::telMascara();
?>