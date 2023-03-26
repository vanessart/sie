<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pwa
 *
 * @author marco
 */
class pwa {

    public static function indexDB($db = 'diario') {
        $dbVersion = 4;
        if ($db == 'diario') {
            ?>
            var db = new Dexie("diario");
            db.version(<?= $dbVersion ?>).stores({
            userSet: 'id_pessoa,n_pessoa,sexo,cpf,email',
            turma: 'id_t_d,id_turma,n_turma,id_disc,n_disc,id_pl,abrev_disc,id_inst,n_inst,id_cur,id_ciclo,un_letiva,qt_letiva,atual_letiva,sg_letiva,aulasDia',
            aluno: 'id_ta,id_pessoa,n_pessoa,sexo,chamada,n_sit,id_turma',
            chamada: 'id_tdda,id_turma,id_disc,data,aula,status,atual_letiva,time_stamp',
            });
            <?php
        }
    }

}
