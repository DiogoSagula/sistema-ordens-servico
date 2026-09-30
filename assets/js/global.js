// global.js
// Utilidades compartilhadas pelas páginas internas (não é usado na tela de login).

document.addEventListener('DOMContentLoaded', destacarLinkAtivo);

// Marca no menu a página em que o usuário está.
function destacarLinkAtivo() {
    const pagina = window.location.pathname.split('/').pop() || 'index.php';
    const ids = {
        'index.php': 'link-home',
        'nova-ordem.php': 'link-nova',
        'relatorios.php': 'link-relatorios',
    };
    const link = document.getElementById(ids[pagina]);
    if (link) link.classList.add('active');
}

// Faz a requisição e devolve o JSON.
// - Sessão expirada (401): leva para o login.
// - Qualquer erro: lança Error com uma mensagem legível.
async function api(url, opcoes = {}) {
    let resposta;
    try {
        resposta = await fetch(url, { credentials: 'same-origin', ...opcoes });
    } catch {
        throw new Error('Erro de conexão com o servidor.');
    }

    if (resposta.status === 401) {
        window.location.href = 'login.php';
        throw new Error('Sessão expirada.');
    }

    let dados = null;
    try {
        dados = await resposta.json();
    } catch {
        // corpo vazio ou inválido: tratado abaixo
    }

    if (!resposta.ok || dados === null) {
        throw new Error((dados && dados.erro) || 'Erro inesperado no servidor.');
    }
    return dados;
}

function postJSON(url, corpo) {
    return api(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(corpo),
    });
}

// Cria <td> com texto puro (textContent nunca interpreta HTML).
function criarCelula(texto) {
    const td = document.createElement('td');
    td.textContent = texto;
    return td;
}

// Cria <td> cujo texto longo é cortado com "..." (o texto completo aparece ao passar o mouse).
function criarCelulaTruncada(texto, larguraMax = 320) {
    const td = document.createElement('td');
    const span = document.createElement('span');
    span.className = 'texto-truncado';
    span.style.maxWidth = `${larguraMax}px`;
    span.textContent = texto;
    span.title = texto;
    td.appendChild(span);
    return td;
}
