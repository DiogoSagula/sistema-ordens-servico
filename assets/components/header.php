<?php
// Cabeçalho + menu. Incluir depois do trava.php.
require_once __DIR__ . '/../../funcoes.php';
iniciar_sessao();
?>
<header class="portal-header">
    <div class="container header-container">
        <div class="logo">
            <img src="assets/img/logo.png" alt="Logo" onerror="this.style.display='none'">
        </div>

        <div class="user-info">
            <span>Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário', ENT_QUOTES, 'UTF-8'); ?></strong></span>
            <a href="logout.php" class="logout-button" title="Sair">
                Sair <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</header>

<nav class="portal-nav">
    <div class="container">
        <ul>
            <li>
                <a href="index.php" id="link-home">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="nova-ordem.php" id="link-nova">
                    <i class="fas fa-plus-circle"></i> Nova Ordem
                </a>
            </li>
            <li>
                <a href="relatorios.php" id="link-relatorios">
                    <i class="fas fa-file-alt"></i> Relatórios
                </a>
            </li>
        </ul>
    </div>
</nav>
