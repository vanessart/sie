<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of infantil
 *
 * @author marco
 */
class infantil {

    public static function setSerie($data) {
        if (strripos($data, '-')) {
            $data = str_replace('-', '', $data);
        }elseif (strripos($data, '/')) {
            $dt = explode('/', $data);
          echo  $data = $dt[2].$dt[1].$dt[0];
        }
        $d_b = ((date("Y") - 1) * 10000) + 331;
        $d_1m = ((date("Y") - 2) * 10000) + 331;
        $d_2m = ((date("Y") - 3) * 10000) + 331;
        $d_3m = ((date("Y") - 4) * 10000) + 331;
        $d_1p = ((date("Y") - 5) * 10000) + 331;
        $d_2p = ((date("Y") - 6) * 10000) + 331;

        if ($data > $d_b) {
            return [21, 'Berçário'];
        } elseif ($data > $d_1m) {
            return [22, '1ª Fase - Maternal'];
        } elseif ($data > $d_2m) {
            return [23, '2ª Fase - Maternal'];
        } elseif ($data > $d_3m) {
            return [24, '3ª Fase - Maternal'];
        } elseif ($data > $d_1p) {
            return [19, '1ª Fase - Pré'];
        } elseif ($data > $d_2p) {
            return [20, '2ª Fase - Pré'];
        } else {
            return [''];
        }
    }

}
