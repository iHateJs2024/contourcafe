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

//! Function to check PASSWORD inputs' VALIDITY !//
/// Run this when page reload
checkValidityPassword();
function checkValidityPassword() {
  let inputElement = document.getElementById("password");
  let label = document.getElementById("label-password");
  let input = document.getElementById("password");
  let errorTextPassword = document.getElementById("example-text-password");

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    let inputValue = inputElement.value.trim();

    //? Valid input
    if (inputValue !== "") {
      // console.log("has value!");
      label.classList.add("active");
    }
    //? Invalid input
    else if (inputValue === "") {
      // console.log("no value!");
      label.classList.remove("active");
      label.classList.add("invalid");
      input.classList.add("invalid");
      errorTextPassword.innerHTML = "Katalaluan diperlukan";
      errorTextPassword.classList.add("invalid");
    }
  });

  inputElement.addEventListener("input", () => {
    //? Input value
    let inputValue = inputElement.value.trim();

    if (inputValue !== "") {
      // console.log("user is typing!");

      const regex = /^[0-9]*$/;

      if (regex.test(inputElement.value)) {
        //? Valid input
        // console.log("Valid: Only numbers are allowed!");
        label.classList.remove("invalid");
        input.classList.remove("invalid");
        errorTextPassword.innerHTML = "Hanya terima 3 nombor e.g. 123";
        errorTextPassword.classList.remove("invalid");
      } else {
        //? Invalid input
        // console.log("Invalid: Please enter only numbers!");
        label.classList.add("invalid");
        input.classList.add("invalid");
        errorTextPassword.innerHTML = "Hanya Nombor!";
        errorTextPassword.classList.add("invalid");
      }
    } else if (inputValue === "") {
      // console.log("user is deleting");

      let style = getComputedStyle(inputElement);

      //? Get the color property (text color)
      let color = style.outlineColor;
      // console.log(color);

      if (color === "rgb(0, 76, 255)") {
        // console.log('valid');
      } else if (color === "rgb(206, 1, 1)") {
        // console.log('invalid');
        errorTextPassword.innerHTML = "Katalaluan diperlukan";
        errorTextPassword.classList.add("invalid");
      }
    }
  });
}

//! Function to check CONFIRM PASSWORD inputs' VALIDITY !//
checkValidityConfirmPassword();
function checkValidityConfirmPassword() {
  let inputElementPassword = document.getElementById("password");
  let inputElementConfirmPassword = document.getElementById("confirm_password");
  let label = document.getElementById("label-confirm_password");
  let errorTextConfirmPassword = document.getElementById(
    "example-text-confirm_password"
  );

  inputElementConfirmPassword.addEventListener("blur", function () {
    let inputValue = inputElementConfirmPassword.value.trim();

    let inputValuePassword = inputElementPassword.value.trim();

    if (inputElementConfirmPassword.checkValidity()) {
      //! valid input !\\
      if (inputValue === inputValuePassword) {
        //? Value is the same
        label.classList.remove("active");
        label.classList.add("active-valid");
        label.classList.remove("invalid");
        inputElementConfirmPassword.classList.remove("invalid");
        errorTextConfirmPassword.innerHTML = "Hanya terima 3 nombor e.g. 123";
        errorTextConfirmPassword.classList.remove("invalid");
      } else {
        //? Value is not the same
        label.classList.remove("active-valid");
        label.classList.add("invalid");
        inputElementConfirmPassword.classList.add("invalid");
        errorTextConfirmPassword.innerHTML = "Katalaluan tidak sama";
        errorTextConfirmPassword.classList.add("invalid");
      }
    } else {
      //! invalid input !\\
      if (inputValue !== "") {
        //? Has value
        label.classList.add("active");
        label.classList.add("invalid");
        inputElementConfirmPassword.classList.add("invalid");
        errorTextConfirmPassword.innerHTML = "Katalaluan tidak sama";
        errorTextConfirmPassword.classList.add("invalid");
      } else if (inputValue === "") {
        //? No value
        label.classList.remove("active");
        label.classList.add("invalid");
        inputElementConfirmPassword.classList.add("invalid");
        errorTextConfirmPassword.innerHTML = "Katalaluan diperlukan";
        errorTextConfirmPassword.classList.add("invalid");
      }
    }
  });

  inputElementConfirmPassword.addEventListener("input", () => {
    //? Input value
    let inputValue = inputElementConfirmPassword.value.trim();

    let inputValuePassword = inputElementPassword.value.trim();

    if (inputValue !== "") {
      // console.log("user is typing!");

      const regex = /^[0-9]*$/;

      if (regex.test(inputElementConfirmPassword.value)) {
        //? Valid input
        // console.log("Valid: Only numbers are allowed!");
        label.classList.remove("invalid");
        inputElementConfirmPassword.classList.remove("invalid");
        errorTextConfirmPassword.innerHTML = "Hanya terima 3 nombor e.g. 123";
        errorTextConfirmPassword.classList.remove("invalid");
      } else {
        //? Invalid input
        // console.log("Invalid: Please enter only numbers!");
        label.classList.add("invalid");
        inputElementConfirmPassword.classList.add("invalid");
        errorTextConfirmPassword.innerHTML = "Hanya Nombor!";
        errorTextConfirmPassword.classList.add("invalid");
      }
    } else if (inputValue === "") {
      // console.log("user is deleting");

      let style = getComputedStyle(inputElementConfirmPassword);

      //? Get the color property (text color)
      let color = style.outlineColor;
      // console.log(color);

      if (color === "rgb(0, 76, 255)") {
        // console.log('valid');
      } else if (color === "rgb(206, 1, 1)") {
        // console.log('invalid');
        errorTextConfirmPassword.innerHTML = "Katalaluan diperlukan";
        errorTextConfirmPassword.classList.add("invalid");
      }
    }
  });
}

//! Function to check ALL INPUT VALUE !//
/// Run this when page reload
checkAllInputValueResetPasswordForm();
function checkAllInputValueResetPasswordForm() {
  let submitButton = document.getElementById("reset-password-button");
  let inputPassword = document.getElementById("password");
  let inputConfirmPassword = document.getElementById("confirm_password");
  let errorTextPassword = document.getElementById("example-text-password");
  let errorTextConfirmPassword = document.getElementById(
    "example-text-confirm_password"
  );

  submitButton.addEventListener("click", () => {
    if (!inputPassword.checkValidity()) {
      errorTextPassword.innerHTML = "Katalaluan diperlukan";
      errorTextPassword.classList.add("invalid");
    }

    if (!inputConfirmPassword.checkValidity()) {
      errorTextConfirmPassword.innerHTML = "Katalaluan tidak sama";
      errorTextConfirmPassword.classList.add("invalid");
    }
  });
}
