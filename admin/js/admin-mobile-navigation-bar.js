const menuButton = document.querySelector(".navbar-toggler");
const navBarMobile = document.querySelector(".nav-bar-mobile");
const navBarMobileCloseButton = document.querySelector(
  ".nav-bar-mobile-close-button"
);
menuButton.addEventListener("click", () => {
  showMobileNavBar();
});

navBarMobileCloseButton.addEventListener("click", () => {
  hideMobileNavBar();
});

function showMobileNavBar() {
  navBarMobile.classList.add("active");
  navBarMobile.classList.remove("closed");
}

function hideMobileNavBar() {
  navBarMobile.classList.remove("active");
  navBarMobile.classList.add("closed");
}

//! Function to update the basket counter in mobile nav bar !\\
const threeLinkSpan = document.querySelector(".three-span-text");

updateBasketCounter();
function updateBasketCounter() {
  const basketItems = JSON.parse(localStorage.getItem("basketItems")) || [];
  const basketItemsLength = basketItems.length;

  if (basketItemsLength !== 0) {
    threeLinkSpan.innerHTML = basketItemsLength;
    threeLinkSpan.classList.add("active");
  } else {
    threeLinkSpan.classList.remove("active");
  }
}
