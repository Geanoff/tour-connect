<div class="passeio-card">
    <div class="passeio-card__imagem">
        <?php 
        // Ajusta o caminho da imagem (adiciona ../../ se for caminho local)
        $caminhoImagem = $destinos['imagem'] ?? '';
        if ($caminhoImagem && strpos($caminhoImagem, 'http') !== 0) {
            $caminhoImagem = '../../' . $caminhoImagem;
        }
        ?>
        <img src="<?= htmlspecialchars($caminhoImagem) ?>" alt="<?= htmlspecialchars($destinos['titulo']) ?>">
    </div>
    <div class="passeio-card__conteudo">
        <h3 class="passeio-card__titulo"><?= htmlspecialchars($destinos['titulo']) ?></h3>
        <p class="passeio-card__descricao"><?= htmlspecialchars($destinos['descricao'] ?? $destinos['descricao_curta'] ?? '') ?></p>
    </div>
    <div class="btn-passeio-card">
        <?php if (isset($destinos['preco'])): ?>
            <span class="passeio-card__preco">R$ <?= number_format($destinos['preco'], 2, ',', '.') ?></span>
        <?php endif; ?>
        <a href="passeio.php?id=<?= $destinos['id'] ?>" class="passeio-card__btn">Ver detalhes</a>
    </div>
</div>


