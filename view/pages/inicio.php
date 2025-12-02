<?php
$tituloPagina = 'Turismo - Início';
$cssPagina = 'inicio.css';
require_once '../components/layout/base-inicio.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Descubra experiências inesquecíveis</h1>
        <p>Encontre passeios, trilhas e guias especializados para sua próxima aventura.</p>
        <a href="login.php" class="btn-login">Fazer Login</a>
    </div>
</section>

<section class="passeios">
    <h2>Passeios em destaque</h2>
    <div class="lista-passeios">
        <div class="passeio-card">
            <img src="../assets/img/trilha1.jpg" alt="Trilha">
            <h3>Trilha da Serra</h3>
            <p>Aventura em meio à natureza com vistas incríveis.</p>
            <a href="#" class="btn-ver">Ver detalhes</a>
        </div>

        <div class="passeio-card">
            <img src="../assets/img/cachoeira1.jpg" alt="Cachoeira">
            <h3>Cachoeira Azul</h3>
            <p>Um dos destinos mais procurados para quem ama água e natureza.</p>
            <a href="#" class="btn-ver">Ver detalhes</a>
        </div>

        <div class="passeio-card">
            <img src="../assets/img/citytour1.jpg" alt="City Tour">
            <h3>City Tour Histórico</h3>
            <p>Explore os pontos turísticos e históricos da cidade.</p>
            <a href="#" class="btn-ver">Ver detalhes</a>
        </div>
    </div>
</section>

<?php
$jsPagina = 'home.js';
require_once '../components/layout/base-fim.php';
?>