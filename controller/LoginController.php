<!-- LOGIN DO USUÁRIO -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/UsuarioModel.php';

$usuarioModel = new UsuarioModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados enviados pelo formulário de login
    $dados = [
        'email'    => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
        'senha'    => $_POST['senha'] ?? null,
    ];
    
    $loginUsuario = $usuarioModel->login($dados);
    if ($loginUsuario) {
        echo 'Login com Sucesso - mudar no controller a rota!';
        // header('Location: ../view/pages/home.php');
        exit;
    } else {
        // Mensagem de erro
        $_SESSION['mensagem_toast'] = ['erro', 'Email ou senha inválidos!'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
