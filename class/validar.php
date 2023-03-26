<?php

/*
 * validador de dados
 */

/**
 * Description of validar
 *
 * @author mc
 */
class validar {

    /**
     * valida CPF
     * @param type $cpf CPF
     * @return int
     */
    public static function Cpf($cpf) {
/*
        $cpf = str_replace(array('.', '-', '/', ' '), '', $cpf);
        if (strlen($cpf) == 11) {
            $verif_cpf = 0;
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += @$cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;

                if (@$cpf{$c} != $d) {
                    $verif_cpf = 1;
                    break;
                }
                if ($cpf == 11111111111 || $cpf == 00000000000) {
                    $verif_cpf = 1;
                }
                return $verif_cpf;
            }
        } else {
            return 1;
        }

        */
          $c = preg_replace('/[^\d]/', '', $cpf);

          if (mb_strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
          return false;
          }

          for (
          $s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--
          ) {
          }

          if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
          return false;
          }

          for (
          $s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--
          ) {
          }

          if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
          return false;
          }

          return true;

         
    }

    /**
     * valida e-mail
     * @param type $cpf e-mail
     * @return int
     */
    public static function validaEmail($email) {
        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";
        $pattern = $conta . $domino . $extensao;
        if (@ereg($pattern, $email))
            return true;
        else
            return false;
    }

}
