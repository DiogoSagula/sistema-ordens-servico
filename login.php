<?php
// login.php
// POST (JSON) -> autentica e responde JSON.
// GET         -> mostra a página de login.
require_once __DIR__ . '/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/conexao.php';

    $dados   = ler_json();
    $usuario = trim((string)($dados['usuario'] ?? ''));
    $senha   = (string)($dados['senha'] ?? '');

    if ($usuario === '' || $senha === '') {
        responder_json(['sucesso' => false, 'erro' => 'Informe usuário e senha.'], 400);
    }

    $stmt = $conn->prepare('SELECT id, usuario, senha FROM usuarios WHERE usuario = ?');
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $linha = $stmt->get_result()->fetch_assoc();

    if ($linha && password_verify($senha, $linha['senha'])) {
        iniciar_sessao();
        session_regenerate_id(true); // evita fixação de sessão

        $_SESSION['usuario_id']   = $linha['id'];
        $_SESSION['usuario_nome'] = $linha['usuario'];
        $_SESSION['logado']       = true;

        responder_json(['sucesso' => true, 'nome' => $linha['usuario']]);
    }

    // Mesma mensagem para "usuário não existe" e "senha errada",
    // para não revelar quais usuários existem. A pausa dificulta tentativa em massa.
    usleep(500000);
    responder_json(['sucesso' => false, 'erro' => 'Usuário ou senha inválidos.'], 401);
}

if (usuario_logado()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Ordens de Serviço</title>

    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <div class="login-container">
        <img src="assets/img/logo.png" alt="Logo" class="login-logo" onerror="this.style.display='none'">

        <h2>Acesso ao Painel de Gestão</h2>

        <form id="login-form">
            <div class="input-group">
                <label for="usuario">Usuário</label>
                <i class="fas fa-user"></i>
                <input type="text" id="usuario" name="usuario" placeholder="Digite seu nome de usuário" autocomplete="username" required>
            </div>

            <div class="input-group">
                <label for="password">Senha</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" autocomplete="current-password" required>
                <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
            </div>

            <p id="error-message" class="error-message"></p>

            <div class="options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Lembrar meu usuário
                </label>
            </div>

            <button type="submit" class="login-button">Entrar</button>
        </form>
    </div>

    <script src="assets/js/login.js"></script>

</body>
</html>
