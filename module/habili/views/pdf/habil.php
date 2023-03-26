<?php
if (!defined('ABSPATH'))
    exit;
$pdf = new pdf();
$hidden['codigo'] = $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_NUMBER_INT);
$hidden['id_cur'] = $id_cur = filter_input(INPUT_POST, 'id_cur', FILTER_SANITIZE_NUMBER_INT);
$hidden['id_disc'] = $id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$hidden['id_ciclo'] = $id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$hidden['nHab'] = $nHab = filter_input(INPUT_POST, 'nHab', FILTER_SANITIZE_STRING);
$hidden['atual_letiva'] = $atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING);
$hidden['id_gh'] = $id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_STRING);
$id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_cur)) {
    $curso = sqlErp::get('ge_cursos', '*', ['id_curso' => $id_cur], 'fetch');

    if (empty($curso['qt_letiva']) || $curso['qt_letiva'] == 1) {
        $letiva = [];
    } else {
        foreach (range(1, $curso['qt_letiva']) as $v) {
            $letiva[$v] = $v . 'º';
        }
    }
    $grupSelect = coordena::grupoHabCurso($id_cur);

    $grupoHab = coordena::setGrupCurso();
    if (empty($id_gh)) {
        $id_gh = $hidden['id_gh'] = @$grupoHab[$id_cur];
    }
}
?>
<?php
if (!empty($id_cur)) {
    ?>
    <?php
    $where['h.fk_id_cur'] = $id_cur;
    $where['fk_id_gh'] = $id_gh;
    $where['atual_letiva'] = $atual_letiva;
    if (!empty($id_ciclo)) {
        $where['id_ciclo'] = $id_ciclo;
    }
    if (!empty($id_disc)) {
        $where['id_disc'] = $id_disc;
    }
    if (!empty($codigo)) {
        $where['codigo'] = $codigo;
    }
    if (!empty($nHab)) {
        $where['n_hab'] = '%' . $nHab . '%';
    }

    $hab = habGrupo($where);
    $habCiclo = coordena::habCiclo(array_keys($hab));

    foreach ($hab as $kh => $h) {
        unset($hc);
        if (!empty($habCiclo[$kh])) {
            foreach ($habCiclo[$kh] as $ky => $y) {
                $hc[] = $ky . (!empty($y) ? (' (' . $y . ')') : null);
            }
            @$hab[$kh]['letivas'] = implode('<br /><br />', $hc);
        }
    }
    ob_start();

    if ($id_disc) {
        $n_disc = '<p>'
                . sql::get('ge_disciplinas', 'n_disc', ['id_disc' => $id_disc], 'fetch')['n_disc']
                . ' </p>';
    } else {
        $n_disc = null;
    }
    if ($atual_letiva) {
        $n_letiva = ' <p>'
                . ' - ' . $atual_letiva . 'Bimestre'
                . '</p>';
    } else {
        $n_letiva = null;
    }
    if ($id_ciclo) {
        $n_ciclo = sql::get('ge_ciclos', 'n_ciclo', ['id_ciclo' => $id_ciclo], 'fetch')['n_ciclo'];
    } else {
        $n_ciclo = null;
    }
    $headerAlt = '<table style="width: 100%"><tr>'
            . '<td style="width: 100px">'
            . '<img src="' . HOME_URI . '/includes/images/brasao.png" alt="alt"/>'
            . '</td>'
            . '<td style="width: 100px">'
            . '<img src="' . HOME_URI . '/includes/images/topo1.png" alt="alt"/>'
            . '</td>'
            . '<td style="text-align: center; font-weight: bold; ">'
            . '<p style="font-size: 22px">'
            . 'Conteúdo Curricular de Barueri'
            . '</p>'
            . ' <p style="font-size: 20px">'
            . $curso['n_curso']
            . '</p>'
            . $n_disc
            . $n_ciclo
            . $n_letiva
            . '<td style="width: 100px">'
            . '<img src="' . HOME_URI . '/includes/images/topo2.png" alt="alt"/>'
            . '</td>'
            . '</tr></table>';
    ?> 
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <thead>
            <tr>
                <td  style="background-color: black; color: white; width: 11%">
                    Ciclo (Unid. Letiva)
                </td>
                <td  style="background-color: black; color: white; width: 11%">
                    Disciplina
                </td>
                <td  style="background-color: black; color: white; width: 11%">
                    Unidade Temática
                </td>
                <td style="background-color: black; color: white; width: 11%">
                    Objeto de conhecimento
                </td>
                <td style="background-color: black; color: white; width: 11%">
                    Habilidade
                </td>
                <td style="background-color: black; color: white; width: 11%">
                    Habilidade alicerce
                </td>
                <td style="background-color: black; color: white; width: 11%">
                    Sugestão metodológica
                </td>
                <td style="background-color: black; color: white; width: 11%">
                    Sugestão de verificação de aprendizagem
                </td>
                <td style="background-color: black; color: white; width: 11%">
                    Conhecimento prévio
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($hab as $v) {
                ?>
                <tr>
                    <td valign="top">
                        <?= $v['letivas'] ?>
                    </td>
                    <td valign="top" >
                        <?= $v['main']['n_disc'] ?>
                    </td>
                    <td valign="top" >
                        <?= $v['main']['n_ut'] ?>
                    </td>
                    <td valign="top">
                        <?= $v['main']['n_oc'] ?>
                    </td>
                    <td valign="top">
                        <?= $v['main']['codigo'] ?> - <?= $v['main']['descricao'] ?>
                    </td>
                    <td valign="top">
                        <?php
                        if (!empty($v['alicerces'])) {
                            foreach ($v['alicerces'] as $a) {
                                ?>
                                <p>
                                    <?= $a['codigo'] ?> - <?= $a['descricao'] ?>
                                </p>
                                <?php
                            }
                        }
                        ?>
                    </td>
                    <td valign="top">
                        <?= $v['main']['metodologicas'] ?>
                    </td>
                    <td valign="top">
                        <?= $v['main']['verific_aprendizagem'] ?>
                    </td>
                    <td valign="top">
                        <?php
                        if (!empty($v['previa'])) {
                            foreach ($v['previa'] as $a) {
                                ?>
                                <p>
                                    <?= $a['codigo'] ?> - <?= $a['descricao'] ?>
                                </p>
                                <?php
                            }
                        }
                        ?>
                    </td>
                </tr>

                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}

function habGrupo($where = NULL) {
    if (!empty($where['fk_id_gh'])) {
        $where['h.fk_id_gh'] = $where['fk_id_gh'];
    }
    unset($where['fk_id_gh']);
    if (!empty($where) && is_array($where)) {
        if (!empty($where['atual_letiva'])) {
            $atual_letiva = " and atual_letiva like '%" . $where['atual_letiva'] . "%'";
        } else {
            $atual_letiva = null;
        }
        unset($where['atual_letiva']);
        $where_ = sql::where($where);
    }
    if (empty($where['id_ciclo'])) {
        if (empty($fields)) {
            $fields = ' d.n_disc, h.*, allx.descricao as n_hab_alicerces, '
                    . 'allx.codigo as codigo_alicerces, pree.descricao as n_hab_previa, '
                    . 'pree.codigo as codigo_previa, n_ut, n_oc ';
        }

        $sql = "SELECT distinct $fields FROM coord_hab h "
                . " JOIN coord_hab_ciclo cc on cc.fk_id_hab = h.id_hab "
                . " LEFT JOIN ge_disciplinas d on d.id_disc = h.fk_id_disc "
                . " left join coord_hab_alicerces al on al.fk_id_hab = h.id_hab "
                . " left join coord_hab_previas pre on pre.fk_id_hab = h.id_hab "
                . " left join coord_hab allx on allx.id_hab = al.fk_id_hab_alicerce and al.fk_id_hab = h.id_hab "
                . " left join coord_hab pree on pree.id_hab = pre.fk_id_hab_previa and pre.fk_id_hab = h.id_hab "
                . " left join coord_uni_tematica te on te.id_ut = h.fk_id_ut "
                . " left join coord_objeto_conhecimento oc on oc.id_oc = h.fk_id_oc "
                . $where_
                . $atual_letiva
                . " order by d.n_disc, h.ordem ";
    } else {
        if (empty($fields)) {
            $fields = ' d.n_disc, ci.n_ciclo, cc.atual_letiva as letivas, '
                    . 'h.*, allx.descricao as n_hab_alicerces, '
                    . 'allx.codigo as codigo_alicerces, pree.descricao as n_hab_previa, '
                    . 'pree.codigo as codigo_previa, n_ut, n_oc ';
        }
        $sql = "SELECT distinct $fields FROM coord_hab h "
                . " LEFT JOIN ge_disciplinas d on d.id_disc = h.fk_id_disc "
                . " JOIN coord_hab_ciclo cc on cc.fk_id_hab = h.id_hab "
                . " JOIN ge_ciclos ci on ci.id_ciclo = cc.fk_id_ciclo "
                . " left join coord_hab_alicerces al on al.fk_id_hab = h.id_hab "
                . " left join coord_hab_previas pre on pre.fk_id_hab = h.id_hab "
                . " left join coord_hab allx on allx.id_hab = al.fk_id_hab_alicerce and al.fk_id_hab = h.id_hab "
                . " left join coord_hab pree on pree.id_hab = pre.fk_id_hab_previa and pre.fk_id_hab = h.id_hab "
                . " left join coord_uni_tematica te on te.id_ut = h.fk_id_ut "
                . " left join coord_objeto_conhecimento oc on oc.id_oc = h.fk_id_oc "
                . $where_
                . $atual_letiva
                . " order by ci.n_ciclo, d.n_disc, h.ordem ";
    }
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        if (!empty($v['n_hab_previa'])) {
            $hab[$v['id_hab']]['previa'][$v['codigo_previa']] = ['n_hab' => $v['n_hab_previa'], 'codigo' => $v['codigo_previa']];
            unset($v['n_hab_previa']);
            unset($v['codigo_previa']);
        }
        if (!empty($v['n_hab_alicerces'])) {
            $hab[$v['id_hab']]['alicerces'][$v['codigo_alicerces']] = ['n_hab' => $v['n_hab_alicerces'], 'codigo' => $v['codigo_alicerces']];
            unset($v['n_hab_alicerces']);
            unset($v['codigo_alicerces']);
        }
        $hab[$v['id_hab']]['main'] = $v;
    }
    unset($array);
    return $hab;
}

$pdf->headerAlt = $headerAlt;
$pdf->orientation = 'L';
$pdf->exec();
