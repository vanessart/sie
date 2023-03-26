<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of predio
 *
 * @author marco
 */
class predio {
    
    public static function get($id_predio, $field = 'id_predio'){
        
        $predio = sql::get('predio', '*', [$field=>$id_predio], 'fetch');
     
        return $predio;
    }
    
    public static function salas($id_predio, $fields = '*'){
        $salas = sql::get(['salas','tipo_sala'], $fields, ['fk_id_predio' => $id_predio]);
        
        return $salas;
    }
    /**
     * devolve predios da instancia
     * @param type $id_inst
     * @return type
     */
    public static function instPredio($id_inst = NULL){
        if(empty($id_inst)){
            $id_inst = $_SESSION['userdata']['id_inst'];
        }
        $predios=sql::get(['instancia_predio','predio'], '*', ['fk_id_inst' => $id_inst]);
        
        return $predios;
    }
}
