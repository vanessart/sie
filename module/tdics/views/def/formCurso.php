<?php
if (!defined('ABSPATH'))
    exit;

$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
if ($id_curso) {
    $c = sql::get('tdics_curso', '*', ['id_curso' => $id_curso], 'fetch');
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Curso
    </div>
      <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/cursoCad" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_curso]', 'Curso', @$c['n_curso']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-4">
                <?= formErp::input('1[abrev]', 'Sigla', @$c['abrev']) ?>
            </div>
            <div class="col-md-4">
                <?= formErp::input('1[icone]', 'Ícone', @$c['icone']) ?>
            </div>
            <div class="col-md-4">
                <?= formErp::select('1[ativo]', [1 => 'Sim', 2 => 'Não'], 'Ativo', $c['ativo'] ?? 1) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descricao]', @$c['descricao'], 'Descrição', '250', true) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div style="text-align: center; padding: 5px">
                    <?=
                    formErp::hidden([
                        '1[id_curso]' => $id_curso
                    ])
                    . formErp::hiddenToken('tdics_cursoSet')
                    . formErp::button('Salvar')
                    ?>
                </div>
            </div>
        </div>
        <br />
    </form>
</div>