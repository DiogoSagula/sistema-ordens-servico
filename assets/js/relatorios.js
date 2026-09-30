document.addEventListener('DOMContentLoaded', async () => {

    const STATUS = ['recebido', 'aguardando', 'produzindo', 'pronto', 'retirado'];

    const modal = document.getElementById('modal-lista-pedidos');
    const tbody = document.getElementById('lista-pedidos-body');
    const tituloModal = document.getElementById('modal-titulo-lista');

    let ordens = [];

    // ---------- Modal ----------

    const fecharModal = () => modal.classList.add('hidden');
    modal.querySelector('.modal-close').addEventListener('click', fecharModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) fecharModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') fecharModal();
    });

    document.querySelectorAll('.stat-card').forEach((card) => {
        card.addEventListener('click', () => {
            const status = card.getAttribute('data-status');
            const nome = card.querySelector('.card-title').textContent;
            abrirLista(status, nome);
        });
    });

    function abrirLista(status, titulo) {
        const filtrados = ordens.filter((o) => o.status === status);
        tituloModal.textContent = `${titulo} (${filtrados.length})`;
        tbody.replaceChildren();

        if (filtrados.length === 0) {
            const tr = document.createElement('tr');
            const td = criarCelula('Nenhum pedido encontrado.');
            td.colSpan = 4;
            td.style.cssText = 'text-align:center; padding:20px; color:#64748b;';
            tr.appendChild(td);
            tbody.appendChild(tr);
        } else {
            filtrados.forEach((o) => {
                const tr = document.createElement('tr');
                const tdId = criarCelula(o.id_visual);
                tdId.style.cssText = 'font-weight:bold; color:#0f172a;';
                tr.append(tdId, criarCelulaTruncada(o.cliente, 160), criarCelulaTruncada(o.descricao, 260), criarCelula(o.data));
                tbody.appendChild(tr);
            });
        }

        modal.classList.remove('hidden');
    }

    // ---------- Contadores ----------

    function atualizarContadores() {
        const contagem = Object.fromEntries(STATUS.map((s) => [s, 0]));
        ordens.forEach((o) => {
            if (o.status in contagem) contagem[o.status]++;
        });
        STATUS.forEach((s) => {
            document.getElementById(`${s}-valor`).textContent = contagem[s];
        });
    }

    function mostrarErro(mensagem) {
        const aviso = document.createElement('p');
        aviso.textContent = mensagem;
        aviso.style.color = '#ef4444';
        document.querySelector('.stats-grid').before(aviso);
    }

    // ---------- Carregar direto do servidor (sempre atualizado) ----------

    try {
        ordens = await api('listar_ordens.php');
        atualizarContadores();
    } catch (erro) {
        console.error(erro);
        mostrarErro(erro.message);
    }
});
