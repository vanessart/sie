<?php

class coord {

    public static function habilidades($id_ciclo, $ul, $id_disc = NULL) {
        if (!empty($id_disc)) {
            $id_disc = " and g.fk_id_disc = $id_disc ";
        }
        $fields = "h.n_hab, h.obs_hab, h.qt_hab, h.importante, h.peso, h.id_hab, "
                . " h.ini, h.fim, h.periodo, h.fk_id_comp, h.fk_id_ei, h.ordem, h.cod_hab, "
                . " c.n_comp, c.cod_comp, obs_comp, g.n_gr, g.fk_id_disc, "
                . " g.fk_id_curso, obs_gr ";
        $sql = "select $fields from coord_habilidade h "
                . " join coord_competencia c on c.id_comp = h.fk_id_comp "
                . " join coord_grupo g on g.id_gr = c.fk_id_gr "
                . " where periodo like '%$id_ciclo-$ul%' "
                . $id_disc
                . " order by c.n_comp, h.ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $hab = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $hab;
        
    }

}
