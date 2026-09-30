<?php
// baixar_anexo.php?id=123
// Entrega o plano de corte só para quem está logado.
require_once __DIR__ . '/funcoes.php';
exigir_login_pagina();
require __DIR__ . '/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Pedido inválido.');
}

$stmt = $conn->prepare('SELECT arquivo_nome, arquivo_path FROM ordens WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$linha = $stmt->get_result()->fetch_assoc();

$caminho = $linha && $linha['arquivo_path']
    ? DIR_ANEXOS . '/' . basename($linha['arquivo_path'])
    : null;

if ($caminho === null || !is_file($caminho)) {
    http_response_code(404);
    exit('Arquivo não encontrado.');
}

$extensao  = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
$tipo      = $extensao === 'pdf' ? 'application/pdf' : 'application/octet-stream';
$nomeBruto = $linha['arquivo_nome'] ?: ('plano-de-corte.' . $extensao);
$nomeAscii = preg_replace('/[^A-Za-z0-9._-]/', '_', $nomeBruto);

header('Content-Type: ' . $tipo);
header('Content-Length: ' . filesize($caminho));
header("Content-Disposition: attachment; filename=\"{$nomeAscii}\"; filename*=UTF-8''" . rawurlencode($nomeBruto));
header('X-Content-Type-Options: nosniff');
readfile($caminho);
