<div class="fieldBody">
    <div class="fieldTop">
        Exportação de dados 2022
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-4">
            <form action="<?php echo HOME_URI ?>/app/excel/doc/expAluno.php" method="POST">
                <table>
                    <tr>
                        <td>
                            <?php
                            $options = sql::idNome('ge_cursos', ['>' => 'n_curso']);
                            echo form::select('id_curso', $options, 'Curso');
                            ?>
                        </td>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                            <button>
                                Alunos
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-2">
            <a href="<?php echo HOME_URI ?>/app/excel/doc/expProf.php">
                <button>
                    Professores
                </button>
            </a> 
        </div>
        <div class="col-sm-2">
            <a href="<?php echo HOME_URI ?>/app/excel/doc/expEscolas.php">
                <button>
                    Escolas
                </button>
            </a>
        </div>
        <div class="col-sm-2">
            <a href="<?php echo HOME_URI ?>/app/excel/doc/expTurma.php">
                <button>
                    Turmas
                </button>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="<?php echo HOME_URI ?>/app/excel/doc/expRmTurmaDisc.php">
                <button>
                    Relação Professor/Turma
                </button>
            </a>
        </div><div class="col-sm-3">
            <a href="<?php echo HOME_URI ?>/app/excel/doc/expAlunoAee.php">
                <button>
                    Relação AEE/Regular
                </button>
            </a>
        </div>
    </div>

</div>