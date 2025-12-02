<!-- Arquivo JS Principal -->
<script src="../assets/js/global/main.js"></script>

<!-- JS Específicos da Página -->
<?php
if (isset($jsPagina)) {
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