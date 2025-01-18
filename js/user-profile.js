let toggleButton1 = document.getElementById("check");
let changePasswordContainer = document.querySelector(
  ".change-password-container"
);
let submitButton = document.getElementById("submit-button");

toggleButton1.addEventListener("click", () => {
  showChangePassword();
});

submitButton.addEventListener("click", () => {
  if (!toggleButton1.checked) {
    checkUsernameInputValue();
  } else {
    checkAllInputValue();
  }
});

function showChangePassword() {
  if (toggleButton1.checked) {
    changePasswordContainer.innerHTML = `
    <div class="text-box">
      <div class="entryarea">
        <input 
        type="password" 
        name="password" 
        id="password"
        required
        title="Only numbers are allowed!"
        pattern="\\d*"
        minlength="3"
        maxlength="3"
        autocomplete="off"
        class="password"
        />
        <label for="password" id="label-password" class="label-password">Katalaluan</label>
      </div>
      <p class="example-text-password" id="example-text-password">e.g. 123</p>
    </div>
    <div class="toggle-button-div">
      <input type="checkbox" id="check2" class="toggle" onclick="showAndRemovePassword()"/>
      <label for="check2">Tunjuk Kata laluan</label>
    </div>
    `;
    checkValidityPassword();
  } else {
    changePasswordContainer.innerHTML = "";
  }
}

function showAndRemovePassword() {
  let password = document.getElementById("password");

  if (password.type === "password") {
    password.type = "text";
  } else {
    password.type = "password";
  }
}

//! Function to check USERNAME inputs' VALIDITY !//
/// Run this when page reload
checkValidityUsername();
function checkValidityUsername() {
  let inputElement = document.getElementById("username");
  let label = document.getElementById("label-username");
  let errorTextUsername = document.getElementById("example-text-username");

  // Use .checkValidity() instead of inputValue !== ""
  // .checkValidity() does not rely on the value attribute in html

  if (inputElement.checkValidity()) {
    label.classList.add("active-valid");
  }

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    //? Valid input
    if (inputElement.checkValidity()) {
      // console.log("has value!");
      label.classList.add("active-valid"); // Make label move up
      label.classList.remove("active"); // Remove red color
    }
    //? Invalid input
    else {
      // console.log("no value!");
      label.classList.remove("active-valid"); // Make label move down
      label.classList.remove("active"); // Make label move down
      label.classList.add("invalid"); // Make label turns red
      inputElement.classList.add("invalid");
      errorTextUsername.innerHTML = "Nama diperlukan";
      errorTextUsername.classList.add("invalid");
    }
  });

  inputElement.addEventListener("input", () => {
    if (inputElement.checkValidity()) {
      // console.log("user is typing!");

      const regex = /^[A-Za-z ]*$/;

      if (regex.test(inputElement.value)) {
        //? Valid input
        // console.log("Valid: Only alphabets!");
        label.classList.remove("invalid");
        inputElement.classList.remove("invalid");
        errorTextUsername.innerHTML = "Hanya terima 30 aksara e.g. Kong Jia Le";
        errorTextUsername.classList.remove("invalid");
      } else {
        //? Invalid input
        // console.log("Invalid: Please enter only alphabets!");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextUsername.innerHTML = "Hanya Abjad!";
        errorTextUsername.classList.add("invalid");
      }
    } else {
      // console.log("user is deleting");

      let style = getComputedStyle(inputElement);

      //? Get the color property (text color)
      let color = style.outlineColor;
      // console.log(color);

      if (color === "rgb(0, 76, 255)") {
        // console.log('valid');
      } else if (color === "rgb(206, 1, 1)") {
        // console.log('invalid');
        errorTextUsername.innerHTML = "Nama diperlukan";
        errorTextUsername.classList.add("invalid");
      }
    }
  });
}

//! Function to check PASSWORD inputs' VALIDITY !//
/// Run this when page reload
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

//! Function to check Email inputs' VALIDITY !//
checkValidityEmail();
function checkValidityEmail() {
  let inputElement = document.getElementById("email");
  let label = document.getElementById("label-email");
  let errorTextEmail = document.getElementById("example-text-email");

  if (inputElement.checkValidity()) {
    // console.log("has value!");
    label.classList.add("active-valid");
  }

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    // console.log("Blur", inputElement.checkValidity());

    let inputValue = inputElement.value.trim();
    //? Valid input
    if (inputElement.checkValidity()) {
      // console.log("has value!");
      label.classList.add("active-valid");
      label.classList.remove("active");
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
      } else {
        // console.log("no value and invalid!");
        label.classList.remove("active");
        label.classList.remove("active-valid");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextEmail.innerHTML = "E-mel diperlukan";
        errorTextEmail.classList.add("invalid");
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

//! Function to check Nohp inputs' VALIDITY !//
checkValidityNohp();
function checkValidityNohp() {
  let inputElement = document.getElementById("nohp");
  let label = document.getElementById("label-nohp");
  let errorTextNohp = document.getElementById("example-text-nohp");

  if (inputElement.checkValidity()) {
    // console.log("has value!");
    label.classList.add("active-valid");
  }

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    // console.log("Blur", inputElement.checkValidity());
    let inputValue = inputElement.value.trim();

    //? Valid input
    if (inputElement.checkValidity()) {
      // console.log("has value and valid!");
      label.classList.remove("active");
      label.classList.add("active-valid");
    }
    //? Invalid input
    else if (!inputElement.checkValidity()) {
      if (inputValue === "") {
        // console.log("no value and invalid!");
        label.classList.remove("active");
        label.classList.remove("active-valid");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextNohp.innerHTML = "Nombor Telefon diperlukan";
        errorTextNohp.classList.add("invalid");
      } else {
        // console.log("has value but invalid!");
        label.classList.add("active");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextNohp.innerHTML = "Sila masukkan nombor telefon yang betul!";
        errorTextNohp.classList.add("invalid");
      }
    }
  });

  inputElement.addEventListener("input", () => {
    //? Input value
    let inputValue = inputElement.value.trim();
    if (inputElement.checkValidity()) {
      // console.log("user is typing!");
      const regex = /^[0-9]*$/;

      if (regex.test(inputElement.value)) {
        //? Valid input
        // console.log("Valid: Only numbers are allowed!");
        label.classList.remove("invalid");
        inputElement.classList.remove("invalid");
        errorTextNohp.innerHTML = "e.g. 0123456789";
        errorTextNohp.classList.remove("invalid");
      } else {
        //? Invalid input
        // console.log("Invalid: Please enter only numbers!");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextNohp.innerHTML = "Hanya Nombor!";
        errorTextNohp.classList.add("invalid");
      }
    } else if (!inputElement.checkValidity()) {
      let style = getComputedStyle(inputElement);

      //? Get the color property (text color)
      let color = style.outlineColor;
      // console.log(color);
      if (inputValue !== "") {
        if (color === "rgb(0, 76, 255)") {
          // console.log('valid');
        } else if (color === "rgb(206, 1, 1)") {
          // console.log('invalid');
          errorTextNohp.innerHTML = "Sila masukkan nombor telefon yang betul!";
          errorTextNohp.classList.add("invalid");
        }
      } else if (inputValue === "") {
        // console.log("user is deleting");
        if (color === "rgb(0, 76, 255)") {
          // console.log('valid');
        } else if (color === "rgb(206, 1, 1)") {
          // console.log('invalid');
          errorTextNohp.innerHTML = "Nombor Telefon diperlukan";
          errorTextNohp.classList.add("invalid");
        }
      }
    }
  });
}

//! Function to check Alamat inputs' VALIDITY !//
checkValidityAlamat();
function checkValidityAlamat() {
  let inputElement = document.getElementById("alamat");
  let label = document.getElementById("label-alamat");

  if (inputElement.value.trim() !== "") {
    // console.log("has value!");
    label.classList.add("active-valid");
  }

  inputElement.addEventListener("focus", () => {
    label.classList.add("active-valid");
    label.classList.remove("empty");
  });

  inputElement.addEventListener("blur", () => {
    if (inputElement.value.trim() === "") {
      // console.log("empty");
      label.classList.remove("active-valid");
      label.classList.add("empty");
    } else if (inputElement.value.trim() !== "") {
      // console.log("has value!");
      label.classList.add("active-valid");
      label.classList.remove("empty");
    }
  });

  inputElement.addEventListener("input", () => {
    label.classList.add("active-valid");
    label.classList.remove("empty");
  });
}

function checkUsernameInputValue() {
  let errorTextUsername = document.getElementById("example-text-username");
  let inputUsername = document.getElementById("username");

  if (inputUsername.value.trim() === "") {
    errorTextUsername.innerHTML = "Nama diperlukan";
    errorTextUsername.classList.add("invalid");
  }
}

function checkAllInputValue() {
  let errorTextUsername = document.getElementById("example-text-username");
  let errorTextPassword = document.getElementById("example-text-password");
  let inputUsername = document.getElementById("username");
  let inputPassword = document.getElementById("password");

  if (inputUsername.value.trim() === "" && inputPassword.value.trim() === "") {
    errorTextUsername.innerHTML = "Nama diperlukan";
    errorTextPassword.innerHTML = "Katalaluan diperlukan";
    errorTextUsername.classList.add("invalid");
    errorTextPassword.classList.add("invalid");
  } else if (inputUsername.value.trim() === "") {
    errorTextUsername.innerHTML = "Nama diperlukan";
    errorTextUsername.classList.add("invalid");
  } else if (inputPassword.value.trim() === "") {
    errorTextPassword.innerHTML = "Katalaluan diperlukan";
    errorTextPassword.classList.add("invalid");
  }
}

let deleteAccountButton = document.getElementById("delete-account-button");
let deleteAccountDialog = document.getElementById("delete-account-dialog");
let closeDeleteAccountDialogButton = document.querySelectorAll(
  ".close-button-delete-account-dialog"
);
let cancelButton = document.querySelector(".cancel-button");

deleteAccountButton.addEventListener("click", () => {
  deleteAccountDialog.showModal();
  document.body.classList.add("blur-hide-scroll-bar");
});

closeDeleteAccountDialogButton.forEach((button) => {
  button.addEventListener("click", () => {
    deleteAccountDialog.close();
    document.body.classList.remove("blur-hide-scroll-bar");
  });
});

cancelButton.addEventListener("click", () => {
  deleteAccountDialog.close();
  document.body.classList.remove("blur-hide-scroll-bar");
});

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

const settingButton = document.querySelector(".setting-btn");
const dialogSetting = document.getElementById("js-dialog-setting");
const closeButtonDialogSetting = document.getElementById(
  "js-dialog-setting-close-button"
);

settingButton.addEventListener("click", () => {
  dialogSetting.showModal();
});

closeButtonDialogSetting.addEventListener("click", () => {
  dialogSetting.close();
});
