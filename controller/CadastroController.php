<!-- CADASTRO DO USUÁRIO -->
<?php
// Se a sessão não existir, inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/UsuarioModel.php';

$usuarioModel = new UsuarioModel();

// Coleta os dados enviados pelo formulário de login
$dados = [
    'nome' => $_POST['nome'],
    'email' => $_POST['email'],
    'cpf' => $_POST['cpf'],
    'telefone' => $_POST['telefone'],
    'senha' => $_POST['senha']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário existe
    $cadastro = $usuarioModel->cadastro($dados);
    if ($cadastro) {
        // Redireciona para a página inicial
        header('Location: ../view/pages/home.php');
        exit;
    } else {
        // Mensagem de erro
        $_SESSION['mensagem_toast'] = ['erro', 'Falha ao Cadastrar!'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
