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


// Máscaras de Input
function aplicarMascara(valor, mascara) {
  valor = valor.replace(/\D/g, ""); // remove tudo que não for número
  let resultado = "";
  let indiceValor = 0;

  for (let i = 0; i < mascara.length; i++) {
    if (mascara[i] === "#") {
      if (indiceValor < valor.length) {
        resultado += valor[indiceValor];
        indiceValor++;
      } else {
        break;
      }
    } else {
      if (indiceValor < valor.length) {
        resultado += mascara[i];
      }
    }
  }
  return resultado;
}

document.addEventListener("DOMContentLoaded", () => {
  // Aplica máscara em tempo real e também nos valores iniciais
  document.querySelectorAll("input[data-mask]").forEach(input => {
    const mascara = input.getAttribute("data-mask");

    // Aplica máscara no valor inicial (se já vier preenchido)
    if (input.value) {
      input.value = aplicarMascara(input.value, mascara);
    }

    // Continua aplicando em tempo real
    input.addEventListener("input", e => {
      e.target.value = aplicarMascara(e.target.value, mascara);
    });
  });

  // Remove máscara antes de enviar em todos os formulários
  document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", function () {
      this.querySelectorAll("input[data-mask]").forEach(input => {
        input.value = input.value.replace(/\D/g, ""); // envia só números
      });
    });
  });
});