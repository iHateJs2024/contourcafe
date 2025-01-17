//* Generate Food Basket JS starts here!
let basketItems = JSON.parse(localStorage.getItem("basketItems")) || [];
/// Basket Place Order Button
let basketPlaceOrderButton;

let choosePaymentMethodText;

/// Delivery Address Input Field
let deliveryAddressInputField;

/// Discount Amount
let discountAmount = 0;

/// Food Data To Be Sent To Payment Page
let data = [];

/// Generate new objects in basketData when cicked the Add To Cart Button.
function addObject(
  selectedItem,
  itemPrice,
  takeAwayPackaging,
  quantity,
  notes,
  img
) {
  basketItems.push({
    name: selectedItem,
    price: itemPrice,
    takeAwayPackaging: takeAwayPackaging,
    quantity: quantity,
    notes: notes,
    img: img,
  });

  //! Assign ascending IDs to each item !
  basketItems.forEach((basketItem, index) => {
    basketItem.id = index + 1; // IDs start from 1
  });

  localStorage.setItem("basketItems", JSON.stringify(basketItems));
  // console.log(basketItems);
}

/// Generate the objects inside the basketItems array in basket list.
function generateBasketItems() {
  let CheckoutBox = document.getElementById("footer-basket-main");
  let CheckoutSummaryDetails = document.getElementById(
    "checkout-summary-details"
  );

  if (basketItems.length !== 0) {
    document.getElementById("basket-items").innerHTML = basketItems
      .map((basketItem) => {
        let { id, name, price, takeAwayPackaging, quantity, notes, img } =
          basketItem;

        if (notes.toLowerCase().includes("undefined")) {
          notes = "";
        }

        return `
          <div class="container">
            <div class="first-vertical-layout">

              <!--First horizontal layout-->
              <div class="decrement-and-increment-button">
                <button class="minus-button-basket">
                    <img src="icons/minus-circular-button.png"></img>
                  </button>
                <div class="quantity"><span class="quantity-value">${quantity}</span></div>
                <button class="plus-button-basket">
                    <img src="icons/plus-button.png"></img>
                  </button>
              </div>

              <!--Second horizontal layout-->
              <div class="basket-item-detail" onclick="
                showModalBasketItem('dialog-${id}');
                getBasketNotes('text-area-${id}', ${id});
                getBasketQuantity(${id}, ${id});
                totalPriceModalBasketItem(${id}, ${price}, ${takeAwayPackaging}, 'price-${id}');
              ">
                <div id="basket-item-name-and-price" class="basket-item-name-and-price">
                  <p>${name}</p>
                  <strong class="item-price">
                    RM${(price * quantity).toFixed(2)}
                  </strong>
                </div>
                <div class="basket-takeaway-packaging-price">
                  <p>Pembungkusan Bawa pulang</p>
                  <strong>
                    RM${(takeAwayPackaging * quantity).toFixed(2)}
                  </strong>
                </div>
                <p id="notes-${id}" class="notes">
                  ${notes}
                </p>
              </div>

            </div>

            <dialog id="dialog-${id}" class="food-preview-modal">

              <!--First Vertical Layout-->
              <div class="modal-title-and-close-button"> 
                <div class="modal-title">
                  Kemas kini Item dalam Bakul
                </div>
                <button class="close-button-food-preview-modal" onclick="
                closeModalBasketItem('dialog-${id}');
                resetModalQuantity(${id}, ${id});
                updateModalPrice('price-${id}', ${id}, ${price}, ${takeAwayPackaging});
                resetTextArea('text-area-${id}');
                ">
                  x
                </button>
              </div>

              <div class="modal-scrollable-content">
                <!--Second Vertical Layout-->
                <div class="modal-food-preview-image">
                  <img class="food-preview-image-modal" src="${img}" alt=""></img>
                </div>

                <!--Third Vertical Layout-->
                <div class="modal-food-preview-name-and-price">
                  <div class="modal-food-preview-name">${name}</div>
                  <div class="modal-food-preview-price">\
                    RM${price.toFixed(2)}
                  </div>
                </div>

                <!--Fourth Vertical Layout-->
                <div class="modal-food-preview-packaging-type">
                  <p class="modal-food-preview-takeaway">Pembungkusan Bawa pulang</p>
                  <p class="modal-food-preview-packaging-description">Diperlukan &middot; Pilih 1</p>
                </div>

                <!--Fifth Vertical Layout-->
                <div class="modal-food-preview-packaging-choice-and-price">
                  <div class="modal-food-preview-packaging-choice">Pembungkusan Bawa pulang</div>
                  <div class="modal-food-preview-packaging-price">
                    <p>+RM${takeAwayPackaging.toFixed(2)}</p>
                    <div class="modal-food-preview-packaging-price-tick">
                      <img src="icons/tick.svg"></img>
                    </div>
                  </div>
                </div>

                <!--Sixth Vertical Layout-->
                <div class="modal-food-preview-additional-notes">
                  <p>Nota Tambahan</p>
                </div>

                <!--Seventh Vertical Layout-->
                <div class="modal-food-preview-additional-notes-textarea-div">
                  <textarea id="text-area-${id}" class="modal-food-preview-additional-notes-textarea" placeholder="e.g. tidak mahu jeruk"></textarea>
                </div>

                <!--Eighth Vertical Layout-->
                <div class="modal-food-preview-blank"></div>
              </div>

              <!--Ninth Vertical Layout-->
              <div class="modal-food-preview-add-to-basket-button">
                <div class="quantity-input-group">
                  <button class="minus-button" onclick="decrementQuantityBasket(${id});
                  updateModalPrice('price-${id}', ${id}, ${price}, ${takeAwayPackaging});
                  ">
                    <img src="icons/minus-circular-button.png"></img>
                  </button>
                  <div id="${id}" class="quantity">1</div>
                  <button class="plus-button" onclick="
                  incrementQuantityBasket(${id});
                  updateModalPrice('price-${id}', ${id}, ${price}, ${takeAwayPackaging});
                  ">
                    <img src="icons/plus-button.png"></img>
                  </button>
                  
                </div>
                <button class="add-to-basket-button"  onclick="
                  let notes = getModalAdditionalNotes('text-area-${id}');
                  closeModalBasketItem('dialog-${id}');
                  updateModalPrice('price-${id}', ${id}, ${price}, ${takeAwayPackaging});
                  resetTextArea('text-area-${id}');
                  updateBasketItemNotes('notes-${id}', notes, ${id});
                  generateBasketItems();
                 ">
                  <div>
                    KEMAS KINI BAKUL
                  </div>
                  <div id="price-${id}">
                    RM${(price + takeAwayPackaging).toFixed(2)}
                  </div>
                </button>
              </div>

            </dialog>
            
          `;
      })
      .join("");

    document.getElementById("footer-basket-main").innerHTML = `
      <div class="basket-checkout-div">
        <a href="#" class="basket-checkout-link">
          <div class="checkout-text">BAYAR</div>
          <div class="checkout-item-and-price">
            <div id="checkout-item" class="checkout-item">0 ITEM</div>
            <div id="checkout-price" class="checkout-price">RM0.00</div>
          </div>
        </a>
      </div>
    `;

    basketPlaceOrderButton = document.querySelector(".basket-checkout-link");
    choosePaymentMethodText = document.getElementById(
      "choose-payment-method-text"
    );
    deliveryAddressInputField = document.getElementById("input-address");

    checkPlaceOrderButton();

    //? Make Checkout Box become visible.
    CheckoutBox.classList.remove("hide");
    CheckoutSummaryDetails.classList.remove("hide");

    updateSubtotal();
    updateTax();
    // checkRoundingBasket();
    CalculateTotalPriceBasket();
    updateTotalQuantity();
    defaultFoodPreviewImage();
    updateBasketItemCount();

    /// Make INCREMENT and DECREMENT button INTERACTIVE
    // Add event listeners to buttons
    document
      .querySelectorAll(".first-vertical-layout")
      .forEach((itemElement, index) => {
        // Add event listener for increment button
        itemElement
          .querySelector(".plus-button-basket")
          .addEventListener("click", () => {
            basketItems[index].quantity++; // Increment the corresponding object's quantity
            updateQuantity(itemElement, basketItems[index].quantity); // Update Quantity
            updateSubtotal();
            updateTax();
            // checkRoundingBasket();
            CalculateTotalPriceBasket();
            updateTotalQuantity();
            updateItemPrice(
              itemElement,
              basketItems[index].quantity,
              basketItems[index]
            );
            generateBasketItems();
          });

        // Add event listener for decrement button
        itemElement
          .querySelector(".minus-button-basket")
          .addEventListener("click", () => {
            if (basketItems[index].quantity !== 0) {
              basketItems[index].quantity--; // Decrement the corresponding object's quantity
              updateQuantity(itemElement, basketItems[index].quantity); // Update Quantity
              updateSubtotal();
              updateTax();
              // checkRoundingBasket();
              CalculateTotalPriceBasket();
              updateTotalQuantity();
              updateItemPrice(
                itemElement,
                basketItems[index].quantity,
                basketItems[index]
              );
              removeItem();
              checkBasketItems();
              generateBasketItems();
              addClassNameToBasketEmpty();
              updateBasketItemCount();
            }
          });
      });
  } else {
    if (CheckoutBox !== null && CheckoutSummaryDetails !== null) {
      CheckoutBox.classList.add("hide");
      CheckoutSummaryDetails.classList.add("hide");
      document.getElementById("basket-items").innerHTML = `
        <div class="basket-items-div" id="basket-items-div">
          <p>Tiada item lagi</p>
        </div>
      `;
    }
  }
}

generateBasketItems();

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

function defaultFoodPreviewImage() {
  document
    .querySelectorAll(".food-preview-image-modal")
    .forEach((foodPreviewImage, foodPreviewImageId) => {
      if (!foodPreviewImage.getAttribute("src")) {
        foodPreviewImage.src = "icons/fork-and-knife.svg";
        foodPreviewImage.classList.add("no-image");
        addNoImageClass(foodPreviewImageId);
      }
    });
}

function addNoImageClass(index) {
  document
    .querySelectorAll(".modal-food-preview-image")
    .forEach((foodPreviewImageModal, i) => {
      if (i === index) {
        foodPreviewImageModal.classList.add("no-image");
      }
    });
}

//! Add class name to the elements if basket is empty
addClassNameToBasketEmpty();
function addClassNameToBasketEmpty() {
  let basketItemsLength = JSON.parse(localStorage.getItem("basketItems")) || [];
  let main = document.querySelector(".main");
  basketItemsLength = basketItemsLength.length;
  if (basketItemsLength === 0) {
    main.classList.add("empty");
  }
}

//! Increment Button
let incrementQuantityBasket = (quantityID) => {
  let selectedQuantity = quantityID;

  let searchQuantity = basketItems.find(
    (basketItem) => basketItem.id === selectedQuantity
  );

  searchQuantity.quantity++;
  document.getElementById(selectedQuantity).innerHTML = searchQuantity.quantity;
};

//! Decrement Button
let decrementQuantityBasket = (quantityID) => {
  let selectedQuantity = quantityID;

  let searchQuantity = basketItems.find(
    (basketItem) => basketItem.id === selectedQuantity
  );

  if (searchQuantity.quantity === 1) {
    return;
  } else {
    searchQuantity.quantity--;
  }

  document.getElementById(selectedQuantity).innerHTML = searchQuantity.quantity;
};

//! Update Modal Price function
function updateModalPrice(priceID, quantityID, itemPrice, takeAwayPackaging) {
  let selectedPrice = priceID;
  let selectedQuantity = quantityID;

  let quantity = document.getElementById(selectedQuantity).innerHTML;

  totalPrice = quantity * itemPrice + takeAwayPackaging * quantity;
  totalPrice = totalPrice.toFixed(2);

  document.getElementById(selectedPrice).innerHTML = `RM${totalPrice}`;
}

//! Reset Modal Quantity function
function resetModalQuantity(quantityID, itemID) {
  let basketItemsLocalStorage = JSON.parse(localStorage.getItem("basketItems"));
  let selectedItemLocalStorage = basketItemsLocalStorage.find(
    (basketItem) => basketItem.id === itemID
  );
  let selectedItem = basketItems.find((basketItem) => basketItem.id === itemID);

  selectedItem.quantity = selectedItemLocalStorage.quantity;
  // console.log(selectedItem.quantity);

  document.getElementById(quantityID).innerHTML = selectedItem.quantity;
}

//! resetTextArea Function
function resetTextArea(textAreaID) {
  let selectedTextArea = document.getElementById(textAreaID);
  selectedTextArea.value = "";
}

//! Get Additional Notes Function
function getModalAdditionalNotes(textAreaID) {
  let selectedTextArea = document.getElementById(textAreaID);
  let additionalNotes = selectedTextArea.value;

  if (additionalNotes === "") {
    additionalNotes = "undefined";
  }

  return `Notes: ${additionalNotes}`;
}

//! Update Basket Item notes Function
function updateBasketItemNotes(notesID, notes, itemID) {
  let selectedItem = basketItems.find((basketItem) => basketItem.id === itemID);

  if (notes.toLowerCase().includes("undefined")) {
    document.getElementById(notesID).innerHTML = "";
    selectedItem.notes = "";
    localStorage.setItem("basketItems", JSON.stringify(basketItems));
  } else {
    document.getElementById(notesID).innerHTML = notes;
    selectedItem.notes = notes;

    localStorage.setItem("basketItems", JSON.stringify(basketItems));
  }
}

//! Get Basket Notes function
function getBasketNotes(textAreaID, itemID) {
  let selectedTextArea = document.getElementById(textAreaID);
  let selectedItem = basketItems.find((basketItem) => basketItem.id === itemID);
  selectedItem.notes = selectedItem.notes.replace("Notes:", "").trim();
  selectedItem.notes = selectedItem.notes.replace("undefined", "").trim();

  selectedTextArea.value = selectedItem.notes;
}

//! Get Basket Quantity function
function getBasketQuantity(quantityID, itemID) {
  let selectedQuantity = quantityID;
  let selectedItem = basketItems.find((basketItem) => basketItem.id === itemID);

  document.getElementById(selectedQuantity).innerHTML = selectedItem.quantity;
}

//! Get Modal Basket Item Price function
function totalPriceModalBasketItem(
  quantityID,
  itemPrice,
  takeAwayPackaging,
  totalPriceID
) {
  let quantity = document.getElementById(quantityID).innerHTML;
  let totalPrice = quantity * itemPrice + takeAwayPackaging * quantity;
  totalPrice = totalPrice.toFixed(2);

  document.getElementById(totalPriceID).innerHTML = `RM${totalPrice}`;
}

/// Show Item Modal When Click in Items Basket
function showModalBasketItem(dialogID) {
  // console.log(dialogID);

  let selectedModal = document.getElementById(dialogID);
  selectedModal.showModal();
  body.classList.add("blur-hide-scroll-bar");
}

/// Close Item Modal When Click in Items Basket
function closeModalBasketItem(dialogID) {
  // console.log(dialogID);

  let selectedModal = document.getElementById(dialogID);
  selectedModal.close();
  body.classList.remove("blur-hide-scroll-bar");
}

/// Remove item when QUANTITY = 0.
function removeItem() {
  let newBasketItems = basketItems.filter((item) => item.quantity !== 0);
  // console.log(newBasketItems);

  localStorage.setItem("basketItems", JSON.stringify(newBasketItems));
  basketItems = newBasketItems;
  // console.log(basketItems);

  generateBasketItems();
}

/// Update item quantity function
function updateQuantity(itemElement, quantity) {
  itemElement.querySelector(".quantity-value").textContent = quantity;
  // console.log(basketItems);

  localStorage.setItem("basketItems", JSON.stringify(basketItems));
}

/// Update total item price function
function updateItemPrice(itemElement, quantity, selectedItemIndex) {
  // console.log(basketItems);
  // console.log(selectedItemIndex);
  itemElement.querySelector(".item-price").textContent = `
    RM${(selectedItemIndex.price * quantity).toFixed(2)}
  `;
}

/// Update Subtotal function
function updateSubtotal() {
  // console.log(basketItems);
  let subTotal = basketItems.reduce(
    (sum, item) =>
      sum + item.price * item.quantity + item.takeAwayPackaging * item.quantity,
    0
  );
  // console.log('Subtotal:', subTotal);

  subTotal = subTotal.toFixed(2);
  document.getElementById("subtotal-basket").innerHTML = `RM${subTotal}`;
}

/// Update Tax function
function updateTax() {
  let tax = 0;
  basketItems.forEach((basketItem) => {
    let { price, quantity } = basketItem;
    tax = tax + (price * quantity * 6) / 100;
  });

  tax = tax.toFixed(2);

  document.getElementById("tax-basket").innerHTML = `RM${tax}`;
}

/// Calculate Total Price Basket function
function CalculateTotalPriceBasket() {
  let totalPrice = document.getElementById("total-price-basket");

  //? Subtotal
  let subtotal = parseFloat(
    document.getElementById("subtotal-basket").textContent.replace("RM", "")
  );
  // console.log(subtotal);

  //? Tax
  let tax = parseFloat(
    document.getElementById("tax-basket").textContent.replace("RM", "")
  );
  // console.log(tax);

  //? Rounding
  // let rounding = parseFloat(
  //   document.getElementById("rounding-price").textContent.replace("RM", "")
  // );
  // console.log(rounding);

  //? Total
  // discountAmount = parseFloat(discountAmount);
  let total = subtotal + tax;
  total = total.toFixed(2);
  // console.log(total);

  totalPrice.innerHTML = `RM${total}`;
  //? Check If This Element Exists!
  if (document.getElementById("checkout-price")) {
    document.getElementById("checkout-price").innerHTML = `RM${total}`;
  }
}

///Update Total Items Quantity function
function updateTotalQuantity() {
  // console.log(basketItems);
  const totalQuantity = basketItems.reduce(
    (totalQuantity, item) => totalQuantity + item.quantity,
    0
  );
  // console.log(totalQuantity);

  //? Check If this Exists!
  if (document.getElementById("checkout-item")) {
    if (totalQuantity === 1) {
      document.getElementById(
        "checkout-item"
      ).innerHTML = `${totalQuantity} ITEM`;
    } else {
      document.getElementById(
        "checkout-item"
      ).innerHTML = `${totalQuantity} ITEMS`;
    }
  }
}

//! Function To Check Basket Items !\\
let checkoutAndBasketSections = document.getElementById(
  "js-checkout-basket-section"
);
checkBasketItems();
function checkBasketItems() {
  if (basketItems.length === 0) {
    checkoutAndBasketSections.innerHTML = "Bakul kosong";
    checkoutAndBasketSections.classList.add("empty");
  }
}

document
  .querySelectorAll(".food-preview-image-modal")
  .forEach((foodPreviewImage, foodPreviewImageId) => {
    if (!foodPreviewImage.getAttribute("src")) {
      foodPreviewImage.src = "icons/fork-and-knife.svg";
      foodPreviewImage.classList.add("no-image");
    }
  });
//* Generate Food Basket JS ends here!

// * Check Out Page JS starts here! *\\
let pickUpButton = document.getElementById("pick-up-button");
let deliveryButton = document.getElementById("delivery-button");
let pickUpPaths = document.querySelectorAll(".path-pick-up");
let deliveryPaths = document.querySelectorAll(".path-delivery");
let checkoutDetailsContainer = document.getElementById(
  "checkout-details-container"
);
let deliveryDetailsContainer = document.querySelector(
  ".delivery-details-container"
);
let paymentMethodDialog = document.getElementById("payment-method-dialog");

let inputCard = document.getElementById("input-Card");
let inputGrabPay = document.getElementById("input-GrabPay");
let inputAlipay = document.getElementById("input-Alipay");
let selectPaymentMethodButton = document.getElementById(
  "js-select-payment-method-button"
);

let redeemCodeButton = document.getElementById("js-redeem-code-button");
let redeemCodeInput = document.getElementById("js-redeem-code-input");
// let discountErrorText = document.querySelector(".discount-error");

let paymentMethodIcon = document.getElementById("payment-method-icon");

let changePaymentMethodIcon = document.getElementById(
  "change-payment-method-icon"
);

/// Close dialog button
let closeDialogButton = document.getElementById("js-close-dialog-button");

//? Check If Exists!
if (pickUpButton) {
  pickUpButton.addEventListener("click", () => {
    deliveryDetailsContainer.innerHTML = "";
    updatePickUpPlaceOrderStatusBUTTON();
  });
}

//? Check If Exists!
if (deliveryButton) {
  deliveryButton.addEventListener("click", () => {
    deliveryDetailsContainer.innerHTML = `
      <p class="delivery-details-title">Alamat Penghantaran</p>
      <div class="delivery-details-sub-container">
        <input id="input-address" title="Sila masukkan alamat anda" type="text" />
      </div>
    `;
    deliveryAddressInputField = document.getElementById("input-address");
    updateDeliveryPlaceOrderStatusBUTTON();
  });
}

//! Render Order Type from LocalStorage !\\
renderOrderType();
function renderOrderType() {
  let orderType = localStorage.getItem("Order Type");
  //? Check If Exists!
  if (document.getElementById("order-type")) {
    document.getElementById("order-type").innerHTML = orderType;
    if (orderType === "Penghantaran") {
      deliveryButton.classList.add("clicked");
      deliveryPaths.forEach((deliveryPath) => {
        deliveryPath.classList.add("active");
      });
      pickUpButton.classList.remove("clicked");
    } else if (orderType === "Ambil Tempahan") {
      pickUpButton.classList.add("clicked");
      pickUpPaths.forEach((pickPath) => {
        pickPath.classList.add("active");
      });
      deliveryButton.classList.remove("clicked");
    }
  }
}

//! Function To Change Pick Up Button !\\
changePickUpPathColor();
function changePickUpPathColor() {
  //? Check If Pick Up Button Exists!
  if (pickUpButton) {
    pickUpButton.addEventListener("mouseenter", () => {
      pickUpPaths.forEach((pickPath) => {
        pickPath.classList.add("active");
      });
    });

    pickUpButton.addEventListener("mouseleave", () => {
      if (!pickUpButton.classList.contains("clicked")) {
        pickUpPaths.forEach((pickPath) => {
          pickPath.classList.remove("active");
        });
      }
    });

    pickUpButton.addEventListener("click", () => {
      pickUpButton.classList.add("clicked");
      deliveryButton.classList.remove("clicked");
      //? Save in the loclalStorage ?//
      localStorage.setItem("Order Type", "Ambil Tempahan");
      renderOrderType();

      deliveryPaths.forEach((deliveryPath) => {
        deliveryPath.classList.remove("active");
      });

      pickUpPaths.forEach((pickPath) => {
        pickPath.classList.add("active");
      });
    });
  }
}

//! Function To Change Delivery Button !\\
changeDeliveryPathColor();
function changeDeliveryPathColor() {
  //? Check If Delivery Button Exists!
  if (deliveryButton) {
    deliveryButton.addEventListener("mouseenter", () => {
      deliveryPaths.forEach((deliveryPath) => {
        deliveryPath.classList.add("active");
      });
    });

    deliveryButton.addEventListener("mouseleave", () => {
      if (!deliveryButton.classList.contains("clicked")) {
        deliveryPaths.forEach((deliveryPath) => {
          deliveryPath.classList.remove("active");
        });
      }
    });

    deliveryButton.addEventListener("click", () => {
      deliveryButton.classList.add("clicked");
      pickUpButton.classList.remove("clicked");
      //? Save in the loclalStorage ?//
      localStorage.setItem("Order Type", "Penghantaran");
      renderOrderType();

      pickUpPaths.forEach((pickPath) => {
        pickPath.classList.remove("active");
      });

      deliveryPaths.forEach((deliveryPath) => {
        deliveryPath.classList.add("active");
      });
    });
  }
}

//! Run the loader when Screen Reloads !\\
window.addEventListener("load", () => {
  let loader = document.querySelector(".loader");
  loader.classList.add("loader--hidden");

  loader.addEventListener("transitionend", () => {
    if (document.querySelector(".loader")) {
      document.body.removeChild(loader);
    }
  });
});

//! Function to Show And Close Payment Methods !\\
paymentMethodButtonAddEvenetListener();
function paymentMethodButtonAddEvenetListener() {
  //? Check If This Exists!
  if (document.querySelector(".payment-method-sub-container")) {
    let paymentMethodButton = document.querySelector(
      ".payment-method-sub-container"
    );
    let closeButton = document.getElementById("js-close-dialog-button");

    paymentMethodButton.addEventListener("click", () => {
      paymentMethodDialog.showModal();
      checkPaymentMethodRadioButton();
    });

    closeButton.addEventListener("click", () => {
      paymentMethodDialog.close();
      uncheckPaymentMethodRadioButton();
    });
  }
}

//! Function To Change Payment Method !\\
function changePaymentMethod() {
  if (inputCard.checked) {
    paymentMethodIcon.innerHTML = `<i class="bi bi-credit-card"></i>`;
    choosePaymentMethodText.innerHTML = inputCard.value;
    changePaymentMethodIcon.innerHTML = `<img src="icons/right-arrow.svg" alt="" />`;
  } else if (inputGrabPay.checked) {
    paymentMethodIcon.innerHTML = `<img src="icons/grab-pay.png" alt="" />`;
    choosePaymentMethodText.innerHTML = inputGrabPay.value;
    changePaymentMethodIcon.innerHTML = `<img src="icons/right-arrow.svg" alt="" />`;
  } else if (inputAlipay.checked) {
    paymentMethodIcon.innerHTML = `<img src="icons/alipay.webp" alt="" />`;
    choosePaymentMethodText.innerHTML = inputAlipay.value;
    changePaymentMethodIcon.innerHTML = `<img src="icons/right-arrow.svg" alt="" />`;
  }
}

//! Function To Show And Hide Delivery Details !\\
showAndHideDeliveryDetails();
function showAndHideDeliveryDetails() {
  if (localStorage.getItem("Order Type") === "Ambil Tempahan") {
    //? Check If This Exits!
    if (deliveryDetailsContainer) {
      deliveryDetailsContainer.innerHTML = "";
    }
  }
}

//! Function To Check If A Payment Method Is Selected !\\
let selectPaymentMethodButtonFUNCTION = () => {
  //? FUNCTIONS THAT WILL RUN WHEN CLICKED
  paymentMethodDialog.close();
  changePaymentMethod();
  updatePickUpOrDeliveryPlaceOrderStatusPAYMENT_METHOD();
};
function checkPaymentMethodRadioButton() {
  if (choosePaymentMethodText.innerText === "Pilih Kaedah Pembayaran") {
    selectPaymentMethodButton.classList.add("disabled");
  } else {
    selectPaymentMethodButton.classList.remove("disabled");
  }
  inputCard.addEventListener("click", () => {
    selectPaymentMethodButton.classList.remove("disabled");
    selectPaymentMethodButton.addEventListener(
      "click",
      selectPaymentMethodButtonFUNCTION
    );
  });

  inputGrabPay.addEventListener("click", () => {
    selectPaymentMethodButton.classList.remove("disabled");
    selectPaymentMethodButton.addEventListener(
      "click",
      selectPaymentMethodButtonFUNCTION
    );
  });

  inputAlipay.addEventListener("click", () => {
    selectPaymentMethodButton.classList.remove("disabled");
    selectPaymentMethodButton.addEventListener(
      "click",
      selectPaymentMethodButtonFUNCTION
    );
  });
}

//! Function To Uncheck Selected Payment Method !\\
function uncheckPaymentMethodRadioButton() {
  if (choosePaymentMethodText.innerText === "Pilih Kaedah Pembayaran") {
    selectPaymentMethodButton.classList.add("disabled");
    inputCard.checked = false;
    inputGrabPay.checked = false;
    inputAlipay.checked = false;
    selectPaymentMethodButton.removeEventListener(
      "click",
      selectPaymentMethodButtonFUNCTION
    );
  } else if (choosePaymentMethodText.innerText === "Kad") {
    inputCard.checked = true;
  } else if (choosePaymentMethodText.innerText === "GrabPay") {
    inputGrabPay.checked = true;
  } else if (choosePaymentMethodText.innerText === "Alipay") {
    inputAlipay.checked = true;
  }
}

//* Function To Update PICK UP Place Order Button Status When Page Reloads
function disableLink(event) {
  if (basketPlaceOrderButton.classList.contains("disabled")) {
    event.preventDefault();
  }
}
updatePickUpPlaceOrderStatusRELOAD();
function updatePickUpPlaceOrderStatusRELOAD() {
  if (localStorage.getItem("Order Type") === "Ambil Tempahan") {
    if (basketPlaceOrderButton) {
      basketPlaceOrderButton.innerHTML = "Tambah Kaedah Pembayaran";
      basketPlaceOrderButton.classList.add("disabled");
      basketPlaceOrderButton.addEventListener("click", disableLink);
      // console.log("Im Disabled");
    }
  }
}

//* Function To Update PICK UP Place Order Button Status When Page Reloads
updateDeliveryPlaceOrderStatusRELOAD();
function updateDeliveryPlaceOrderStatusRELOAD() {
  if (localStorage.getItem("Order Type") === "Penghantaran") {
    basketPlaceOrderButton.innerHTML = "Tambah Alamat";
    basketPlaceOrderButton.classList.add("disabled");
    basketPlaceOrderButton.addEventListener("click", disableLink);

    deliveryAddressInputField.addEventListener("keyup", () => {
      //? Typing Values
      if (deliveryAddressInputField.value.trim() !== "") {
        //? Already Selected A Payment Method!
        if (choosePaymentMethodText.innerText !== "Pilih Kaedah Pembayaran") {
          basketPlaceOrderButton.innerHTML = `
          <div class="checkout-text">BUAT PESANAN</div>
          <div class="checkout-item-and-price">
            <div id="checkout-item" class="checkout-item">0 ITEM</div>
            <div id="checkout-price" class="checkout-price">RM0.00</div>
          </div>
          `;
          basketPlaceOrderButton.classList.remove("disabled");
          basketPlaceOrderButton.removeEventListener("click", disableLink);
          CalculateTotalPriceBasket();
          updateTotalQuantity();
        } else if (
          //? Haven't Select A Payment Method!
          choosePaymentMethodText.innerText === "Pilih Kaedah Pembayaran"
        ) {
          basketPlaceOrderButton.innerHTML = "Tambah Kaedah Pembayaran";
          basketPlaceOrderButton.classList.add("disabled");
          basketPlaceOrderButton.addEventListener("click", disableLink);
        }
      } else if (deliveryAddressInputField.value.trim() === "") {
        //? Not Typing Any Value
        basketPlaceOrderButton.innerHTML = "Tambah Alamat";
        basketPlaceOrderButton.classList.add("disabled");
        basketPlaceOrderButton.addEventListener("click", disableLink);
      }
    });

    deliveryAddressInputField.addEventListener("blur", () => {
      //? No Value Inside Input Field
      if (deliveryAddressInputField.value.trim() === "") {
        basketPlaceOrderButton.innerHTML = "Tambah Alamat";
        basketPlaceOrderButton.classList.add("disabled");
        basketPlaceOrderButton.addEventListener("click", disableLink);
      }
    });
  }
}

//* Function To Update PICK UP or DELIVERY Place Order Button Status When Any Payment Method Is Added
function updatePickUpOrDeliveryPlaceOrderStatusPAYMENT_METHOD() {
  //? Pick Up Order Type
  if (localStorage.getItem("Order Type") === "Ambil Tempahan") {
    basketPlaceOrderButton.innerHTML = `
    <div class="checkout-text">BUAT PESANAN</div>
    <div class="checkout-item-and-price">
      <div id="checkout-item" class="checkout-item">0 ITEM</div>
      <div id="checkout-price" class="checkout-price">RM0.00</div>
    </div>
  `;
    basketPlaceOrderButton.classList.remove("disabled");
    basketPlaceOrderButton.removeEventListener("click", disableLink);
    CalculateTotalPriceBasket();
    updateTotalQuantity();
    //? Delivery Order Type
  } else if (localStorage.getItem("Order Type") === "Penghantaran") {
    //? There is value inside Input Field
    if (deliveryAddressInputField.value.trim() !== "") {
      basketPlaceOrderButton.innerHTML = `
        <div class="checkout-text">BUAT PESANAN</div>
        <div class="checkout-item-and-price">
          <div id="checkout-item" class="checkout-item">0 ITEM</div>
          <div id="checkout-price" class="checkout-price">RM0.00</div>
        </div>
      `;
      basketPlaceOrderButton.classList.remove("disabled");
      basketPlaceOrderButton.removeEventListener("click", disableLink);
      CalculateTotalPriceBasket();
      updateTotalQuantity();
    } else if (deliveryAddressInputField.value.trim() === "") {
      basketPlaceOrderButton.innerHTML = "Tambah Alamat";
      basketPlaceOrderButton.classList.add("disabled");
      basketPlaceOrderButton.addEventListener("click", disableLink);
    }
  }
}

//* Function To Update PICK UP Order Button Status When PICK UP Order Button Is Clicked
function updatePickUpPlaceOrderStatusBUTTON() {
  //? A Payment Method Is Not Selected
  if (choosePaymentMethodText.innerText === "Pilih Kaedah Pembayaran") {
    basketPlaceOrderButton.innerHTML = "Tambah Kaedah Pembayaran";
    basketPlaceOrderButton.classList.add("disabled");
    basketPlaceOrderButton.addEventListener("click", disableLink);
    //? A Payment Method Is Selected
  } else if (choosePaymentMethodText.innerText !== "Pilih Kaedah Pembayaran") {
    basketPlaceOrderButton.innerHTML = `
      <div class="checkout-text">BUAT PESANAN</div>
      <div class="checkout-item-and-price">
        <div id="checkout-item" class="checkout-item">0 ITEM</div>
        <div id="checkout-price" class="checkout-price">RM0.00</div>
      </div>
    `;
    basketPlaceOrderButton.classList.remove("disabled");
    basketPlaceOrderButton.removeEventListener("click", disableLink);
    CalculateTotalPriceBasket();
    updateTotalQuantity();
  }
}

//* Function To Update DELIVERY Order Button Status When DELIVERY Order Button Is Clicked
function updateDeliveryPlaceOrderStatusBUTTON() {
  basketPlaceOrderButton.innerHTML = "Tambah Alamat";
  basketPlaceOrderButton.classList.add("disabled");
  basketPlaceOrderButton.addEventListener("click", disableLink);

  deliveryAddressInputField.addEventListener("keyup", () => {
    //? Typing Values
    if (deliveryAddressInputField.value.trim() !== "") {
      //? Already Selected A Payment Method!
      if (choosePaymentMethodText.innerText !== "Pilih Kaedah Pembayaran") {
        basketPlaceOrderButton.innerHTML = `
        <div class="checkout-text">BUAT PESANAN</div>
        <div class="checkout-item-and-price">
          <div id="checkout-item" class="checkout-item">0 ITEM</div>
          <div id="checkout-price" class="checkout-price">RM0.00</div>
        </div>
        `;
        basketPlaceOrderButton.classList.remove("disabled");
        basketPlaceOrderButton.removeEventListener("click", disableLink);
        CalculateTotalPriceBasket();
        updateTotalQuantity();
      } else if (
        //? Haven't Select A Payment Method!
        choosePaymentMethodText.innerText === "Pilih Kaedah Pembayaran"
      ) {
        basketPlaceOrderButton.innerHTML = "Tambah Kaedah Pembayaran";
        basketPlaceOrderButton.classList.add("disabled");
        basketPlaceOrderButton.addEventListener("click", disableLink);
      }
    } else if (deliveryAddressInputField.value.trim() === "") {
      //? Not Typing Any Value
      basketPlaceOrderButton.innerHTML = "Tambah Alamat";
      basketPlaceOrderButton.classList.add("disabled");
      basketPlaceOrderButton.addEventListener("click", disableLink);
    }
  });

  //? Add Payment Method
  //? There is value inside Input Field
  if (deliveryAddressInputField.value.trim() !== "") {
    basketPlaceOrderButton.innerHTML = `
      <div class="checkout-text">BUAT PESANAN</div>
      <div class="checkout-item-and-price">
        <div id="checkout-item" class="checkout-item">0 ITEM</div>
        <div id="checkout-price" class="checkout-price">RM0.00</div>
      </div>
    `;
    basketPlaceOrderButton.classList.remove("disabled");
    basketPlaceOrderButton.removeEventListener("click", disableLink);
    CalculateTotalPriceBasket();
    updateTotalQuantity();
    //? Empty value inside Input Field
  } else if (deliveryAddressInputField.value.trim() === "") {
    basketPlaceOrderButton.innerHTML = "Tambah Alamat";
    basketPlaceOrderButton.classList.add("disabled");
    basketPlaceOrderButton.addEventListener("click", disableLink);
  }
}

function checkPlaceOrderButton() {
  if (localStorage.getItem("Order Type") === "Ambil Tempahan") {
    if (choosePaymentMethodText.innerText === "Pilih Kaedah Pembayaran") {
      updatePickUpPlaceOrderStatusRELOAD();
    } else if (
      choosePaymentMethodText.innerText !== "Pilih Kaedah Pembayaran"
    ) {
      updatePickUpOrDeliveryPlaceOrderStatusPAYMENT_METHOD();
    }
  } else if (localStorage.getItem("Order Type") === "Penghantaran") {
    if (deliveryAddressInputField.value.trim() !== "") {
      //? Already Selected A Payment Method!
      if (choosePaymentMethodText.innerText !== "Pilih Kaedah Pembayaran") {
        basketPlaceOrderButton.innerHTML = `
        <div class="checkout-text">BUAT PESANAN</div>
        <div class="checkout-item-and-price">
          <div id="checkout-item" class="checkout-item">0 ITEM</div>
          <div id="checkout-price" class="checkout-price">RM0.00</div>
        </div>
        `;
        basketPlaceOrderButton.classList.remove("disabled");
        basketPlaceOrderButton.removeEventListener("click", disableLink);
        CalculateTotalPriceBasket();
        updateTotalQuantity();
      } else if (
        //? Haven't Select A Payment Method!
        choosePaymentMethodText.innerText === "Pilih Kaedah Pembayaran"
      ) {
        basketPlaceOrderButton.innerHTML = "Tambah Kaedah Pembayaran";
        basketPlaceOrderButton.classList.add("disabled");
        basketPlaceOrderButton.addEventListener("click", disableLink);
      }
    } else if (deliveryAddressInputField.value.trim() === "") {
      //? Not Typing Any Value
      basketPlaceOrderButton.innerHTML = "Tambah Alamat";
      basketPlaceOrderButton.classList.add("disabled");
      basketPlaceOrderButton.addEventListener("click", disableLink);
    }
  }
}
// * Check Out Page JS ends here! *\\

//* Send Data To checkout.php When Button Is Clicked starts here! *\\
if (basketPlaceOrderButton != null) {
  basketPlaceOrderButton.addEventListener("click", () => {
    if (!basketPlaceOrderButton.classList.contains("disabled")) {
      let deliveryAddress;
      if (document.getElementById("input-address") !== null) {
        deliveryAddress = document.getElementById("input-address").value;
      } else {
        deliveryAddress = "";
      }
      const additionalNotes = document.getElementById("additionalNotes").value;
      let subtotal = document.getElementById("subtotal-basket").innerText;
      subtotal = subtotal.replace("RM", "");
      subtotal = Number(subtotal).toFixed(2);

      let tax = document.getElementById("tax-basket").innerText;
      tax = tax.replace("RM", "");
      tax = Number(tax).toFixed(2);

      const basketItemsString = localStorage.getItem("basketItems");
      if (!basketItemsString) {
        console.error("Basket items not found in localStorage.");
        alert("Your basket is empty!");
        return;
      }

      const data = JSON.parse(basketItemsString);

      // Validate basket items
      if (!Array.isArray(data) || data.length === 0) {
        console.error("Invalid or empty basket items.");
        alert("Your basket items are invalid or empty.");
        return;
      }

      totalItems = 0;

      data.forEach((item) => {
        totalItems += item.quantity;
      });
      console.log(totalItems);

      // Convert discount amount to cents
      // const discountAmountCents = discountAmount * 100;

      // Disable button to prevent multiple requests
      basketPlaceOrderButton.disabled = true;

      fetch("checkout.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          items: data,
          // Order Details
          customerDetails: {
            deliveryAddress: deliveryAddress,
            additionalNotes: additionalNotes,
            subtotal: subtotal,
            tax: tax,
            totalItems: totalItems,
          },
        }),
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => response.json())
        .then((result) => {
          if (result.url) {
            window.location.href = result.url; // Redirect to Stripe Checkout
          } else {
            console.error("Error:", result.error);
            alert("An error occurred while creating the checkout session.");
          }
        })
        .catch((error) => {
          console.error("Fetch Error:", error);
          alert("Network error occurred. Please try again later.");
        })
        .finally(() => {
          // Re-enable button
          basketPlaceOrderButton.disabled = false;
        });
    }
  });
}

//* Send Data To checkout.php When Button Is Clicked ends here! *\\
