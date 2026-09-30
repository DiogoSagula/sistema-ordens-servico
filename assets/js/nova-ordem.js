document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('nova-ordem-form');
    const fileInput = document.getElementById('plano-corte');
    const submitButton = form.querySelector('button[type="submit"]');
    const textoOriginalBotao = submitButton.textContent;

    const TAMANHO_MAX = 5 * 1024 * 1024; // 5 MB (o servidor valida de novo)

    // Validação rápida no navegador, só para dar retorno imediato ao usuário.
    fileInput.addEventListener('change', () => {
        const arquivo = fileInput.files[0];
        if (!arquivo) return;

        if (arquivo.size > TAMANHO_MAX) {
            alert('O arquivo é muito grande! O limite é 5 MB.');
            fileInput.value = '';
            return;
        }

        const nome = arquivo.name.toLowerCase();
        if (!nome.endsWith('.pdf') && !nome.endsWith('.dxf')) {
            alert('Apenas arquivos PDF ou DXF são permitidos.');
            fileInput.value = '';
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        submitButton.disabled = true;
        submitButton.textContent = 'Enviando...';

        try {
            // FormData envia os campos e o arquivo direto (multipart), sem Base64.
            const resultado = await api('salvar_ordem.php', {
                method: 'POST',
                body: new FormData(form),
            });

            alert(`Ordem #${resultado.id} cadastrada com sucesso.`);
            window.location.href = 'index.php';
        } catch (erro) {
            alert('Erro ao salvar: ' + erro.message);
            submitButton.disabled = false;
            submitButton.textContent = textoOriginalBotao;
        }
    });
});
