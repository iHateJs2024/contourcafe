//* Generate Food Basket JS starts here!
let basketItems = JSON.parse(localStorage.getItem("basketItems")) || [];

/// Generate new objects in basketData when cicked the Add To Cart Button.
function addObject(
  selectedItem,
  itemPrice,
  takeAwayPackaging,
  quantity,
  notes,
  img
) {
  let findBasketItem = basketItems.find(
    (basketItem) => basketItem.name === selectedItem
  );

  if (findBasketItem === undefined) {
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
  } else if (findBasketItem !== undefined) {
    basketItems = basketItems.map((basketItem) => {
      if (basketItem.name === selectedItem) {
        return { ...basketItem, quantity: basketItem.quantity + quantity }; // Modify the object
      }
      return basketItem; // Keep other objects unchanged
    });

    // Step 3: Store the updated array back in localStorage
    localStorage.setItem("basketItems", JSON.stringify(basketItems));
  }
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
                    <img src="../../icons/minus-circular-button.png"></img>
                  </button>
                <div class="quantity"><span class="quantity-value">${quantity}</span></div>
                <button class="plus-button-basket">
                    <img src="../../icons/plus-button.png"></img>
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
                  <strong class="take-away-packaging-${id}">
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
                  <img class="food-preview-image-modal" src="admin/${img}" alt=""></img>
                </div>

                <!--Third Vertical Layout-->
                <div class="modal-food-preview-name-and-price">
                  <div class="modal-food-preview-name">${name}</div>
                  <div class="modal-food-preview-price">
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
                      <img src="../../icons/tick.svg"></img>
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
                  defaultFoodPreviewImage();
                  ">
                    <img src="../../icons/minus-circular-button.png"></img>
                  </button>
                  <div id="${id}" class="quantity">1</div>
                  <button class="plus-button" onclick="
                  incrementQuantityBasket(${id});
                  updateModalPrice('price-${id}', ${id}, ${price}, ${takeAwayPackaging});
                  defaultFoodPreviewImage();
                  ">
                    <img src="../../icons/plus-button.png"></img>
                  </button>
                  
                </div>
                <button class="add-to-basket-button"  onclick="
                  let notes = getModalAdditionalNotes('text-area-${id}');
                  closeModalBasketItem('dialog-${id}');
                  updateModalPrice('price-${id}', ${id}, ${price}, ${takeAwayPackaging});
                  resetTextArea('text-area-${id}');
                  updateBasketItemNotes('notes-${id}', notes, ${id});
                  generateBasketItems();
                  defaultFoodPreviewImage();
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
            <a href="login-signup.html" class="basket-checkout-link">
              <div class="checkout-text">BAYAR</div>
              <div class="checkout-item-and-price">
                <div id="checkout-item" class="checkout-item">0 ITEM</div>
                <div id="checkout-price" class="checkout-price">RM0.00</div>
              </div>
            </a>
          </div>
        `;

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
    addNoImageClassBasket();

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
            defaultFoodPreviewImage();
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
              generateBasketItems();
              defaultFoodPreviewImage();
              updateBasketItemCount();
            }
          });
      });
  } else {
    CheckoutBox.classList.add("hide");
    CheckoutSummaryDetails.classList.add("hide");

    document.getElementById("basket-items").innerHTML = `
        <div class="basket-items-div" id="basket-items-div">
          <p>Tiada item lagi</p>
        </div>
      `;
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

addNoImageClassBasket();
function addNoImageClassBasket() {
  document.querySelectorAll(".modal-food-preview-image").forEach((modal) => {
    let image = modal.querySelector("img");

    if (
      image.src === "http://contourcafe.great-site.net/icons/fork-and-knife.svg"
    ) {
      modal.classList.add("no-image");
    }
  });
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
  console.log(totalPrice);

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
  const subTotal = basketItems.reduce(
    (sum, item) =>
      sum + item.price * item.quantity + item.takeAwayPackaging * item.quantity,
    0
  );
  // console.log('Subtotal:', subTotal);

  document.getElementById("subtotal-basket").innerHTML = `
    RM${subTotal.toFixed(2)}
  `;
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

// / Check Rounding Basket function
// function checkRoundingBasket() {
//   let tax = document.getElementById("tax-basket").textContent;
//   let number = parseFloat(tax.replace("RM", ""));
//   number = number.toFixed(2);
//   number = Math.trunc(number * 100);
//   let lastDigit = number % 10;
//   let rounding = 0;

//   let basketRoundingAndPrice = document.getElementById(
//     "rounding-and-price-basket"
//   );

//   switch (lastDigit) {
//     case 5:
//     case 0:
//       basketRoundingAndPrice.classList.add("hide");
//       break;

//     case 1:
//       rounding = 4;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;

//     case 2:
//       rounding = 3;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;

//     case 3:
//       rounding = 2;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;

//     case 4:
//       rounding = 1;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;

//     case 6:
//       rounding = 4;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;

//     case 7:
//       rounding = 3;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;

//     case 8:
//       rounding = 2;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;

//     case 9:
//       rounding = 1;
//       rounding = rounding / 100;
//       // console.log(rounding);
//       basketRoundingAndPrice.classList.remove("hide");
//       updateRounding(rounding);
//       break;
//   }

//   //! Update Rounding Price function
//   function updateRounding(rounding) {
//     document.getElementById("rounding-price").innerHTML = `RM${rounding}`;
//   }
// }

/// Calculate Total Price Basket function
function CalculateTotalPriceBasket() {
  let totalPrice = document.getElementById("total-price-basket");

  //? SUBTOTAL
  let subtotal = parseFloat(
    document.getElementById("subtotal-basket").textContent.replace("RM", "")
  );
  // console.log(subtotal);

  //? Total
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
  // let total = subtotal + tax + rounding;
  let total = subtotal + tax;
  total = total.toFixed(2);
  // console.log(total);

  totalPrice.innerHTML = `RM${total}`;
  document.getElementById("checkout-price").innerHTML = `RM${total}`;
}

///Update Total Items Quantity function
function updateTotalQuantity() {
  // console.log(basketItems);
  const totalQuantity = basketItems.reduce(
    (totalQuantity, item) => totalQuantity + item.quantity,
    0
  );
  // console.log(totalQuantity);

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
//* Generate Food Basket JS ends here!
