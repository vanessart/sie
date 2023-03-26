<?php
if (!defined('ABSPATH'))
    exit;

$n_campanha = $model->campanha();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pl = $model->campanha('id_pl');
$alunos = array();
$turmas = array();
$escolas = array();
$instancias = ng_escolas::gets();

css::switchButton();
if (!empty($id_turma)) {
   $alunos = $model->getAlunosOdonto($id_turma,$id_pl,1); 
}
if (empty($habAlu)) {
    $habAlu = [];
}

if (!empty($id_inst)) {
    $turmas = $model->getTurmas(toolErp::id_pessoa(),$id_inst); 
}
if (!empty($instancias)) {
    foreach ($instancias as $v) {
        $escolas[$v['id_inst']] = $v['n_inst'];
    }
}
echo toolErp::divAlert('warning','Para salvar as informações clique no botão SALVAR ao final da página');
?>
<div class="body">
    <br><br>
    <div class="row">
        <div class="col" style="font-weight:bold; font-size:20px; text-align: center;">
            Saúde Bucal - PSE - <?= $n_campanha ?>
            <br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-5">
            <?= formErp::select('id_inst',$escolas,'Escolas',$id_inst,1,['id_inst' => $id_inst]) ?>
        </div>
        <?php 
        if (!empty($id_inst)) {?>
            <div class="col-2">
                <?= formErp::select('id_turma',$turmas,'Turma',$id_turma,1,['id_inst' => $id_inst]) ?>
            </div>
           <?php  
        }
        ?>
    </div>
    <form id="formEnvia" enctype="multipart/form-data" method="POST">
        <table style="background-color: white" class="table table-bordered table-hover sortable" id="tabela">
            <thead>
                <tr>
                    <th class="table-active" style="cursor: pointer">
                    Aluno
                    </th>
                    <th class="table-active" style="cursor: pointer">
                    Data Atend
                    </th>
                    <th class="table-active" style="cursor: pointer">
                    Palestra (orientação odontologica)
                    </th>
                    <th class="table-active" style="cursor: pointer">
                    Avaliação Odontológica
                    </th>
                    <th class="table-active" style="cursor: pointer">
                    Necessita de Tratamento dentário
                    </th>
                    <th class="table-active" style="cursor: pointer">
                    Tratamento dentário realizado
                    </th>
                </tr>
            </thead>
            <tr>
                <td ></td>
                <td ></td>
                <td align="center">
                    <label class="btn btn-outline-info" onclick="checkTodos(this, 'orientacao_odonto')" campo="n" >Todos</label>
                </td>
                <td align="center">
                    <label class="btn btn-outline-info" onclick="checkTodos(this, 'avalia_odonto')" campo='n'>Todos</label>
                </td>
                <td align="center">
                    <label class="btn btn-outline-info" onclick="checkTodos(this, 'necssita_tratamento')" campo='n'>Todos</label>
                </td>
                <td align="center">
                    <label class="btn btn-outline-info" onclick="checkTodos(this, 'realizou_tratamento')" campo='n'>Todos</label>
                </td>
            </tr>
            <tbody id="myTable">
                <?php
                foreach ($alunos as $v) {
                    if (!empty($v['dt_avaliacao'])) {
                       $data = substr($v['dt_avaliacao'], 0, 10);
                    }else{
                        $data = date('Y-m-d');
                    }
                    if (!empty($v['id_atend_odonto'])) {?>
                       <input type="hidden" name="<?= $v['id_pessoa'] ?>[id_atend_odonto]" value="<?= $v['id_atend_odonto'] ?>"> 
                       <?php
                    }?>
                    <tr>
                        <td style="padding: 15px">
                            <p style="font-size:22px">
                                <?= toolErp::abreviaLogradouro($v['n_pessoa']) ?>
                            </p>  
                        </td>
                        <td style="text-align: right; padding: 5px; width: 10px">
                            <?php
                                echo formErp::input($v['id_pessoa'].'[dt_avaliacao]', NULL, $data, ' required', null, 'date');
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            echo '<input campo="orientacao_odonto" name="' . $v['id_pessoa'].'[orientacao_odonto]" ' . ($v['orientacao_odonto'] ? 'checked' : null) . ' id="switch-shadow-' . $v['id_pessoa'].'-orientacao_odonto" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-' . $v['id_pessoa'].'-orientacao_odonto"></label>';
                                ?>
                        </td>
                        <td align="center">
                            <?php
                            echo '<input campo="avalia_odonto" name="' . $v['id_pessoa'].'[avalia_odonto]" ' . ($v['avalia_odonto'] ? 'checked' : null) . ' id="switch-shadow-' . $v['id_pessoa'].'-avalia_odonto" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-' . $v['id_pessoa'].'-avalia_odonto"></label>';
                                ?>
                        </td>
                        <td align="center">
                            <?php
                            echo '<input campo="necssita_tratamento" name="' . $v['id_pessoa'].'[necssita_tratamento]" ' . ($v['necssita_tratamento'] ? 'checked' : null) . ' id="switch-shadow-' . $v['id_pessoa'].'-necssita_tratamento" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-' . $v['id_pessoa'].'-necssita_tratamento"></label>';
                                ?>
                        </td>
                        <td align="center">
                            <?php
                            echo '<input campo="realizou_tratamento" name="' . $v['id_pessoa'].'[realizou_tratamento]" ' . ($v['realizou_tratamento'] ? 'checked' : null) . ' id="switch-shadow-' . $v['id_pessoa'].'-realizou_tratamento" class="switch switch--shadow" type="checkbox"><label for="switch-shadow-' . $v['id_pessoa'].'-realizou_tratamento"></label>';
                                ?>
                        </td>
                    </tr>
                    <?php
                }?>
            </tbody>
        </table>
        <br><br>
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    'id_turma' => $id_turma,
                    'id_inst' => $id_inst,
                    'id_pl' => $id_pl,
                ])
                . formErp::hiddenToken('cadOdonto')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>
    </form>
</div>
<script>
    function checkTodos(e, campo) {
        debugger;
      // Seleciona todos os elementos do tipo checkbox que possuem o atributo verde
      const checkboxes = document.querySelectorAll('input[type="checkbox"][campo="'+campo+'"]');

      // Marca todos os checkboxes
      checkboxes.forEach((checkbox) => {
        checkbox.checked = (e.getAttribute('campo') == 'n');
      });

      e.setAttribute('campo',(e.getAttribute('campo') == 'n' ? 's' : 'n'));
    }
</script>