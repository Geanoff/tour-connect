<div class="galeria__miniaturas">
    <img 
        src="<?= $imagem ?>" 
        alt="Miniatura <?= $index + 1 ?>" 
        class="galeria__thumb <?= $index === 0 ? 'galeria__thumb--ativa' : '' ?>"
        onclick="selecionarImagem(<?= $index ?>)"
    >
</div>           