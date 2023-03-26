<?php

class adm {
    
    public static function getCursos() {
        return sql::get('cursos');
    }
    
        public static function seriacoes($id) {
         return sql::get(['serie','grade'], '*', ['serie.fk_id_cur' => $id]);
    }
}
