const flip = document.getElementById('flip');

document.querySelectorAll('.btn-flip').forEach(btn => {
    btn.addEventListener('click', () => {
        flip.classList.toggle('ativo');
    });
});

// Formullário de Cadastro
const formCadastro = document.getElementById('form-cadastro');

if (formCadastro) {
    formCadastro.addEventListener('submit', function (e) {
        let erro = false;

        // Oculta os erros antes de validar
        document.querySelectorAll('.input-erro').forEach(el => {
            el.classList.remove('ativo');
        });

        // ===== VALIDAR EMAIL =====
        const emailInput = document.getElementById('c_email');
        const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim());
        if (!emailValido) {
            const erroEmail = emailInput.parentElement.querySelector('.input-erro');
            erroEmail.classList.add('ativo');
            erro = true;
        }

        // ===== VALIDAR SENHA =====
        const senhaInput = document.getElementById('c_senha');
        const confirmarSenhaInput = document.getElementById('r_senha');

        if (senhaInput.value !== confirmarSenhaInput.value) {
            const erroSenha = confirmarSenhaInput.parentElement.querySelector('.input-erro');
            erroSenha.classList.add('ativo');
            erro = true;
        }

        // Bloqueia envio se houve erro
        if (erro) {
            e.preventDefault();
        }
    });
}

// Captura todos os botões de olho
document.querySelectorAll('.btn-senha').forEach(botao => {
    botao.addEventListener('click', () => {

        // pega o input dentro do mesmo input-box
        const input = botao.parentElement.querySelector('input');

        // Alterna o tipo
        if (input.type === "password") {
            input.type = "text";
            botao.classList.remove("fa-eye");
            botao.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            botao.classList.remove("fa-eye-slash");
            botao.classList.add("fa-eye");
        }
    });
});
