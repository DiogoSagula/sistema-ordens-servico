<?php
// criar_usuario.php
// Cria (ou atualiza a senha de) um usuário. Só roda pelo terminal:
//   php criar_usuario.php NOME SENHA
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

if ($argc < 3) {
    fwrite(STDERR, "Uso: php criar_usuario.php NOME SENHA\n");
    exit(1);
}

[, $usuario, $senha] = $argv;

if (strlen($senha) < 8) {
    fwrite(STDERR, "A senha precisa ter pelo menos 8 caracteres.\n");
    exit(1);
}

require __DIR__ . '/conexao.php';

$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    'INSERT INTO usuarios (usuario, senha) VALUES (?, ?)
     ON DUPLICATE KEY UPDATE senha = VALUES(senha)'
);
$stmt->bind_param('ss', $usuario, $hash);

if ($stmt->execute()) {
    echo "Usuário '{$usuario}' salvo com sucesso.\n";
} else {
    fwrite(STDERR, "Erro: {$stmt->error}\n");
    exit(1);
}
