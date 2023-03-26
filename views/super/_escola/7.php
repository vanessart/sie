<?php
alunos::AlunosAutocomplete(NULL, "p.id_pessoa, p.n_pessoa");
?>
<div class="row">
    <div class="col-sm-6">
        <?php echo formulario::input('1[gmail_nome]', 'Nome do Aluno:', NULL, @$campos['gmail_nome'], ' id="busca" onkeypress="complete()" ') ?>
    </div>
    <div class="col-sm-2">
        <?php echo formulario::input('1[gmail_rse]', 'RSE:', NULL, @$campos['gmail_rse'], ' id="rse" required ') ?>
    </div>
    <div class="col-sm-4">
        <?php
        $optE = [
            'Novo E-mail '=>'Novo E-mail ',
            'Resetar Senha'=>'Resetar Senha',
            'Bloqueio'=>'Bloqueio',
            'Outros'=>'Outros'
        ];
        echo form::select('1[gmail_status]', $optE, 'Solicitação', @$campos['gmail_status']);
        ?>
    </div>
</div>
<br />
<div class="row">
        <div class="col-sm-12">
            <?php echo form::input('1[gmail]', 'E-mail', @$campos['gmail']) ?>
        </div>
    </div>