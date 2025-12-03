<?php
/**
 * Componente: Card de Passeio
 * 
 * Variáveis esperadas:
 * - $passeio['id'] - ID do passeio
 * - $passeio['imagem'] - Caminho da imagem
 * - $passeio['titulo'] - Título do passeio
 * - $passeio['descricao'] - Descrição breve do passeio
 * - $passeio['preco'] - Preço (opcional)
 */
?>

<div class="passeio-card">
    <div class="passeio-card__imagem">
        <img src="<?= htmlspecialchars($passeio['imagem']) ?>" alt="<?= htmlspecialchars($passeio['titulo']) ?>">
    </div>
    <div class="passeio-card__conteudo">
        <h3 class="passeio-card__titulo"><?= htmlspecialchars($passeio['titulo']) ?></h3>
        <p class="passeio-card__descricao"><?= htmlspecialchars($passeio['descricao']) ?></p>
        <?php if (isset($passeio['preco'])): ?>
            <span class="passeio-card__preco">R$ <?= number_format($passeio['preco'], 2, ',', '.') ?></span>
        <?php endif; ?>
        <a href="passeio.php?id=<?= $passeio['id'] ?>" class="passeio-card__btn">Ver detalhes</a>
    </div>
</div>


