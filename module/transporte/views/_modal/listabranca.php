<?php
if (!defined('ABSPATH'))
    exit;

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
?>
<div class="body">
    <form method="POST">
        <div class="row">
            <div class="col-sm-8">
                <?php echo formErp::input('nome', 'Nome', $nome) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::button('Buscar'); ?>
            </div>
        </div>
        <br /><br />
    </form>
        <?php
        if (!empty($nome)) {
            $result = ng_aluno::busca($nome);
            if (empty($result)) {
                ?>
                <div class="alert alert-danger text-center">
                    Nenhum resultado encontrado
                </div>
                <?php
            } else {
                $token = formErp::token('transporte_lita_branca', 'ireplace');
                foreach ($result as $k => $v) {
                    $removeItem = true;

                    //se nao está em um curso ativo
                    if (!empty($v['cursoAtivo'])) {
                        foreach ($v['cursoAtivo'] as $item) {
                            if (in_array($item['id_curso'], [1, 3])) {
                                $removeItem = false;
                                break;
                            }
                        }
                    }

                    if ($removeItem) {
                        unset($result[$k]);
                        continue;
                    }

                    if (!empty($v['cursoAtivo'])) {
                        $result[$k]['n_inst'] = @$v['cursoAtivo'][0]['n_inst'];
                        $result[$k]['codigo'] = @$v['cursoAtivo'][0]['codigo'];
                    }
                    $result[$k]['ac'] = formErp::submit('Incluir', $token, ['1[id_pessoa]' => $v['id_pessoa']], HOME_URI ."/transporte/listabranca", "_parent");
                }
                $form['array'] = $result;
                $form['fields'] = [
                    'RA' => 'ra',
                    'Nome' => 'n_pessoa',
                    'Escola' => 'n_inst',
                    'Turma' => 'codigo',
                    '||'=>'ac'
                ];
                toolErp::relatSimples($form);
            }
        }
    ?>
</div>