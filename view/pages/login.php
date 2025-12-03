<?php
$tituloPagina = 'Login';
$cssPagina = 'login.css';
require_once '../components/layout/base-inicio.php';
?>

<main class="form-group flex bg-white shadow-md rounded-md items-center relative">
    <div id="flip" class="flip absolute h-full w-1/2 flex items-center justify-center right-0 text-center">
        <div class="front absolute gap-1">
            <h2 class="font-bold text-2xl">Não tem conta?</h2>
            <p>Crie sua conta agora para benefícios exclusivos!</p>
            <button class="btn btn-flip">CADASTRO</button>
        </div>
        <div class="back absolute gap-1">
            <h2 class="font-bold text-2xl">Já tem conta?</h2>
            <p>Explore benefícios exclusivos com sua conta existente agora!</p>
            <button class="btn btn-flip">LOGIN</button>
        </div>
    </div>

    <!-- Formulário de Login -->
    <form action="../../controller/LoginController.php" method="POST" class="w-100">
        <h1>LOGIN</h1>
        <div class="input-box">
            <label for="email">Email<span>*</span></label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-box">
            <label for="email">Senha<span>*</span></label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <button class="btn btn-primary">ENTRAR</button>
    </form>
    <!-- Formulário de Cadastro -->
    <form id="form-cadastro" action="../../controller/CadastroController.php" method="POST" class="w-100">
        <h1>CADASTRO</h1>
        <div class="input-group flex gap-2">
            <div class="input-box w-[calc(50%-4px)]">
                <label for="nome">Nome Completo<span>*</span></label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="input-box w-[calc(50%-4px)]">
                <label for="cpf">CPF<span>*</span></label>
                <input data-mask="###.###.###-##" type="text" id="cpf" name="cpf" required>
            </div>
        </div>
        <div class="input-group flex gap-2">
            <div class="input-box w-[calc(50%-4px)]">
                <label for="email">Email<span>*</span></label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-box w-[calc(50%-4px)]">
            <label for="telefone">Telefone<span>*</span></label>
            <input data-mask="(##) #####-####" type="text" id="telefone" name="telefone" required>
        </div>
        </div>
        <div class="input-group flex gap-2">
            <div class="input-box w-[calc(50%-4px)]">
                <label for="senha">Senha<span>*</span></label>
                <input type="password" id="senha" name="senha" minlength="8" maxlength="20" required>
            </div>
            <div class="input-box w-[calc(50%-4px)]">
                <label for="c_senha">Confirmar Senha<span>*</span></label>
                <input type="password" id="c_senha" name="c_senha" minlength="8" maxlength="20" required>
            </div>
        </div>
        <button class="btn btn-primary">CADASTRAR</button>
    </form>
</main>

<?php
$jsPagina = 'login.js';
require_once '../components/layout/base-fim.php';
?>