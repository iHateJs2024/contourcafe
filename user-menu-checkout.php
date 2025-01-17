<!--WELCOME TO CHECKOUT PAGE CONTOUR CAFE'-->
<!-- Author: Kong Jia Le' -->
<!-- Start date:12-12-2024 -->
<!-- Version: 1.0 -->

<?php

// Start the session
session_start();

// Include the database connection (replace with actual DB connection details)
$host = "localhost";
$username = "root";
$password = "";
$dbname = "tempahan"; // Replace with your database name

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//! PHP TO CHECK IF USER exists in db and logged in
if (!isset($_SESSION['idpelanggan'])) {
  echo '<script>alert("Sila Log masuk/Daftar!");
                window.location.href = "signup.php";
        </script>';
  exit();
} else {
  $idpelanggan = $_SESSION['idpelanggan'];

  $query = "SELECT idpelanggan FROM pelanggan WHERE idpelanggan = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $idpelanggan);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    //? The user exists in db and logged in!

    // Query to select alamat_pelanggan
    $query = "SELECT alamat_pelanggan FROM pelanggan WHERE idpelanggan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idpelanggan);

    // Execute the query
    $stmt->execute();

    // Bind the result to a variable
    $stmt->bind_result($alamat_pelanggan);

    // Fetch the result
    if ($stmt->fetch()) {
    } else {
      echo "User not found or no address available!";
    }
  } else {
    //? The user does not exists in db and not logged in!
    echo '<script>alert("Sila Log masuk/Daftar!");
                  window.location.href = "signup.php";
          </script>';
  }

  // Close the statement
  $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contour Cafe'</title>

  <!--Barlow fonts-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap"
    rel="stylesheet" />

  <!--Link the CSS file-->
  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/scrollbar.css" />
  <link rel="stylesheet" href="css/user-basket-items.css" />
  <link rel="stylesheet" href="css/modal-food-preview.css" />
  <link rel="stylesheet" href="css/mobile-navigation.css" />
  <link rel="stylesheet" href="css/user-menu-checkout.css" />
  <link rel="icon" type="image/jpg" href="Logo image/Contour Cafe’.jpg" />

  <!--Link the icons file-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body id="body">
  <!--Navigation bar starts here-->
  <header>
    <div class="main-header">
      <!--Middle section starts here-->
      <div class="middle-section">
        <!--Logo section-->
        <a class="logo-link" href="user-index.php">
          <img
            class="contour-cafe-logo"
            src="Logo image/Contour Cafe’.jpg"
            alt="" />
        </a>
        <!--Links section-->
        <nav class="links-section">
          <ul id="ul-header" class="ul-header"></ul>
        </nav>
      </div>
      <!--Middle section ends here-->
    </div>
  </header>
  <!--Navigation bar ends here-->

  <!-- Screen Loader starts here! -->
  <div class="loader"></div>
  <!-- Screen Loader ends here! -->

  <!--  Main content Section starts here! -->
  <section class="checkout-section">
    <main class="main">
      <!-- Title starts here! -->
      <div class="title">
        <a href="user-menu.php">
          <img src="icons/popup-back-icon (1).svg" alt="" />
        </a>
        <div>
          <h1>Checkout</h1>
          <p>From <strong>Contour Cafe'</strong></p>
        </div>
      </div>
      <!-- Title starts here! -->

      <!-- Checkout Section and Basket Section starts here! -->
      <section
        id="js-checkout-basket-section"
        class="checkout-section-basket-section">
        <!-- Checkout Section starts here! -->
        <div class="checkout-div">
          <!-- Order Type Container starts here! -->
          <div class="order-type-container">
            <p class="type-text">Jenis</p>
            <div class="order-type-buttons-container">
              <button id="pick-up-button" class="order-type-button pickup">
                <svg
                  _ngcontent-ng-c2177227435=""
                  xmlns="http://www.w3.org/2000/svg"
                  width="22"
                  height="22"
                  viewBox="0 0 22 22"
                  fill="none">
                  <path
                    class="path-pick-up"
                    _ngcontent-ng-c2177227435=""
                    d="M2.93912 8.25C2.70805 8.24976 2.48625 8.34086 2.32206 8.50344C2.15787 8.66603 2.06459 8.88691 2.06255 9.11797C2.06162 9.19805 2.07321 9.27779 2.09693 9.3543L4.31841 17.1875C4.42137 17.5534 4.64142 17.8755 4.94485 18.1044C5.24828 18.3333 5.61839 18.4565 5.99849 18.4551H16.0016C16.3825 18.4554 16.7531 18.332 17.0577 18.1033C17.3622 17.8747 17.5843 17.5533 17.6903 17.1875L19.9118 9.3543L19.9376 9.11797C19.9355 8.88691 19.8422 8.66603 19.678 8.50344C19.5139 8.34086 19.2921 8.24976 19.061 8.25H2.93912ZM11.2458 15.128C10.8887 15.1262 10.5401 15.0187 10.244 14.819C9.94792 14.6193 9.71763 14.3363 9.58219 14.0059C9.44675 13.6754 9.41224 13.3122 9.483 12.9621C9.55377 12.6121 9.72664 12.2908 9.97981 12.0389C10.233 11.787 10.5551 11.6157 10.9055 11.5467C11.2559 11.4777 11.6189 11.514 11.9487 11.6511C12.2785 11.7882 12.5603 12.0199 12.7585 12.317C12.9568 12.614 13.0625 12.9632 13.0626 13.3203C13.0603 13.8006 12.8679 14.2604 12.5274 14.5992C12.1869 14.938 11.7261 15.1281 11.2458 15.128V15.128Z"
                    stroke="#8C8C8C"
                    stroke-linejoin="round"></path>
                  <path
                    class="path-pick-up"
                    _ngcontent-ng-c2177227435=""
                    d="M6.875 8.25L11 2.75L15.125 8.25"
                    stroke="#8C8C8C"
                    stroke-linejoin="round"></path>
                </svg>
                Ambil Tempahan
              </button>
              <button id="delivery-button" class="order-type-button delivery">
                <svg
                  _ngcontent-ng-c2177227435=""
                  xmlns="http://www.w3.org/2000/svg"
                  width="22"
                  height="22"
                  viewBox="0 0 22 22"
                  fill="none">
                  <path
                    class="path-delivery"
                    _ngcontent-ng-c2177227435=""
                    d="M16.6719 12.375C16.026 12.375 15.3946 12.5665 14.8576 12.9254C14.3206 13.2842 13.902 13.7942 13.6548 14.3909C13.4077 14.9876 13.343 15.6442 13.469 16.2777C13.595 16.9112 13.906 17.4931 14.3627 17.9498C14.8194 18.4065 15.4013 18.7175 16.0348 18.8435C16.6683 18.9695 17.3249 18.9048 17.9216 18.6577C18.5183 18.4105 19.0283 17.9919 19.3871 17.4549C19.746 16.9179 19.9375 16.2865 19.9375 15.6406C19.9348 14.7754 19.5899 13.9463 18.978 13.3345C18.3662 12.7226 17.5371 12.3777 16.6719 12.375V12.375Z"
                    stroke="#8C8C8C"
                    stroke-miterlimit="10"></path>
                  <path
                    class="path-delivery"
                    _ngcontent-ng-c2177227435=""
                    d="M5.32813 12.375C4.68225 12.375 4.05087 12.5665 3.51384 12.9254C2.97681 13.2842 2.55825 13.7942 2.31108 14.3909C2.06391 14.9876 1.99924 15.6442 2.12525 16.2777C2.25125 16.9112 2.56228 17.4931 3.01898 17.9498C3.47569 18.4065 4.05757 18.7175 4.69103 18.8435C5.3245 18.9695 5.98111 18.9048 6.57783 18.6577C7.17454 18.4105 7.68456 17.9919 8.0434 17.4549C8.40223 16.9179 8.59375 16.2865 8.59375 15.6406C8.59103 14.7754 8.2461 13.9463 7.63427 13.3345C7.02243 12.7226 6.19339 12.3777 5.32813 12.375Z"
                    stroke="#8C8C8C"
                    stroke-miterlimit="10"></path>
                  <path
                    class="path-delivery"
                    _ngcontent-ng-c2177227435=""
                    d="M11 15.4688V11.7734L8.25 9.96875L11.6875 6.1875L13.4062 9.28125H15.8125"
                    stroke="#8C8C8C"
                    stroke-linecap="round"
                    stroke-linejoin="round"></path>
                  <path
                    class="path-delivery"
                    _ngcontent-ng-c2177227435=""
                    d="M13.75 5.84374C13.9311 5.84436 14.1105 5.80908 14.2779 5.73994C14.4453 5.67079 14.5973 5.56915 14.7252 5.44089C14.853 5.31262 14.9542 5.16029 15.0228 4.99269C15.0915 4.82508 15.1262 4.64554 15.125 4.46444C15.127 4.28424 15.093 4.10545 15.0251 3.93852C14.9572 3.7716 14.8567 3.61989 14.7295 3.49226C14.6023 3.36463 14.4509 3.26365 14.2842 3.19523C14.1175 3.1268 13.9388 3.09229 13.7586 3.09373C13.578 3.09317 13.3991 3.12817 13.2321 3.19675C13.065 3.26533 12.9131 3.36614 12.785 3.49342C12.5264 3.75048 12.3804 4.09976 12.3793 4.46444C12.3781 4.82911 12.5219 5.1793 12.779 5.43797C13.036 5.69664 13.3853 5.8426 13.75 5.84374Z"
                    fill="#8C8C8C"></path>
                </svg>
                Penghantaran
              </button>
            </div>
          </div>
          <!-- Order Type Container ends here! -->

          <!-- Checkout Details Container starts here! -->
          <div
            id="checkout-details-container"
            class="checkout-details-container">
            <!-- Delivery Details starts here! -->
            <div class="delivery-details-container">
              <p class="delivery-details-title">Alamat Penghantaran</p>
              <div class="delivery-details-sub-container">
                <input
                  id="input-address"
                  title="Sila masukkan alamat anda"
                  value="<?= $alamat_pelanggan; ?>"
                  type="text" />
              </div>
            </div>
            <!-- Delivery Details ends here! -->

            <!-- Payment Method starts here! -->
            <div class="payment-method-container">
              <p class="payment-method-title">Kaedah pembayaran</p>
              <div class="payment-method-sub-container">
                <div>
                  <div id="payment-method-icon" class="payment-method-icon">
                    <img src="icons/pay.svg" alt="" />
                  </div>
                  <p id="choose-payment-method-text">
                    Pilih Kaedah Pembayaran
                  </p>
                </div>
                <div
                  class="change-payment-method-icon"
                  id="change-payment-method-icon">
                  <svg
                    _ngcontent-ng-c2177227435=""
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      _ngcontent-ng-c2177227435=""
                      d="M10 4.375V15.625"
                      stroke="#eb4917"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
                    <path
                      _ngcontent-ng-c2177227435=""
                      d="M15.625 10H4.375"
                      stroke="#eb4917"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
                  </svg>
                </div>
              </div>
            </div>

            <dialog class="payment-method-dialog" id="payment-method-dialog">
              <div class="modal-title-and-close-button">
                <div class="modal-title">Kaedah Pembayaran</div>
                <button
                  id="js-close-dialog-button"
                  class="close-button-food-preview-modal"
                  onclick="">
                  x
                </button>
              </div>

              <div class="main-content">
                <div class="payment-method-dialog-main-content-container">
                  <p class="payment-method-title">Bank</p>
                  <div>
                    <label for="input-Card" class="radio">
                      <div>
                        <i class="bi bi-credit-card"></i>
                        <p>Kad</p>
                      </div>
                      <div>
                        <input
                          type="radio"
                          value="Kad"
                          id="input-Card"
                          name="myRadioField"
                          class="radio__input" />
                        <div class="radio__radio"></div>
                      </div>
                    </label>
                  </div>

                  <p class="payment-method-title">eDompet / Pembayaran QR</p>
                  <div>
                    <label for="input-GrabPay" class="radio">
                      <div>
                        <img src="icons/grab-pay.png" alt="" />
                        <p>GrabPay</p>
                      </div>
                      <div>
                        <input
                          type="radio"
                          value="GrabPay"
                          id="input-GrabPay"
                          name="myRadioField"
                          class="radio__input" />
                        <div class="radio__radio"></div>
                      </div>
                    </label>

                    <label for="input-Alipay" class="radio">
                      <div>
                        <img src="icons/alipay.webp" alt="" />
                        <p>Alipay</p>
                      </div>
                      <div>
                        <input
                          type="radio"
                          value="Alipay"
                          id="input-Alipay"
                          name="myRadioField"
                          class="radio__input" />
                        <div class="radio__radio"></div>
                      </div>
                    </label>
                  </div>
                </div>

                <div
                  class="modal-food-preview-add-to-basket-button select-payment-method-container">
                  <button
                    id="js-select-payment-method-button"
                    class="select-payment-method-button">
                    PILIH KAEDAH PEMBAYARAN
                  </button>
                </div>
              </div>

            </dialog>
            <!-- Payment Method ends here! -->

            <!-- Venue Location starts here! -->
            <div class="venue-location-container">
              <p class="venue-location-title">Lokasi Tempat</p>
              <div class="venue-location-sub-container">
                <div>
                  <img src="icons/location.svg" alt="" />
                  <p>
                    55, Jalan 1/12, Pj Old Town, 46000 Petaling Jaya, Selangor
                  </p>
                </div>
                <a
                  href="https://www.google.com/maps/place/Contour/@3.0860515,101.6462004,984m/data=!3m2!1e3!4b1!4m6!3m5!1s0x31cc4befa556e89b:0x30b9864d474dcde1!8m2!3d3.0860515!4d101.6487753!16s%2Fg%2F11vd6_624y?entry=ttu&g_ep=EgoyMDI0MTIxMS4wIKXMDSoASAFQAw%3D%3D"
                  target="_blank">BUKA DALAM PETA</a>
              </div>
            </div>
            <!-- Venue Location ends here! -->

            <!-- Notes, Allergies or Message Card starts here! -->
            <div class="notes-container">
              <p class="notes-title">Nota, Alahan atau Kad Pesanan</p>
              <div class="notes-sub-container">
                <textarea
                  placeholder="cth. Alahan kepada kacang dan tolong sediakan tisu tambahan"
                  name="additionalNotes"
                  id="additionalNotes"
                  title="Nota Tambahan"></textarea>
              </div>
            </div>
            <!-- Notes, Allergies or Message Card ends here! -->
          </div>
          <!-- Checkout Details Container ends here! -->
        </div>
        <!-- Checkout Section ends here! -->

        <!--Food Basket starts here!-->
        <div class="food-basket-div">
          <div class="basket-wrapper">
            <div class="basket">
              <div class="basket-main">
                <p class="basket-name">Bakul</p>
                <p
                  style="
                      font-size: 12px;
                      color: rgb(140, 140, 140);
                      margin-bottom: 10px;
                    ">
                  Dari
                  <strong
                    style="
                        color: rgb(194, 138, 132);
                        font-size: 12px;
                        font-weight: 700;
                      ">Contour Cafe'</strong>
                </p>
                <a class="basket-link" href="user-ordertype.php">
                  <div class="basket-type-text">Jenis</div>
                  <div class="basket-delivery-text">
                    <span id="order-type" class="order-type"></span>
                    <img class="edit-icon" src="icons/edit icon.png" alt="" />
                  </div>
                </a>

                <p class="basket-edit-text">
                  Edit item dengan mengklik padanya
                </p>

                <div id="basket-items" class="basket-items"></div>

                <div
                  id="checkout-summary-details"
                  class="checkout-summary-details">
                  <div class="details-top">
                    <div class="basket-subtotal-and-price">
                      <strong>Jumlah kecil</strong>
                      <strong id="subtotal-basket" class="total">RM0.00</strong>
                    </div>

                    <div class="basket-discount"></div>

                    <div
                      id="basket-tax-and-price"
                      class="basket-tax-and-price">
                      <strong>SST(6%)</strong>
                      <strong id="tax-basket" class="total">RM0.00</strong>
                    </div>

                    <!-- <div
                        id="rounding-and-price-basket"
                        class="rounding-and-price-basket"
                      >
                        <strong>Membulatkan</strong>
                        <strong id="rounding-price" class="total"
                          >RM0.00</strong
                        >
                      </div> -->
                  </div>

                  <div class="basket-total-and-price">
                    <strong>Jumlah</strong>
                    <strong id="total-price-basket" class="total">RM0.00</strong>
                  </div>
                </div>
              </div>
              <div id="footer-basket-main" class="footer-basket-main"></div>
            </div>
          </div>
        </div>
        <!--Food Basket ends here!-->
      </section>
      <!-- Checkout Section and Basket Section ends here! -->
    </main>
  </section>
  <!--  Main content Section ends here! -->

  <!-- Mobile Navigation bar starts here-->
  <header class="mobile-header-container">
    <div class="mobile-header">
      <!--Middle section starts here-->
      <div class="mobile-middle-section">
        <!--Links section-->
        <nav class="mobile-links-section">
          <ul id="mobile-ul-header" class="mobile-ul-header"></ul>
        </nav>
      </div>
      <!--Middle section ends here-->
    </div>
  </header>
  <!-- Mobile Navigation bar ends here-->

  <!--Footer starts here!-->
  <footer>
    <div class="footer">
      <div style="display: flex; justify-content: center">
        <div class="logo-and-socialmedia-icons-div">
          <div>
            <a href="user-index.php">
              <img
                class="logo-image-footer"
                src="Logo image/Contour Cafe’.jpg"
                alt="" />
            </a>
          </div>
          <div class="socialmedia-icons-div">
            <div class="socialmedia-icons-subdiv">
              <a
                class="footer-icon-link"
                href="https://www.instagram.com/contour.pj/?hl=en"
                target="_blank">
                <img class="insta-icon" src="icons/insta.svg" alt="" />
              </a>
              <a
                class="footer-icon-link"
                href="https://www.facebook.com/p/ContourPJ-61551751387405/"
                target="_blank">
                <img class="fb-icon" src="icons/fb.svg" alt="" />
              </a>
            </div>
          </div>
        </div>
      </div>

      <hr class="footer-hr" style="width: 1103.6px" />

      <div class="terms-and-conditions-join-us-div">
        <div class="terms-and-conditions-join-us-subdiv">
          <div>
            <p>Sah</p>
            <a href="user-privacy-policy.html" class="a-footer-link">Dasar Privasi</a>
            <a href="user-terms-and-conditions.html" class="a-footer-link">Syarat Penggunaan</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--Footer ends here!-->

  <!--Link the JS file-->
  <script src="data/HeaderLinksDataUserMenu.js"></script>
  <script src="js/generateHeaderLinksUserMenu.js"></script>
  <script src="data/mobileHeaderLinksDataUserMenu.js"></script>
  <script src="js/generateMobileHeaderLinksUserMenu.js"></script>
  <script src="js/home.js"></script>
  <script src="js/user-menu-checkout.js"></script>
  <script src="js/orderType.js"></script>
</body>

</html>