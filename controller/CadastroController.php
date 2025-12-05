<!-- CADASTRO DO USUÁRIO -->
<?php
// Se a sessão não existir, inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/UsuarioModel.php';

$usuarioModel = new UsuarioModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados enviados pelo formulário de cadastro
    $dados = [
        'nome'     => filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS),
        'email'    => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
        'cpf'      => filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT),
        'telefone' => filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_NUMBER_INT),
        'senha'    => $_POST['senha'] ?? null,
    ];

    // Realiza o cadastro
    $cadastro = $usuarioModel->cadastro($dados);
    if ($cadastro) {
        // Faz login automático após cadastro
        $loginUsuario = $usuarioModel->login($dados);
        if ($loginUsuario) {
            $_SESSION['usuario_id'] = $loginUsuario['id'];
            $_SESSION['usuario_nome'] = $loginUsuario['nome'];
            $_SESSION['usuario_email'] = $loginUsuario['email'];
            $_SESSION['usuario_telefone'] = $loginUsuario['telefone'];
        }

        if ($dados['email'] == 'administrador@example.com')
        {
            header('Location: ../view/pages/admin/index.php');
            exit;
        }
        
        // Redireciona para a home do usuário
        header('Location: ../view/pages/home-usuario.php');
        exit;
    } else {
        // Mensagem de erro
        $_SESSION['mensagem_toast'] = ['erro', 'Falha ao Cadastrar!'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
