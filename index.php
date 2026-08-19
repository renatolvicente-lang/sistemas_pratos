<?php

require_once __DIR__ . '/includes/bootstrap.php';

$nomeUsuario = '';
$emailUsuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeUsuario = trim($_POST['usuario'] ?? '');
    $emailUsuario = trim($_POST['email'] ?? '');

    if ($nomeUsuario === '' || $emailUsuario === '') {
        flash('erro', 'Preencha nome e e-mail para cadastrar o usuário.');
    } else {
        $verificar = $conexao->prepare('SELECT id_usuario FROM usuarios WHERE nome_usuario = ? OR email = ?');
        $verificar->bind_param('ss', $nomeUsuario, $emailUsuario);
        $verificar->execute();
        $resultadoVerificacao = $verificar->get_result();

        if ($resultadoVerificacao->num_rows > 0) {
            flash('aviso', 'Esse usuário ou e-mail já está cadastrado.');
        } else {
            $inserir = $conexao->prepare('INSERT INTO usuarios (nome_usuario, email) VALUES (?, ?)');
            $inserir->bind_param('ss', $nomeUsuario, $emailUsuario);

            if ($inserir->execute()) {
                flash('sucesso', 'Usuário cadastrado com sucesso.');
                $nomeUsuario = '';
                $emailUsuario = '';
            } else {
                flash('erro', 'Não foi possível cadastrar o usuário.');
            }

            $inserir->close();
        }

        $verificar->close();
    }
}

$usuarios = $conexao->query('SELECT id_usuario, nome_usuario, email FROM usuarios ORDER BY nome_usuario');

renderizar_cabecalho('Cadastro de usuários');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="styles/css/style.css">
</head>
<body>
    <section class="grade">
    <article class="card">
        <h2>Novo usuário</h2>
        <p class="texto-suave">Cadastre o responsável pelos pratos do restaurante.</p>

        <?php echo flash(); ?>

        <form method="POST">
            <div class="form-grid">
                <div class="campo">
                    <label for="usuario">Nome</label>
                    <input type="text" id="usuario" name="usuario" value="<?php echo esc($nomeUsuario); ?>" placeholder="Ex.: Maria Silva">
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo esc($emailUsuario); ?>" placeholder="exemplo@restaurante.com">
                </div>
            </div>

            <div class="acoes-form">
                <button class="botao" type="submit">Cadastrar usuário</button>
                <a class="botao-secundario" href="/sistemas_pratos/public/cadastro_pratos.php">Ir para pratos</a>
                <a class="botao-secundario" href="/sistemas_pratos/public/usuarios.php">Ver usuários</a>
            </div>
        </form>
    </article>

    
</section>

</body>
</html>

<?php renderizar_rodape(); ?>
