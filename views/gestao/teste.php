<table style="width: 100%">
    <tr>
        <td rowspan="2">
            <img style="width: 80px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
            <div style="font-size: 15px; font-weight: bold">
                Prefeitura Municipal de Barueri
                <br>SE - Secretaria de Educação
            </div>
            <div style="font-size: 11px">
                $this->_nome .
                <br>' . $this->_email . 
            </div>
        </td>
        <td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td width: 200px>
            <img style="width: 200px" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>
        </td>
    </tr>
    <tr>
        <td>

        </td>
        <td colspan="3" style="font-size: 8px;text-align: left">
            . $this->enderecoEstruturado(1) . '
        </td>
    </tr>
</table>

<table style="width: 100%">'
            . '<tr>
                <td rowspan="3"><img style="width: 80px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>
                </td>
                <td>
                    <div style="font-size: 15px; font-weight: bold">Prefeitura Municipal de Barueri
                        <br>SE - Secretaria de Educação
                    </div>
                    <div style="font-size: 11px">'
                        . $this->_nome .
                        '<br>' . $this->_email . '
                    </div>
                </td>
                <td rowspan="3" width: 200px>
                    <img style="width: 200px" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>
                </td>
            </tr>'
            . '<tr>
                <td></td>
                <td style="font-size: 8px;text-align: center">' 
                    . $this->enderecoEstruturado(1) . '
                </td>
            </tr>'
            . '</table>' . @$ato;
            
            
            
            
           <table style="width: 100%">
            <tr>
                <td rowspan="3"><img style="width: 80px" src="' . HOME_URI . '/views/_images/brasao.jpg"/></td>
                <td style = "text-align: center">
                    <div style="font-size: 15px; font-weight: bold">
                        Prefeitura Municipal de Barueri<br>SE - Secretaria de Educação
                    </div>
                    <div style="font-size: 11px; text-align: center">
                        $this->_nome
                        <br>
                        $this->_email
                    </div>
                </td>
                <td rowspan="3" width: 200px>
                    <img style="width: 200px" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>
                </td>
            </tr>
            <tr>
                <td style="font-size: 8px;text-align: center">
                    $this->enderecoEstruturado(1)
                </td>
            </tr>
            </table>
            @$ato;