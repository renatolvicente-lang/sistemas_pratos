<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$idPrato = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$retorno = '/sistemas_pratos/public/cadastro_pratos.php';

if ($idPrato <= 0) {
    flash('erro', 'Prato inválido para edição.');
    redirecionar($retorno);
}

$consulta = $conexao->prepare(
    'SELECT p.id_prato, p.nome_prato, p.descricao, p.preco, p.categoria, u.nome_usuario, u.id_usuario
     FROM pratos p
     INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
     WHERE p.id_prato = ?'
);
$consulta->bind_param('i', $idPrato);
$consulta->execute();
$resultado = $consulta->get_result();
$prato = $resultado->fetch_assoc();
$consulta->close();

if (!$prato) {
    flash('erro', 'O prato não foi encontrado.');
    redirecionar($retorno);
}

$nomePrato = $prato['nome_prato'];
$descricaoPrato = $prato['descricao'];
$precoPrato = number_format((float) $prato['preco'], 2, '.', '');
$categoriaPrato = $prato['categoria'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomePrato = trim($_POST['nome_prato'] ?? '');
    $descricaoPrato = trim($_POST['descricao'] ?? '');
    $precoPrato = trim($_POST['preco'] ?? '');
    $categoriaPrato = trim($_POST['categoria'] ?? '');

    if ($nomePrato === '' || $descricaoPrato === '' || $precoPrato === '' || $categoriaPrato === '') {
        flash('erro', 'Preencha todos os campos para atualizar o prato.');
    } else {
        $precoConvertido = (float) str_replace(',', '.', $precoPrato);

        $atualizar = $conexao->prepare(
            'UPDATE pratos
             SET nome_prato = ?, descricao = ?, preco = ?, categoria = ?
             WHERE id_prato = ?'
        );
        $atualizar->bind_param('ssdsi', $nomePrato, $descricaoPrato, $precoConvertido, $categoriaPrato, $idPrato);

        if ($atualizar->execute()) {
            flash('sucesso', 'Prato atualizado com sucesso.');
            redirecionar('/sistemas_pratos/public/cadastro_pratos.php?usuario_id=' . (int) $prato['id_usuario']);
        }

        flash('erro', 'Não foi possível atualizar o prato.');
        $atualizar->close();
    }
}

renderizar_cabecalho('Editar prato');
?>

<section class="card">
    <h2>Editar prato</h2>
    <p class="texto-suave">Responsável pelo cadastro: <strong><?php echo esc($prato['nome_usuario']); ?></strong></p>

    <?php echo flash(); ?>

    <form method="POST">
        <div class="form-grid">
            <div class="campo">
                <label for="nome_prato">Nome do prato</label>
                <input type="text" id="nome_prato" name="nome_prato" value="<?php echo esc($nomePrato); ?>">
            </div>

            <div class="campo">
                <label for="categoria">Categoria</label>
                <input type="text" id="categoria" name="categoria" value="<?php echo esc($categoriaPrato); ?>">
            </div>

            <div class="campo">
                <label for="preco">Preço</label>
                <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?php echo esc($precoPrato); ?>">
            </div>

            <div class="campo">
                <label for="usuario">Usuário responsável</label>
                <input type="text" id="usuario" value="<?php echo esc($prato['nome_usuario']); ?>" disabled>
            </div>
        </div>

        <div class="campo" style="margin-top: 16px;">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao"><?php echo esc($descricaoPrato); ?></textarea>
        </div>

        <div class="acoes-form">
            <button class="botao" type="submit">Salvar alterações</button>
            <a class="botao-secundario" href="/sistemas_pratos/public/cadastro_pratos.php?usuario_id=<?php echo (int) $prato['id_usuario']; ?>">Cancelar</a>
        </div>
    </form>
</section>

<?php renderizar_rodape(); ?>

