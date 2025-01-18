let addItemButton = document.getElementById("add-item-button");
let closeDialogButton = document.getElementById("close-dialog-button");
let addItemDialog = document.getElementById("add-item-dialog");

addItemButton.addEventListener("click", () => {
  addItemDialog.showModal();
  document.body.classList.add("blur-hide-scroll-bar");
});
closeDialogButton.addEventListener("click", () => {
  addItemDialog.close();
  document.body.classList.remove("blur-hide-scroll-bar");
  resetInputValue();
});

function resetInputValue() {
  document.getElementById("image_preview").style.display = "none";
  document.getElementById("add-item-form").reset();
}
