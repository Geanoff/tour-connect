<?php
$tituloPagina = 'Exemplo';
$cssPagina = 'home.css';
require_once '../components/layout/base-inicio.php';

require_once '../components/popup/exemplo.php';
?>

<h1>Olá Mundo!</h1>
<button class="btn" onclick="abrir_popup('exemplo-popup')">ATIVAR POPUP</button>
<fieldset>
    <legend>Toasts</legend>
    <button class="btn" onclick="abrir_toast('sucesso', 'Toast de Sucesso')">Sucesso</button>
    <button class="btn" onclick="abrir_toast('erro', 'Toast de Erro')">Erro</button>
    <button class="btn" onclick="abrir_toast('info', 'Toast Informativo')">Info</button>
</fieldset>

<?php
$jsPagina = 'home.js';
require_once '../components/layout/base-fim.php';
?>