<?php
$tituloPagina = 'Turismo - Início';
$cssPagina = 'inicio.css';
require_once '../components/layout/base-inicio.php';

// Dados mockados dos passeios (futuramente virá do banco de dados)
// Ordenados por data de inserção (mais recentes primeiro)
$passeios = [
    [
        'id' => 4,
        'imagem' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=300&fit=crop',
        'titulo' => 'Passeio de Barco',
        'descricao' => 'Navegue pelas águas cristalinas e descubra paisagens deslumbrantes.',
        'preco' => 180.00
    ],
    [
        'id' => 3,
        'imagem' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=400&h=300&fit=crop',
        'titulo' => 'City Tour Histórico',
        'descricao' => 'Explore os pontos turísticos e históricos da cidade.',
        'preco' => 95.00
    ],
    [
        'id' => 2,
        'imagem' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=400&h=300&fit=crop',
        'titulo' => 'Cachoeira Azul',
        'descricao' => 'Um dos destinos mais procurados para quem ama água e natureza.',
        'preco' => 120.00
    ],
    [
        'id' => 1,
        'imagem' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=400&h=300&fit=crop',
        'titulo' => 'Trilha da Serra',
        'descricao' => 'Aventura em meio à natureza com vistas incríveis.',
        'preco' => 75.00
    ],
];

// Pegar apenas os 4 primeiros passeios (ordem de inserção - mais recentes)
$passeiiosDestaque = array_slice($passeios, 0, 4);
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
        <?php foreach ($passeiiosDestaque as $passeio): ?>
            <?php include '../components/cards/passeio-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<?php
$jsPagina = 'home.js';
require_once '../components/layout/base-fim.php';
?>
