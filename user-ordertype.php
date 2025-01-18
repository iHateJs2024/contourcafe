<!--WELCOME TO ORDER TYPE CONTOUR CAFE'-->
<!-- Author: Kong Jia Le' -->
<!-- Start date:12-12-2024 -->
<!-- Version: 1.0 -->

<?php
// Start the session
session_start();

include_once(__DIR__ . '/connect.php');
$conn = getConnection();

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
  <link rel="stylesheet" href="css/ordertypePAGE.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/scrollbar.css" />
  <link rel="stylesheet" href="css/mobile-navigation.css" />
  <link rel="icon" type="image/jpg" href="Logo image/Contour Cafe’.jpg" />

  <!--Link the icons file-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body>
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

  <!--Main Content starts here!-->
  <main class="main">
    <div class="main-content">
      <a href="user-menu.php">
        <img src="icons/popup-back-icon.svg" alt="" />
        <h2>Contour Cafe'</h2>
      </a>
      <p class="select-order-type-text">Pilih Jenis Pesanan</p>
      <div>
        <a
          class="order-type-button"
          href="user-menu.php"
          onclick="
          updateOrderType('Penghantaran');
          ">
          <img class="bicycle-icon" src="icons/bicycle.png" alt="" />
          <span>Penghantaran</span>
        </a>
      </div>
      <div>
        <a
          class="order-type-button"
          href="user-menu.php"
          onclick="updateOrderType('Ambil Tempahan');">
          <img class="basket-icon" src="icons/basket.png" />
          <span>Ambil Tempahan</span>
        </a>
      </div>
    </div>
  </main>
  <!--Main Content ends here!-->

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
  <script src="js/orderType.js"></script>
</body>

</html>