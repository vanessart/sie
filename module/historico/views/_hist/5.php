<?php
if (!defined('ABSPATH'))
    exit;
$h = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
$anoCompleto = @$_SESSION['TMP']['anoCompleto'][$id_pessoa];
$id_ciclo = @$_SESSION['TMP']['id_ciclo'][$id_pessoa];
if ($model->db->tokenCheck('textoPadrao') || empty($h['certificado'])) {
    $turma = $model->frequenteTexto($id_pessoa, $id_ciclo);
    if ($turma) {
        $anoEnsinoAtual = $turma['n_ciclo'] . ' do ' . $turma['n_curso'];
        $ano = $turma['ano'];
        if ($anoCompleto == 'x' || empty($turma['id_final'])) {
            $concluiu = 'está cursando';
            $apto = 'a continuar';
            $anoEnsinoProximo = $turma['n_ciclo'] . ' do ' . $turma['n_curso'];
        } else {
            if (empty($id_ciclo)) {
                $id_ciclo = $turma['fk_id_ciclo'];
            }
            $concluiu = 'concluiu';
            $apto = 'ao prosseguimento dos estudos';
            $anoEnsinoProximo = $model->proxCiclo($id_ciclo);
        }
        $id_ciclo = $turma['fk_id_ciclo'];
    } elseif ($anoCompleto && $id_ciclo) {
        $ant = $model->anosAnteriores($dados->dadosPessoais);
        if (!empty($ant[$id_ciclo]['ano'])) {
            $ano = $ant[$id_ciclo]['ano'];
        } else {
            $ano = '_______________';
        }
        $anoEnsinoAtual = $model->proxCiclo($id_ciclo, 0);
        if ($anoCompleto == 1) {
            $anoEnsinoProximo = $model->proxCiclo($id_ciclo);
            $apto = 'ao prosseguimento dos estudos';
            $concluiu = 'concluiu';
        } else {
            $anoEnsinoProximo = $model->proxCiclo($id_ciclo, 0);
            $concluiu = 'está cursando';
            $apto = 'apto a continuar';
        }
    } else {
        $ant = $model->anosAnteriores($dados->dadosPessoais);
        $id_ciclo = 0;
        if (!empty($ant)) {
            foreach ($ant as $v) {
                if (!empty($v['ano']) && $v['fk_id_ciclo'] > $id_ciclo) {
                    $id_ciclo = $v['fk_id_ciclo'];
                    $ano = $v['ano'];
                }
            }
        }
        if (!empty($id_ciclo)) {
            $anoEnsinoAtual = $model->proxCiclo($id_ciclo, 0);
            $anoEnsinoProximo = $model->proxCiclo($id_ciclo);
            $apto = 'ao prosseguimento dos estudos';
            $concluiu = 'concluiu';
        } else {
            $concluiu = 'concluiu';
            $anoEnsinoAtual = '______________ do _____________';
            $ano = '_______________';
           // $apto = 'ao prosseguimento dos estudos';
            $apto = 'a continuar';
            $anoEnsinoProximo = '_______________ do _______________';
        }
    }

    $apto_a = $h['sexo'] == 'M' ? "apto " : "apta ";
    $no_a = $anoEnsinoProximo == "1ª série do Ensino Médio" ? "na" : "no"; 
    $h = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
    $ins['id_dg'] = $h['id_dg'];
    if (empty($h['obs'])) {
        $ins['obs'] = "Nada consta em seu prontuário que " . toolErp::sexoArt($h['sexo']) . " desabone.";
    }
    $ins['certificado'] = "O(A) Diretor(a) da " . $h['escola'] . ", de acordo com o artigo 24 - Lei Federal 9394/96 certiﬁca que " . $h['nome'] . ", " . $h['tp_doc'] . ": " . $h['rg'] . (!empty($h['rg_dig']) ? '-' . $h['rg_dig'] : '') . " " . $h['rg_oe'] . "; " . $h['rg_uf'] . ";, $concluiu o $anoEnsinoAtual no Ano Letivo de $ano, estando $apto_a $apto $no_a $anoEnsinoProximo.";

    $model->db->ireplace('historico_dados_gerais', $ins, 1);
    $h = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
}
$hidden = [
    'id_pessoa' => $id_pessoa,
    'activeNav' => 5,
    '1[id_dg]' => $h['id_dg']
];
?>
<div class="row">
    <div class="col" style="text-align: right">
        <form id="restor" method="POST">
            <?=
            formErp::hidden($hidden)
            . formErp::hiddenToken('textoPadrao')
            ?>
            <button onclick="if (confirm('Esta ação apagará as alterações. Continuar?')) {
                        restor.submit()
                    }" type="button" class="btn btn-info">
                Atualizar Certificado
            </button>
        </form>
    </div>
</div>
<br />
<form method="POST">
    <p>
        <?= formErp::textarea('1[diario_oficial]', $h['diario_oficial'], '<div style="width: 240px">Publicação no Diário Oficial</div>') ?>
    </p>
    <p>
        <?= formErp::textarea('1[obs]', $h['obs'], '<div style="width: 240px">Observações</div>') ?>
    </p>
    <p>
        <?= formErp::textarea('1[certificado]', $h['certificado'], '<div style="width: 240px">Certificado</div>') ?>
    </p>
    <div style="text-align: center; padding: 40px">
        <?=
        formErp::hidden($hidden)
        . formErp::hiddenToken('historico_dados_gerais', 'ireplace')
        . formErp::button('Salvar')
        ?>
    </div>
</form>