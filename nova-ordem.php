<?php require 'trava.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Ordem - Ordens de Serviço</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'assets/components/header.php'; ?>

    <main class="main-content">
        <div class="container">
            <div class="form-container">
                <h1>Cadastrar Nova Ordem de Serviço</h1>

                <form id="nova-ordem-form">

                    <div class="form-group">
                        <label for="cliente">Cliente Final</label>
                        <input type="text" id="cliente" name="cliente" maxlength="120" placeholder="Nome do cliente que comprou as peças" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição do Pedido</label>
                        <input type="text" id="descricao" name="descricao" maxlength="255" placeholder="Ex: Mdf Branco 15mm" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="material">Tipo de Material</label>
                            <select id="material" name="material" required>
                                <option value="">Selecione o material</option>
                                <option value="mdf-cru">MDF Cru</option>
                                <option value="mdf-madeirado">MDF Madeirado</option>
                                <option value="mdf-branco">MDF Branco</option>
                                <option value="compensado-naval">Compensado Naval</option>
                                <option value="compensado-virola">Compensado Paricá</option>
                                <option value="pinus">Pinus</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="espessura">Espessura (mm)</label>
                            <select id="espessura" name="espessura" required>
                                <option value="">Selecione a espessura</option>
                                <option value="3">3mm</option>
                                <option value="4">4mm</option>
                                <option value="6">6mm</option>
                                <option value="10">10mm</option>
                                <option value="15">15mm</option>
                                <option value="18">18mm</option>
                                <option value="25">25mm</option>
                                <option value="30">30mm</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="plano-corte">Anexar Plano de Corte (PDF, DXF - até 5 MB)</label>
                        <input type="file" id="plano-corte" name="plano_corte" class="file-input" accept=".pdf,.dxf">
                    </div>

                    <div class="form-actions">
                        <a href="index.php" class="button-secondary">Cancelar</a>
                        <button type="submit" class="button-primary">Enviar Ordem de Serviço</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="portal-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistema de Ordens de Serviço - Painel de Gestão Interno.</p>
        </div>
    </footer>

    <script src="assets/js/global.js"></script>
    <script src="assets/js/nova-ordem.js"></script>

</body>
</html>
