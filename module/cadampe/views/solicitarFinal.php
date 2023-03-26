<?php
if (!defined('ABSPATH'))
    exit;
    $id_pessoa = toolErp::id_pessoa();
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
    echo $id_turma;
?>

<div class="body">
    <div class="row" style="margin-bottom:110px">
        <div class="col-9" style="font-weight:bold; font-size:20px">
            <label for="">Professor: XXXXXX</label>
        </div>
        <div class="col-3" style="font-weight:bold; font-size:20px">
            <label for="">Periodo xx/xx/xxxx a xx/xx/xxxx</label>
        </div>
    </div>


    <div class="row" style="margin-bottom:30px">
        <div class="col border text-center" style="font-size:20px">
            Confirme a grade de aulas que serão substituidas
        </div>
    </div>
    <table class="table table-bordered table-hover table-striped border">
        <tr>
            <td></td>
            <td style="font-weight: bold; font-size: 1.2em; text-align:center">
                Manhã
            </td>
            <td style="font-weight: bold; font-size: 1.2em; text-align:center">
                Tarde
            </td>
            <td style="font-weight: bold; font-size: 1.2em">
                Noite
            </td>

        </tr>

        <tr>
            <td>
                Segunda-Feira
            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
        </tr>

        <tr>
            <td>
                Terça-Feira
                
            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>

        </tr>

        <tr>
            <td>
                Quarta-Feira
            </td>
            
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
            <td>
                <input type="button" value="Todos" class="btn-success">
                1º
                <input type="checkbox" name="" id="">
                2º
                <input type="checkbox" name="" id="">
                3º
                <input type="checkbox" name="" id="">
                4º
                <input type="checkbox" name="" id="">
                5º
                <input type="checkbox" name="" id="">

            </td>
        </tr>


    </table>

    <div class="row">
        <div class="col text-center">
            <button type="button" class="btn-success border">Finalizar</button>
        </div>
    </div>
</div>