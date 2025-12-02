<?php
$tituloPagina = 'Login';
$cssPagina = 'login.css';
require_once '../components/layout/base-inicio.php';
?>

<form action="../../controller/LoginController.php" method="POST">
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
    <small>Não tem uma conta? <a>Criar Conta</a></small>
</form>

<?php
$jsPagina = '';
require_once '../components/layout/base-fim.php';
?>