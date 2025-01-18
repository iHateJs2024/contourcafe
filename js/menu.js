/*
 *  Author: Kong Jia Le';
 *  Theme: Restaurant Food Order;
 *  version: 1.0;
 */

/*
 * JavaScripts For Dialog, Open button and Close Button starts here!
 */

//!Target dialog open and close buttons.
const openModalButton = document.getElementById("open-work-hour-button");
const mobileOpenModalButton = document.getElementById(
  "mobile-open-work-hour-button"
);
const openModalDropDownButton = document.getElementById("drop-down-button");
const mobileOpenModalDropDownButton = document.getElementById(
  "mobile-drop-down-button"
);
const dropDownIcon = document.getElementById("drop-down-icon");
const mobileDropDownIcon = document.getElementById("mobile-drop-down-icon");
const openHoursMenu = document.getElementById("open-hours-menu-dialog");
const closeModalButton = document.getElementById("close-modal-button");
const blurAndRemoveScrollBar = document.getElementById("body");

//!Run this when Open button is clicked.
openModalButton.addEventListener("click", () => {
  blurAndRemoveScrollBar.classList.add("blur-hide-scroll-bar");
  openHoursMenu.showModal();
});

mobileOpenModalButton.addEventListener("click", () => {
  blurAndRemoveScrollBar.classList.add("blur-hide-scroll-bar");
  openHoursMenu.showModal();
});

//!Run this when Drop down button is clicked.
openModalDropDownButton.addEventListener("click", () => {
  blurAndRemoveScrollBar.classList.add("blur-hide-scroll-bar");
  openHoursMenu.showModal();
});

mobileOpenModalDropDownButton.addEventListener("click", () => {
  blurAndRemoveScrollBar.classList.add("blur-hide-scroll-bar");
  openHoursMenu.showModal();
});

//!Run this when Close button is clicked.
closeModalButton.addEventListener("click", () => {
  blurAndRemoveScrollBar.classList.remove("blur-hide-scroll-bar");
  openHoursMenu.close();
});

//!Get current day
let date = new Date();
let dayNumber = date.getDay();
const day = [
  "Sunday:",
  "Monday:",
  "Tuesday:",
  "Wednesday:",
  "Thursday:",
  "Friday:",
  "Saturday:",
];
const time = ["8am–4pm", "8am–6pm"];

//!Get current Time
let currentTime = new Date();
let hours = currentTime.getHours();
let minutes = currentTime.getMinutes();

//!Check current Time and Update the Open Button.
switch (dayNumber) {
  //*TODAY IS SUNDAY, WEDNESDAY or SUNDAY.
  case 0:
  case 3:
  case 6:
    if (hours < 8 || (hours === 18) & (minutes > 0) || hours > 18) {
      openModalButton.classList.add("before-open-time");
      openModalButton.textContent = "Open At 8am";
      mobileOpenModalButton.classList.add("before-open-time");
      mobileOpenModalButton.textContent = "Open At 8am";
      dropDownIcon.classList.add("close");
      mobileDropDownIcon.classList.add("close");
    } else if (hours >= 8 && hours <= 18) {
      openModalButton.classList.remove("before-open-time");
      openModalButton.textContent = "Open";
      mobileOpenModalButton.classList.remove("before-open-time");
      mobileOpenModalButton.textContent = "Open";
      dropDownIcon.classList.remove("close");
      mobileDropDownIcon.classList.remove("close");
    }
    break;

  //*TODAY IS MONDAY, TUESDAY, THURSDAY or FRIDAY.
  case 1:
  case 2:
  case 4:
  case 5:
    if (hours < 8 || (hours === 16) & (minutes > 0) || hours > 16) {
      openModalButton.classList.add("before-open-time");
      openModalButton.textContent = "Open At 8am";
      mobileOpenModalButton.classList.add("before-open-time");
      mobileOpenModalButton.textContent = "Open At 8am";
      dropDownIcon.classList.add("close");
      mobileDropDownIcon.classList.add("close");
    } else if (hours >= 8 && hours <= 16) {
      openModalButton.classList.remove("before-open-time");
      openModalButton.textContent = "Open";
      mobileOpenModalButton.classList.remove("before-open-time");
      mobileOpenModalButton.textContent = "Open";
      dropDownIcon.classList.remove("close");
      mobileDropDownIcon.classList.remove("close");
    }
    break;
}

//*Let all Days and Time be empty String.
let firstDay = "";
let firstTime = "";
let secondDay = "";
let secondTime = "";
let thirdDay = "";
let thirdTime = "";
let fourthDay = "";
let fourthTime = "";
let fifthDay = "";
let fifthTime = "";
let sixthDay = "";
let sixthTime = "";
let seventhDay = "";
let seventhTime = "";

//*Change the Days and Time
switch (dayNumber) {
  //*TODAY IS SUNDAY!
  case 0:
    firstDay = 0;
    firstTime = 1;
    secondDay = 1;
    secondTime = 0;
    thirdDay = 2;
    thirdTime = 0;
    fourthDay = 3;
    fourthTime = 1;
    fifthDay = 4;
    fifthTime = 0;
    sixthDay = 5;
    sixthTime = 0;
    seventhDay = 6;
    seventhTime = 1;
    break;

  //*TODAY IS MONDAY!
  case 1:
    firstDay = 1;
    firstTime = 0;
    secondDay = 2;
    secondTime = 0;
    thirdDay = 3;
    thirdTime = 1;
    fourthDay = 4;
    fourthTime = 0;
    fifthDay = 5;
    fifthTime = 0;
    sixthDay = 6;
    sixthTime = 1;
    seventhDay = 0;
    seventhTime = 1;
    break;

  //*TODAY IS TUESDAY!
  case 2:
    firstDay = 2;
    firstTime = 0;
    secondDay = 3;
    secondTime = 1;
    thirdDay = 4;
    thirdTime = 0;
    fourthDay = 5;
    fourthTime = 0;
    fifthDay = 6;
    fifthTime = 1;
    sixthDay = 0;
    sixthTime = 1;
    seventhDay = 1;
    seventhTime = 0;
    break;

  //*TODAY IS WEDNESDAY!
  case 3:
    firstDay = 3;
    firstTime = 1;
    secondDay = 4;
    secondTime = 0;
    thirdDay = 5;
    thirdTime = 0;
    fourthDay = 6;
    fourthTime = 1;
    fifthDay = 0;
    fifthTime = 1;
    sixthDay = 1;
    sixthTime = 0;
    seventhDay = 2;
    seventhTime = 0;
    break;

  //*TODAY IS THURSDAY!
  case 4:
    firstDay = 4;
    firstTime = 0;
    secondDay = 5;
    secondTime = 0;
    thirdDay = 6;
    thirdTime = 1;
    fourthDay = 0;
    fourthTime = 1;
    fifthDay = 1;
    fifthTime = 0;
    sixthDay = 2;
    sixthTime = 0;
    seventhDay = 3;
    seventhTime = 1;
    break;

  //*TODAY IS FRIDAY!
  case 5:
    firstDay = 5;
    firstTime = 0;
    secondDay = 6;
    secondTime = 1;
    thirdDay = 0;
    thirdTime = 1;
    fourthDay = 1;
    fourthTime = 0;
    fifthDay = 2;
    fifthTime = 0;
    sixthDay = 3;
    sixthTime = 1;
    seventhDay = 4;
    seventhTime = 0;
    break;

  //*TODAY IS SATURDAY!
  case 6:
    firstDay = 6;
    firstTime = 1;
    secondDay = 0;
    secondTime = 1;
    thirdDay = 1;
    thirdTime = 0;
    fourthDay = 2;
    fourthTime = 0;
    fifthDay = 3;
    fifthTime = 1;
    sixthDay = 4;
    sixthTime = 0;
    seventhDay = 5;
    seventhTime = 0;
    break;
}

//?Update the day and time
document.getElementById("1st-day").textContent = day[firstDay];
document.getElementById("1st-time").textContent = time[firstTime];
document.getElementById("2nd-day").textContent = day[secondDay];
document.getElementById("2nd-time").textContent = time[secondTime];
document.getElementById("3rd-day").textContent = day[thirdDay];
document.getElementById("3rd-time").textContent = time[thirdTime];
document.getElementById("4th-day").textContent = day[fourthDay];
document.getElementById("4th-time").textContent = time[fourthTime];
document.getElementById("5th-day").textContent = day[fifthDay];
document.getElementById("5th-time").textContent = time[fifthTime];
document.getElementById("6th-day").textContent = day[sixthDay];
document.getElementById("6th-time").textContent = time[sixthTime];
document.getElementById("7th-day").textContent = day[seventhDay];
document.getElementById("7th-time").textContent = time[seventhTime];

/*
 * JavaScripts For Dialog, Open button and Close Button ends here!
 */

//* JS for actvie Nav Bar starts here!

//?Get all sections and nav a
const sections = document.querySelectorAll("section");
const navA = document.querySelectorAll("nav ul li a");

//?When the window is scrolled RUN THIS FUNCTION.
window.addEventListener("scroll", () => {
  //? Default value
  let current = "K01";

  //?Loop through the all the sections
  sections.forEach((section) => {
    //?Get the top of the section.
    const sectionTop = section.offsetTop;

    //?Check the current section.
    //? IF THE SCROLL Y IS GREATER THAN THE TOP OF THE SECTION IT MEANS THAT SECTION IS VISIBLE.
    if (window.scrollY >= sectionTop - 80) {
      current = section.getAttribute("id");
      // console.log("window.addEventListener  current:", current);
    }
  });

  //? a is the link in the nav bar.
  //? Loop through the nav a and add active class to the current section.
  //? IF THE NAV A HAS THE SAME CLASS AS THE CURRENT SECTION ADD ACTIVE CLASS TO IT.
  //? ELSE REMOVE THE ACTIVE CLASS.
  //? THIS WILL MAKE THE NAV A ACTIVE WHEN THE SECTION IS VISIBLE.
  //? THIS WILL ALSO REMOVE THE ACTIVE CLASS WHEN THE SECTION IS NOT VISIBLE.
  navA.forEach((a) => {
    a.classList.remove("active");
    if (a.classList.contains(current)) {
      a.classList.add("active");
    }
  });
});

//! Add active class to the first link
document.getElementById("K01").classList.add("active");
//* JS for actvie Nav Bar ends here!

//* JS for food preview starts here!
let basket = [];

//! Open Food Preview Modal
function showFoodPreviewModal(dialogID) {
  let selectedDialog = document.getElementById(dialogID);
  selectedDialog.showModal();
  blurAndRemoveScrollBar.classList.add("blur-hide-scroll-bar");
}

//! Close Food Preview Modal
function closeFoodPreviewModal(dialogID) {
  let selectedDialog = document.getElementById(dialogID);
  selectedDialog.close();
  blurAndRemoveScrollBar.classList.remove("blur-hide-scroll-bar");
}

//! Increment Button
let increment = (id) => {
  let selectedItem = id;
  let search = basket.find((foodPreview) => foodPreview.id === selectedItem.id);

  if (search === undefined) {
    basket.push({
      id: selectedItem.id,
      item: 2,
    });
  } else {
    search.item += 1;
  }

  // console.log(basket);
  update(selectedItem.id);

  // localStorage.setItem("data", JSON.stringify(basket));
  // updateBasket(selectedItem);
};

//! Decrement Button
let decrement = (id) => {
  let selectedItem = id;
  let search = basket.find((foodPreview) => foodPreview.id === selectedItem.id);

  if (search === undefined) {
    return;
  } else if (search.item === 1) {
    return;
  } else {
    search.item -= 1;
  }

  // console.log(basket);
  update(selectedItem.id);
  basket = basket.filter((foodPreview) => foodPreview.item !== 0);

  // localStorage.setItem("data", JSON.stringify(basket));
  // updateBasket(selectedItem);
};

//! Reset quantity function
function resetQuantity(id) {
  //? Select the whole quantity div.
  let selectedItem = id;

  let search = basket.find((foodPreview) => foodPreview.id === selectedItem.id);

  if (search === undefined) {
    return;
  } else {
    search.item = 1;
  }

  update(selectedItem.id);
}

//! Update quantity function
let update = (id) => {
  let search = basket.find((foodPreview) => foodPreview.id === id);
  // console.log(search);
  document.getElementById(id).innerHTML = search.item;
  // console.log(document.getElementById(id).innerHTML);
};

//! Show Add To Basket Price Function
function showAddToBasketPrice(
  selectedPrice,
  itemPrice,
  takeAwayPackagingPrice
) {
  let totalPrice = itemPrice + takeAwayPackagingPrice;
  totalPrice = totalPrice.toFixed(2);
  document.getElementById(selectedPrice).innerHTML = `RM${totalPrice}`;
}

//! Increase Add To Basket Price Function
function increaseAddToBasketPrice(
  itemPrice,
  selectedQuantity,
  selectedPriceID,
  takeAwayPackaging
) {
  // console.log(itemPrice);
  // console.log(selectedItem);
  // console.log(basket);
  /*
      //! 1st METHOD
      let search = basket.find((foodPreview) => foodPreview.id === selectedItem.id);
      // console.log(search.item);
      let total = (itemPrice * search.item) + 1;

      document.getElementById(selectedPrice).innerHTML = `RM${total}.00`;
      */
  //! 2nd METHOD
  let selectedPrice = document.getElementById(selectedPriceID);

  let price = selectedPrice.innerHTML;
  //? Get the price in Float.
  let initialTotal = parseFloat(price.replace("RM", ""));

  let total = initialTotal + itemPrice + takeAwayPackaging;
  total = total.toFixed(2);
  // console.log(total);

  document.getElementById(selectedPriceID).innerHTML = `RM${total}`;
}

//! Decrease Add To Basket Price Function
function decreaseAddToBasketPrice(
  itemPrice,
  selectedPriceID,
  takeAwayPackaging
) {
  // console.log(itemPrice);
  // console.log(selectedItem);
  // console.log(basket);
  let total = itemPrice + takeAwayPackaging;

  let selectedPrice = document.getElementById(selectedPriceID);
  let price = selectedPrice.innerHTML;
  //? Get the price in Float.
  let intitialTotal = parseFloat(price.replace("RM", ""));

  //? Prevent price from NEGATIVE.
  if (intitialTotal === total) {
    return;
  } else {
    total = intitialTotal - itemPrice - takeAwayPackaging;
    total = total.toFixed(2);
  }

  document.getElementById(selectedPriceID).innerHTML = `RM${total}`;
}

//! Get Additional Notes Function
function getAdditionalNotes(textAreaID) {
  let selectedTextArea = document.getElementById(textAreaID);
  let additionalNotes = selectedTextArea.value;

  if (additionalNotes === "") {
    additionalNotes = "undefined";
  }
  return `Notes: ${additionalNotes}`;
}

//! resetTextArea Function
function resetTextArea(textAreaID) {
  let selectedTextArea = document.getElementById(textAreaID);
  selectedTextArea.value = "";
}
//* JS for food preview ends here!

//* JS for disabling food preview starts here!
document
  .querySelectorAll(".food-preview")
  .forEach((foodPreview, foodPreviewId) => {
    if (foodPreview.classList.contains("Habis")) {
      foodPreview.onclick = null;
      document
        .querySelectorAll(".sold-out-image")
        .forEach((soldOutImage, soldOutImageId) => {
          if (foodPreviewId === soldOutImageId) {
            soldOutImage.style.display = "block";
          }
        });
    }
  });
//* JS for disabling food preview ends here!

//* JS for adding default food preview image starts here!
addClassNameNoImage();
function addClassNameNoImage() {
  document
    .querySelectorAll(".food-preview-image")
    .forEach((foodPreviewImage, foodPreviewImageId) => {
      if (!foodPreviewImage.getAttribute("src")) {
        foodPreviewImage.src = "icons/fork-and-knife.svg";
        foodPreviewImage.classList.add("no-image");
        addNoImageClassDiv(foodPreviewImageId);
      }
    });
}

function addNoImageClassDiv(index) {
  document
    .querySelectorAll(".food-preview-image-div")
    .forEach((element, elementId) => {
      if (index === elementId) {
        element.classList.add("no-image");
      }
    });
  document
    .querySelectorAll(".modal-food-preview-image")
    .forEach((element, elementId) => {
      if (index === elementId) {
        element.classList.add("no-image");
      }
    });
}

defaultFoodPreviewImage();
function defaultFoodPreviewImage() {
  document
    .querySelectorAll(".food-preview-image-modal")
    .forEach((foodPreviewImage, foodPreviewImageId) => {
      if (!foodPreviewImage.getAttribute("src")) {
        foodPreviewImage.src = "icons/fork-and-knife.svg";
        foodPreviewImage.classList.add("no-image");
      }
    });
}

//* JS for adding default food preview image ends here!
