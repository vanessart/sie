<?php

//Base url: https://erpeducgp.app.br/api/
class rhImport {

    public static function alocaProf($ano, $matricula = null) {
        if ($matricula) {
            $ch = curl_init('https://erpeducgp.app.br/api/professor/consulta/' . $ano . '/' . $matricula);
        } else {
            $ch = curl_init('https://erpeducgp.app.br/api/professor/lote/' . $ano);
        }
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode('cliente:fr6%5uhmzy'),
                'Content-Type: application/json;charset=UTF-8',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $resultado = curl_exec($ch);

        curl_close($ch);

        return json_decode($resultado, true);
    }

    public static function funcionarios($datas = null, $matricula = null) {
        if ($matricula) {
            $ch = curl_init('https://erpeducgp.app.br/api/funcionarios/consulta/' . $matricula);
        } elseif (!empty($datas[0])) {
            if (!empty($datas[1])) {
                $dataFim = "&data_termino=" . $datas[1];
            } else {
                $dataFim = null;
            }
            $ch = curl_init('https://erpeducgp.app.br/api/funcionarios/consulta?data_inicio=' . $datas[0] . $dataFim);
        } else {
            return;
        }
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode('cliente:fr6%5uhmzy'),
                'Content-Type: application/json;charset=UTF-8',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $resultado = curl_exec($ch);

        curl_close($ch);

        return json_decode($resultado, true);
    }

    public static function htpc($ano = null) {
        if (empty($ano)) {
            $ano = date("Y");
        }
        $ch = curl_init('https://erpeducgp.app.br/api/htpc/lote/' . $ano);

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode('cliente:fr6%5uhmzy'),
                'Content-Type: application/json;charset=UTF-8',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $resultado = curl_exec($ch);

        curl_close($ch);

        return json_decode($resultado, true);
    }

    public static function segmentoAnoEscolar($ano = null) {
        if (empty($ano)) {
            $ano = date("Y");
        }
        $ch = curl_init('https://erpeducgp.app.br/api/segmento_ano_escolar/consulta/' . $ano);

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode('cliente:fr6%5uhmzy'),
                'Content-Type: application/json;charset=UTF-8',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $resultado = curl_exec($ch);

        curl_close($ch);

        return json_decode($resultado, true);
    }

    public static function professorTurma($ano = null) {
        if (empty($ano)) {
            $ano = date("Y");
        }
        $ch = curl_init('https://erpeducgp.app.br/api/professor_sala/lote/' . $ano);

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode('cliente:fr6%5uhmzy'),
                'Content-Type: application/json;charset=UTF-8',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $resultado = curl_exec($ch);

        curl_close($ch);

        return json_decode($resultado, true);
    }

    public static function disciplinas($ano = null) {
        if (empty($ano)) {
            $ano = date("Y");
        }
        $ch = curl_init('https://erpeducgp.app.br/api/disciplina/consulta/' . $ano);

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode('cliente:fr6%5uhmzy'),
                'Content-Type: application/json;charset=UTF-8',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $resultado = curl_exec($ch);

        curl_close($ch);

        return json_decode($resultado, true);
    }

}
