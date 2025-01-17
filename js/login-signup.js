updateBasketItemCount();
function updateBasketItemCount() {
  let basketItemsLength = JSON.parse(
    localStorage.getItem("basketItems")
  ).length;
  let basketItemCounter = document.querySelector(".basket-item-counter");
  basketItemCounter.innerHTML = basketItemsLength;

  if (basketItemsLength !== 0) {
    basketItemCounter.classList.add("active");
  } else if (basketItemsLength === 0) {
    basketItemCounter.classList.remove("active");
  }
}
