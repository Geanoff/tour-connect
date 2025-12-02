<?php
// Se a sessão não existir, inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título da aba (dinâmico) -->
    <title><?= isset($tituloPagina) ? $tituloPagina : 'Sem Nome'; ?></title>

    <!-- CSS global do sistema -->
    <link rel="stylesheet" href="../assets/css/global/style.css">

    <!-- CSS específico da página (se houver) -->
    <?php
    if (isset($cssPagina)) {
        echo '<link rel="stylesheet" href="../assets/css/pages/' . $cssPagina . '">' . PHP_EOL;
    }
    ?>
</head>

<body>
    <div id="toast" class="toast">
        <i id="toast-icon" class=""></i>
        <span id="toast-msg"></span>
    </div>