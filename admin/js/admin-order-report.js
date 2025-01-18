function resetButtonsOrderStatusColor(buttonId) {
  document
    .getElementById(`ready-button-${buttonId}`)
    .classList.remove("clicked");
  document
    .getElementById(`pending-button-${buttonId}`)
    .classList.remove("clicked");
  document
    .getElementById(`cancel-button-${buttonId}`)
    .classList.remove("clicked");
}

//! Function to change all the pending order status to YELLOW
changeOrderStatusColor();

function changeOrderStatusColor() {
  let allOrderStatusText = document.querySelectorAll(".order-status-text");

  //? Order status on the page
  allOrderStatusText.forEach((statusText) => {
    if (statusText.innerText === "Pending") {
      statusText.classList.add("pending");
    } else if (statusText.innerText === "Ready") {
      statusText.classList.add("ready");
    } else if (statusText.innerText === "Cancelled") {
      statusText.classList.add("cancelled");
    }
  });
}

//! Function to Change the Color of Order Status Buttons
function changeReadyButtonColor(buttonId) {
  document.getElementById(`ready-button-${buttonId}`).classList.add("clicked");
  document
    .getElementById(`pending-button-${buttonId}`)
    .classList.remove("clicked");
  document
    .getElementById(`cancel-button-${buttonId}`)
    .classList.remove("clicked");
}

function changePendingButtonColor(buttonId) {
  document
    .getElementById(`ready-button-${buttonId}`)
    .classList.remove("clicked");
  document
    .getElementById(`pending-button-${buttonId}`)
    .classList.add("clicked");
  document
    .getElementById(`cancel-button-${buttonId}`)
    .classList.remove("clicked");
}

function changeCancelButtonColor(buttonId) {
  document
    .getElementById(`ready-button-${buttonId}`)
    .classList.remove("clicked");
  document
    .getElementById(`pending-button-${buttonId}`)
    .classList.remove("clicked");
  document.getElementById(`cancel-button-${buttonId}`).classList.add("clicked");
}

//! Function to Change the Color Of Order Status Button based on the Order Status text
function ChangeOrderStatusButton(orderStatusTextId) {
  const orderStatusText = document.getElementById(
    `js-order-status-text-${orderStatusTextId}`
  ).innerText;

  const buttonId = orderStatusTextId;

  if (orderStatusText === "Ready") {
    document
      .getElementById(`ready-button-${buttonId}`)
      .classList.add("clicked");
  } else if (orderStatusText === "Pending") {
    document
      .getElementById(`pending-button-${buttonId}`)
      .classList.add("clicked");
  } else if (orderStatusText === "Cancelled") {
    document
      .getElementById(`cancel-button-${buttonId}`)
      .classList.add("clicked");
  }
}

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
