<?php

class dataErp {

    public static function timeStamp() {
        return date("Y-m-d H:i:s");
    }

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
                $data = str_pad($dt[2], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[1], 2, "0", STR_PAD_LEFT) . '/' . str_pad($dt[0], 4, "20", STR_PAD_LEFT);
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
     * @param type $retorna_negativo : se true irá retornar a qtde negativa de acordo com a data inicial x final
     * @return type
     */
    public static function diferencaDias($data_inicial, $data_final, $retorna_negativo = false) {
        $data1 = new DateTime($data_final);
        $data2 = new DateTime($data_inicial);

        $intervalo = $data1->diff($data2);
        $d = $intervalo->days;

        if (!empty($intervalo) && !empty($retorna_negativo)) {
            $d *= ($intervalo->invert ? -1 : 1);
        }

        return $d;
    }

    /**
     * 
     * @return string devolve todos os meses do ano
     */
    public static function meses($mes = NULL) {
        if (!empty($mes)) {
            $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
        }
        $meses = [
            '01' => 'janeiro',
            '02' => 'fevereiro',
            '03' => 'março',
            '04' => 'abril',
            '05' => 'maio',
            '06' => 'junho',
            '07' => 'julho',
            '08' => 'agosto',
            '09' => 'setembro',
            '10' => 'outubro',
            '11' => 'novembro',
            '12' => 'dezembro'
        ];

        if (empty($mes)) {
            return $meses;
        } else {
            return @$meses[$mes];
        }
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

    public static function porExtenso($data) {
        if ($data != '0000-00-00' && !empty($data)) {
            $dia = substr($data, 8, 2);
            $mes = substr($data, 5, 2);
            $ano = substr($data, 0, 4);

            return $dia . ' de ' . dataErp::meses($mes) . ' de ' . $ano;
        } else {
            return;
        }
    }

    /**
     * 
     * @param type $periodo   M,T,N,I separado com virgula
     * @return string
     */
    public static function periodoDoDia($periodo = NULL) {
        $per = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'I' => 'Integral', 'G' => 'Geral'];
        if (empty($periodo)) {
            return $per;
        } elseif (strlen($periodo) > 1) {
            foreach ($per as $k => $v) {
                if (in_array($k, explode(',', $periodo))) {
                    $p[$k] = $v;
                }
            }
            return $p;
        } else {
            return $per[$periodo];
        }
    }

    /**
     * 
     * @param type $mes: 1,2,3,4....,12
     * @param type $letras: qte de caracteres do nome do dia que será retornado
     * @param type $ano: Ano
     * @param type $tipo_retorno: T (Texto, padrao) ou N (numérico)
     */
    public static function diasUteis($mes, $letras = NULL, $ano = NULL, $tipo_retorno = null) {
        $mes = intval($mes);
        if (empty($ano)) {
            $ano = date("Y");
        }
        if (empty($letras)) {
            $letras = 3;
        }
        if (empty($tipo_retorno)) {
            $tipo_retorno = 'T';
        }

        // Exemplo: Feriados de Novembro
        //$feriados = array(2 => 'Finados', 15 => 'Proclamação da Republica');

        $feriados = array();

        // Total de dias no mês
        $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

        $dias_letivos = 0;

        for ($d = 1; $d <= $dias_do_mes; $d++) {
            $dia_da_semana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $d, $ano), 0);

            // 0 = domingo e 6 = sábado
            if (!($dia_da_semana == 0 || $dia_da_semana == 6 || in_array($d, $feriados))) {
                if ($tipo_retorno == 'T') {
                    $dias[$d] = self::diasDaSemana($dia_da_semana, $letras);
                } else {
                    $dias[$d] = $d;
                }
            }
        }
        return $dias;
    }

    /**
     * 
     * @param type $diaDaSemana 0,1,2,3,4....
     * @param type $letras o número de letras que será exibida
     */
    public static function diasDaSemana($diaDaSemana = NULL, $letras = null) {
        $ds = [
            0 => empty($letras) ? 'Domingo' : substr('Domingo', 0, $letras),
            1 => empty($letras) ? 'Segunda' : substr('Segunda', 0, $letras),
            2 => empty($letras) ? 'Terça' : substr('Terça', 0, $letras),
            3 => empty($letras) ? 'Quarta' : substr('Quarta', 0, $letras),
            4 => empty($letras) ? 'Quinta' : substr('Quinta', 0, $letras),
            5 => empty($letras) ? 'Sexta' : substr('Sexta', 0, $letras),
            6 => empty($letras) ? 'Sábado' : substr('Sabado', 0, $letras)
        ];
        if (empty($diaDaSemana) && $diaDaSemana != '0') {
            return $ds;
        } elseif (strlen($diaDaSemana) > 1) {
            foreach ($ds as $k => $v) {
                if (in_array($k, explode(',', $diaDaSemana))) {
                    $p[$k] = $v;
                }
            }
            return $p;
        } else {
            return $ds[$diaDaSemana];
        }
    }

    /**
     * 
     * @param type $dtInicio
     * @param type $dtFim
     * @param type $planoAula 1= um por semana 2 uma cada 2 semana ...
     * @return type
     */
    public static function DiasUteisPorPeriodos($dtInicio, $dtFim, $planoAula) {
        if ($dtInicio < $dtFim) {
            $dtInicioEx = explode('-', $dtInicio);
            $SemanaIni = date("w", mktime(0, 0, 0, $dtInicioEx[1], $dtInicioEx[2], $dtInicioEx[0]));
            if ($SemanaIni == 0) {
                $SemanaIni = 1;
                $dtInicio = data::proximoDia($dtInicio);
            } elseif ($SemanaIni == 6) {
                $SemanaIni = 1;
                $dtInicio = data::proximoDia($dtInicio, 2);
            }
            $dtFimEx = explode('-', $dtFim);
            $SemanaFim = date("w", mktime(0, 0, 0, $dtFimEx[1], $dtFimEx[2], $dtFimEx[0]));

            if ($SemanaFim == 0) {
                $dtFim = data::proximoDia($dtInicio, -2);
            } elseif ($SemanaFim == 6) {
                $dtFim = data::proximoDia($dtFim, -1);
            }

            $dtInicioFim = data::proximoDia($dtInicio, (5 - $SemanaIni));
            $semana[] = ['inicio' => $dtInicio, 'fim' => $dtInicioFim];

            $ct = 0;
            $parar = data::proximoDia($dtFim, -7);
            while (($dtInicioFim <= $parar) && $ct <= 52) {
                $dtInicio = data::proximoDia($dtInicioFim, 3);
                $dtInicioFim = data::proximoDia($dtInicioFim, 7);
                $semana[] = ['inicio' => $dtInicio, 'fim' => $dtInicioFim];

                $ct++;
            }
            $dtInicio = data::proximoDia($dtInicioFim, 3);
            if ($dtFim > $dtInicio) {
                $semana[] = ['inicio' => $dtInicio, 'fim' => $dtFim];
            }
            $ct = 1;
            $a = 1;
            foreach ($semana as $v) {

                if (empty($pula)) {
                    $plano[$a]['inicio'] = $v['inicio'];
                    $pula = 1;
                }
                if ($ct % $planoAula == 0) {
                    $plano[$a]['fim'] = $v['fim'];
                    $a++;
                    $pula = NULL;
                }
                $ct++;
            }
            if (!empty($plano[$a]['inicio']) && empty($plano[$a]['fim'])) {
                $plano[$a]['fim'] = $dtFim;
            }

            return $plano;
        }
    }

    public static function datasPeriodo($dtInicio, $dtFim, $diaSemada = []) {
        $dtInicio = new DateTime($dtInicio);

        $dtFim = new DateTime($dtFim);

        //Prints days according to the interval
        $dateRange = array();
        while ($dtInicio <= $dtFim) {
            $dtInicioStr = $dtInicio->format('Y-m-d');
            if (count($diaSemada) < 1 || in_array(date('w', strtotime($dtInicioStr)), $diaSemada)) {
                $dateRange[] = $dtInicioStr;
            }
            $dtInicio = $dtInicio->modify('+1day');
        }

        return $dateRange;
    }
    
    public static function diaHora($data) {
        if (!empty($data)) {
            $dia = self::converteBr($data);
            $hora = substr($data, 10, 20);
            $diaHora = $dia.' às '.$hora; 
            return $diaHora;
        }
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

        $feriado = [];
        foreach ($array as $v) {
            $feriado[$v['tipo_escola']][$v['dia']] = $v[$tipoFeriado];
        }
        return $feriado;
    }

    public static function minutoParaHora($minuto) {
        $hora = intval($minuto / 60);
        $minuto = $minuto - ($hora * 60);
        $hora = $hora . ':' . str_pad($minuto, 2, "0", STR_PAD_LEFT);

        return $hora;
    }

    /**
     * 
     * @param type $dataIni data inicial - YYYY-MM-DD
     * @param type $dataFim data final - YYYY-MM-DD
     * @return array com as semanas entre as duas datas
     */
    public static function semanas($dataIni, $dataFim, $dias_uteis = true) {
        if (empty($dataIni)) return [];
        if (empty($dataFim)) return [];

        $r = [];
        $di = DateTime::createFromFormat('Y-m-d', $dataIni);
        $df = DateTime::createFromFormat('Y-m-d', $dataFim);

        $diff = $di->diff($df)->days/7;
        $diff = $diff+1;
        for ($i=0; $i < $diff; $i++) 
        {
            $diF = $dias_uteis ? 5 : 6;
            $di->setISODate((int)$di->format('o'), (int)$di->format('W'), 1);
            $df->setISODate((int)$di->format('o'), (int)$di->format('W'), $diF);
            if (!$dias_uteis) {
                $di->modify("-1 days");
            }

            // retorna como array de datas
            // $r[$di->format('W') .'-'. $di->format('Y')] = [
            //     $di->format('Y-m-d'), $df->format('Y-m-d')
            // ];
            // retorna array de string das datas
            $week = intval($di->format('W'));
            if ($di->format('w') == 0) { // 0 = Sunday
               $week++;
            }
            $r[$week .'-'. $di->format('Y')] = $di->format('d/m/Y') .' - '. $df->format('d/m/Y');

            $di = DateTime::createFromFormat('Y-m-d', $df->add(new DateInterval('P3D'))->format('Y-m-d'));
        }
        return $r;
    }

    /**
     * 
     * @param type $semana - número da semana do ano - date('W')
     * @param type $ano - date('Y')
     * @param type $todosDias - retorna todos os dias da semana
     * @param type $dias_uteis - retorna os dias uteis da semana ou nao
     * @return array com a data inicial e final a partir de uma semana
     */
    public static function dataPorSemana($semana = null, $ano = NULL, $todosDias = false, $dias_uteis = true) {
        if (empty($semana)) {
            $semana = date('W');
        };
        if (empty($ano)) {
            $ano = date('Y');
        };

        if ($todosDias) {
            $d = $dias_uteis ? 4 : 6;
            $add = 1;
        } else {
            $d = 1;
            $add = $dias_uteis ? 4 : 6;
        }

        $dto = new DateTime();
        $dto->setISODate($ano, $semana);
        if (!$dias_uteis) {
            $dto->modify("-1 days");
        }
        if ($todosDias) {
            $ret[] = $dto->format('Y-m-d');
        } else {
            $ret['data_inicio'] = $dto->format('Y-m-d');
        }

        for ($i=0; $i < $d; $i++) { 
            $dto->modify("+$add days");
            if ($todosDias) {
                $ret[] = $dto->format('Y-m-d');
            } else {
                $ret['data_fim'] = $dto->format('Y-m-d');
            }
        }

        return $ret;
    }

    /**
     * 
     * @param type $data_ini: YYYY-MM-DD
     * @param type $data_fim: YYYY-MM-DD
     * @param type $letras: qte de caracteres do nome do dia que será retornado
     * @param type $permite_data_futura: permite retornar dados ate o dia corrente
     */
    public static function DiasUteisPorPeriodo($data_ini, $data_fim, $letras = NULL, $permite_data_futura = true)
    {
        if (empty($letras)) {
            $letras = 3;
        }

        $feriados = array();

        // Total de dias no mês
        $dias_do_mes = self::diferencaDias($data_ini, $data_fim);

        $data_hoje = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $data_ini = DateTime::createFromFormat('Y-m-d', $data_ini);
        $diaIni = $data_ini->format('d');

        for ($d = $diaIni; $d <= $diaIni + $dias_do_mes; $d++)
        {
            $mesIni = $data_ini->format('m');
            if ( !isset($feriados[$mesIni]) ) {
                $feriados[$mesIni] = self::feriadoMes($mesIni);
            }
            $dia_da_semana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $data_ini->format('m'), $data_ini->format('d'), $data_ini->format('Y')), 0);

            try {

                if (empty($permite_data_futura) && self::diferencaDias($data_hoje->format('Y-m-d'), $data_ini->format('Y-m-d'), true) < 0 ){
                    throw new Exception();
                }

                // 0 = domingo e 6 = sábado
                if ($dia_da_semana == 0 || $dia_da_semana == 6) {
                    throw new Exception();
                }

                $dias[$data_ini->format('Y-m-d')] = [
                    'dia' => $d,
                    'mes' => $data_ini->format('m'),
                    'ano' => $data_ini->format('Y'),
                    'semana' => self::diasDaSemana($dia_da_semana, $letras),
                    'data' => $data_ini->format('Y-m-d'),
                    'feriado' => false,
                    'feriadoTipEnsino' => [],
                ];

                if (!empty($feriados[$mesIni]['EF']) && in_array($d, $feriados[$mesIni]['EF']) ) {
                    $dias[$data_ini->format('Y-m-d')]['feriado'] = true;
                    $dias[$data_ini->format('Y-m-d')]['feriadoTipEnsino']['EF'] = true;
                }

                if (!empty($feriados[$mesIni]['EI']) && in_array($d, $feriados[$mesIni]['EI']) ) {
                    $dias[$data_ini->format('Y-m-d')]['feriado'] = true;
                    $dias[$data_ini->format('Y-m-d')]['feriadoTipEnsino']['EI'] = true;
                }

                if (!empty($feriados[$mesIni]['EM']) && in_array($d, $feriados[$mesIni]['EM']) ) {
                    $dias[$data_ini->format('Y-m-d')]['feriado'] = true;
                    $dias[$data_ini->format('Y-m-d')]['feriadoTipEnsino']['EM'] = true;
                }

            } catch (Exception $e){
            }

            $data_ini = DateTime::createFromFormat('Y-m-d', $data_ini->add(new DateInterval('P1D'))->format('Y-m-d'));
        }
        return $dias;
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
}
