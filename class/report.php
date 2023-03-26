<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of report
 *
 * @author mc
 */
class report {

    /**
     * inclui o caminho para gerar formulario
     * @param type $form array [array, fields, titulo=null]
     */
    public static function simple($form) {
        require ABSPATH . '/includes/views/report/simples.php';
    }

    /**
     * 
     * @param type $quant quantidade de tuplas por vez
     * @param type $conta Quantidade de tuplas da tabela
     * @param type $hidden input oculto
     * @param type $buttons quantidade de botoes (1,2,3,4) vão aparecer
     * @return type o valor inicial do limit 
     */
    public static function pagination($quant, $conta, $hidden = NULL, $buttons = 10) {
        require ABSPATH . '/includes/views/report/pagination.php';

        return $pag;
    }

    /**
     * inclui o caminho para gerar formulario com Apagar e Acessar
     *  criar variáveis "apagar" e "acessar" no $form['fields']
     * Atenção!!! Voce só pode ter 1 "id_" no array
     * @param type $form array [array, fields, titulo=null]
     */
    public static function forms($form, $table = NULL, $hidden = NULL, $location = NULL, $target = NULL, $msg = NULL, $buttonName = NULL) {
        require ABSPATH . '/includes/views/report/forms.php';
    }

    public static function vertical($form) {
        require ABSPATH . '/includes/views/report/relatVert.php';
    }

    /*     * cria uma tabela de cadastro
     * Ex:
     * $form[] = [[4,'Nome do Aluno:','teste']];
     * $form[] = [[1,'RSE:','teste'],[3,'RA','teste']];
     * $form[]= NULL;  Fecha uma tabela, quebra página e abre outra
     * total somatória máxima é 8  
     */

    public static function cad($form) {
        require ABSPATH . '/includes/views/report/cad.php';
    }

    public static function includeView($view) {
        return ABSPATH . '/views/relat/' . $view . '.php';
    }

    /**
     *     
      $abas[1] = [ 'nome' => "", 'ativo' => 1, 'hidden' => 'array', 'link' => "", ];
     * @param type $abas
     */
    public static function abas($abas, $btn = NULL, $aba = NULL) {
        if (empty($btn)) {
            $btn = ["secondary", "primary", "warning"];
        }
        if (empty($aba)) {
            $aba = 'activeNav';
        }
        include ABSPATH . '/includes/views/tab/abas.php';

        return $activeNav;
    }

    /**
     *     
      $abas[1] = [ 'nome' => "", 'ativo' => 1, 'hidden' => 'array', 'link' => "", ];
     * @param type $abas
     */
    public static function abasbs($abas, $btn = NULL, $aba = NULL) {
        if (empty($btn)) {
            $btn = ["secondary", "primary", "warning"];
        }
        if (empty($aba)) {
            $aba = 'activeNav';
        }
        include ABSPATH . '/includes/views/tab/abasbs.php';

        return $activeNav;
    }

    /**
     * inclui o caminho select segmento e turma
     * devolve $id_pl e $id_turma
     */
    public static function segmTurma($hidden) {
        require ABSPATH . '/includes/views/form/segturma.php';

        return @$id_turma;
    }

    /**
     *     
      $abas[1] = [ 'nome' => "", 'hidden' => 'array', 'url'= null, 'target' => null  ];
     * @param type $abas
     */
    public static function dropdown($abas, $btn = NULL, $style = NULL, $styleBtn = NULL) {
        if (empty($btn)) {
            $btn = "btn btn-info";
        }
        include ABSPATH . '/includes/views/tab/dropdown.php';

        return $activeNav;
    }

}
