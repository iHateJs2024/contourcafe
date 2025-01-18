let addCategoryButton = document.querySelector(".add-category-button");
let addCategoryDialog = document.getElementById("add-category-dialog");
let closeDialogButton = document.getElementById("close-dialog-button");
addCategoryButton.addEventListener("click", () => {
  addCategoryDialog.showModal();
  document.body.classList.add("blur-hide-scroll-bar");
});

closeDialogButton.addEventListener("click", () => {
  addCategoryDialog.close();
  document.body.classList.remove("blur-hide-scroll-bar");
  document.getElementById("add-category-form").reset();
  document.getElementById("image_preview").style.display = "none";
});
