<?php
// atualizar_status.php
require_once __DIR__ . '/funcoes.php';
exigir_login_api();
exigir_metodo('POST');
require __DIR__ . '/conexao.php';

$dados  = ler_json();
$id     = filter_var($dados['id'] ?? null, FILTER_VALIDATE_INT);
$status = (string)($dados['status'] ?? '');

if (!$id || !array_key_exists($status, STATUS)) {
    responder_json(['sucesso' => false, 'erro' => 'Dados inválidos.'], 400);
}

$stmt = $conn->prepare('UPDATE ordens SET status = ? WHERE id = ?');
$stmt->bind_param('si', $status, $id);

if (!$stmt->execute()) {
    error_log('atualizar_status: ' . $stmt->error);
    responder_json(['sucesso' => false, 'erro' => 'Erro ao atualizar o status.'], 500);
}

responder_json(['sucesso' => true]);
