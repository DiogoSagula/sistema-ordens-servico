<?php
// conexao.php
// Os dados de acesso ao banco NÃO ficam neste arquivo.
// Copie config.example.php para config.local.php e preencha com os seus dados.

$config = [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int)(getenv('DB_PORT') ?: 3306),
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'name' => getenv('DB_NAME') ?: 'sistema_ordens',
];

$arquivoLocal = __DIR__ . '/config.local.php';
if (is_file($arquivoLocal)) {
    $config = array_merge($config, require $arquivoLocal);
}

mysqli_report(MYSQLI_REPORT_OFF); // tratamos os erros manualmente

$conn = @new mysqli($config['host'], $config['user'], $config['pass'], $config['name'], (int)$config['port']);

if ($conn->connect_error) {
    // O detalhe técnico vai para o log do servidor, não para o usuário.
    error_log('Falha na conexão com o banco: ' . $conn->connect_error);

    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, 'Falha na conexão com o banco: ' . $conn->connect_error . "\n");
        exit(1);
    }
    require_once __DIR__ . '/funcoes.php';
    responder_json(['sucesso' => false, 'erro' => 'Erro ao conectar ao banco de dados.'], 500);
}

$conn->set_charset('utf8mb4');