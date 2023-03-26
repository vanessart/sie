<?php
/**
 * Description of concord
 * Funções para concordar e flexionar palavras
 * @author vanessa
 */
class concord {

    public static function sexo_pessoa($id_pessoa = NULL) {
        if (empty($id_pessoa)) {
            return @$_SESSION['userdata']['sexo'];
        } else {
            @$sexo = sql::get('pessoa', 'sexo', ['id_pessoa' => $id_pessoa], 'fetch')['sexo'];
            return $sexo;
        }
    }
    /**
     * retorna Feminino para sexo = F e Masculino para sexo = M
     * @param type $sigla
     * @return string
     */
    public static function sexo($sigla = NULL) {
        $sexo = ['F' => 'Feminino', 'M' => 'Masculino'];
        if (empty($sigla)) {
            return $sexo;
        } else {
            return $sexo[$sigla];
        }
    }

    public static function sexoSet($sigla = NULL) {
        $sexo = ['F' => 'Feminino', 'M' => 'Masculino'];
        if (empty($sigla)) {
            return;
        } else {
            return $sexo[$sigla];
        }
    }

    /**
     * retorna 'a' para sexo = F e 'o' para sexo = M
     * @param type $sigla
     * @return string
     */
    public static function sexoArt($sigla = NULL,$maiusculo = null) {
        if ($sigla == 'F') {
            if(empty($maiusculo)){
                return 'a';
            }else{
               return 'A'; 
            }
        } elseif ($sigla == 'M') {
            if(empty($maiusculo)){
                return 'o';
            }else{
               return 'O'; 
            }
        } else {
            if(empty($maiusculo)){
                return 'o(a)';
            }else{
               return 'O(a)'; 
            }
        }
    }
    
    public static function oa($id_pessoa,$maiusculo = null){
       $FM =  self::sexo_pessoa($id_pessoa);
       $oa = self::sexoArt($FM,$maiusculo);
       return $oa;
    }

    public static function seu($id_pessoa,$maiusculo = null) {
        $FM =  self::sexo_pessoa($id_pessoa);
        if ($FM == 'F') {
            if(empty($maiusculo)){
                return 'sua';
            }else{
               return 'SUA'; 
            }
        } elseif ($FM == 'M') {
            if(empty($maiusculo)){
                return 'seu';
            }else{
               return 'SEU'; 
            }
        } else {
            if(empty($maiusculo)){
                return 'seu/sua';
            }else{
               return 'SEU/SUA'; 
            }
        }
    }

    public static function meu($id_pessoa) {
        $FM =  self::sexo_pessoa($id_pessoa);
        if ($FM == 'F') {
            return 'minha';
        } elseif ($FM == 'M') {
            return 'meu';
        } else {
            return 'meu/minha';
        }
    }

    public static function a_ao($id_pessoa){
       $FM =  self::sexo_pessoa($id_pessoa);
        if ($FM == 'F') {
            return 'à';
        } elseif ($FM == 'M') {
            return 'ao';
        } else {
            return 'à/ao';
        }
    }
}