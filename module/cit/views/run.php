<?php
if (!defined('ABSPATH'))
    exit;

/**
  $cie = 444091;

  $turmas = rest::relacaoClasses($cie, 2022);

  foreach ($turmas['outClasses'] as $v) {
  $ins['outNumClasse'] = $v['outNumClasse'];
  $ins['outCodTipoEnsino'] = $v['outCodTipoEnsino'];
  $ins['outDescTipoEnsino'] = $v['outDescTipoEnsino'];
  $ins['outCodSerieAno'] = $v['outCodSerieAno'];
  $ins['outTurma'] = $v['outTurma'];
  $ins['outDescricaoTurno'] = $v['outDescricaoTurno'];
  $ins['cie'] = $cie;
  $model->db->insert('itb_turmas', $ins, 1);
  }
 * 
 */
/**
  $turmas = sql::get('itb_turmas');
  foreach ($turmas as $v) {
  $a = rest::formacaoClasse($v['outNumClasse']);
  foreach ($a['outAlunos'] as $y) {
  $ins['outNumRA'] = @$y['outNumRA'];
  $ins['outDigitoRA'] = @$y['outDigitoRA'];
  $ins['outSiglaUFRA'] = @$y['outSiglaUFRA'];
  $ins['outNomeAluno'] = @$y['outNomeAluno'];
  $ins['outNumAluno'] = @$y['outNumAluno'];
  $ins['outDataNascimento'] = @$y['outDataNascimento'];
  $ins['outDescSitMatricula'] = @$y['outDescSitMatricula'];
  $ins['turma'] = $v['outNumClasse'];
  $model->db->insert('itb_alunos', $ins, 1);
  $mongo = new mongoCrude('Ibt');
  $mongo->insert('formTurma', $a);
  }
  }
 * 
 */
/**
$al = sql::get('itb_alunos');
foreach ($al as $v) {
    $d = rest::exibirFichaAluno($v['outNumRA'], $v['outSiglaUFRA']);
 
    $mongo = new mongoCrude('Ibt');
    $mongo->insert('alunos', $d);
    $y = $d['outEnderecoResidencial'];
    $ins['ra'] = $v['outNumRA'];
    $ins['ra_uf'] = $v['outSiglaUFRA'];
    $ins['outLogradouro'] = @$y['outLogradouro'];
    $ins['outNumero'] = @$y['outNumero'];
    $ins['outComplemento'] = @$y['outComplemento'];
    $ins['outBairro'] = @$y['outBairro'];
    $ins['outNomeCidade'] = @$y['outNomeCidade'];
    $ins['outUFCidade'] = @$y['outUFCidade'];
    $ins['outLatitude'] = @$y['outLatitude'];
    $ins['outLongitude'] = @$y['outLongitude'];
    $ins['outCep'] = @$y['outCep'];
    $model->db->insert('itb_endereco', $ins, 1);
}
 * 
 */

exit;
$mongo = new mongoCrude('Gdae');

$d = $mongo->query('ExibirFichaAluno', ['outIrmaos' => ['$ne' => null]]);

foreach ($d as $v) {
    $v = (array) $v;
    foreach ($v['outIrmaos'] as $i) {
        $i = (array) $i;
        $ins['ra_pessoa'] = $v['inNumRA'];
        $ins['ra_dig_pessoa'] = $v['inDigitoRA'];
        $ins['ra_uf_pessoa'] = $v['inSiglaUFRA'];
        $ins['ra_irmao'] = $i['outNumRA'];
        $ins['ra_dig_irmao'] = $i['outDigitoRA'];
        $ins['ra_uf_irmao'] = $i['outSiglaUFRA'];

        $model->db->insert('aaa_irmao', $ins, 1, 1);
    }
}

exit();

$sql = "SELECT id_pessoa, p.ra, p.ra_dig, p.ra_uf FROM pessoa p "
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_tas = 0 "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 ";
// . " LIMIT 0, 2000 ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    $dados = rest::exibirFichaAluno($v['ra'], $v['ra_uf']);
    if (!empty($dados['outIrmaos'])) {
        foreach ($dados['outIrmaos'] as $i) {
            $ins['id_pessoa'] = $v['id_pessoa'];
            $ins['ra_pessoa'] = $v['ra'];
            $ins['ra_dig_pessoa'] = $v['ra_dig'];
            $ins['ra_uf_pessoa'] = $v['ra_uf'];
            $ins['ra_irmao'] = $i['outNumRA'];
            $ins['ra_dig_irmao'] = $i['outDigitoRA'];
            $ins['ra_uf_irmao'] = $i['outSiglaUFRA'];
            $model->db->insert('aaa_irmao', $ins, 1, 1);
        }
    }
}
?>
<div class="body">

</div>
