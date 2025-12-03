<!-- Arquivo JS Principal -->
<script src="../assets/js/global/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<!-- JS Específicos da Página -->
<?php
if (!empty($jsPagina)) {
    echo '<script src="../assets/js/pages/' . $jsPagina . '"></script>' . PHP_EOL;
}

if (isset($_SESSION['mensagem_toast'])) {
    $tipo = json_encode($_SESSION['mensagem_toast'][0]);
    $mensagem = json_encode($_SESSION['mensagem_toast'][1]);
    echo "<script>exibir_toast($tipo, $mensagem)</script>";
    unset($_SESSION['mensagem_toast']);
}
?>
</body>

</html>