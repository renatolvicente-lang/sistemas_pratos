<?php

require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    flash('erro', 'Ação inválida para excluir o prato.');
    redirecionar('/sistemas_pratos/public/cadastro_pratos.php');
}

$idPrato = filter_input(INPUT_POST, 'id_prato', FILTER_VALIDATE_INT) ?: 0;
$retorno = trim($_POST['retorno'] ?? '/sistemas_pratos/public/cadastro_pratos.php');

if ($idPrato <= 0) {
    flash('erro', 'Prato inválido para exclusão.');
    redirecionar($retorno);
}

$excluir = $conexao->prepare('DELETE FROM pratos WHERE id_prato = ?');
$excluir->bind_param('i', $idPrato);

if ($excluir->execute()) {
    flash('sucesso', 'Prato excluído com sucesso.');
} else {
    flash('erro', 'Não foi possível excluir o prato.');
}

$excluir->close();
redirecionar($retorno);

