<?php
// excluir_ordem.php
require_once __DIR__ . '/funcoes.php';
exigir_login_api();
exigir_metodo('POST');
require __DIR__ . '/conexao.php';

$dados = ler_json();
$id    = filter_var($dados['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    responder_json(['sucesso' => false, 'erro' => 'ID inválido.'], 400);
}

// Descobre o arquivo antes de apagar a linha, para removê-lo do disco também.
$stmt = $conn->prepare('SELECT arquivo_path FROM ordens WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$linha = $stmt->get_result()->fetch_assoc();

if (!$linha) {
    responder_json(['sucesso' => false, 'erro' => 'Ordem não encontrada.'], 404);
}

$stmt = $conn->prepare('DELETE FROM ordens WHERE id = ?');
$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    error_log('excluir_ordem: ' . $stmt->error);
    responder_json(['sucesso' => false, 'erro' => 'Erro ao excluir a ordem.'], 500);
}

if (!empty($linha['arquivo_path'])) {
    @unlink(DIR_ANEXOS . '/' . basename($linha['arquivo_path']));
}

responder_json(['sucesso' => true]);
