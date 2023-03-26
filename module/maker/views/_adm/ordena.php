<?php
if (!defined('ABSPATH'))
    exit;
if (in_array(tool::id_pessoa(), [1, 5])) {
    foreach ($polos as $k => $v) {
        $pEsc = $v['escolas'][0];
        unset($v['escolas'][0]);
        $ctt = 2;
        if (!empty($dados['rede'])) {
            $graf = 0;
            foreach ($dados['rede'] as $periodo => $ci) {
                $ctt++;
                foreach ($ci as $ciclo => $stt) {
                    foreach ($stt as $status => $q) {
                        $graf += intval(@$dados['esc'][$pEsc['id_inst']][$periodo][$ciclo][$status]);
                        $ctt++;
                        @$total[$periodo][$ciclo][$status] += intval(@$dados['esc'][$pEsc['id_inst']][$periodo][$ciclo][$status]);
                    }
                }
            }
        }
        ################################################ id ###########################
        @$bar = ($graf / (intval($pEsc['cota_m']) + intval($pEsc['cota_t']) )) * 100;
       
        if (!empty($v['escolas'])) {
            foreach ($v['escolas'] as $e) {
                    if (!empty($dados['rede'])) {
                        $graf = 0;
                        foreach ($dados['rede'] as $periodo => $ci) {
                            foreach ($ci as $ciclo => $stt) {
                                foreach ($stt as $status => $q) {
                                    $graf += intval(@$dados['esc'][$e['id_inst']][$periodo][$ciclo][$status]);
                                }
                            }
                        }
                    }
                    ################################## id #################################
                    @$bar = ($graf / (intval($e['cota_m']) + intval($e['cota_t']) )) * 100;
            }
        }
    }
}
?>

