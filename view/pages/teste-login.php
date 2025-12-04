<?php
/**
 * ARQUIVO DE TESTE - REMOVER EM PRODUÇÃO
 * 
 * Este arquivo simula um login para testar as páginas
 * que precisam de autenticação.
 */

session_start();

// Simula dados do usuário logado
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nome'] = 'João Silva';
$_SESSION['usuario_email'] = 'joao@email.com';
$_SESSION['usuario_telefone'] = '(41) 99999-8888';

// Redireciona para a home do usuário
header('Location: home-usuario.php');
exit;
?>

