updateBasketItemCount();
function updateBasketItemCount() {
  let basketItems = JSON.parse(localStorage.getItem("basketItems")) || null;
  let basketItemCounter = document.querySelector(".basket-item-counter");
  basketItemCounter.innerHTML = basketItems.length;

  if (basketItems !== null) {
    basketItemCounter.classList.add("active");
  } else if (basketItems === null) {
    basketItemCounter.classList.remove("active");
  }
}
