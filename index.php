<?php require 'trava.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Ordens de Serviço</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'assets/components/header.php'; ?>

    <main class="main-content">
        <div class="container">

            <div class="main-title-actions">
                <h1>Painel de Controle - Ordens de Serviço</h1>
                <a href="nova-ordem.php" class="button-primary">
                    <i class="fa fa-plus"></i> Nova Ordem de Serviço
                </a>
            </div>

            <div class="filters">
                <input type="text" id="search-input" placeholder="Buscar por nº, cliente ou descrição...">
                <select id="status-filter">
                    <option value="">Todos os Status</option>
                    <option value="recebido">Pedido Recebido</option>
                    <option value="aguardando">Aguardando Produção</option>
                    <option value="produzindo">Produzindo</option>
                    <option value="pronto">Pronto</option>
                    <option value="retirado">Retirado ou Encaminhado</option>
                </select>
                <button id="filter-button" class="filter-button">Filtrar</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nº da Ordem</th>
                            <th>Cliente</th>
                            <th>Descrição</th>
                            <th>Data de Envio</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        <tr id="no-results-row" class="no-results" style="display: none;">
                            <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">
                                Nenhum resultado encontrado.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <footer class="portal-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistema de Ordens de Serviço - Painel de Gestão Interno.</p>
        </div>
    </footer>

    <div id="order-details-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <button class="modal-close">&times;</button>

            <div class="modal-header">
                <h2 id="modal-title">Ordem #0000</h2>
                <span class="modal-subtitle">Detalhes do Pedido</span>
            </div>

            <div class="modal-body">
                <div class="detail-item">
                    <i class="fas fa-user item-icon"></i>
                    <div class="item-content">
                        <span class="label">Cliente</span>
                        <span id="modal-client" class="value">---</span>
                    </div>
                </div>

                <div class="detail-item">
                    <i class="fas fa-align-left item-icon"></i>
                    <div class="item-content">
                        <span class="label">Descrição do Serviço</span>
                        <span id="modal-description" class="value">---</span>
                    </div>
                </div>

                <div class="detail-item">
                    <i class="fas fa-layer-group item-icon"></i>
                    <div class="item-content">
                        <span class="label">Material e Espessura</span>
                        <span id="modal-material" class="value">---</span>
                    </div>
                </div>

                <div class="detail-item">
                    <i class="fas fa-calendar-alt item-icon"></i>
                    <div class="item-content">
                        <span class="label">Data de Entrada</span>
                        <span id="modal-date" class="value">---</span>
                    </div>
                </div>

                <div class="detail-item" id="area-anexo" style="display: none;">
                    <i class="fas fa-paperclip item-icon" style="background-color: #f3e8ff; color: #7e22ce;"></i>
                    <div class="item-content">
                        <span class="label">Plano de Corte</span>
                        <a id="btn-download-anexo" href="#" class="value" style="color: #2563eb; text-decoration: underline;">
                            Baixar Arquivo Anexado
                        </a>
                    </div>
                </div>

                <div class="status-control-area">
                    <label for="modal-status-select"><i class="fas fa-exchange-alt"></i> Atualizar Status:</label>
                    <div class="select-wrapper">
                        <select name="status" id="modal-status-select">
                            <option value="recebido">Pedido Recebido</option>
                            <option value="aguardando">Aguardando Produção</option>
                            <option value="produzindo">Produzindo</option>
                            <option value="pronto">Pronto</option>
                            <option value="retirado">Retirado ou Encaminhado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="delete-order-button" class="button-danger">
                    <i class="fas fa-trash"></i> Excluir
                </button>
                <button class="save-status-button-modal button-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        </div>
    </div>

    <script src="assets/js/global.js"></script>
    <script src="assets/js/script.js"></script>

</body>
</html>
