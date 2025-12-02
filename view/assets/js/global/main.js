// EFEITO POPUP 
function abrir_popup(popupId) {
    const fundoPopup = document.getElementById(popupId);

    if (fundoPopup) {
        fundoPopup.classList.toggle('ativo');

        // Fecha o popup ao clicar fora dele
        fundoPopup.addEventListener('click', (event) => {
            if (event.target === fundoPopup) {
                fundoPopup.classList.remove('ativo');
            }
        });
    } else {
        console.error(`Elemento com ID "${popupId}" não encontrado.`);
    }
}
// FECHAR O POPUP
function fechar_popup(popupId) {
    const fundoPopup = document.getElementById(popupId);
    fundoPopup.classList.remove('ativo');
}

//EFEITO DO TOAST
function exibir_toast(tipo, mensagem) {
    let toastTimeout;
    const toast = document.getElementById("toast");
    const icon = document.getElementById("toast-icon");
    const msg = document.getElementById("toast-msg");

    // Cancela qualquer animação anterior
    clearTimeout(toastTimeout);

    // Define classe de cor
    toast.className = "toast " + tipo;

    // Define ícone conforme tipo
    switch (tipo) {
        case "sucesso":
            icon.className = "fa-regular fa-circle-check";
            break;
        case "erro":
            icon.className = "fa-solid fa-triangle-exclamation";
            break;
        case "favorito":
            icon.className = "fa-solid fa-heart";
            break;
        case "desfavorito":
            icon.className = "fa-solid fa-heart-crack";
            break;
        case "info":
            icon.className = "fa-solid fa-circle-info";
            break;
        default:
            icon.className = "fa-regular fa-comment";
    }

    // Define mensagem
    msg.textContent = mensagem;

    // Exibe o toast
    toast.style.right = "0px";
    toast.style.opacity = "1";

    // Oculta após 3 segundos
    toastTimeout = setTimeout(() => {
        toast.style.right = "-300px";
        toast.style.opacity = "0";
    }, 3000);
};