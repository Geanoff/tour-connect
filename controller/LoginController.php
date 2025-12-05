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
        // Salva os dados do usuário na sessão
        $_SESSION['usuario_id'] = $loginUsuario['id'];
        $_SESSION['usuario_nome'] = $loginUsuario['nome'];
        $_SESSION['usuario_email'] = $loginUsuario['email'];
        $_SESSION['usuario_telefone'] = $loginUsuario['telefone'];
        
        if ($dados['email'] == 'administrador@example.com')
        {
            
            header('Location: ../view/pages/admin/index.php');
            exit;
        }

        header('Location: ../view/pages/home-usuario.php');
        exit;
        // Redireciona para a home do usuário
        
    } else {
        // Mensagem de erro
        $_SESSION['mensagem_toast'] = ['erro', 'Email ou senha inválidos!'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
