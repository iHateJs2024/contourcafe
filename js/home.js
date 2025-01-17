/// Change the styles of links in header
function changeStyles() {
  document.querySelector(".second-link").classList.add("remove");
  document.querySelector(".first-link").classList.add("new");
}

changeStyles();

updateBasketItemCount();
function updateBasketItemCount() {
  let basketItemsLength = JSON.parse(localStorage.getItem("basketItems")) || [];
  basketItemsLength = basketItemsLength.length;
  let basketItemCounter = document.querySelector(".basket-item-counter");
  basketItemCounter.innerHTML = basketItemsLength;

  if (basketItemsLength !== 0) {
    basketItemCounter.classList.add("active");
  } else if (basketItemsLength === 0) {
    basketItemCounter.classList.remove("active");
  }
}
