let displayCurrentOrderButton = document.querySelector(
  ".js-display-current-order-button"
);

let displayPastOrderButton = document.querySelector(
  ".js-display-past-order-button"
);

// let orderStatus = document.querySelector(".order-status");

displayCurrentOrderButton.classList.add("active");

displayCurrentOrderButton.addEventListener("click", () => {
  displayCurrentOrderButton.classList.add("active");
  displayPastOrderButton.classList.remove("active");
  showCurrentOrders();
});
displayPastOrderButton.addEventListener("click", () => {
  displayPastOrderButton.classList.add("active");
  displayCurrentOrderButton.classList.remove("active");
  showPastOrders();
});

function showCurrentOrders() {
  document.getElementById("currentOrders").style.display = "block";
  document.getElementById("pastOrders").style.display = "none";
  changeOrderStatusColor();
}

function showPastOrders() {
  document.getElementById("currentOrders").style.display = "none";
  document.getElementById("pastOrders").style.display = "block";
  changeOrderStatusColor();
}

//! Function to change all the pending order status to YELLOW
changeOrderStatusColor();
function changeOrderStatusColor() {
  let allOrderStatusText = document.querySelectorAll(".order-status-text");
  let allDialogOrderStatus = document.querySelectorAll(".modal-title");

  allOrderStatusText.forEach((statusText) => {
    if (statusText.innerText === "Pending") {
      statusText.classList.add("pending");
    } else if (statusText.innerText === "Ready") {
      statusText.classList.add("ready");
    } else if (statusText.innerText === "Cancelled") {
      statusText.classList.add("cancelled");
    }
  });

  allDialogOrderStatus.forEach((statusText) => {
    if (statusText.innerText === "Pending") {
      statusText.classList.add("pending");
    } else if (statusText.innerText === "Ready") {
      statusText.classList.add("ready");
    } else if (statusText.innerText === "Cancelled") {
      statusText.classList.add("cancelled");
    }
  });
}

//! Make the lihat button interactive
let allLihatButton = document.querySelectorAll(".lihat-order-button");

allLihatButton.forEach((lihatButton) => {
  lihatButton.addEventListener("click", () => {
    let orderId = lihatButton.dataset.orderId;
    // console.log(`Lihat Order ${orderId}`);
    showOrderDialog(orderId);
    changeOrderStatusColor();
  });
});

//! Make all close order Dialog button interactive
let allCloseOrderDialogButton = document.querySelectorAll(
  ".js-close-order-dialog-button"
);

allCloseOrderDialogButton.forEach((button) => {
  button.addEventListener("click", () => {
    let orderId = button.dataset.orderId;
    // console.log(`Lihat Order ${orderId}`);
    closeOrderDialog(orderId);
  });
});

//! Function to show order dialog
function showOrderDialog(orderId) {
  const selectedOrderDialog = document.getElementById(
    `js-order-dialog-${orderId}`
  );
  selectedOrderDialog.showModal();
  document.body.classList.add("hideScrollbar");
}

//! Function to close order dialog
function closeOrderDialog(orderId) {
  const selectedOrderDialog = document.getElementById(
    `js-order-dialog-${orderId}`
  );
  selectedOrderDialog.close();
  document.body.classList.remove("hideScrollbar");
}

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
