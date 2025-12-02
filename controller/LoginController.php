<!-- CADASTRO DO USUÁRIO -->
<?php
require_once __DIR__ . '/../model/UsuarioModel.php';

$usuarioModel = new UsuarioModel();

// Coleta os dados enviados pelo formulário de login
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário existe
    $usuario = $usuarioModel->login($email, $senha);
    if ($usuario) {
        // Redireciona para a página inicial
        header('Location: /view/pages/home.php');
        exit;
    } else {
        // Mensagem de erro
        echo 'Email ou senha inválidos.';
    }
}