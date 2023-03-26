<?php
ob_start();
$escola = new escola();
?>
<head>
    <style>
        .titulocab{        
            font-weight: bolder;
            font-size: 12px;
            background-color: #000000;
            color:#ffffff;
            border-width: 2px;
            text-align: center;
            height: 5px;     
            border: 10px solid;

        }

        .topo{
            height: 5px;
            font-size: 10pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 5px;  
        }   
        .topo2{       
            font-size: 10pt;
            text-align: left;             
            color: red; 
            border: 1px solid;
        }   
    </style>   
</head>

<div style="width: 679px" class="titulocab">
    PERFIL DO ALUNO              
</div> 
<div style="text-align: center; font-size: 12px; font-weight: bolder; border: 1px solid; width: 679px" >
    <?php echo ' ' ?>
</div>

<div style="border: 1px solid">
    <table>
        <tr>
            <td style="width: 118px" class="topo">
                Data Nasc.
            </td>
            <td style="width: 82px" class="topo">
                Sexo
            </td>
            <td style="width: 130px" class="topo">
                RSE
            </td>
            <td style="width: 130px" class="topo">
                RA
            </td>
            <td style="width: 130px" class="topo">
                RG
            </td>              
            <td rowspan="4" style="width: 89px">

            </td>
        </tr>     
        <tr>
            <td class="topo" style="height: 20px">
                <?php echo ' ' ?>
            </td>
            <td class="topo" style="height: 20px">
                <?php echo ' ' ?>
            </td>
            <td class="topo" style="height: 20px">
                <?php echo ' ' ?>
            </td>
            <td class="topo" style="height: 20px">
                <?php echo ' ' ?>
            </td>
            <td class="topo" style="height: 20px">
                <?php echo ' ' ?>
            </td>
        </tr>             
        <tr>
            <td colspan ="5" class="topo2">
                Endereço
            </td> 
        </tr>
        <tr>
            <td colspan="5" style="text-align: left" class="topo">
                <?php echo ' ' ?>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="width: 350px" class="topo">
                Bairro
            </td>
            <td colspan="2" style="width: 300px" class="topo">
                Cidade - Estado
            </td>
            <td colspan="2" style="width: 29px" class="topo">
                CEP
            </td>
        </tr>

        <tr>
            <td colspan="2" style="width: 350px; height: 20px" class="topo">
                <?php echo ' ' ?>
            </td>
            <td colspan="2" style="width: 300px; height: 20px" class="topo">
                <?php echo ' ' ?>
            </td>
            <td colspan="2" style="width: 29px; height: 20px" class="topo">
                <?php echo ' ' ?>
            </td>
        </tr>
    </table>
</div>
<table>
    <tr>    
        <td colspan="2" style="width: 679px" class="topo2">
            Filiação
        </td>
    </tr>
    <tr>
        <td style="width: 350px" class="topo">
            Mãe
        </td>
        <td style="width: 329px" class="topo">
            Pai
        </td>
    </tr>
    <tr>
        <td style="width: 350px; height: 15px" class="topo">
            <?php echo ' ' ?>
        </td>
        <td style="width: 329px; height: 15px" class="topo">
            <?php echo ' ' ?>
        </td>
    </tr>              
</table>
<table>
    <tr>    
        <td colspan="5" style="width: 679px" class="topo2">
            <?php echo 'Responsável pela retirada do aluno' ?>        
        </td>
    </tr>
    <tr>
        <td style="width: 300px" class="topo">
            Nome
        </td>
        <td style="width: 60px" class="topo">
            Grau Parentesco
        </td>
        <td style="width: 94px" class="topo">
            Documento
        </td>
        <td style="width: 70px" class="topo">
            Telefone
        </td>
        <td style="width: 155px" class="topo">
            Assinatura
        </td>
    </tr>

    <?php
    for ($x = 0; $x < 11; $x++) {
        ?>
        <tr>
            <td style="width: 300px; height: 30px; text-align: left" class="topo">
                    <?php echo ' ' ?>
                </td>
                <td style="width: 80px; height: 30px; text-align: left" class="topo">
                    <?php echo ' ' ?>
                </td>
                <td style="width: 84px; height: 30px; text-align: left" class="topo">
                    <?php echo ' ' ?>
                </td>
                <td style="width: 60px; height: 30px; text-align: left" class="topo">
                    <?php echo ' ' ?>
                </td>
            <td style="width: 155px; height: 30px" class="topo">
                <?php echo ' ' ?>
            </td>  
        </tr>
        <?php
    }
    ?>
</table>
<table>
    <tr>
        <td colspan="2" style="width: 679px" class="topo2">
            Transporte Escolar
        </td>
    </tr> 
    <tr>
        <td style="widht: 600px" class="topo">
            Nome do Condutor
        </td>
        <td style="width: 69px" class="topo">
            Telefone
        </td>
    </tr>
    <tr>
        <td style="widht: 600px; height:20px" class="topo">
            <?php echo ' ' ?>
        </td>
        <td style="width: 69px; height: 20px" class="topo">
            <?php echo ' ' ?>
        </td>
    </tr>  
</table>
<div style="border: 1px solid; padding: 5px">
    <table>
        <tr>
            <td style="padding-left: 120px; color: red; font-size:10px; text-align: left">
                A criança já teve: 
            </td>
            <td style="padding-left: 120px; color: red; font-size:10px; text-align: left">
                Outras informações:
            </td>
        </tr>
        <tr>
            <td style="font-size: 9px; text-align: left; padding-left: 120px">
                (  ) Catapora
            </td>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Vacinação em dia

            </td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Sarampo
            </td>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Fez alguma cirurgia
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Meningite
            </td>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Já tomou Benzetacil
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Rubeola
            </td>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Já teve convulsão
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Caxumba
            </td>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Enxerga bem
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Hepatite
            </td>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                (  ) Escuta bem
            </td>
        </tr>
        <tr>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                ( ) Bronquite
            </td>
            <td style="font-size: 10px; text-align: left; padding-left: 120px">
                ( ) Fala Direito
            </td>
        </tr>
    </table>            
</div>

<div style="border: 1px solid; padding: 5px; font-size: 10px; color: red">
    Obs.: (Alergia a Medicação)
    <br /><br /><br /><br />
</div>
<div style="border: 1px solid; padding: 5px; font-size: 10px; color: red">
    Obs.: (Restrição Alimentar e Outros)
    <br /><br /><br />
</div>
<?php
tool::pdfSemRodape();
?>