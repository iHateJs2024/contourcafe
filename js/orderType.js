changeFirstLinkStyle();

/// Get The Order Type from localStorage ///
let orderType = localStorage.getItem("Order Type");
// console.log(orderType);
//? Check If This Exists!
if (document.getElementById("order-type")) {
  document.getElementById("order-type").innerHTML = orderType;
}

//! Update the order type function
function updateOrderType(orderType) {
  localStorage.setItem("Order Type", orderType);
}

//! Change the styles of first Link
function changeFirstLinkStyle() {
  // console.log("hi");

  document.querySelector(".second-link").classList.add("removeStyles");
}
