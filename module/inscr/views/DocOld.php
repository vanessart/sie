<?php
if (!defined('ABSPATH'))
    exit;

$docPesq = $periodo = filter_input(INPUT_POST, 'docPesq');
$nomeCpf = $periodo = filter_input(INPUT_POST, 'nomeCpf');
if (!empty($nomeCpf) && empty($docPesq)) {
    $sql = "SELECT * FROM `inscr_incritos_processo_1` WHERE `id_cpf` LIKE '$nomeCpf' or `nome` LIKE '%$nomeCpf%' ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        foreach ($array as $k => $v) {
            $array[$k]['ac'] = formErp::submit('Acessar', null, ['nomeCpf' => $v['id_cpf'], 'docPesq' => 1]);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'CPF' => 'id_cpf',
            'Nome' => 'nome',
            '||1' => 'ac'
        ];
    } else {
        ?>
        <div class="alert alert-danger">
            Não encontrado
        </div>
        <?php
    }
} elseif (!empty($docPesq)) {
    $sql = "SELECT * FROM inscr_incritos_processo_1 i "
            . " join civil ci on ci.id_civil = i.fk_id_civil "
            . " WHERE i.id_cpf LIKE '$nomeCpf' ";
    $query = pdoSis::getInstance()->query($sql);
    $pess = $query->fetch(PDO::FETCH_ASSOC);
    $sql = "SELECT * FROM inscr_upload_processo_1 u "
            . " join inscr_final_tipo_up us on us.id_ftu = u.fk_id_ftu "
            . " WHERE u.cpf LIKE '$nomeCpf' ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        foreach ($array as $k => $v) {
            $location = HOME_URI . '/pub/inscrOnline/processo_1/' . $v['link'];
            $array[$k]['ac'] = formErp::submit('Visualizar', null, null, $location, 1);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Tipo' => 'descr_up',
            'Nome Original' => 'nome_origin',
            '||1' => 'ac'
        ];
    } else {
        ?>
        <div class="alert alert-danger">
            Documento não encontrado
        </div>
        <?php
    }
}
?>
<div class="body">
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-10">
                <?= formErp::input('nomeCpf', 'Nome ou CPF', $nomeCpf) ?>
            </div>
            <div class="col-2">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br />
    </form>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    if (!empty($pess)) {
        $mostra = [
            'nome' => 'Nome',
            'id_cpf' => 'CPF',
            'nome_social' => 'Nome Social',
            'pai' => 'Pai',
            'mae' => 'Mãe',
            'rg' => 'RG',
            'rg_dig' => 'RG Dig',
            'rg_oe' => 'RG OE',
            'dt_rg' => 'RG Data',
            'dt_nasc' => 'Dt.Nasc.',
            'n_civil' => 'Estado Civil',
            'sexo' => 'Sexo',
            'municipio_nasc' => 'Municipio Nasc.',
            'estado_nasc' => 'Estado Nasc.',
            'pais_nasc' => 'País de Nasc.',
            'nacionalidade' => 'Nacionalidade',
            'email' => 'E-mail',
            'logradouro' => 'Logradouro',
            'num' => 'Número',
            'complemento' => 'Complemento',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'cep' => 'CEP',
            'fixo' => 'Tel. Fixo',
            'celular' => 'Celular',
            'obs' => 'Obs:',
            'conta_banco' => 'Conta Banco',
            'conta_agencia' => 'Conta Agencia',
            'conta_num' => 'Conta Número',
            'conta_dig' => 'Donta Dígito',
        ];
        ?>
        <table class="table table-bordered table-hover table-striped">
            <?php
            foreach ($pess as $k => $v) {
                if (key_exists($k, $mostra) && !empty($v)) {
                    ?>
                    <tr>
                        <td>
                            <?= $mostra[$k] ?>
                        </td>
                        <td>
                            <?php
                            if ($k == 'dt_nasc') {
                                echo data::converte($v);
                            } else {
                                echo $v;
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <?php
    }
    ?>
</div>
