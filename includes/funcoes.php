<?php

function esc($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function flash(?string $tipo = null, ?string $mensagem = null): string
{
    if ($tipo !== null && $mensagem !== null) {
        $_SESSION['flash'] = [
            'tipo' => $tipo,
            'mensagem' => $mensagem,
        ];
        return '';
    }

    if (empty($_SESSION['flash'])) {
        return '';
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return '<div class="alerta alerta-' . esc($flash['tipo']) . '">' . esc($flash['mensagem']) . '</div>';
}

function redirecionar(string $destino): void
{
    header('Location: ' . $destino);
    exit();
}
