<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../infra/conexao.php';
require_once __DIR__ . '/funcoes.php';
require_once __DIR__ . '/layout.php';

