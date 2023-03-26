<h2>Matricular Aluno - modal</h2>

<?php
//echo '<pre>';

//print_r($_SESSION);

//get instancia
$id_inst = $_SESSION['userdata']['id_inst'];
//var_dump($id_inst);

//get id_vaga
$id_vaga = $_POST['id_vaga'];
$_SESSION['id_vaga'] = $id_vaga;
$result_vaga = sql::get('vagas', '*', ['id_vaga' => $id_vaga], 'fetch');
//print_r($result_vaga);

//formaliza data de nascimento
$dt = new DateTime($result_vaga['dt_aluno']);
$data_nascimento = $dt->format('Ymd');
//var_dump($data_nascimento);

//get ciclos da instancia
$sql = "SELECT t3.fk_id_ciclo FROM ge_turmas AS t3 WHERE t3.fk_id_inst = $id_inst GROUP BY t3.fk_id_ciclo";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
//print_r($array);

$i = 1;
$id_ciclo = 0;
foreach ($array as $c => $v) {
    $data = ((date("Y") - $i) * 10000) + 331;
    if($data_nascimento>$data){
        $id_ciclo = $v['fk_id_ciclo'];
        break;
    }
    $i++;
}
//var_dump($id_ciclo);

//obtem o ciclo com base na data de nascimento
//$total_ciclos = count($arr_ciclos);
//$indice = 0;
//for($i=1;$i<=$total_ciclos;$i++){
//    $data = ((date("Y") - $i) * 10000) + 331;
//    if($data_nascimento>$data){
//        $id_ciclo = $arr_ciclos[$indice];
//        break;
//    }
//    $indice++;
//}
//var_dump($id_ciclo);

//get turma
//$sql2 = "SELECT t5.id_turma, t5.n_turma, t5.prodesp, t6.ano, t6.semestre
//FROM ge_turmas AS t5 
//inner JOIN ge_periodo_letivo AS t6 ON t6.id_pl = t5.fk_id_pl
//WHERE t5.fk_id_inst = $id_inst AND t5.fk_id_ciclo = $id_ciclo AND t6.at_pl = 1";
//$query2 = pdoSis::getInstance()->query($sql2);
//$result_turmas = $query2->fetchAll(PDO::FETCH_ASSOC);
//var_dump($result_turmas);

if (!empty($id_inst)) {
    $turma = turmas::optionNome( $id_ciclo, NULL, NULL, NULL, NULL, $id_inst );
    echo form::select('id_turma', $turma, 'Turma');
}

/* exit;

  //get cie_escola sieb
  $result_escola = sql::get('ge_escolas', 'cie_escola', ['fk_id_inst' => $id_inst], 'fetch');
  $cie_escola = $result_escola['cie_escola'];
  print_r($cie_escola);
  exit;

  $validar = new validar();


  //normaliza
  $result['sx_aluno'] = ( $result['sx_aluno'] == 'Feminino' ) ? 'F' : 'M' ;

  //valida a certidão de nascimento
  $cn = $result['cn_matricula'];
  $valida_cn = $validar->certidao_nova($cn);
  var_dump($valida_cn);

  //valida cpf
  $valida_cpf = $validar->validar_cpf($result['cpf_resp']);
  var_dump($valida_cpf);

  print_r($result); */

?>

<div  id="resumo-matricula" style="display: none; width: 95%">
    <div class="row-fluid" >
        <div class="col-md-12">
            <h3>Resumo da Matrícula</h3>
        </div>
    </div>
    <div class="row-fluid" >
        <div id="dados-turma" class="col-md-3">Aguarde....</div>
        <div id="dados-vaga" class="col-md-5">Aguarde...</div>
        <div id="dados-coleta" class="col-md-4">Aguarde...</div>
    </div>
    <div class="row-fluid" >
        <div class="col-md-12 text-center">
            <p>&nbsp;</p>
        </div>
    </div>
    <div class="row-fluid" >
        <div class="col-md-12 text-center">
            <p><button style="display: none;" class="btn btn-primary" id="btnConfirmar">Confirmar Matrícula</button></p>
        </div>
    </div>
</div>

<!-- Seta para corrigir conflito de versão -->
<script async type="text/javascript"  crossorigin="anonymous">
    $.noConflict();
    $(document).ready(function ($) {
        console.log('debug...ok');
        $("select[name='id_turma']").change(function () {
            
            $('#resumo-matricula').fadeOut('slow', function(){
                
                $('#btnConfirmar').hide();
                $('#dados-turma').html('Atualizando...');
                $('#dados-vaga').html('Atualizando...');
                $('#dados-coleta').html('Atualizando...');
            });
            
            var id_turma = $(this).val();
            
            if(id_turma==''){
                
                $('#resumo-matricula').fadeIn('slow', function(){
                    $('#dados-turma').html('Nenhum valor informado');
                    $('#dados-vaga').html('');
                    $('#dados-coleta').html('');
                    $('#btnConfirmar').hide();
                });
                
            } else {
                
                $('#resumo-matricula').fadeIn('slow', function(){
                    siebsed.getProdesp(id_turma);
                });

                
            }

        });
        
        
    });
</script>
