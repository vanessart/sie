<?php
if (!defined('ABSPATH'))
    exit;
$prof = rhImport::htpc();
$ec = sqlErp::get('ge_escolas', 'cie_escola id_cie, fk_id_inst n_cie ');
$escCie = toolErp::idName($ec);
 ##################            
        ?>
        <pre>   
            <?php
            print_r($prof);
            ?>
        </pre>
        <?php
###################
foreach ($prof as $v) {
    $dia = $v['diaSemana'] + 1;
    $p = [
        'T' => 'Tarde',
        'M' => 'Manhã',
        'N' => 'Noite'
    ];

    $sql = "select rm from ge_prof_esc p "
            . " join ge_escolas e on e.fk_id_inst = p.fk_id_inst "
            . " where p.rm = '" . intval($v['rm']) . "' "
            . " and e.cie_escola = '" . intval($v['cie']) . "' ";
    $query = pdoSis::getInstance()->query($sql);
    $cadastrado = $query->fetch(PDO::FETCH_ASSOC);
    if ($cadastrado) {
        $sql = "UPDATE ge_prof_esc p "
                . " join ge_escolas e on e.fk_id_inst = p.fk_id_inst "
                . " and p.rm = '" . intval($v['rm']) . "' "
                . " and e.cie_escola = '" . intval($v['cie']) . "' "
                . " SET p.hac_dia= $dia, "
                . " p.hac_periodo='" . $p[$v['periodo']] . "', "
                . " p.nao_hac=0, "
                . " p.cit = 1 ";
        try {
            $query = pdoSis::getInstance()->query($sql);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    } elseif (!empty($escCie[intval($v['cie'])])) {
        $ins['rm'] = intval($v['rm']);
        $ins['fk_id_inst'] = $escCie[intval($v['cie'])];
        $ins['hac_dia'] = $dia;
        $ins['hac_periodo'] = $p[$v['periodo']];
        $ins['cit'] = 1;
        $model->db->insert('ge_prof_esc', $ins, 1);
    } else {
        echo 'erro: ' . intval($v['rm']);
        $model->db->insert('ge_aloca_prof_erro', 'HTPC do rm ' . intval($v['rm']) . ' não cadastrado', 1);
    }
}