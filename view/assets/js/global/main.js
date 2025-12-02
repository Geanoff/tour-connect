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
function abrir_toast(tipo, mensagem) {
    const toast = document.getElementById("toast");
    const icon = document.getElementById("toast-icon");
    const msg = document.getElementById("toast-msg");

    // Define classe de cor
    toast.className = "toast " + tipo;

    // Define ícone
    switch (tipo) {
        case "sucesso":
            icon.className = "fa-regular fa-circle-check";
            break;
        case "erro":
            icon.className = "fa-solid fa-triangle-exclamation";
            break;
        case "info":
            icon.className = "fa-solid fa-circle-info";
            break;
        default:
            icon.className = "fa-regular fa-comment";
    }

    // Define mensagem
    msg.textContent = mensagem;

    // Mostra
    toast.style.top = "20px";
    toast.style.opacity = "1";

    // Esconde depois de 3s
    setTimeout(() => {
        toast.style.top = "-100px";
        toast.style.opacity = "0";
    }, 3000);
}