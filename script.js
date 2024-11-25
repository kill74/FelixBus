// Seleciona os elementos do pop-up
const popupOverlay = document.getElementById("popupOverlay");
const popup = document.getElementById("popup");
const closePopup = document.getElementById("closePopup");
const Quantidade = document.getElementById("Qunatidade");
const Valor = document.getElementById("Valor");
// Seleciona todos os botões "Comprar"
const buyButtons = document.querySelectorAll(".Butao-Comprar");
// Adiciona evento de clique em cada botão
buyButtons.forEach((button) => {
  button.addEventListener("click", () => {
    popupOverlay.style.display = "flex";
  });
});
// Fecha o pop-up ao clicar no botão "Fechar"
closePopup.addEventListener("click", () => {
  popupOverlay.style.display = "none";
});
// Fecha o pop-up ao clicar fora da caixa
popupOverlay.addEventListener("click", (event) => {
  if (event.target === popupOverlay) {
    popupOverlay.style.display = "none";
  }
});



