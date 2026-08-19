<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$usuarios = $conexao->query(
    'SELECT u.id_usuario, u.nome_usuario, u.email, COUNT(p.id_prato) AS total_pratos
     FROM usuarios u
     LEFT JOIN pratos p ON p.id_usuario = u.id_usuario
     GROUP BY u.id_usuario, u.nome_usuario, u.email
     ORDER BY u.nome_usuario'
);

renderizar_cabecalho('Usuários cadastrados');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../styles/css/style.css">
</head>
<body>
    <section class="card">
    <h2>Usuários cadastrados</h2>
    <p class="texto-suave">Aqui ficam os usuários registrados no sistema e a quantidade de pratos vinculados a cada um.</p>

    <?php if ($usuarios && $usuarios->num_rows > 0): ?>
        <table class="lista">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Pratos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo esc($usuario['nome_usuario']); ?></td>
                        <td><?php echo esc($usuario['email']); ?></td>
                        <td><span class="tag"><?php echo (int) $usuario['total_pratos']; ?> pratos</span></td>
                        <td>
                            <div class="acoes">
                                <a class="botao-secundario" href="/sistemas_pratos/public/cadastro_pratos.php?usuario_id=<?php echo (int) $usuario['id_usuario']; ?>">Cadastrar prato</a>
                                <a class="botao-secundario" href="/sistemas_pratos/public/pratos_por_usuario.php?usuario_id=<?php echo (int) $usuario['id_usuario']; ?>">Ver pratos</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="texto-suave">Nenhum usuário cadastrado ainda.</p>
    <?php endif; ?>
</section>
</body>
</html>

<?php renderizar_rodape(); ?>

