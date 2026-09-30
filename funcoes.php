<?php
// funcoes.php
// Funções compartilhadas: sessão, proteção de rotas, respostas JSON e listas fixas.

// ---------- Listas fixas (única fonte de verdade) ----------

const STATUS = [
    'recebido'   => 'Pedido Recebido',
    'aguardando' => 'Aguardando Produção',
    'produzindo' => 'Produzindo',
    'pronto'     => 'Pronto',
    'retirado'   => 'Retirado ou Encaminhado',
];

// valor do <select> => texto salvo no banco
const MATERIAIS = [
    'mdf-cru'           => 'MDF Cru',
    'mdf-madeirado'     => 'MDF Madeirado',
    'mdf-branco'        => 'MDF Branco',
    'compensado-naval'  => 'Compensado Naval',
    'compensado-virola' => 'Compensado Paricá',
    'pinus'             => 'Pinus',
];

const ESPESSURAS_MM = [3, 4, 6, 10, 15, 18, 25, 30];

const TAMANHO_MAX_ANEXO = 5 * 1024 * 1024; // 5 MB
const EXTENSOES_ANEXO   = ['pdf', 'dxf'];

define('DIR_ANEXOS', __DIR__ . '/storage/anexos');

// ---------- Sessão ----------

function iniciar_sessao(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,          // expira ao fechar o navegador
            'path'     => '/',
            'httponly' => true,       // JavaScript não lê o cookie (mitiga XSS)
            'samesite' => 'Strict',   // não é enviado em requisições de outros sites (mitiga CSRF)
            'secure'   => !empty($_SERVER['HTTPS']),
        ]);
        session_start();
    }
}

function usuario_logado(): bool
{
    iniciar_sessao();
    return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
}

// Para páginas: manda para o login.
function exigir_login_pagina(): void
{
    if (!usuario_logado()) {
        header('Location: login.php');
        exit;
    }
}

// Para endpoints JSON: responde 401 (o JavaScript redireciona para o login).
function exigir_login_api(): void
{
    if (!usuario_logado()) {
        responder_json(['sucesso' => false, 'erro' => 'Sessão expirada. Faça login novamente.'], 401);
    }
}

// ---------- Requisição / resposta ----------

function responder_json($dados, int $codigo = 200): void
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

function exigir_metodo(string $metodo): void
{
    if ($_SERVER['REQUEST_METHOD'] !== $metodo) {
        header('Allow: ' . $metodo);
        responder_json(['sucesso' => false, 'erro' => 'Método não permitido.'], 405);
    }
}

// Lê o corpo da requisição como JSON. Devolve [] se vier inválido.
function ler_json(): array
{
    $dados = json_decode(file_get_contents('php://input'), true);
    return is_array($dados) ? $dados : [];
}
