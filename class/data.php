<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-data
 *
 * @author marco
 */
class data {

    /**
     * retorna a data futura a partir de uma data data e o segundo valor
     * @param type $data
     * @param type $dias
     * @return type
     */
    public static function proximoDia($data, $dias = 1) {
        if (strpos($data, '/')) {
            $data_ = explode('/', $data);
            $d = $data_[0];
            $m = $data_[1];
            $Y = $data_[2];
            $forma = "d/m/Y";
        } else {
            $data_ = explode('-', $data);
            $d = $data_[2];
            $m = $data_[1];
            $Y = $data_[0];
            $forma = "Y-m-d";
        }

        return date("$forma", mktime(0, 0, 0, $m, $d + $dias, $Y));
    }

    /**
     * converte de us para br ou viceversa - detecta automaticamente
     * @param type $data
     * @return string
     */
    public static function converte($data) {
        if (!empty($data)) {
            if (substr(@$data, 2, 1) == '/' && substr(@$data, 5, 1) == '/' && substr(@$data, 8, 1) == '/') {
                $dt = explode('/', $data);
                $data = str_pad($dt[2], 4, "20", STR_PAD_LEFT) . '-' . str_pad($dt[1], 2, "0", STR_PAD_LEFT) . '-' . str_pad($dt[0], 2, "0", STR_PAD_LEFT);
            } else {
                $dt = explode('-', $data);
                @$data = str_pad($dt[2], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[1], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[0], 4, "20", STR_PAD_LEFT);
            }

            return $data;
        }
    }

    /**
     * só converte se for US
     * @param type $data
     * @return string
     */
    public static function converteUS($data) {
        if (!empty($data)) {
            if (substr(@$data, 2, 1) == '/' && substr(@$data, 5, 1) == '/' && strlen(@$data) == 10) {
                $dt = explode('/', $data);
                $data = str_pad($dt[2], 4, "20", STR_PAD_LEFT) . '-' . str_pad($dt[1], 2, "0", STR_PAD_LEFT) . '-' . str_pad($dt[0], 2, "0", STR_PAD_LEFT);
            }
            return $data;
        }
    }

    /**
     * só converte se for US
     * @param type $data
     * @return string
     */
    public static function converteBr($data) {
        if (!empty($data)) {
            if (substr(@$data, 4, 1) == '-' && substr(@$data, 7, 1) == '-') {
                $data = substr($data, 0, 10);
                $dt = explode('-', $data);
                $data = str_pad($dt[2], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[1], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[0], 4, "20", STR_PAD_LEFT);
            }
            return $data;
        }
    }

    /**
     * devolve a quantidade de dias entre as duas datas
     * @param type $data_inicial
     * @param type $data_final
     * @return type
     */
    public static function diferencaDias($data_inicial, $data_final) {
        $data1 = new DateTime($data_final);
        $data2 = new DateTime($data_inicial);

        $intervalo = $data1->diff($data2);
        return $intervalo->days;
    }

    /**
     * converte número em mes
     * @param type $m
     * @return string
     */
    public static function mes($m) {
        $m = intval($m);
        switch (intval($m)) {
            case 1: $mes3 = 'Janeiro';
                break;
            case 2: $mes3 = 'Fevereiro';
                break;
            case 3: $mes3 = 'Março';
                break;
            case 4: $mes3 = 'Abril';
                break;
            case 5: $mes3 = 'Maio';
                break;
            case 6: $mes3 = 'Junho';
                break;
            case 7: $mes3 = 'Julho';
                break;
            case 8: $mes3 = 'Agosto';
                break;
            case 9: $mes3 = 'Setembro';
                break;
            case 10: $mes3 = 'Outubro';
                break;
            case 11: $mes3 = 'Novembro';
                break;
            case 12: $mes3 = 'Dezembro';
                break;
        }
        return @$mes3;
    }

    /**
     * 
     * @return string devolve todos os meses do ano
     */
    public static function meses() {
        $meses = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];

        return $meses;
    }

    /**
     * devolve a diferença em dias =, mese ou ano
     * @param type $data_inicial
     * @param type $data_final
     * @param type $periodo
     * @return type
     */
    public static function calcula($data_inicial, $data_final, $periodo = 'ano') {

        // Usa a função strtotime() e pega o timestamp das duas datas:
        $time_inicial = strtotime($data_inicial);
        $time_final = strtotime($data_final);

        // Calcula a diferença de segundos entre as duas datas:
        $diferenca = $time_final - $time_inicial; // 19522800 segundos
        // Calcula a diferença de dias
        if ($periodo == "dia") {
            $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias
            return $dias;
        }

        if ($periodo == "mes") {
            $mes = (int) floor($diferenca / (60 * 60 * 24 * 30)); // 225 dias
            return $mes;
        }

        if ($periodo == "ano") {
            $ano = round($diferenca / (60 * 60 * 24 * 365), 1); // 225 dias
            return $ano;
        }
    }

    public static function pegasemana($mes) {

        switch ($mes) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $qd = 31;
                break;
            case 2:
                if (($a = date('Y') % 4) == 0) {
                    $qd = 29;
                } else {
                    $qd = 28;
                }
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                $qd = 30;
                break;
        }

        $seq = 1;
        $du = 0;

        while ($seq <= $qd) {

            $dsp = array('D', 'S', 'T', 'Q', 'Q', 'S', 'S');
            $ds = (int) date("w", strtotime($mes . "/" . $seq . "/" . date('Y')));

            if ($ds == 0 or $ds == 6) {
                //pula
            } else {
                $tab = ($dsp[$ds]);

                if ($seq < 10) {
                    $tabd = "0" . $seq;
                } else {
                    $tabd = $seq;
                }

                $acd = @$acd . $tabd;
                $ac = @$ac . $tab;
                $du++;
            }
            $seq++;
        }
        $m = data::mes($mes);
        return $ac . '|' . $acd . '|' . $qd . '|' . $du . '|' . $m;
    }

    public static function diasUteis($mes, $ano) {

        $uteis = 0;
        // Obtém o número de dias no mês 
        // (http://php.net/manual/en/function.cal-days-in-month.php)
        $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

        for ($dia = 1; $dia <= $dias_no_mes; $dia++) {

            // Aqui você pode verifica se tem feriado
            // ----------------------------------------
            // Obtém o timestamp
            // (http://php.net/manual/pt_BR/function.mktime.php)
            $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
            $semana = date("N", $timestamp);

            if ($semana < 6) {
                $dia_[$dia] = $dia;
            }
        }

        return $dia_;
    }

    public static function idade($data) {
        if (substr($data, 2, 1) == '/') {
            // Separa em dia, mês e ano
            list($dia, $mes, $ano) = explode('/', $data);
        } else {
            list($ano, $mes, $dia) = explode('-', $data);
        }

        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

        // Depois apenas fazemos o cálculo já citado :)
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

        return $idade;
    }

    public static function porExtenso($data) {
        if ($data != '0000-00-00' && !empty($data)) {
            $dia = substr($data, 8, 2);
            $mes = substr($data, 5, 2);
            $ano = substr($data, 0, 4);

            return $dia . ' de ' . data::mes($mes) . ' de ' . $ano;
        } else {
            return;
        }
    }

    public static function minutoParaHora($minuto) {
        $hora = intval($minuto / 60);
        $minuto = $minuto - ($hora * 60);
        $hora = $hora . ':' . str_pad($minuto, 2, "0", STR_PAD_LEFT);

        return $hora;
    }

    /**
     * 
     * @param type $mes
     * @param type $dia
     * @param type $tipoesc recebe o  código da classe
     * @return string
     */
    public static function wpegaferiados($mes, $dia, $tipoesc) {

        $tipo = substr($tipoesc, 0, 2);
        $ano = date('Y');

        $sql = "SELECT tipo_feriado FROM ge_feriados"
                . " WHERE dia = '" . $dia . "' AND mes = '" . $mes . "'"
                . " AND ano = '" . $ano . "' AND tipo_escola = '" . $tipo . "'";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            $fer = $array['tipo_feriado'];
        } else {
            $fer = ' ';
        }

        return $fer;
    }

    public static function feriadoMes($mes, $tipoesc = NULL, $ano = NULL, $tipoFeriado = NULL) {
        if (empty($tipoFeriado)) {
            $tipoFeriado = 'dia';
        } else {
            $tipoFeriado = 'tipo_feriado';
        }
        if (empty($ano)) {
            $ano = date('Y');
        }
        if (!empty($tipoesc)) {
            $tipoesc = " AND tipo_escola = '" . $tipo . "'";
        }
        $sql = "SELECT * FROM ge_feriados "
                . " WHERE mes = '" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "'"
                . " AND ano = '" . $ano . "' "
                . " $tipoesc ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchALL(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $feriado[$v['tipo_escola']][$v['dia']] = $v[$tipoFeriado];
        }
        return @$feriado;
    }

    public static function calculoIdade($data) {

        //Data atual
        $dia = date('d');
        $mes = date('m');
        $ano = date('Y');

        //Data do aniversário
        $nascimento = explode('-', $data);
        $dianasc = ($nascimento[2]);
        $mesnasc = ($nascimento[1]);
        $anonasc = ($nascimento[0]);

        // se for formato do banco, use esse código em vez do de cima!
        /*
          $nascimento = explode('-', $nascimento);
          $dianasc = ($nascimento[2]);
          $mesnasc = ($nascimento[1]);
          $anonasc = ($nascimento[0]);
         */

        //Calculando sua idade
        $idade = $ano - $anonasc; // simples, ano- nascimento!

        if ($mes < $mesnasc) { // se o mes é menor, só subtrair da idade
            $idade--;
            return $idade;
        } elseif ($mes == $mesnasc && $dia <= $dianasc) { // se esta no mes do aniversario mas não passou ou chegou a data, subtrai da idade
            $idade--;
            return $idade;
        } else { // ja fez aniversario no ano, tudo certo!
            return $idade;
        }
    }



}
