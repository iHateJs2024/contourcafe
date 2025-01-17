let form = document.getElementById("form");

function showLoginForm() {
  form.innerHTML = `
    <div class="text-box">
      <div class="entryarea">
        <input
          type="text" 
          name="username"
          id="username" 
          class="username"
          required
          pattern="[A-Za-z ]+"
          maxlength="30"
          autocomplete="off"
          title="Only alphabets are allowed"
          />
        <label for="username" id="label-username" class="label-username">Nama</label>
      </div>
      <p id="example-text-username" class="example-text-username">e.g. Kong Jia Le</p>
    </div>
    <div class="text-box">
      <div class="entryarea">
        <input 
        type="email" 
        name="email"
        id="email"
        class="email"
        pattern="[A-Za-z0-9@.]*" 
        required 
        maxlength="100000"
        autocomplete="off"
        />
        <label for="email" id="label-email" class="label-username">E-mel</label>
      </div>
      <p id="example-text-email" class="example-text-username">e.g. kongjiale12@gmail.com</p>
    </div>
    <div class="text-box">
      <div class="entryarea">
        <input 
        type="tel" 
        name="nohp"
        id="nohp"
        class="username" 
        required 
        pattern="\\d*"
        minlength="10"
        maxlength="11"
        autocomplete="off"
        />
        <label for="nohp" id="label-nohp" class="label-username">Nombor Telefon</label>
      </div>
      <p id="example-text-nohp" class="example-text-username">e.g. 0123456789</p>
    </div>
    <div class="text-box">
      <div class="entryarea">
        <input 
        type="password" 
        name="password" 
        id="password"
        required
        pattern="\\d*"
        title="Only numbers are allowed!"
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
      <input type="checkbox" id="check" class="toggle" onclick="showAndRemovePassword()"/>
      <label for="check">Tunjuk Katalaluan</label>
    </div>
    <div class="forgot-password-link">
      <a href="forgot-password.php">Lupa Katalaluan?</a>
    </div>
    <div class="terms-privacy-div">
      <p>Dengan log masuk, anda bersetuju dengan
      </p>
      <p>
        <a href="terms-and-conditions.html">Syarat & Ketentuan</a> serta
        <a href="privacy-policy.html">Polisi Privasi kami</a>
      </p>
    </div>
    <input id="login-button" type="submit" name="login-button" value="LOG MASUK" class="login-button">
  `;
  checkValidityUsername();
  checkValidityPassword();
  checkValidityEmail();
  checkValidityNohp();
  checkAllInputValueLoginForm();
}

function showSignUpForm() {
  form.innerHTML = `
    <div class="text-box">
      <div class="entryarea">
        <input 
        type="text" 
        name="username"
        id="username"
        class="username" 
        required 
        maxlength="30"
        pattern="[A-Za-z ]+"
        autocomplete="off"
        title="Only alphabets are allowed"
        />
        <label for="username" id="label-username" class="label-username">Nama</label>
      </div>
      <p id="example-text-username" class="example-text-username">e.g. Kong Jia Le</p>
    </div>
    <div class="text-box">
      <div class="entryarea">
        <input 
        type="email" 
        name="email"
        id="email"
        class="email" 
        pattern="[A-Za-z0-9@.]*" 
        required 
        maxlength="100000"
        autocomplete="off"
        />
        <label for="email" id="label-email" class="label-username">E-mel</label>
      </div>
      <p id="example-text-email" class="example-text-username">e.g. kongjiale12@gmail.com</p>
    </div>
    <div class="text-box">
      <div class="entryarea">
        <input 
        type="tel" 
        name="nohp"
        id="nohp"
        class="username" 
        required 
        pattern="\\d*"
        minlength="10"
        maxlength="11"
        autocomplete="off"
        />
        <label for="nohp" id="label-nohp" class="label-username">Nombor Telefon</label>
      </div>
      <p id="example-text-nohp" class="example-text-username">e.g. 0123456789</p>
    </div>
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
      <input type="checkbox" id="check" class="toggle" onclick="showAndRemovePassword()" />
      <label for="check">Tunjuk Katalaluan</label>
    </div>
    <div class="checkbox2-div">
      <div class="checkbox-div">
        <input class="checkbox2" type="checkbox" id="checkbox" required/>
        <svg id="uncheck-svg" viewBox="0 0 22 16" fill="none">
          <path d="M1 6.85L8.09677 14L21 1" />
        </svg>
        <div id="ripple" class="ripple"></div>
      </div>
      <label class="label-checkbox2" for="checkbox">
        Saya menerima <a href="terms-and-conditions.html">Syarat & Ketentuan</a> serta
        <a href="privacy-policy.html">Polisi Privasi</a>
      </label>
      </div>
      <div class="unchecked-text" id="unchecked-text"></div>
    <input id="signup-button" type="submit" name="signup-button" value="DAFTAR" class="login-button"></input>
    <button style="display: none;" class="login-button" id="signup-button-loading" disabled>Sedang Mendaftar...</button>
  `;
  checkValidityUsername();
  checkValidityPassword();
  checkValidityEmail();
  checkValidityNohp();
  checkAllInputValueSignupForm();
}

showLoginForm();

function showAndRemovePassword() {
  let password = document.getElementById("password");

  if (password.type === "password") {
    password.type = "text";
  } else {
    password.type = "password";
  }
}

let loginDiv = document.getElementById("login");
let signupDiv = document.getElementById("signup");
/// Run when reload the page!
changeLoginButtonColour();

//! Change Login Button Colour !
function changeLoginButtonColour() {
  loginDiv.classList.add("active");
}

loginDiv.addEventListener("click", () => {
  signupDiv.classList.remove("active");
  loginDiv.classList.add("active");
  showLoginForm();
});

//! Change Sign Up Button Colour !
signupDiv.addEventListener("click", () => {
  signupDiv.classList.add("active");
  loginDiv.classList.remove("active");
  showSignUpForm();
  checkCheckBox();
  checkboxSVG();
});

function checkCheckBox() {
  let checkbox2 = document.getElementById("checkbox");

  checkbox2.addEventListener("click", () => {
    if (checkbox2.checked) {
      // console.log("checked");
      let uncheckedText = document.getElementById("unchecked-text");
      uncheckedText.innerHTML = "";
    } else {
      let uncheckedText = document.getElementById("unchecked-text");
      uncheckedText.innerHTML = "Terma & Syarat diperlukan";
    }
  });
}

function checkboxSVG() {
  let checkBoxSVG = document.getElementById("uncheck-svg");
  let ripple = document.getElementById("ripple");
  checkBoxSVG.addEventListener("click", () => {
    let checkbox2 = document.getElementById("checkbox");
    checkbox2.checked = false; //? Unchecks the checkbox
    let uncheckedText = document.getElementById("unchecked-text");
    uncheckedText.innerHTML = "Terma & Syarat diperlukan";
  });
}

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

//! Function to check ALL LOGIN INPUT VALUE !//
/// Run this when page reload
checkAllInputValueLoginForm();
function checkAllInputValueLoginForm() {
  let submitButton = document.getElementById("login-button");
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

//! Function to check ALL SIGN UP INPUT VALUE !//
/// Run this when page reload
function checkAllInputValueSignupForm() {
  let submitButton = document.getElementById("signup-button");
  let errorTextUsername = document.getElementById("example-text-username");
  let errorTextPassword = document.getElementById("example-text-password");
  let errorTextEmail = document.getElementById("example-text-email");
  let errorTextNohp = document.getElementById("example-text-nohp");
  let checkBox = document.getElementById("checkbox");

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

    if (
      inputUsername.checkValidity() &&
      inputPassword.checkValidity() &&
      inputEmail.checkValidity() &&
      inputNohp.checkValidity() &&
      checkBox.checked
    ) {
      submitButton.style.display = "none";
      let signupButtonLoading = document.getElementById(
        "signup-button-loading"
      );
      signupButtonLoading.style.display = "block";
    }
  });
}

updateBasketItemCount();
function updateBasketItemCount() {
  let basketItemsLength = JSON.parse(
    localStorage.getItem("basketItems")
  ).length;
  let basketItemCounter = document.querySelector(".basket-item-counter");
  basketItemCounter.innerHTML = basketItemsLength;

  if (basketItemsLength !== 0) {
    basketItemCounter.classList.add("active");
  } else if (basketItemsLength === 0) {
    basketItemCounter.classList.remove("active");
  }
}

// Prevent form submission on enter key
document.addEventListener("DOMContentLoaded", function () {
  form.addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
      event.preventDefault();
      return false;
    }
  });
});
