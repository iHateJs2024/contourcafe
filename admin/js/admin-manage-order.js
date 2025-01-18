let displayPesananSiapButton = document.querySelector(
  ".js-display-pesanan-siap-button"
);

let displayPesananBelumSiapButton = document.querySelector(
  ".js-display-pesanan-belum-siap-button"
);

let displayPesananDibatalkanButton = document.querySelector(
  ".js-display-pesanan-dibatalkan-button"
);

displayPesananBelumSiapButton.classList.add("active");

displayPesananSiapButton.addEventListener("click", () => {
  displayPesananSiapButton.classList.add("active");
  displayPesananBelumSiapButton.classList.remove("active");
  displayPesananDibatalkanButton.classList.remove("active");
  // showPesananSiap();
});
displayPesananBelumSiapButton.addEventListener("click", () => {
  displayPesananBelumSiapButton.classList.add("active");
  displayPesananSiapButton.classList.remove("active");
  displayPesananDibatalkanButton.classList.remove("active");
  // showPesananDibatalkan();
});

displayPesananDibatalkanButton.addEventListener("click", () => {
  displayPesananDibatalkanButton.classList.add("active");
  displayPesananSiapButton.classList.remove("active");
  displayPesananBelumSiapButton.classList.remove("active");
  // showPesananDibatalkan();
});

// function showPesananSiap() {
//   document.getElementById("js-pesanan-siap").style.display = "block";
//   document.getElementById("js-pesanan-belum-siap").style.display = "none";
//   document.getElementById("js-pesanan-dibatalkan").style.display = "none";
//   changeOrderStatusColor();
// }

// function showPesananBelumSiap() {
//   document.getElementById("js-pesanan-siap").style.display = "none";
//   document.getElementById("js-pesanan-belum-siap").style.display = "block";
//   document.getElementById("js-pesanan-dibatalkan").style.display = "none";
//   changeOrderStatusColor();
// }

// function showPesananDibatalkan() {
//   document.getElementById("js-pesanan-siap").style.display = "none";
//   document.getElementById("js-pesanan-belum-siap").style.display = "block";
//   document.getElementById("js-pesanan-dibatalkan").style.display = "block";
//   changeOrderStatusColor();
// }

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

//! Function to Make the lihat button interactive
function makeLihatButtonInteractive(orderId) {
  showOrderDialog(orderId);
  changeOrderStatusColor();
}

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

//! Function to reset ALl Buttons Order Status Color
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
