<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$usuarios = $conexao->query('SELECT id_usuario, nome_usuario FROM usuarios ORDER BY nome_usuario');
$usuarioSelecionadoId = isset($_GET['usuario_id']) ? (int) $_GET['usuario_id'] : 0;
$nomeUsuarioSelecionado = '';

if ($usuarioSelecionadoId > 0) {
    $consultaUsuario = $conexao->prepare('SELECT nome_usuario FROM usuarios WHERE id_usuario = ?');
    $consultaUsuario->bind_param('i', $usuarioSelecionadoId);
    $consultaUsuario->execute();
    $resultadoUsuario = $consultaUsuario->get_result();

    if ($resultadoUsuario->num_rows > 0) {
        $nomeUsuarioSelecionado = $resultadoUsuario->fetch_assoc()['nome_usuario'];
    } else {
        $usuarioSelecionadoId = 0;
    }

    $consultaUsuario->close();
}

if ($usuarioSelecionadoId > 0) {
    $consultaPratos = $conexao->prepare(
        'SELECT id_prato, nome_prato, descricao, preco, categoria
         FROM pratos
         WHERE id_usuario = ?
         ORDER BY nome_prato'
    );
    $consultaPratos->bind_param('i', $usuarioSelecionadoId);
    $consultaPratos->execute();
    $pratos = $consultaPratos->get_result();
} else {
    $pratos = null;
}

renderizar_cabecalho('Pratos por usuário');
?>

<section class="card">
    <h2>Consulta por usuário</h2>
    <p class="texto-suave">Escolha um usuário para ver os pratos cadastrados por ele.</p>

    <form method="GET">
        <div class="form-grid">
            <div class="campo">
                <label for="usuario_id">Usuário</label>
                <select id="usuario_id" name="usuario_id">
                    <option value="">Selecione</option>
                    <?php if ($usuarios && $usuarios->num_rows > 0): ?>
                        <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                            <option value="<?php echo (int) $usuario['id_usuario']; ?>" <?php echo ((int) $usuarioSelecionadoId === (int) $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                <?php echo esc($usuario['nome_usuario']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="campo">
                <label>&nbsp;</label>
                <button class="botao" type="submit">Buscar pratos</button>
            </div>
        </div>
    </form>
</section>

<section class="card">
    <h2>
        <?php if ($usuarioSelecionadoId > 0): ?>
            Pratos de <?php echo esc($nomeUsuarioSelecionado); ?>
        <?php else: ?>
            Selecione um usuário
        <?php endif; ?>
    </h2>

    <?php if ($usuarioSelecionadoId > 0 && $pratos && $pratos->num_rows > 0): ?>
        <table class="lista">
            <thead>
                <tr>
                    <th>Prato</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($prato = $pratos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo esc($prato['nome_prato']); ?></td>
                        <td><span class="tag"><?php echo esc($prato['categoria']); ?></span></td>
                        <td>R$ <?php echo number_format((float) $prato['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo esc($prato['descricao']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($usuarioSelecionadoId > 0): ?>
        <p class="texto-suave">Esse usuário ainda não cadastrou pratos.</p>
    <?php else: ?>
        <p class="texto-suave">Use o formulário acima para fazer a consulta.</p>
    <?php endif; ?>
</section>

<?php renderizar_rodape(); ?>

