let addUserButton = document.getElementById("js-tambah-pengguna-button");
let closeDialogButton = document.getElementById("close-dialog-button");
let addUserDialog = document.getElementById("add-user-dialog");
let addUserForm = document.getElementById("add-user-form");

addUserButton.addEventListener("click", () => {
  addUserDialog.showModal();
  document.body.classList.add("blur-hide-scroll-bar");
});

closeDialogButton.addEventListener("click", () => {
  addUserDialog.close();
  addUserForm.reset();
  resetInputUsername();
  resetInputEmail();
  resetInputNohp();
  resetInputPassword();
  document.body.classList.remove("blur-hide-scroll-bar");
});

//! Function to check USERNAME inputs' VALIDITY !//
/// Run this when page reload
checkValidityUsername();
function checkValidityUsername() {
  let inputElement = document.getElementById("username");
  let label = document.getElementById("label-username");
  let input = document.getElementById("username");
  let errorTextUsername = document.getElementById("example-text-username");

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
      errorTextUsername.innerHTML = "Nama diperlukan";
      errorTextUsername.classList.add("invalid");
    }
  });

  inputElement.addEventListener("input", () => {
    //? Input value
    let inputValue = inputElement.value.trim();

    if (inputValue !== "") {
      // console.log("user is typing!");

      const regex = /^[A-Za-z ]*$/;

      if (regex.test(inputElement.value)) {
        //? Valid input
        // console.log("Valid: Only alphabets!");
        label.classList.remove("invalid");
        input.classList.remove("invalid");
        errorTextUsername.innerHTML = "Hanya terima 30 aksara e.g. Kong Jia Le";
        errorTextUsername.classList.remove("invalid");
      } else {
        //? Invalid input
        // console.log("Invalid: Please enter only alphabets!");
        label.classList.add("invalid");
        input.classList.add("invalid");
        errorTextUsername.innerHTML = "Hanya Abjad!";
        errorTextUsername.classList.add("invalid");
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
        errorTextUsername.innerHTML = "Nama diperlukan";
        errorTextUsername.classList.add("invalid");
      }
    }
  });
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

//! Function to check Email inputs' VALIDITY !//
checkValidityEmail();
function checkValidityEmail() {
  let inputElement = document.getElementById("email");
  let label = document.getElementById("label-email");
  let errorTextEmail = document.getElementById("example-text-email");

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    // console.log("Blur", inputElement.checkValidity());

    let inputValue = inputElement.value.trim();
    //? Valid input
    if (inputElement.checkValidity()) {
      // console.log("has value!");
      label.classList.add("active");
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

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    // console.log("Blur", inputElement.checkValidity());
    let inputValue = inputElement.value.trim();

    //? Valid input
    if (inputElement.checkValidity()) {
      // console.log("has value and valid!");
      label.classList.add("active");
    }
    //? Invalid input
    else if (!inputElement.checkValidity()) {
      if (inputValue === "") {
        // console.log("no value and invalid!");
        label.classList.remove("active");
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

//! Function to check ALL SIGN UP INPUT VALUE !//
/// Run this when page reload
checkAllInputValue();
function checkAllInputValue() {
  let submitButton = document.querySelector(".add-button-dialog");
  let errorTextUsername = document.getElementById("example-text-username");
  let errorTextPassword = document.getElementById("example-text-password");
  let errorTextEmail = document.getElementById("example-text-email");
  let errorTextNohp = document.getElementById("example-text-nohp");

  submitButton.addEventListener("click", () => {
    let inputUsername = document.getElementById("username");
    let inputPassword = document.getElementById("password");
    let inputEmail = document.getElementById("email");
    let inputNohp = document.getElementById("nohp");

    if (
      !inputUsername.checkValidity() &&
      !inputPassword.checkValidity() &&
      !inputEmail.checkValidity() &&
      !inputNohp.checkValidity()
    ) {
      errorTextUsername.innerHTML = "Nama diperlukan";
      errorTextPassword.innerHTML = "Katalaluan diperlukan";
      errorTextEmail.innerHTML = "E-mel diperlukan";
      errorTextNohp.innerHTML = "Nombor Telefon diperlukan";
      errorTextUsername.classList.add("invalid");
      errorTextPassword.classList.add("invalid");
      errorTextEmail.classList.add("invalid");
      errorTextNohp.classList.add("invalid");
    } else if (!inputUsername.checkValidity()) {
      errorTextUsername.innerHTML = "Nama diperlukan";
      errorTextUsername.classList.add("invalid");
    } else if (!inputPassword.checkValidity()) {
      errorTextPassword.innerHTML = "Katalaluan diperlukan";
      errorTextPassword.classList.add("invalid");
    } else if (!inputEmail.checkValidity()) {
      errorTextEmail.innerHTML = "E-mel diperlukan";
      errorTextEmail.classList.add("invalid");
    } else if (!inputNohp.checkValidity()) {
      errorTextNohp.innerHTML = "Nombor Telefon diperlukan";
      errorTextNohp.classList.add("invalid");
    }
  });
}

//! Function to reset classname input field
function resetInputUsername() {
  let inputElement = document.getElementById("username");
  let label = document.getElementById("label-username");
  let errorTextUsername = document.getElementById("example-text-username");

  label.classList.remove("invalid");
  label.classList.remove("active");
  inputElement.classList.remove("invalid");
  errorTextUsername.classList.remove("invalid");
  errorTextUsername.innerHTML = "e.g. Kong Jia Le";
}

function resetInputEmail() {
  let inputElement = document.getElementById("email");
  let label = document.getElementById("label-email");
  let errorTextEmail = document.getElementById("example-text-email");

  errorTextEmail.classList.remove("invalid");
  label.classList.remove("active");
  label.classList.remove("invalid");
  inputElement.classList.remove("invalid");
  errorTextEmail.innerHTML = "e.g. kongjiale12@gmail.com";
}

function resetInputNohp() {
  let inputElement = document.getElementById("nohp");
  let label = document.getElementById("label-nohp");
  let errorTextNohp = document.getElementById("example-text-nohp");

  label.classList.remove("active");
  label.classList.remove("invalid");
  inputElement.classList.remove("invalid");
  errorTextNohp.classList.remove("invalid");
  errorTextNohp.innerHTML = "e.g. 0123456789";
}

function resetInputPassword() {
  let inputElement = document.getElementById("password");
  let label = document.getElementById("label-password");
  let errorTextPassword = document.getElementById("example-text-password");

  label.classList.remove("active");
  label.classList.remove("invalid");
  inputElement.classList.remove("invalid");
  errorTextPassword.classList.remove("invalid");
  errorTextPassword.innerHTML = "e.g. 123";
}

document.querySelectorAll(".edit-button").forEach((editButton) => {
  let editButtonId = editButton.dataset.buttonId;
  editButton.addEventListener("click", () => {
    showEditUserDialog(`edit-user-dialog-${editButtonId}`);

    checkValidityUsernameEditUserDialog(editButtonId);
    checkValidityPasswordEditUserDialog(editButtonId);
    checkValidityEmailEditUserDialog(editButtonId);
    checkValidityNohpEditUserDialog(editButtonId);

    resetInputUsernameEditUserDialog(editButtonId);
    resetInputEmailEditUserDialog(editButtonId);
    resetInputNohpEditUserDialog(editButtonId);
    resetInputPasswordEditUserDialog(editButtonId);
  });
});

//! Function to open edit user dialog
function showEditUserDialog(dialogId) {
  let dialog = document.getElementById(dialogId);
  dialog.showModal();
  document.body.classList.add("blur-hide-scroll-bar");
}

function closeEditUserDialog(id) {
  let dialog = document.getElementById(`edit-user-dialog-${id}`);
  let editUserForm = document.getElementById(`edit-user-form-${id}`);
  dialog.close();
  editUserForm.reset();
  document.body.classList.remove("blur-hide-scroll-bar");
}

//! Function to check USERNAME inputs' VALIDITY !//
/// Run this when page reload
function checkValidityUsernameEditUserDialog(id) {
  let inputElement = document.getElementById(`username-${id}`);
  let label = document.getElementById(`label-username-${id}`);
  let errorTextUsername = document.getElementById(
    `example-text-username-${id}`
  );

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
      if (inputElement.value.trim() === "") {
        // console.log("no value and invalid!");
        label.classList.remove("active-valid"); // Make label move down
        label.classList.remove("active"); // Make label move down
        label.classList.add("invalid"); // Make label turns red
        inputElement.classList.add("invalid");
        errorTextUsername.innerHTML = "Nama diperlukan";
        errorTextUsername.classList.add("invalid");
      } else {
        label.classList.remove("active-valid"); // Make label move down
        label.classList.add("active"); // Make label move down
        label.classList.add("invalid"); // Make label turns red
        inputElement.classList.add("invalid");
        errorTextUsername.innerHTML = "Nama diperlukan";
        errorTextUsername.classList.add("invalid");
      }
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
function checkValidityPasswordEditUserDialog(id) {
  let inputElement = document.getElementById(`password-${id}`);
  let label = document.getElementById(`label-password-${id}`);
  let errorTextPassword = document.getElementById(
    `example-text-password-${id}`
  );

  if (inputElement.checkValidity()) {
    label.classList.add("active-valid");
  }

  // Attach event listener for 'blur' event (when input loses focus)
  inputElement.addEventListener("blur", function () {
    let inputValue = inputElement.value.trim();

    //? Valid input
    if (inputElement.checkValidity()) {
      // console.log("has value!");
      label.classList.add("active");
    }
    //? Invalid input
    else {
      if (inputElement.value.trim() === "") {
        // console.log("no value and invalid!");
        label.classList.remove("active");
        label.classList.remove("active-valid");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextPassword.innerHTML = "Katalaluan diperlukan";
        errorTextPassword.classList.add("invalid");
      } else {
        label.classList.add("active");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
        errorTextPassword.innerHTML = "Katalaluan diperlukan";
        errorTextPassword.classList.add("invalid");
      }
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
        inputElement.classList.remove("invalid");
        errorTextPassword.innerHTML = "Hanya terima 3 nombor e.g. 123";
        errorTextPassword.classList.remove("invalid");
      } else {
        //? Invalid input
        // console.log("Invalid: Please enter only numbers!");
        label.classList.add("invalid");
        inputElement.classList.add("invalid");
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
function checkValidityEmailEditUserDialog(id) {
  let inputElement = document.getElementById(`email-${id}`);
  let label = document.getElementById(`label-email-${id}`);
  let errorTextEmail = document.getElementById(`example-text-email-${id}`);

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
function checkValidityNohpEditUserDialog(id) {
  let inputElement = document.getElementById(`nohp-${id}`);
  let label = document.getElementById(`label-nohp-${id}`);
  let errorTextNohp = document.getElementById(`example-text-nohp-${id}`);

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

//! Function to reset classname input field
function resetInputUsernameEditUserDialog(id) {
  let inputElement = document.getElementById(`username-${id}`);
  let label = document.getElementById(`label-username-${id}`);
  let errorTextUsername = document.getElementById(
    `example-text-username-${id}`
  );

  label.classList.remove("invalid");
  label.classList.remove("active");
  inputElement.classList.remove("invalid");
  errorTextUsername.classList.remove("invalid");
  errorTextUsername.innerHTML = "e.g. Kong Jia Le";
}

function resetInputEmailEditUserDialog(id) {
  let inputElement = document.getElementById(`email-${id}`);
  let label = document.getElementById(`label-email-${id}`);
  let errorTextEmail = document.getElementById(`example-text-email-${id}`);

  errorTextEmail.classList.remove("invalid");
  label.classList.remove("active");
  label.classList.remove("invalid");
  inputElement.classList.remove("invalid");
  errorTextEmail.innerHTML = "e.g. kongjiale12@gmail.com";
}

function resetInputNohpEditUserDialog(id) {
  let inputElement = document.getElementById(`nohp-${id}`);
  let label = document.getElementById(`label-nohp-${id}`);
  let errorTextNohp = document.getElementById(`example-text-nohp-${id}`);

  label.classList.remove("active");
  label.classList.remove("invalid");
  inputElement.classList.remove("invalid");
  errorTextNohp.classList.remove("invalid");
  errorTextNohp.innerHTML = "e.g. 0123456789";
}

function resetInputPasswordEditUserDialog(id) {
  let inputElement = document.getElementById(`password-${id}`);
  let label = document.getElementById(`label-password-${id}`);
  let errorTextPassword = document.getElementById(
    `example-text-password-${id}`
  );

  label.classList.remove("active");
  label.classList.remove("invalid");
  inputElement.classList.remove("invalid");
  errorTextPassword.classList.remove("invalid");
  errorTextPassword.innerHTML = "e.g. 123";
}
