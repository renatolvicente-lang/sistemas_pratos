<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$nomePrato = '';
$descricaoPrato = '';
$precoPrato = '';
$categoriaPrato = '';
$usuarioSelecionadoId = isset($_GET['usuario_id']) ? (int) $_GET['usuario_id'] : 0;

$usuarios = $conexao->query('SELECT id_usuario, nome_usuario FROM usuarios ORDER BY nome_usuario');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomePrato = trim($_POST['nome_prato'] ?? '');
    $descricaoPrato = trim($_POST['descricao'] ?? '');
    $precoPrato = trim($_POST['preco'] ?? '');
    $categoriaPrato = trim($_POST['categoria'] ?? '');
    $usuarioSelecionadoId = (int) ($_POST['id_usuario'] ?? 0);

    if ($nomePrato === '' || $descricaoPrato === '' || $precoPrato === '' || $categoriaPrato === '' || $usuarioSelecionadoId <= 0) {
        flash('erro', 'Preencha todos os campos para cadastrar o prato.');
    } else {
        $verificarUsuario = $conexao->prepare('SELECT id_usuario FROM usuarios WHERE id_usuario = ?');
        $verificarUsuario->bind_param('i', $usuarioSelecionadoId);
        $verificarUsuario->execute();
        $resultadoUsuario = $verificarUsuario->get_result();

        if ($resultadoUsuario->num_rows === 0) {
            flash('aviso', 'Selecione um usuário válido para cadastrar o prato.');
        } else {
            $precoConvertido = (float) str_replace(',', '.', $precoPrato);

            $inserir = $conexao->prepare(
                'INSERT INTO pratos (nome_prato, descricao, preco, categoria, id_usuario) VALUES (?, ?, ?, ?, ?)'
            );
            $inserir->bind_param('ssdsi', $nomePrato, $descricaoPrato, $precoConvertido, $categoriaPrato, $usuarioSelecionadoId);

            if ($inserir->execute()) {
                flash('sucesso', 'Prato cadastrado com sucesso.');
                $nomePrato = '';
                $descricaoPrato = '';
                $precoPrato = '';
                $categoriaPrato = '';
            } else {
                flash('erro', 'Não foi possível cadastrar o prato.');
            }

            $inserir->close();
        }

        $verificarUsuario->close();
    }

    redirecionar('/sistemas_pratos/public/cadastro_pratos.php' . ($usuarioSelecionadoId > 0 ? '?usuario_id=' . $usuarioSelecionadoId : ''));
}

$listaPratos = null;
$nomeFiltroUsuario = '';

if ($usuarioSelecionadoId > 0) {
    $consultaFiltro = $conexao->prepare('SELECT nome_usuario FROM usuarios WHERE id_usuario = ?');
    $consultaFiltro->bind_param('i', $usuarioSelecionadoId);
    $consultaFiltro->execute();
    $resultadoFiltro = $consultaFiltro->get_result();

    if ($resultadoFiltro->num_rows > 0) {
        $nomeFiltroUsuario = $resultadoFiltro->fetch_assoc()['nome_usuario'];
    } else {
        $usuarioSelecionadoId = 0;
    }

    $consultaFiltro->close();
}

if ($usuarioSelecionadoId > 0) {
    $consultaPratos = $conexao->prepare(
        'SELECT p.id_prato, p.nome_prato, p.descricao, p.preco, p.categoria, u.nome_usuario
         FROM pratos p
         INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
         WHERE p.id_usuario = ?
         ORDER BY p.nome_prato'
    );
    $consultaPratos->bind_param('i', $usuarioSelecionadoId);
    $consultaPratos->execute();
    $listaPratos = $consultaPratos->get_result();
} else {
    $listaPratos = $conexao->query(
        'SELECT p.id_prato, p.nome_prato, p.descricao, p.preco, p.categoria, u.nome_usuario
         FROM pratos p
         INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
         ORDER BY p.nome_prato'
    );
}

renderizar_cabecalho('Cadastro de pratos');
?>

<section class="bloco-grid">
    <article class="card">
        <h2>Novo prato</h2>
        <p class="texto-suave">Cada prato é vinculado ao usuário que fez o cadastro.</p>

        <?php echo flash(); ?>

        <?php if ($usuarios && $usuarios->num_rows > 0): ?>
            <form method="POST">
                <div class="form-grid">
                    <div class="campo">
                        <label for="nome_prato">Nome do prato</label>
                        <input type="text" id="nome_prato" name="nome_prato" value="<?php echo esc($nomePrato); ?>" placeholder="Ex.: Filé à parmegiana">
                    </div>

                    <div class="campo">
                        <label for="categoria">Categoria</label>
                        <input type="text" id="categoria" name="categoria" value="<?php echo esc($categoriaPrato); ?>" placeholder="Ex.: Principal">
                    </div>

                    <div class="campo">
                        <label for="preco">Preço</label>
                        <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?php echo esc($precoPrato); ?>" placeholder="0,00">
                    </div>

                    <div class="campo">
                        <label for="id_usuario">Usuário responsável</label>
                        <select id="id_usuario" name="id_usuario">
                            <option value="">Selecione</option>
                            <?php
                            $usuarios->data_seek(0);
                            while ($usuario = $usuarios->fetch_assoc()):
                            ?>
                                <option value="<?php echo (int) $usuario['id_usuario']; ?>" <?php echo ((int) $usuarioSelecionadoId === (int) $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                    <?php echo esc($usuario['nome_usuario']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="campo" style="margin-top: 16px;">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" placeholder="Descreva o prato"><?php echo esc($descricaoPrato); ?></textarea>
                </div>

                <div class="acoes-form">
                    <button class="botao" type="submit">Cadastrar prato</button>
                    <a class="botao-secundario" href="/sistemas_pratos/index.php">Cadastrar usuários</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alerta alerta-aviso">Cadastre pelo menos um usuário antes de adicionar pratos.</div>
        <?php endif; ?>
    </article>

    <article class="card">
        <h2>Filtro por usuário</h2>
        <p class="texto-suave">Escolha um usuário para ver somente os pratos cadastrados por ele.</p>

        <form method="GET">
            <div class="campo">
                <label for="usuario_id">Usuário</label>
                <select id="usuario_id" name="usuario_id">
                    <option value="">Todos os usuários</option>
                    <?php
                    if ($usuarios && $usuarios->num_rows > 0) {
                        $usuarios->data_seek(0);
                        while ($usuario = $usuarios->fetch_assoc()):
                        ?>
                            <option value="<?php echo (int) $usuario['id_usuario']; ?>" <?php echo ((int) $usuarioSelecionadoId === (int) $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                <?php echo esc($usuario['nome_usuario']); ?>
                            </option>
                        <?php endwhile;
                    }
                    ?>
                </select>
            </div>

            <div class="acoes-form">
                <button class="botao-secundario" type="submit">Filtrar</button>
                <a class="botao-secundario" href="/sistemas_pratos/public/cadastro_pratos.php">Ver todos</a>
            </div>
        </form>
    </article>
</section>

<section class="card">
    <h2>
        <?php if ($usuarioSelecionadoId > 0): ?>
            Pratos de <?php echo esc($nomeFiltroUsuario); ?>
        <?php else: ?>
            Pratos cadastrados
        <?php endif; ?>
    </h2>

    <?php if ($listaPratos && $listaPratos->num_rows > 0): ?>
        <table class="lista">
            <thead>
                <tr>
                    <th>Prato</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Usuário</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($prato = $listaPratos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo esc($prato['nome_prato']); ?></td>
                        <td><span class="tag"><?php echo esc($prato['categoria']); ?></span></td>
                        <td>R$ <?php echo number_format((float) $prato['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo esc($prato['nome_usuario']); ?></td>
                        <td><?php echo esc($prato['descricao']); ?></td>
                        <td>
                            <div class="acoes">
                                <a class="botao-secundario" href="/sistemas_pratos/public/editar_prato.php?id=<?php echo (int) $prato['id_prato']; ?>">Editar</a>
                                <form method="POST" action="/sistemas_pratos/public/excluir_prato.php" onsubmit="return confirm('Tem certeza que deseja excluir este prato?');">
                                    <input type="hidden" name="id_prato" value="<?php echo (int) $prato['id_prato']; ?>">
                                    <input type="hidden" name="retorno" value="/sistemas_pratos/public/cadastro_pratos.php<?php echo $usuarioSelecionadoId > 0 ? '?usuario_id=' . (int) $usuarioSelecionadoId : ''; ?>">
                                    <button class="botao-perigo" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="texto-suave">Nenhum prato cadastrado ainda.</p>
    <?php endif; ?>
</section>

<?php renderizar_rodape(); ?>

