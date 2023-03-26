<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of css
 *
 * @author marco
 */
class css {

    public static function clasAlert($num) {
        while ($num > 7) {
            $num -= 7;
        }
        $alert = [
            1 => 'alert alert-primary',
            2 => 'alert alert-secondary',
            3 => 'alert alert-success',
            4 => 'alert alert-danger',
            5 => 'alert alert-warning',
            6 => 'alert alert-info',
            7 => 'alert alert-dark',
        ];
        return $alert[$num];
    }

    public static function clasBtn($num) {
        while ($num > 7) {
            $num -= 7;
        }
        $btn = [
            1 => 'btn btn-primary',
            2 => 'btn btn-secondary',
            3 => 'btn btn-success',
            4 => 'btn btn-danger',
            5 => 'btn btn-warning',
            6 => 'btn btn-info',
            7 => 'btn btn-dark',
        ];
        return $btn[$num];
    }

    public static function clasBtnOutline($num) {
        while ($num > 7) {
            $num -= 7;
        }
        $btn = [
            1 => 'btn btn-outline-primary',
            2 => 'btn btn-outline-secondary',
            3 => 'btn btn-outline-success',
            4 => 'btn btn-outline-danger',
            5 => 'btn btn-outline-warning',
            6 => 'btn btn-outline-info',
            7 => 'btn btn-outline-dark',
        ];
        return $btn[$num];
    }

    /**
     * <input <?= $v['retirada'] == 1 ? 'checked' : '' ?> id="switch-shadow-id" class="switch switch--shadow" type="checkbox">
      <label for="switch-shadow-id"></label>
     */
    public static function switchButton() {
        ?>
        <style>
            .switch {
                visibility: hidden;
                position: absolute;
                margin-left: -9999px;
            }

            .switch + label {
                display: block;
                position: relative;
                cursor: pointer;
                outline: none;
                user-select: none;
            }

            .switch--shadow + label {
                padding: 2px;
                width: 40px;
                height: 20px;
                background-color: #dddddd;
                border-radius: 20px;
            }
            .switch--shadow + label:before,
            .switch--shadow + label:after {
                display: block;
                position: absolute;
                top: 1px;
                left: 1px;
                bottom: 1px;
                content: "";
            }
            .switch--shadow + label:before {
                right: 1px;
                background-color: #f1f1f1;
                border-radius: 20px;
                transition: background 0.4s;
            }
            .switch--shadow + label:after {
                width: 20px;
                background-color: #fff;
                border-radius: 100%;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                transition: all 0.4s;
            }
            .switch--shadow:checked + label:before {
                background-color: #08f;
            }
            .switch--shadow:checked + label:after {
                transform: translateX(20px);
            }

            /* Estilo Flat */
            .switch--flat + label {
                padding: 2px;
                width: 40px;
                height: 20px;
                background-color: #dddddd;
                border-radius: 20px;
                transition: background 0.4s;
            }
            .switch--flat + label:before,
            .switch--flat + label:after {
                display: block;
                position: absolute;
                content: "";
            }
            .switch--flat + label:before {
                top: 2px;
                left: 2px;
                bottom: 2px;
                right: 2px;
                background-color: #fff;
                border-radius: 20px;
                transition: background 0.4s;
            }
            .switch--flat + label:after {
                top: 4px;
                left: 4px;
                bottom: 4px;
                width: 18px;
                background-color: #dddddd;
                border-radius: 52px;
                transition: margin 0.4s, background 0.4s;
            }
            .switch--flat:checked + label {
                background-color: #8ce196;
            }
            .switch--flat:checked + label:after {
                margin-left: 20px;
                background-color: #8ce196;
            }

        </style>
        <?php

    }

}
