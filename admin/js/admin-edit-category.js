let editCategoryButton = document.getElementById("edit-category-button");
let editCategoryDialog = document.getElementById("edit-category-dialog");

editCategoryButton.addEventListener("click", () => {
  editCategoryDialog.showModal();
  document.body.classList.add("blur-hide-scroll-bar");
});

closeDialogButton.addEventListener("click", () => {
  editCategoryDialog.close();
  document.body.classList.remove("blur-hide-scroll-bar");
});
