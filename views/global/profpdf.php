<div class="fieldBody">
    <?php
    $sql = "SELECT * FROM prod.aaa a 
join ge.funcionarios f on f.MATRICULA = a.rm 
join ge.pessoa p on p.cpf = f.CPF 
WHERE p.id_pessoa = " . tool::id_pessoa();
    $query = $model->db->query($sql);
    $func = $query->fetchAll();
    ?>
    <div style="text-align: center; font-size: 20px">
        Bonus Produtividade
    </div>
    <br /><br />
    <div style="text-align: center; font-size: 20px">
        Metodologia de Calculo
    </div>
    <br /><br />
    <table border="1" style="width: 100%">
        <tr>
            <td style="text-align: justify; padding: 10px">
                <strong>método 1</strong> - Seu bônus foi calculado a partir da média aritmética das notas das classes de sua escola, por você não ter alocação em classe específica ou por não ser professor.							
                <br /><br />
                <strong>método 2</strong> - Seu bônus foi calculado a partir da média aritmética das notas de todas as escolas da rede, por estar afastado das atividades do cargo em atendimento à Secretaria de Educação							
                <br /><br />
                <strong>método 3</strong> - Seu bônus foi calculado a partir de sua nota individual, aferida por um avaliador externo.							
                <br /><br />
                <strong>método 4</strong> - Seu bônus foi calculado a partir da média aritmética das notas das classes nas quais você foi alocado.							
                <br /><br />
                <strong>método 5</strong> - Seu bonus foi calculado a partir da média aritmética das notas das classes de 4.o ao 9.o anos, nas quais você foi alocado e de sua nota individual aferida por um avaliador externo (esta nota foi repetida em todas as classes do ciclo de alfabetização, bem como nas classes do segmento infantil nas quais você foi alocado)							
                <br /><br />
                <strong>método 6</strong> - Seu bonus foi calculado a partir da média aritmética das notas das classes de sua escola e de sua nota individual.							
                <br /><br />
                Obs: Nas classes do infantil, em que mais de um professor leciona, a nota da classe será calculada a partir da média das notas de todos os professores.
                <br />
                As notas dos alunos estão disponíveis no Subsistema "Avaliação Global" na aba "Resultados Individuais".
            </td>
        </tr>
    </table>
    <div style="page-break-after: always"></div>
    <style>
        #fun td{
            padding: 4px;
        }
        th{
            padding: 4px;
        }
    </style>
    <table id="fun" style="width: 100%" border="1">
        <thead>
            <tr>
                <th>
                    Matrícula
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Mét.
                </th>
                <th>
                    Nota
                </th>
                <th>
                    Perc.
                </th>
                <th style="width: 355px">
                    Calculo
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($func as $v) {
                ?>
                <tr>
                    <td>
                        <?php echo $v['rm'] ?>
                    </td>
                    <td>
                        <?php echo $v['nome'] ?>
                    </td>
                    <td>
                        <?php echo $v['metodo'] ?>
                    </td>
                    <td>
                        <?php echo $v['nota'] ?>
                    </td>
                    <td>
                        <?php echo ($v['perc'] * 100) . '%' ?>
                    </td>
                    <td>
                        <?php
                        if (!empty($v['calculo'])) {

                            if ($v['metodo'] == 6) {
                                $v['calculo'] = str_replace(['/3', 'Média('], [') / 2', 'Média(('], $v['calculo']);
                            }

                            echo str_replace([';', ' - ', ' - , ', 'Fase Maternal', 'Maternal Fase'], ['<br />', ' ', ', ', 'Maternal', 'Maternal'], $v['calculo']);
                        } elseif ($v['metodo'] == 1) {
                            ?>
                            Nota da ESCOLA
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>    
</div>
