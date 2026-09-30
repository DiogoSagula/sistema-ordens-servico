document.addEventListener('DOMContentLoaded', () => {

    const loginForm = document.getElementById('login-form');
    const userInput = document.getElementById('usuario');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const errorMessage = document.getElementById('error-message');
    const rememberCheckbox = document.querySelector('input[name="remember"]');

    // "Lembrar meu usuário": guarda só o nome de usuário, nunca a senha.
    const usuarioSalvo = localStorage.getItem('usuarioLembrado');
    if (usuarioSalvo) {
        userInput.value = usuarioSalvo;
        rememberCheckbox.checked = true;
    }

    // Mostrar/esconder senha
    togglePassword.addEventListener('click', () => {
        const tipo = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = tipo;
        togglePassword.classList.toggle('fa-eye');
        togglePassword.classList.toggle('fa-eye-slash');
    });

    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const usuario = userInput.value.trim();
        const senha = passwordInput.value; // sem trim: espaços podem fazer parte da senha

        errorMessage.style.color = '#94a3b8';
        errorMessage.textContent = 'Verificando...';

        try {
            const resposta = await fetch('login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ usuario, senha }),
            });
            const resultado = await resposta.json();

            if (resultado.sucesso) {
                if (rememberCheckbox.checked) {
                    localStorage.setItem('usuarioLembrado', usuario);
                } else {
                    localStorage.removeItem('usuarioLembrado');
                }

                errorMessage.style.color = '#22c55e';
                errorMessage.textContent = 'Login autorizado! Entrando...';
                setTimeout(() => { window.location.href = 'index.php'; }, 600);
            } else {
                errorMessage.style.color = '#ef4444';
                errorMessage.textContent = resultado.erro || 'Não foi possível entrar.';
                loginForm.classList.add('shake');
                setTimeout(() => loginForm.classList.remove('shake'), 500);
            }
        } catch (erro) {
            console.error(erro);
            errorMessage.style.color = '#ef4444';
            errorMessage.textContent = 'Erro de conexão com o servidor.';
        }
    });
});
