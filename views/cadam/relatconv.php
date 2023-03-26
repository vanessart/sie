<?php
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="fieldBody">
    <div class="fieldTop">
        Relatório - Convocados
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-3">
            <?php
            for ($c = 1; $c <= 12; $c++) {
                $options[date("Y") . '-' . str_pad($c, 2, "0", STR_PAD_LEFT) . '-'] = data::mes($c);
            }

            echo formulario::select('mes', $options, 'Mês', NULL, 1);
            ?> 
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($mes)) {
        $sql = "SELECT "
                . " p.n_pessoa, p.cpf, cs.*, cc.cad_pmb, cc.contato, cc.id_cad, "
                . " i.n_inst "
                . " FROM `cadam_convoca_sup` cs "
                . " join cadam_cadastro cc on cc.id_cad = cs.fk_id_cad "
                . " join pessoa p on p.id_pessoa = cc.fk_id_pessoa "
                . " join instancia i on i.id_inst = cs.fk_id_inst "
                . " where dt_cs like '$mes%' "
                . " ORDER BY `cs`.`dt_cs` DESC ";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();

        $opt = [
            1 => 'Contactado com sucesso e aceitou as aulas',
            2 => 'Não foi possível Contactar',
            3 => 'Já está alocado ou recusa justificada',
            4 => 'Contactado com sucesso, mas recusou as aulas'
        ];
        foreach ($array as $k => $v) {
            $array[$k]['just'] = $opt[$v['justifica']];
            $v['mes'] = $mes;
            $array[$k]['ac'] = formulario::submit('Acessar', NULL, $v);
            $array[$k]['d'] = data::converteBr(substr(@$v['dt_hora'], 0, 10));
            $array[$k]['h'] = substr(@$v['dt_hora'], 10);
        }

        $form['array'] = $array;
        $form['fields'] = [
            'Contrato' => 'cad_pmb',
            'Escola' => 'n_inst',
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            'Justificativa' => 'just',
            'Data escola' => 'dt_cs',
            'Data Atendimento' => 'd',
            'Hora Atendimento' => 'h',
            '||1' => 'ac'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>

<?php
$id_cad = filter_input(INPUT_POST, 'id_cad', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_cad)) {
    tool::modalInicio();
    ?>
    <pre>
        <?php
        print_r($_POST)
        ?>
    </pre>
    <?php
    tool::modalFim();
}
?>