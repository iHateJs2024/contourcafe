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

//! Function to check Email inputs' VALIDITY !//
checkValidityEmail();
function checkValidityEmail() {
  let inputElement = document.getElementById("email");
  let label = document.getElementById("label-email");
  let errorTextEmail = document.getElementById("example-text-email");

  if (inputElement.checkValidity()) {
    label.classList.add("active-valid");
  }

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    // console.log("Blur", inputElement.checkValidity());

    let inputValue = inputElement.value.trim();
    //? Valid input
    if (inputElement.checkValidity()) {
      // console.log("has value!");
      label.classList.remove("active");
      label.classList.add("active-valid");
    }
    //? Invalid input
    else if (!inputElement.checkValidity()) {
      if (inputValue) {
        // console.log("has value but invalid!");
        label.classList.add("active");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextEmail.innerHTML = "Sila masukkan e-mel yang betul!";
        errorTextEmail.classList.add("invalid");
        label.classList.remove("active-valid");
      } else {
        // console.log("no value and invalid!");
        label.classList.remove("active");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextEmail.innerHTML = "E-mel diperlukan";
        errorTextEmail.classList.add("invalid");
        label.classList.remove("active-valid");
      }
    }
  });

  inputElement.addEventListener("input", () => {
    let inputValue = inputElement.value.trim();
    // console.log("Input", inputElement.checkValidity());
    //? Input value is TRUE
    if (inputElement.checkValidity()) {
      label.classList.remove("active");
      label.classList.remove("invalid");
      inputElement.classList.remove("invalid");
      errorTextEmail.innerHTML = "e.g. kongjiale12@gmail.com";
      errorTextEmail.classList.remove("invalid");
    } else if (!inputElement.checkValidity()) {
      const regex = /[^A-Za-z0-9@.]/;
      if (regex.test(inputValue)) {
        label.classList.add("active");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextEmail.innerHTML = "Sila masukkan e-mel yang betul!";
        errorTextEmail.classList.add("invalid");
      }
      // console.log("user is deleting");

      let style = getComputedStyle(inputElement);

      //? Get the color property (text color)
      let color = style.outlineColor;
      // console.log(color);

      if (color === "rgb(0, 76, 255)") {
        // console.log('valid');
      } else if (color === "rgb(206, 1, 1)") {
        // console.log('invalid');
        errorTextEmail.innerHTML = "Sila masukkan e-mel yang betul!";
        errorTextEmail.classList.add("invalid");
      }
    }
  });
}

//! Function to check ALL INPUT VALUE !//
function checkInputValueForgotPasswordForm() {
  let inputEmail = document.getElementById("email");
  let errorTextEmail = document.getElementById("example-text-email");

  if (!inputEmail.checkValidity()) {
    errorTextEmail.innerHTML = "E-mel diperlukan";
    errorTextEmail.classList.add("invalid");
  }

  if (inputEmail.checkValidity()) {
    sessionStorage.setItem("isEmailSent", "true");
    sessionStorage.setItem("emailValue", inputEmail.value);
    document.querySelector(".loading-button-div").classList.add("active");
    document.querySelector(".send-username-div").style.display = "none";
  }
}

if (sessionStorage.getItem("isEmailSent") === "true") {
  document.querySelector(".main-content-success").style.display = "flex";
  document.querySelector(".main-content").style.display = "none";
  sessionStorage.removeItem("isEmailSent");
}

//! Function to RESEND EMAIL !//
function resendEmail() {
  let inputEmailValue = sessionStorage.getItem("emailValue");
  document.getElementById("email").value = inputEmailValue;

  document.querySelector(".main-content-success").style.display = "none";
  document.querySelector(".main-content").style.display = "block";
  checkValidityEmail();
}
