document.addEventListener('DOMContentLoaded', () => {

    let listaDeOrdens = [];
    let ordemAtual = null; // ordem aberta no modal

    const tableBody = document.getElementById('orders-table-body');
    const noResultsRow = document.getElementById('no-results-row');
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');

    const modalOverlay = document.getElementById('order-details-modal');
    const statusSelect = document.getElementById('modal-status-select');
    const btnSalvar = document.querySelector('.save-status-button-modal');
    const btnExcluir = document.getElementById('delete-order-button');

    // ---------- 1. Carregar do servidor ----------

    async function carregarOrdens() {
        try {
            listaDeOrdens = await api('listar_ordens.php');
            aplicarFiltros();
        } catch (erro) {
            console.error(erro);
            mostrarErroNaTabela(erro.message);
        }
    }

    function mostrarErroNaTabela(mensagem) {
        const tr = document.createElement('tr');
        const td = criarCelula(mensagem);
        td.colSpan = 6;
        td.style.cssText = 'text-align:center; color:#ef4444; padding:20px;';
        tr.appendChild(td);
        tableBody.replaceChildren(tr);
    }

    // ---------- 2. Desenhar a tabela (sem innerHTML: evita XSS) ----------

    function renderizarTabela(ordens) {
        tableBody.replaceChildren(noResultsRow);
        noResultsRow.style.display = ordens.length === 0 ? 'table-row' : 'none';

        ordens.forEach((ordem) => {
            const tr = document.createElement('tr');

            const tdId = document.createElement('td');
            const forte = document.createElement('strong');
            forte.textContent = ordem.id_visual;
            tdId.appendChild(forte);

            const tdStatus = document.createElement('td');
            const badge = document.createElement('span');
            badge.className = `status status-${ordem.status}`;
            badge.textContent = ordem.statusTexto;
            tdStatus.appendChild(badge);

            const tdAcoes = document.createElement('td');
            const botao = document.createElement('button');
            botao.className = 'details-button';
            botao.textContent = 'Ver Detalhes';
            botao.addEventListener('click', () => abrirModal(ordem.id));
            tdAcoes.appendChild(botao);

            tr.append(
                tdId,
                criarCelulaTruncada(ordem.cliente, 180),
                criarCelulaTruncada(ordem.descricao, 320),
                criarCelula(ordem.data),
                tdStatus,
                tdAcoes
            );
            tableBody.appendChild(tr);
        });
    }

    // ---------- 3. Filtros ----------

    function aplicarFiltros() {
        const termo = searchInput.value.trim().toLowerCase();
        const status = statusFilter.value;

        const filtradas = listaDeOrdens.filter((o) => {
            const textoOk =
                o.id_visual.toLowerCase().includes(termo) ||
                o.cliente.toLowerCase().includes(termo) ||
                o.descricao.toLowerCase().includes(termo);
            const statusOk = status === '' || o.status === status;
            return textoOk && statusOk;
        });

        renderizarTabela(filtradas);
    }

    document.getElementById('filter-button').addEventListener('click', aplicarFiltros);
    searchInput.addEventListener('input', aplicarFiltros);
    statusFilter.addEventListener('change', aplicarFiltros);

    // ---------- 4. Modal de detalhes ----------

    function abrirModal(id) {
        const ordem = listaDeOrdens.find((o) => o.id === id);
        if (!ordem) return;
        ordemAtual = ordem;

        document.getElementById('modal-title').textContent = `Ordem ${ordem.id_visual}`;
        document.getElementById('modal-client').textContent = ordem.cliente;
        document.getElementById('modal-description').textContent = ordem.descricao;
        document.getElementById('modal-material').textContent = `${ordem.material} - ${ordem.espessura} mm`;
        document.getElementById('modal-date').textContent = ordem.data;
        statusSelect.value = ordem.status;

        const areaAnexo = document.getElementById('area-anexo');
        const linkDownload = document.getElementById('btn-download-anexo');

        if (ordem.arquivo_nome) {
            areaAnexo.style.display = 'flex';
            linkDownload.href = `baixar_anexo.php?id=${encodeURIComponent(ordem.id)}`;
            linkDownload.textContent = `Baixar ${ordem.arquivo_nome}`;
        } else {
            areaAnexo.style.display = 'none';
        }

        modalOverlay.classList.remove('hidden');
    }

    function fecharModal() {
        modalOverlay.classList.add('hidden');
        ordemAtual = null;
    }

    document.querySelector('.modal-close').addEventListener('click', fecharModal);
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) fecharModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') fecharModal();
    });

    // ---------- 5. Salvar status ----------

    btnSalvar.addEventListener('click', async () => {
        if (!ordemAtual) return;

        btnSalvar.disabled = true;
        try {
            await postJSON('atualizar_status.php', {
                id: ordemAtual.id,
                status: statusSelect.value,
            });
            fecharModal();
            await carregarOrdens();
        } catch (erro) {
            alert('Erro ao atualizar: ' + erro.message);
        } finally {
            btnSalvar.disabled = false;
        }
    });

    // ---------- 6. Excluir ----------

    btnExcluir.addEventListener('click', async () => {
        if (!ordemAtual) return;
        if (!confirm(`Tem certeza que deseja excluir a ordem ${ordemAtual.id_visual}? Isso não pode ser desfeito.`)) return;

        btnExcluir.disabled = true;
        try {
            await postJSON('excluir_ordem.php', { id: ordemAtual.id });
            fecharModal();
            await carregarOrdens();
        } catch (erro) {
            alert('Erro ao excluir: ' + erro.message);
        } finally {
            btnExcluir.disabled = false;
        }
    });

    carregarOrdens();
});
