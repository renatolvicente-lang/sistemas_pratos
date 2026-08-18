<?php

function caminho_css(): string
{
    $diretorio = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));

    if ($diretorio === '/' || $diretorio === '.') {
        return '/public/css/style.css';
    }

    if (substr($diretorio, -7) === '/public') {
        return $diretorio . '/css/style.css';
    }

    return $diretorio . '/public/css/style.css';
}

function renderizar_cabecalho(string $titulo): void
{
    $css = caminho_css();
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo esc($titulo); ?></title>
        <link rel="stylesheet" href="<?php echo esc($css); ?>">
    </head>
    <body>
        <header class="topo">
            <div class="container topo-conteudo">
                <div>
                    <p class="topo-subtitulo">Sistema de restaurante</p>
                    <h1><?php echo esc($titulo); ?></h1>
                </div>
                <nav class="menu">
                    <a href="/sistemas_pratos/index.php">Usuários</a>
                    <a href="/sistemas_pratos/public/cadastro_pratos.php">Pratos</a>
                    <a href="/sistemas_pratos/public/pratos_por_usuario.php">Por usuário</a>
                </nav>
            </div>
        </header>
        <main class="container conteudo-principal">
    <?php
}

function renderizar_rodape(): void
{
    ?>
        </main>
        <footer class="rodape">
            <div class="container">
                <p>Sistema de pratos do restaurante</p>
            </div>
        </footer>
    </body>
    </html>
    <?php
}
