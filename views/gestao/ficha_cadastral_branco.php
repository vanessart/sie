<?php
ob_start();
$escola = new escola();
?>
<head>
    <style>
        .titulocab{        
            font-weight:bolder;
            font-size: 9px;
            background-color: #000000;
            color:#ffffff;
            border-width: 1px;
            text-align: center;
            padding-left: 3px;
            padding-right: 3px;
            border: solid;
            border-style: border;
            height: 4px
        }
        .subtitulocab{
            height: 4px;
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 3px; 
            padding-right: 3px;
            color: red;
        }
        .topo{
            height: 4px;
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 3px;
            padding-right: 3px;
        }
    </style>   
</head>


<div class="titulocab" >FICHA CADASTRAL DO ALUNO</div>   
<div class="subtitulocab">Identificação do Aluno</div> 

<div style="border: 1px solid">            
    <div style="text-align: center; height: 20px"> <b><?php echo ' ' ?></b></div>
    <table>
        <tr>
            <td style="width:70px; height: 12px" class="topo" >RSE</td>
            <td style="width:90px; height: 12px" class="topo" >RA</td>
            <td style="width:90px; height: 12px" class="topo" >NIS</td>
            <td style="width:130px; height: 12px" class="topo" >RG</td>
            <td style="width:75px; height: 12px" class="topo" > Data Nasc</td>
            <td style="width:60px; height: 12px" class="topo" >Sexo</td>
            <td style="width:75px; height: 12px" class="topo" >Raça/Cor</td>
            <td rowspan="4">
                    <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="89" height="89" alt="foto"/> 
            </td>
        </tr> 

        <tr>
            <td style="width:70px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td style="width:90px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td style="width:90px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td style="width:130px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td style="width:75px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>              
            <td style="width:60px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td style="width:75px; height: 12px" class="topo" > 
                <?php echo ' ' ?>
        </tr>
        <tr>
            <td colspan="3" style="height: 15px" class="topo" > Certidão Nasc.</td>   
            <td colspan="2" style="height: 15px;" class="topo" >Naturalidade</td>
            <td colspan="1" style="height: 15px;" class="topo" >Estado</td>
            <td colspan="1" style="height: 15px;" class="topo" >Nacionalidade</td>            
        </tr>
        <tr>
            <td colspan="3" style="height: 15px; font-size: 8pt" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td colspan="2" style="height: 15px;" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td colspan="1" style="height: 15px;" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td colspan="1" style="height: 15px;" class="topo" > 
                <?php echo ' ' ?>
            </td>
        </tr>
    </table>
</div>   

<div class="subtitulocab">Filiação</div>
<table>
    <tr>
        <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
            <?php echo ' ' ?>
        </td>
        <td style="width:339.5px; height: 15px; text-align: left" class="topo" >
            <?php echo ' ' ?>
        </td>
    </tr>
</table>
<div class="subtitulocab">Endereço</div>
<table>
    <tr>
        <td style="width:679px; height: 15px; text-align: left" class="topo" >
            <?php echo ' ' ?>
        </td>
    </tr>
</table>
<div class="subtitulocab">Outras Informações</div>
<table>
    <thead>      
        <tr>
            <th style="width:100px; height: 15px" class="topo" >Telefone</th>
            <th style="width:479px; height: 15px" class="topo" >Email</th>
            <th style="width:200px; height: 15px" class="topo" >CPF Responsável</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width:100px; height: 15px" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td style="width:479px; height: 15px" class="topo" >
                <?php echo ' ' ?>
            </td>
            <td style="width:200px; height: 15px" class="topo" >
                <?php echo ' ' ?>
            </td>
        </tr>
    </tbody>
</table>    

<div class="subtitulocab">Procedência do Aluno</div>

<table>
    <thead>
        <tr>
            <th style="width:450px; height: 12px" class="topo" >Escola ou Equivalente</th>
            <th style="width:229px; height: 12px" class="topo" >Cidade-Estado ou País</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width:450px; height: 12px" class="topo">
                <?php echo ' ' ?>
            </td>
            <td style="width:229px; height: 12px" class="topo">   
                <?php echo ' ' ?>
            </td>           
        </tr> 
    </tbody>
</table>    
<table>
    <thead>
        <tr>
            <th style="width:250px; height: 12px" class="topo" >Ensino</th>
            <th style="width:100px; height: 12px" class="topo" >Série/Ano</th>
            <th style="width:329px; height: 12px" class="topo" >Curso/Habilidade</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width:250px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td> 
            <td style="width:100px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>   
            <td style="width:329px; height: 12px" class="topo" >
                <?php echo ' ' ?>
            </td>   
        </tr>
    </tbody>
</table> 

<div class="subtitulocab">Matrícula e Renovação de Matrícula</div>

<div class="topo" >

    <div style="text-align: justify" >
        Solicito Matrícula na _______ Série/Ano do Ensino ____________________________ Curso/Habilitação _____________________________
    </div>
    <div style="text-align: left" >
        Declaro acatar as normas regimentais desse estabelecimento de ensino.
    </div>
    <div style="text-align: justify" >
        Assinatura _________________________________________________
    </div> 
    <div style="text-align: right; padding-right: 20px">
        Barueri, ____ de _______________________ 20____.     
    </div>
</div>



<div class="subtitulocab">Para uso da Escola</div>
<table> 
    <tr>
        <td style="width:254px; height: 12px" class="topo" >Movimentação do Aluno</td>
        <td style="width:275px; height: 25px" class="topo" >Responsável</td>
        <td style="width:50px; height: 25px" class="topo" >Idade</td>
        <td style="width:100px; height: 25px" class="topo">Diretor</td>
    </tr>  
</table>
<table>
    <tr>
        <td style="width:62.5px; height: 12px" class="topo" >Ano</td>  
        <td style="width:53.5px; height: 12px" class="topo">Período</td>      
        <td style="width:56px; height: 12px" class="topo" >Série/Ano</td>      
        <td style="width:52px; height: 12px" class="topo" >Turma</td> 
        <td style="width:30px; height: 12px" class="topo" >Ch</td> 
        <td style="width:275.5px; height: 12px" class="topo" >/////////////////////////////////////////////////////////</td>  
        <td style="width:50px; height: 12px" class="topo" >/////////////</td>  
        <td style="width:50px; height: 12px" class="topo" >Desp.</td>
        <td style="width:50px; height: 12px" class="topo" >Visto</td>
    </tr>
    <?php
    for ($x = 1; $x < 10; $x++) {
        ?>
        <tr>    
            <td style="width:62.5px; height: 12px" class="topo" ></td>  
            <td style="width:53.5px; height: 12px" class="topo"></td>      
            <td style="width:56px; height: 12px" class="topo" ></td>      
            <td style="width:52px; height: 12px" class="topo" ></td>
            <td style="width:30px; height: 12px" class="topo" ></td>
            <td style="width:275.5px; height: 12px" class="topo" ></td>  
            <td style="width:50px; height: 12px" class="topo" ></td>  
            <td style="width:50px; height: 12px" class="topo" ></td>
            <td style="width:50px; height: 12px" class="topo" ></td>
        </tr>
        <?php
    }
    ?>
</table>
<div class="subtitulocab">Transferência</div>
<div class="topo">Solicito Transferência de estudos para outro estabelecimento de ensino</div>
<table>
    <tr>
        <td style="width:279px; height: 12px" class="topo" >Responsável</td>
        <td style="width:400px; height: 12px" class="topo" >Diretor</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <td style="width:279px; height: 12px" class="topo" >Assinatura</td>
            <td style="width:200px; height: 12px" class="topo" >Despacho</td>
            <td style="width:200px; height: 12px" class="topo" >Assinatura</td>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td style="width:279px; height: 12px" class="topo" ></td>
            <td style="width:200px; height: 12px" class="topo" ></td>
            <th style="width:200px; height: 12px" class="topo" ></th>
        </tr>
    </tbody>
</table>

<div class="subtitulocab">Autorização</div>
<div class="topo">
    <div style="text-align: justify; font-size: 8pt" >
       Eu, _______________________________________________________, R.G. _______________________ responsável legal pelo(a) aluno(a) _______________________________________________,
        autorizo o uso de materiais produzidos pelo(a) meu filho (minha filha), bem como a divulgação de sua imagem pela Secretaria de Educação, sem nenhum ônus lucrativo, para todos os fins educacionais.
    </div > 
    <div style="text-align: right; padding-right: 20px">Barueri, ____ de _______________________ 20____.</div>

    <div style="text-align: right; padding-right: 20px" >
        <br />
        <span style= "text-decoration: overline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura do Responsável&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>    
    </div> 

</div>
<div class="subtitulocab">Declaração</div>

<div class="topo">
    <div style="text-align: justify; font-size: 8pt" >
        Eu, ______________________________________, R.G. __________ responsável legal pelo(a) aluno(a) acima identificado(a).
        <br />
        Declaro, sob as penas da lei, que resido no município de Barueri, conforme documentação 
        anexa, e que o aluno(a aluna) 
        estuda na Rede Municipal de Ensino de Barueri.
        <br />
        Declaro-me ciente, ainda, de que qualquer tipo de falsidade constante na declaração,
        inclusive, nos documentos residencias, poderá acarretar na adoção de medidas 
        pertinentes a rever a matrícula 
        do(a) aluno(a), 
        bem como na comunicação da conduta criminal ao Ministério Público para apuração
        e aplicação das sanções penais pertinentes, sem prejuízo da adoção das medidas 
        nas esferas administrativa e cível.
    </div > 
    <div style="text-align: right; padding-right: 20px">Barueri, ____ de _______________________ 20____.</div>

    <div style="text-align: right; padding-right: 20px" >
        <br />
        <span style= "text-decoration: overline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura do Responsável&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>    
    </div> 

</div>
<?php
tool::pdfSemRodape();
?>