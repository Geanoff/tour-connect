const flip = document.getElementById('flip');

document.querySelectorAll('.btn-flip').forEach(btn => {
    btn.addEventListener('click', () => {
        flip.classList.toggle('ativo');
    });
});