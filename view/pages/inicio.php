<?php
$tituloPagina = 'Turismo - Início';
$cssPagina = 'inicio.css';
require_once '../components/layout/base-inicio.php';
require_once '../../model/DestinoModel.php';

$modelDestino = new DestinoModel();

$destinosEmDestaque = $modelDestino->buscarDestinosComLimite(4, 0);
?>

<section class="hero">
    <a href="login.php" class="btn-login">Fazer Login</a>
    <div class="hero-content">
        <h1>Descubra experiências inesquecíveis</h1>
        <p>Encontre passeios, trilhas e guias especializados para sua próxima aventura.</p>
    </div>
</section>

<section class="secao-passeios">
    <h2 class="secao-passeios__titulo">Passeios em destaque</h2>
    <div class="lista-passeios">
        <?php foreach ($destinosEmDestaque as $destinos): ?>
            <?php include '../components/cards/passeio-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<?php
$jsPagina = 'home.js';
require_once '../components/layout/base-fim.php';
?>
