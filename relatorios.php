<?php require 'trava.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Ordens de Serviço</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'assets/components/header.php'; ?>

    <main class="main-content">
        <div class="container">
            <h1>Relatórios de Produção</h1>
            <p style="color: #64748b; margin-bottom: 25px;">
                <i class="fas fa-info-circle"></i> Clique nos cartões abaixo para ver os pedidos detalhados.
            </p>

            <div class="stats-grid">

                <div class="stat-card" data-status="recebido">
                    <div class="card-icon icon-recebido"><i class="fas fa-clipboard-list"></i></div>
                    <div class="card-info">
                        <span class="card-title">Novos Pedidos</span>
                        <span class="card-value" id="recebido-valor">0</span>
                    </div>
                </div>

                <div class="stat-card" data-status="aguardando">
                    <div class="card-icon icon-aguardando"><i class="fas fa-hourglass-half"></i></div>
                    <div class="card-info">
                        <span class="card-title">Aguardando Produção</span>
                        <span class="card-value" id="aguardando-valor">0</span>
                    </div>
                </div>

                <div class="stat-card" data-status="produzindo">
                    <div class="card-icon icon-producao"><i class="fas fa-cogs"></i></div>
                    <div class="card-info">
                        <span class="card-title">Em Produção</span>
                        <span class="card-value" id="produzindo-valor">0</span>
                    </div>
                </div>

                <div class="stat-card" data-status="pronto">
                    <div class="card-icon icon-pronto"><i class="fas fa-check-circle"></i></div>
                    <div class="card-info">
                        <span class="card-title">Prontos p/ Retirada</span>
                        <span class="card-value" id="pronto-valor">0</span>
                    </div>
                </div>

                <div class="stat-card" data-status="retirado">
                    <div class="card-icon icon-finalizado"><i class="fas fa-box-open"></i></div>
                    <div class="card-info">
                        <span class="card-title">Finalizados (Total)</span>
                        <span class="card-value" id="retirado-valor">0</span>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div id="modal-lista-pedidos" class="modal-overlay hidden">
        <div class="modal-content" style="max-width: 800px;">
            <button class="modal-close">&times;</button>
            <h2 id="modal-titulo-lista">Pedidos</h2>

            <div class="modal-body">
                <div class="table-container" style="box-shadow: none; border: 1px solid #e2e8f0; max-height: 400px; overflow-y: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Ordem</th>
                                <th>Cliente</th>
                                <th>Descrição</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody id="lista-pedidos-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="portal-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistema de Ordens de Serviço - Painel de Gestão Interno.</p>
        </div>
    </footer>

    <script src="assets/js/global.js"></script>
    <script src="assets/js/relatorios.js"></script>

</body>
</html>
